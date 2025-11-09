<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CaringTelepon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class KelolaController extends Controller
{
    /**
     * Display a listing of users.
     */
    public function index()
    {
        $users = User::orderBy('created_at', 'desc')->get();
        return view('kelola.index', compact('users'));
    }

    /**
     * Show the form for creating a new user.
     */
    public function create()
    {
        return view('kelola.create');
    }

    /**
     * Store a newly created user in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => 'required|string|max:255|unique:users,username',
            'email' => 'required|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|in:Administrator,Collection Agent',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
        ]);

        $user = User::create([
            'nama_lengkap' => $validated['name'],
            'username' => $validated['username'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => $validated['role'] === 'Administrator' ? 'admin' : 'ca',
            'status' => 'Aktif', // Default status
        ]);

        // Redistribute caring_telepon for any new active user
        if ($validated['role'] === 'Collection Agent' || $validated['role'] === 'Administrator') {
            $this->redistributeCaringTelepon();
        }

        return redirect()->route('kelola.index')
            ->with('success', 'Akun berhasil ditambahkan.');
    }

    /**
     * Show the form for editing the specified user.
     */
    public function edit(User $user)
    {
        return view('kelola.edit', compact('user'));
    }



    /**
     * Update the specified user in storage.
     */
    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'username' => [
                'required',
                'string',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users')->ignore($user->id)
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => 'required|in:Administrator,Collection Agent',
            'status' => 'required|in:Aktif,Nonaktif',
        ], [
            'name.required' => 'Nama lengkap wajib diisi.',
            'username.required' => 'Username wajib diisi.',
            'username.unique' => 'Username sudah digunakan.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah terdaftar.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
            'role.required' => 'Role wajib dipilih.',
            'status.required' => 'Status wajib dipilih.',
        ]);

        $user->nama_lengkap = $validated['name'];
        $user->username = $validated['username'];
        $user->email = $validated['email'];
        $user->role = $validated['role'] === 'Administrator' ? 'admin' : 'ca';
        $user->status = $validated['status'];

        // Only update password if provided
        if ($request->filled('password')) {
            $user->password = Hash::make($validated['password']);
        }

        $user->save();

        return redirect()->route('kelola.index')
            ->with('success', 'Akun berhasil diperbarui.');
    }

    /**
     * Toggle user status (Aktif/Nonaktif).
     */
    public function toggleStatus(User $user)
    {
        $oldStatus = $user->status;
        $user->status = $user->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $user->save();

        // If deactivating user, set user_id to null for their CaringTelepon records
        // and redistribute "belum follow up" data to active users
        if ($oldStatus === 'Aktif' && $user->status === 'Nonaktif') {
            // Set user_id to null for all records assigned to this user
            CaringTelepon::where('user_id', $user->id)->update(['user_id' => null]);

            // Redistribute only "belum follow up" data (contact_date is null)
            $this->redistributeCaringTelepon();
        }

        $statusText = $user->status === 'Aktif' ? 'diaktifkan' : 'dinonaktifkan';

        return redirect()->route('kelola.index')
            ->with('success', "Akun berhasil {$statusText}.");
    }

    /**
     * Remove the specified user from storage.
     */
    public function destroy(User $user)
    {
        // Prevent deleting own account
        if (auth()->id() === $user->id) {
            return redirect()->route('kelola.index')
                ->with('error', 'Anda tidak dapat menghapus akun Anda sendiri.');
        }

        $user->delete();

        return redirect()->route('kelola.index')
            ->with('success', 'Akun berhasil dihapus.');
    }

    /**
     * Redistribute "Belum Follow Up" caring_telepon data evenly among active users (CA/Admin).
     * Only data with contact_date null is redistributed to ensure even distribution.
     * Also handles unassigned data (user_id IS NULL) by allocating to users with least customers.
     */
 private function redistributeCaringTelepon()
{
    DB::transaction(function () {
        // Get all active users (CA and Admin)
        $activeUsers = User::whereIn('role', ['ca', 'admin'])
            ->where('status', 'Aktif')
            ->pluck('id')
            ->toArray();

        if (empty($activeUsers)) {
            return; // No active users to assign to
        }

        // Ambil semua data belum follow up
        $belumFollowUpRecords = CaringTelepon::whereNull('contact_date')->get();
        if ($belumFollowUpRecords->isEmpty()) {
            return;
        }

        // Reset semua user_id ke null
        CaringTelepon::whereNull('contact_date')->update(['user_id' => null]);

        // 🔥 Ambil ulang data segar dari DB setelah reset
        $belumFollowUpRecords = CaringTelepon::whereNull('contact_date')->get();

        // Round robin distribusi ke semua user aktif
        $userCount = count($activeUsers);
        $index = 0;

        foreach ($belumFollowUpRecords as $record) {
            $record->user_id = $activeUsers[$index];
            $record->save();
            $index = ($index + 1) % $userCount;
        }
    });
}
}
