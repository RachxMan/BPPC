<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi massal
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Relasi Many-to-Many ke tabel harian melalui tabel pivot harian_user
     * Setiap user (CA) dapat memiliki banyak data pelanggan (harian)
     */
    public function harian()
    {
        return $this->belongsToMany(Harian::class, 'harian_user', 'user_id', 'snd')
                    ->withTimestamps();
    }

    /**
     * Scope opsional untuk filter user berdasarkan peran tertentu (jika nanti ada role)
     */
    public function scopeByRole($query, $role)
    {
        return $query->where('role', $role);
    }
}
