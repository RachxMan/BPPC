<?php

namespace App\Imports;

use App\Models\Bulanan;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class BulananImport implements ToModel, WithHeadingRow
{
    public function model(array $row)
    {
        return new Bulanan([
            'witel' => $row['witel'],
            'type' => $row['type'],
            'produk_bundling' => $row['produk_bundling'],
            'fi_home' => $row['fi_home'],
            'account_num' => $row['account_num'],
            'snd' => $row['snd'],
            'snd_group' => $row['snd_group'],
            'nama' => $row['nama_ncli'] ?? null, // Company name from NAMA_NCLI
            'cp' => $row['cp'],
            'datel' => $row['datel'],
            'payment_date' => $row['payment_date'] ?? null,
            'status_bayar' => $row['status_bayar'],
            'no_hp' => $row['no_hp'],
            'nama_real' => $row['nama'] ?? null, // PIC name from NAMA
            'segmen_real' => $row['segmen_real'],
        ]);
    }
}
