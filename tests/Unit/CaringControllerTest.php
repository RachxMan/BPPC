<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\User;
use App\Models\CaringTelepon;
use App\Models\Harian;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CaringControllerTest extends TestCase
{
    use RefreshDatabase;

    protected User $user;

    protected function setUp(): void
    {
        parent::setUp();

        // Nonaktifkan semua middleware (CSRF, auth, dll) untuk test ini
        $this->withoutMiddleware();

        // Buat user dummy
        $this->user = User::factory()->create();

        // Buat data Harian dummy
        Harian::factory()->create([
            'snd' => 'SND001',
            'nama' => 'Pelanggan 1',
            'account_num' => 'ACC001',
            'cp' => '08123456789',
        ]);

        // Sinkronisasi Harian ke CaringTelepon
        $harian = Harian::first();
        CaringTelepon::create([
            'witel' => $harian->witel ?? null,
            'type' => $harian->type ?? null,
            'produk_bundling' => $harian->produk_bundling ?? null,
            'fi_home' => $harian->fi_home ?? null,
            'account_num' => $harian->account_num,
            'snd' => $harian->snd,
            'snd_group' => $harian->snd_group ?? null,
            'nama' => $harian->nama,
            'cp' => $harian->cp,
            'datel' => $harian->datel ?? null,
            'payment_date' => $harian->payment_date ?? null,
            'status_bayar' => $harian->status_bayar ?? null,
            'user_id' => $this->user->id,
            'status_call' => null,
            'keterangan' => null,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function telepon_page_displays_correctly(): void
    {
        $this->actingAs($this->user)
             ->get(route('caring.telepon'))
             ->assertStatus(200)
             ->assertViewIs('caring.telepon')
             ->assertViewHasAll(['data', 'limit', 'search']);

        $this->assertDatabaseHas('caring_telepon', [
            'snd' => 'SND001',
            'user_id' => $this->user->id,
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function telepon_page_respects_search_query(): void
    {
        $harian = Harian::factory()->create([
            'snd' => 'SND002',
            'nama' => 'Customer Test',
            'account_num' => 'ACC002',
        ]);

        // Assign the harian to the test user
        $this->user->harian()->attach($harian->snd);

        $this->actingAs($this->user)
             ->get(route('caring.telepon', ['search' => 'Customer']))
             ->assertStatus(200)
             ->assertViewHas('data', function($data) {
                 return $data->contains('nama', 'Customer Test');
             });
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function update_status_can_update_status_call(): void
    {
        $record = CaringTelepon::first();

        $response = $this->actingAs($this->user)
                         ->postJson(route('caring.telepon.update'), [
                             'id' => $record->id,
                             'status_call' => 'contact',
                         ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('caring_telepon', [
            'id' => $record->id,
            'status_call' => 'contact',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function update_status_can_update_keterangan(): void
    {
        $record = CaringTelepon::first();

        $response = $this->actingAs($this->user)
                         ->postJson(route('caring.telepon.update'), [
                             'id' => $record->id,
                             'keterangan' => 'Test keterangan',
                         ]);

        $response->assertStatus(200)
                 ->assertJson(['success' => true]);

        $this->assertDatabaseHas('caring_telepon', [
            'id' => $record->id,
            'keterangan' => 'Test keterangan',
        ]);
    }

    #[\PHPUnit\Framework\Attributes\Test]
    public function update_status_requires_valid_id(): void
    {
        $response = $this->actingAs($this->user)
                         ->postJson(route('caring.telepon.update'), [
                             'id' => 999, // ID tidak ada
                             'status_call' => 'contact',
                         ]);

        $response->assertStatus(422);
    }
}
