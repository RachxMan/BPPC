<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Foundation\Testing\RefreshDatabase;

class KelolaControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->withoutMiddleware(); // matikan semua middleware supaya bisa akses route
    }

    /** @test */
    public function it_returns_kelola_index_view()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get(route('kelola.index'));

        $response->assertStatus(200);
        $response->assertViewIs('kelola.index');
        $response->assertViewHas('users');
    }

    /** @test */
    public function it_returns_kelola_create_view()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $response = $this->get(route('kelola.create'));

        $response->assertStatus(200);
        $response->assertViewIs('kelola.create');
    }

    /** @test */
    public function it_can_store_new_user()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        $data = [
            'name' => 'User Baru',
            'username' => 'userbaru',
            'email' => 'userbaru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'Administrator',
        ];

        $response = $this->post(route('kelola.store'), $data);

        $response->assertRedirect(route('kelola.index'));
        $this->assertDatabaseHas('users', [
            'username' => 'userbaru',
            'email' => 'userbaru@example.com',
            'role' => 'admin',
        ]);
    }

    /** @test */
    public function it_redistributes_caring_telepon_when_creating_new_ca()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Create existing CA
        $existingCA = User::factory()->create(['role' => 'ca', 'status' => 'Aktif']);

        // Create some caring_telepon records with contact_date null (Belum Follow Up)
        \App\Models\CaringTelepon::factory()->count(5)->create([
            'user_id' => $existingCA->id,
            'contact_date' => null,
        ]);

        // Create new CA
        $data = [
            'name' => 'CA Baru',
            'username' => 'cabaru',
            'email' => 'cabaru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'Collection Agent',
        ];

        $this->post(route('kelola.store'), $data);

        // Check that records are redistributed
        $totalRecords = \App\Models\CaringTelepon::whereNull('contact_date')->count();
        $activeUsers = User::whereIn('role', ['ca', 'admin'])->where('status', 'Aktif')->count();

        if ($activeUsers > 0) {
            $expectedPerUser = ceil($totalRecords / $activeUsers);
            // Verify that no user has more than expected +1 (due to rounding)
            $maxRecords = \App\Models\CaringTelepon::whereNull('contact_date')
                ->selectRaw('user_id, COUNT(*) as count')
                ->groupBy('user_id')
                ->get()
                ->max('count');

            $this->assertLessThanOrEqual($expectedPerUser + 1, $maxRecords);
        }
    }

    /** @test */
    public function it_redistributes_caring_telepon_when_creating_new_admin()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Create existing CA
        $existingCA = User::factory()->create(['role' => 'ca', 'status' => 'Aktif']);

        // Create some caring_telepon records with contact_date null (Belum Follow Up)
        \App\Models\CaringTelepon::factory()->count(5)->create([
            'user_id' => $existingCA->id,
            'contact_date' => null,
        ]);

        // Create new Admin
        $data = [
            'name' => 'Admin Baru',
            'username' => 'adminbaru',
            'email' => 'adminbaru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'Administrator',
        ];

        $this->post(route('kelola.store'), $data);

        // Check that records are redistributed
        $totalRecords = \App\Models\CaringTelepon::whereNull('contact_date')->count();
        $activeUsers = User::whereIn('role', ['ca', 'admin'])->where('status', 'Aktif')->count();

        if ($activeUsers > 0) {
            $expectedPerUser = ceil($totalRecords / $activeUsers);
            // Verify that no user has more than expected +1 (due to rounding)
            $maxRecords = \App\Models\CaringTelepon::whereNull('contact_date')
                ->selectRaw('user_id, COUNT(*) as count')
                ->groupBy('user_id')
                ->get()
                ->max('count');

            $this->assertLessThanOrEqual($expectedPerUser + 1, $maxRecords);
        }
    }

    /** @test */
    public function it_does_not_redistribute_followed_up_records()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Create existing CA
        $existingCA = User::factory()->create(['role' => 'ca', 'status' => 'Aktif']);

        // Create some caring_telepon records - some followed up, some not
        \App\Models\CaringTelepon::factory()->count(3)->create([
            'user_id' => $existingCA->id,
            'contact_date' => null, // Belum Follow Up
        ]);

        $followedUpRecords = \App\Models\CaringTelepon::factory()->count(2)->create([
            'user_id' => $existingCA->id,
            'contact_date' => now()->toDateString(), // Sudah Follow Up
        ]);

        // Create new CA
        $data = [
            'name' => 'CA Baru',
            'username' => 'cabaru',
            'email' => 'cabaru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'Collection Agent',
        ];

        $this->post(route('kelola.store'), $data);

        // Check that followed up records still have the same user_id
        foreach ($followedUpRecords as $record) {
            $this->assertDatabaseHas('caring_telepon', [
                'id' => $record->id,
                'user_id' => $existingCA->id,
            ]);
        }
    }

    /** @test */
    public function it_allocates_unassigned_records_to_users_with_least_customers()
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $this->actingAs($admin);

        // Create existing users with different amounts of data
        $ca1 = User::factory()->create(['role' => 'ca', 'status' => 'Aktif']);
        $ca2 = User::factory()->create(['role' => 'ca', 'status' => 'Aktif']);

        // CA1 has 5 records, CA2 has 2 records
        \App\Models\CaringTelepon::factory()->count(5)->create([
            'user_id' => $ca1->id,
            'contact_date' => null,
        ]);
        \App\Models\CaringTelepon::factory()->count(2)->create([
            'user_id' => $ca2->id,
            'contact_date' => null,
        ]);

        // Create unassigned records
        \App\Models\CaringTelepon::factory()->count(3)->unassigned()->create([
            'contact_date' => null,
        ]);

        // Create new CA - this should trigger redistribution
        $data = [
            'name' => 'CA Baru',
            'username' => 'cabaru',
            'email' => 'cabaru@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
            'role' => 'Collection Agent',
        ];

        $this->post(route('kelola.store'), $data);

        // Check that unassigned records were allocated to users with least customers
        // Since redistribution happens, all records should be evenly distributed
        $totalRecords = \App\Models\CaringTelepon::whereNull('contact_date')->count();
        $activeUsers = User::whereIn('role', ['ca', 'admin'])->where('status', 'Aktif')->count();

        if ($activeUsers > 0) {
            $expectedPerUser = ceil($totalRecords / $activeUsers);
            $maxRecords = \App\Models\CaringTelepon::whereNull('contact_date')
                ->selectRaw('user_id, COUNT(*) as count')
                ->groupBy('user_id')
                ->get()
                ->max('count');

            $this->assertLessThanOrEqual($expectedPerUser + 1, $maxRecords);
        }

        // Ensure no records remain unassigned after redistribution
        $this->assertEquals(0, \App\Models\CaringTelepon::whereNull('contact_date')->whereNull('user_id')->count());
    }}
