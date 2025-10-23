<?php

namespace App\Imports;

use App\Models\Harian;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class HarianImport implements ToModel, WithHeadingRow
{
    /**
     * Map row dari Excel ke model Harian
     *
     * @param array $row
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {
        return new Harian([
            'no'             => $row['no'] ?? null,
            'witel'          => $row['witel'] ?? null,
            'type'           => $row['type'] ?? null,
            'produk_bundling'=> $row['produk_bundling'] ?? null,
            'fi_home'        => $row['fi_home'] ?? null,
            'account_num'    => $row['account_num'] ?? null,
            'snd'            => $row['snd'] ?? null,
            'snd_group'      => $row['snd_group'] ?? null,
            'nama'           => $row['nama'] ?? null,
            'cp'             => $row['cp'] ?? null,
            'datel'          => $row['datel'] ?? null,
            'payment_date'   => !empty($row['payment_date']) ? date('Y-m-d', strtotime($row['payment_date'])) : null,
            'status_bayar'   => $row['status_bayar'] ?? null,
            'no_hp'          => $row['no_hp'] ?? null,
            'nama_real'      => $row['nama_real'] ?? null,
            'segmen_real'    => $row['segmen_real'] ?? null,
        ]);
    }
}
