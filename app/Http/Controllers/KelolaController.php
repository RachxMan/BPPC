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

        // Redistribute caring_telepon if new user is CA
        if ($validated['role'] === 'Collection Agent') {
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
        $user->status = $user->status === 'Aktif' ? 'Nonaktif' : 'Aktif';
        $user->save();

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
     * Redistribute all caring_telepon data evenly among active CAs.
     */
    private function redistributeCaringTelepon()
    {
        DB::transaction(function () {
            // Get all active CAs
            $activeCAs = User::where('role', 'ca')
                ->where('status', 'Aktif')
                ->pluck('id')
                ->toArray();

            if (empty($activeCAs)) {
                return; // No active CAs to assign to
            }

            // Get all caring_telepon
            $allRecords = CaringTelepon::all();

            if ($allRecords->isEmpty()) {
                return; // No data to assign
            }

            // First, unassign all to reset
            CaringTelepon::query()->update(['user_id' => null]);

            // Assign round-robin
            $caCount = count($activeCAs);
            $index = 0;

            foreach ($allRecords as $record) {
                $record->user_id = $activeCAs[$index];
                $record->save();
                $index = ($index + 1) % $caCount;
            }
        });
    }
}
