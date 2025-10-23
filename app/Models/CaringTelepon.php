<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory; // wajib ditambahkan
use Illuminate\Database\Eloquent\Model;

class CaringTelepon extends Model
{
    use HasFactory; // wajib agar ::factory() tersedia

    protected $table = 'caring_telepon';

    /**
     * Kolom yang bisa diisi secara massal
     */
    protected $fillable = [
        'witel',
        'type',
        'produk_bundling',
        'fi_home',
        'account_num',
        'snd',
        'snd_group',
        'nama',
        'cp',
        'datel',
        'payment_date',
        'status_bayar',
        'no_hp',
        'nama_real',
        'segmen_real',
        'user_id',
        'keterangan',
        'status_call',
        'created_at',
        'updated_at'
    ];

    /**
     * Relasi ke Harian (mengambil data pelanggan berdasarkan snd)
     */
    public function harian()
    {
        return $this->hasOne(Harian::class, 'snd', 'snd');
    }

    /**
     * Relasi ke User (CA/Admin)
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
