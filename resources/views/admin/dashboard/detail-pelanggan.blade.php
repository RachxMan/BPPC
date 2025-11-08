{{-- Pop-up for Detail Pelanggan --}}
<div class="popup-content">
    <h3>Detail Pelanggan</h3>
    <table class="interactive-table">
        <thead>
            <tr>
                <th>ID NET</th>
                <th>NAMA</th>
                <th>ALAMAT</th>
                <th>KONTAK</th>
                <th>STATUS</th>
                <th>TANGGAL PEMBAYARAN</th>
            </tr>
        </thead>
        <tbody>
            @foreach($dataPelanggan as $pel)
            <tr>
                <td>{{ $pel->snd }}</td>
                <td>{{ $pel->nama }}</td>
                <td>{{ $pel->alamat }}</td>
                <td>{{ $pel->cp ?? $pel->no_hp }}</td>
                <td>{{ $pel->status_bayar }}</td>
                <td>{{ $pel->payment_date ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
