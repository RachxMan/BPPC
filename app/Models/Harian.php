<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Harian extends Model
{
    use HasFactory;

    /**
     * Nama tabel di database
     */
    protected $table = 'harian';

    /**
     * Primary key menggunakan kolom 'snd'
     */
    protected $primaryKey = 'snd';

    /**
     * Karena kolom snd bukan auto increment
     */
    public $incrementing = false;

    /**
     * Tipe data primary key (string)
     */
    protected $keyType = 'string';

    /**
     * Kolom yang dapat diisi massal (mass assignable)
     */
    protected $fillable = [
        'snd',
        'witel',
        'type',
        'produk_bundling',
        'fi_home',
        'account_num',
        'snd_group',
        'nama',
        'cp',
        'datel',
        'payment_date',
        'status_bayar',
        'no_hp',
        'nama_real',
        'segmen_real',
    ];

    /**
     * Relasi ke CaringTelepon (One-to-Many)
     * Satu data Harian bisa memiliki banyak entry di tabel caring_telepon
     */
    public function caringTelepon()
    {
        return $this->hasMany(CaringTelepon::class, 'harian_snd', 'snd');
    }

    /**
     * Relasi Many-to-Many ke tabel users melalui pivot harian_user
     * Opsional: hapus jika tidak diperlukan lagi
     */
    public function users()
    {
        return $this->belongsToMany(User::class, 'harian_user', 'snd', 'user_id')
                    ->withTimestamps();
    }

    /**
     * Scope untuk filter data harian berdasarkan witel
     */
    public function scopeByWitel($query, $witel)
    {
        return $query->where('witel', $witel);
    }

    /**
     * Scope untuk filter data berdasarkan status bayar
     */
    public function scopeByStatusBayar($query, $status)
    {
        return $query->where('status_bayar', $status);
    }
}
