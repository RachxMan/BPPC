<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataUpload extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode', 'file_name', 'upload_date', 'id_admin'
    ];

    public function administrator()
    {
        return $this->belongsTo(Administrator::class, 'id_admin');
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }
}

