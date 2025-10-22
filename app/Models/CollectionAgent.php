<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CollectionAgent extends Model
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
        return $this->hasMany(Customer::class);
    }

    public function reminders()
    {
        return $this->hasMany(Reminder::class, 'collection_agent_id');
    }
}

