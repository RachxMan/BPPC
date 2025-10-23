@extends('layouts.app')

@section('title', 'Caring Telepon - PayColl PT. Telkom')
@section('header-title', 'Caring Telepon')
@section('header-subtitle', 'Halaman ini menampilkan daftar pelanggan yang harus dihubungi oleh CA/Admin, lengkap dengan status pembayaran, kontak, dan keterangan call.')

@section('content')
<div class="container">

    {{-- Search Bar --}}
    <div class="search-container" style="margin-bottom:10px;">
        <form method="GET" class="search-form">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelanggan..." style="padding:4px 8px; width:300px; border-radius:4px; border:1px solid #ccc;">
            <button type="submit" class="btn-search" style="padding:4px 10px; background-color:#E60012; color:#fff; border:none; border-radius:4px; cursor:pointer;">Search</button>
            @if(request('search'))
                <a href="{{ route('caring.telepon') }}" style="margin-left:10px; color:#E60012; text-decoration:underline;">Reset</a>
            @endif
        </form>
    </div>

    {{-- Limit per page --}}
    <div class="limit-container">
        <form method="GET" class="limit-form">
            <label>Tampilkan:</label>
            <select name="limit" onchange="this.form.submit()">
                @foreach([10,20,30,50,100] as $l)
                    <option value="{{ $l }}" {{ $limit == $l ? 'selected' : '' }}>{{ $l }}</option>
                @endforeach
            </select>
            <span>data per halaman</span>
        </form>
        <div class="total-pelanggan">
            Total: {{ $data->total() }} pelanggan
        </div>
    </div>

    <div class="table-wrapper">
        <table class="caring-table">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Witel</th>
                    <th>Type</th>
                    <th>Produk Bundling</th>
                    <th>FI Home</th>
                    <th>Account Num</th>
                    <th>ID_NET (SND)</th>
                    <th>SND Group</th>
                    <th>Nama</th>
                    <th>Nama Real</th>
                    <th>Segmen Real</th>
                    <th>Alamat</th>
                    <th>Kontak</th>
                    <th>Payment Date</th>
                    <th>Status Bayar</th>
                    <th>Nama Lengkap</th>
                    <th>Keterangan</th>
                    <th>Status Call</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data as $index => $row)
                <tr class="{{ $index % 2 == 0 ? 'even' : 'odd' }}">
                    <td>{{ $data->firstItem() + $index }}</td>
                    <td>{{ $row->witel ?? '-' }}</td>
                    <td>{{ $row->type ?? '-' }}</td>
                    <td>{{ $row->produk_bundling ?? '-' }}</td>
                    <td>{{ $row->fi_home ?? '-' }}</td>
                    <td>{{ $row->account_num ?? '-' }}</td>
                    <td>{{ $row->snd ?? '-' }}</td>
                    <td>{{ $row->snd_group ?? '-' }}</td>
                    <td>{{ $row->nama ?? '-' }}</td>
                    <td>{{ $row->nama_real ?? '-' }}</td>
                    <td>{{ $row->segmen_real ?? '-' }}</td>
                    <td>{{ $row->datel ?? '-' }}</td>
                    <td>
                        <div class="kontak">
                            <span>{{ $row->cp ?? $row->no_hp ?? '-' }}</span>
                            <button type="button" onclick="copyNumber('{{ $row->cp ?? $row->no_hp }}')">Copy</button>
                        </div>
                    </td>
                    <td>{{ $row->payment_date ?? '-' }}</td>
                    <td>{{ $row->status_bayar ?? '-' }}</td>
                    <td>{{ $row->user->nama_lengkap ?? '-' }}</td>
                    <td>
                        <textarea onchange="updateKeterangan({{ $row->id }}, this.value)" placeholder="Keterangan..." rows="2">{{ $row->keterangan }}</textarea>
                    </td>
                    <td>
                        <div class="status-dropdown">
                            <select onchange="showSubStatus({{ $row->id }}, this.value)">
                                <option value="">Pilih Status</option>
                                <option value="contact">Contact</option>
                                <option value="uncontact">Uncontact</option>
                            </select>
                            <select id="sub-status-{{ $row->id }}" style="display:none;" onchange="updateStatus({{ $row->id }}, this.value)">
                                <!-- sub opsi muncul via JS -->
                            </select>
                            <div class="status-buttons" style="margin-top:2px;">
                                <button type="button" onclick="submitStatus({{ $row->id }})" class="submit-btn">Submit</button>
                                <button type="button" onclick="resetRow({{ $row->id }})" class="reset-btn">Reset</button>
                            </div>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-container">
        {{ $data->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
</div>

<style>
.container {
    max-width: 100%;
    margin: 0 auto;
    padding: 20px;
    font-family: Arial, sans-serif;
    background-color: #FDFCF9;
}

.search-container input[type=text] {
    width: 300px;
}

.search-container .btn-search:hover {
    background-color: #B0000E;
}

.limit-container {
    display: flex;
    justify-content: space-between;
    margin-bottom: 10px;
    align-items: center;
}

.limit-form select {
    padding: 4px 8px;
    margin-left: 5px;
    border-radius: 4px;
    font-size: 14px;
}

.table-wrapper {
    overflow-x: auto;
}

.caring-table {
    width: 100%;
    min-width: 1800px;
    border-collapse: collapse;
    font-size: 14px;
}

.caring-table th, .caring-table td {
    border: 1px solid #ccc;
    padding: 8px;
    text-align: left;
    vertical-align: top;
}

.caring-table th {
    background-color: #E60012;
    color: #fff;
    text-transform: uppercase;
    font-weight: bold;
    font-size: 12px;
}

.caring-table tr.even { background-color: #F5F5F5; }
.caring-table tr.odd { background-color: #FFF9F5; }
.caring-table tr:hover { background-color: #FFE5E0; }

.kontak {
    display: flex;
    align-items: center;
    gap: 5px;
}

.kontak button {
    background-color: #E60012;
    color: #fff;
    border: none;
    padding: 3px 6px;
    font-size: 12px;
    border-radius: 3px;
    cursor: pointer;
}

.kontak button:hover { background-color: #B0000E; }

.status-dropdown {
    display: flex;
    flex-direction: column;
    gap: 2px;
}

.status-dropdown select {
    width: 100%;
    padding: 3px 5px;
    font-size: 12px;
    border-radius: 3px;
    border: 1px solid #ccc;
}

.status-buttons {
    display: flex;
    gap: 5px;
}

.submit-btn {
    background-color: #3182CE;
    color: #fff;
    padding: 3px 6px;
    font-size: 12px;
    border-radius: 3px;
    border: none;
    cursor: pointer;
}

.submit-btn:hover { background-color: #2B6CB0; }

.reset-btn {
    background-color: #F56565;
    color: #fff;
    padding: 3px 6px;
    font-size: 12px;
    border-radius: 3px;
    cursor: pointer;
    border: none;
}

.reset-btn:hover { background-color: #C53030; }

textarea {
    width: 100%;
    padding: 4px;
    border-radius: 3px;
    border: 1px solid #ccc;
    font-size: 12px;
    resize: vertical;
}

.pagination-container {
    margin-top: 15px;
    text-align: center;
}

.pagination-container .page-item {
    display: inline-block;
    margin: 0 3px;
}

.pagination-container .page-link {
    color: #fff;
    background-color: #E60012;
    border-radius: 4px;
    padding: 4px 8px;
    text-decoration: none;
}

.pagination-container .page-item.active .page-link {
    background-color: #B0000E;
}

.pagination-container .page-link:hover {
    background-color: #9A000C;
}
</style>

<script>
const contactOptions = [
    {value:'Konfirmasi Pembayaran', text:'Konfirmasi Pembayaran'},
    {value:'Tidak Konfirmasi Pembayaran', text:'Tidak Konfirmasi Pembayaran'},
    {value:'Tutup Telpon', text:'Tutup Telpon'}
];
const uncontactOptions = [
    {value:'RNA', text:'RNA'},
    {value:'Tidak Aktif', text:'Tidak Aktif'},
    {value:'Nomor Luar Jangkauan', text:'Nomor Luar Jangkauan'},
    {value:'Tidak Tersambung', text:'Tidak Tersambung'}
];

function showSubStatus(id, type) {
    const sub = document.getElementById('sub-status-'+id);
    sub.innerHTML = '';
    if(type === '') {
        sub.style.display = 'none';
        return;
    }
    let options = type === 'contact' ? contactOptions : uncontactOptions;
    sub.style.display = 'inline-block';
    sub.innerHTML = '<option value="">Pilih Opsi</option>';
    options.forEach(opt => {
        const option = document.createElement('option');
        option.value = opt.value;
        option.text = opt.text;
        sub.appendChild(option);
    });
}

function copyNumber(number) {
    if(!number) return;
    navigator.clipboard.writeText(number).then(() => alert('Nomor berhasil disalin: '+number));
}

function updateStatus(id, status) {
    if(!status) return;
    fetch('{{ route("caring.telepon.update") }}', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({id:id, status_call:status})
    }).then(res=>res.json()).then(data=>{
        if(data.success) alert('Status berhasil diupdate!');
    });
}

function submitStatus(id) {
    const sub = document.getElementById('sub-status-'+id);
    const status = sub.value;
    if(status) updateStatus(id, status);
    else alert('Pilih opsi status terlebih dahulu.');
}

function updateKeterangan(id, keterangan) {
    fetch('{{ route("caring.telepon.update") }}', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
        body: JSON.stringify({id:id, keterangan:keterangan})
    }).then(res=>res.json()).then(data=>{
        if(data.success) console.log('Keterangan berhasil diupdate');
    });
}

function resetRow(id) {
    const sub = document.getElementById('sub-status-'+id);
    sub.style.display = 'none';
    sub.innerHTML = '';
}
</script>
@endsection
