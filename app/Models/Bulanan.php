<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bulanan extends Model
{
    use HasFactory;

    protected $table = 'bulanan';

    protected $fillable = [
        'witel', 'type', 'produk_bundling', 'fi_home', 'account_num',
        'snd', 'snd_group', 'nama', 'cp', 'datel', 'alamat', 'payment_date',
        'status_bayar', 'no_hp', 'nama_real', 'segmen_real'
    ];
}
