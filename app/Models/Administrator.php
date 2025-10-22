<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Administrator extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'nama_lengkap', 'email'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customers()
    {
        return $this->hasMany(Customer::class, 'id_admin');
    }

    public function dataUploads()
    {
        return $this->hasMany(DataUpload::class, 'id_admin');
    }

    public function reports()
    {
        return $this->hasMany(Report::class, 'id_admin');
    }
}
