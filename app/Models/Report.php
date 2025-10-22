<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'periode', 'jenis_report', 'format_file', 'id_admin'
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

