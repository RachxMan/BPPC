<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama', 'alamat', 'no_telp', 'status_pembayaran', 'catatan_follow_up', 'id_admin'
    ];

    public function administrator()
    {
        return $this->belongsTo(Administrator::class, 'id_admin');
    }

    public function collectionAgent()
    {
        return $this->belongsTo(CollectionAgent::class);
    }

    public function reminders()
    {
        return $this->belongsToMany(Reminder::class);
    }

    public function dataUploads()
    {
        return $this->belongsToMany(DataUpload::class);
    }

    public function reports()
    {
        return $this->belongsToMany(Report::class);
    }
}

