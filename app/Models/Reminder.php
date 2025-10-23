<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reminder extends Model
{
    use HasFactory;

    protected $fillable = [
        'tanggal_jatuh_tempo', 'daftar_customer_prioritas', 'collection_agent_id'
    ];

    public function collectionAgent()
    {
        return $this->belongsTo(CollectionAgent::class, 'collection_agent_id');
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class);
    }
}
