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
     * Tipe data primary key
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
        'alamat',
        'payment_date',
        'status_bayar',
        'no_hp',
        'nama_real',
        'segmen_real',
        'keterangan',     // tambahkan jika akan disimpan keterangan
        'status_call',    // tambahkan jika akan disimpan status call
    ];

    /**
     * RELASI
     */

    /**
     * One-to-Many ke CaringTelepon
     * Satu Harian bisa memiliki banyak entry di tabel caring_telepon
     */
    public function caringTelepon()
    {
        return $this->hasMany(CaringTelepon::class, 'harian_snd', 'snd');
    }

    /**
     * Many-to-Many ke User melalui pivot harian_user
     * Digunakan untuk assign Harian ke CA/Admin
     * Pivot table: harian_user, kolom: snd, user_id
     */
    public function assignedUsers()
    {
        return $this->belongsToMany(User::class, 'harian_user', 'snd', 'user_id')
                    ->withTimestamps();
    }

    /**
     * SCOPE / QUERY BUILDER HELPERS
     */

    /**
     * Filter data berdasarkan witel
     */
    public function scopeByWitel($query, $witel)
    {
        return $query->where('witel', $witel);
    }

    /**
     * Filter data berdasarkan status bayar
     */
    public function scopeByStatusBayar($query, $status)
    {
        return $query->where('status_bayar', $status);
    }

    /**
     * Ambil data Harian yang assigned ke user tertentu
     */
    public function scopeAssignedToUser($query, $userId)
    {
        return $query->whereHas('assignedUsers', function ($q) use ($userId) {
            $q->where('user_id', $userId);
        });
    }

    /**
     * Ambil kolom kontak (prioritas cp > no_hp)
     */
    public function getKontakAttribute()
    {
        return $this->cp ?? $this->no_hp ?? '-';
    }
}
