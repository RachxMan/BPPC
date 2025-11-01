{{-- Pop-up for Belum Follow-up --}}
<div class="popup-content">
    <h3>Belum Follow-up Details</h3>
    <table class="interactive-table">
        <thead>
            <tr>
                <th>ID NET</th>
                <th>NAMA</th>
                <th>CA</th>
                <th>AKSI</th>
                <th>MASALAH</th>
            </tr>
        </thead>
        <tbody>
            @foreach($belumFollowUp as $item)
            <tr>
                <td>{{ $item->snd }}</td>
                <td>{{ $item->nama_real }}</td>
                <td>{{ $item->ca_name ?? '-' }}</td>
                <td>{{ $item->status_call ?? 'Belum' }}</td>
                <td>{{ $item->keterangan ?? '-' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
