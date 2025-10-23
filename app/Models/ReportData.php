<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReportData extends Model
{
    protected $fillable = [
        'type','No','Witel','Type','Produk_Bundling','FI_HOME','Account_Num','SND',
        'SND_GROUP','NAMA','CP','DATEL','Payment_date','Status_Bayar','No_HP','Nama_Real','Segmen_Real'
    ];
}
