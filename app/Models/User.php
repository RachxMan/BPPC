<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * Kolom yang dapat diisi massal / The attributes that are mass assignable.
     *
     * @var array<int,string>
     */
    protected $fillable = [
        'username',
        'email',
        'nama_lengkap',
        'no_telp',
        'password',
        'role',
        'status',
    ];

    /**
     * Kolom yang disembunyikan saat serialisasi / The attributes that should be hidden for serialization.
     *
     * @var array<int,string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Casting atribut / The attributes that should be cast.
     *
     * @var array<string,string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

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
