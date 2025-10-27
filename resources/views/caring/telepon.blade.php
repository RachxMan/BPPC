@extends('layouts.app')

@section('title', 'Caring Telepon - PayColl PT. Telkom')
@section('header-title', 'Caring Telepon')
@section('header-subtitle', 'Daftar pelanggan yang harus dihubungi oleh CA/Admin.')

@section('content')
<div class="container">

    {{-- Search & Limit & Total --}}
    <div class="top-controls" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
        <form method="GET" style="display:flex; align-items:center; gap:10px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelanggan..." class="search-input">
            <button type="submit" class="btn-search">Search</button>
            @if(request('search'))
                <a href="{{ route('caring.telepon') }}" class="reset-link">Reset</a>
            @endif
        </form>

        <div style="display:flex; align-items:center; gap:20px;">
            <form method="GET" style="display:flex; align-items:center; gap:5px;">
                <label>Tampilkan:</label>
                <select name="limit" onchange="this.form.submit()">
                    @foreach([10,20,30,50,100] as $l)
                        <option value="{{ $l }}" {{ $limit == $l ? 'selected' : '' }}>{{ $l }}</option>
                    @endforeach
                </select>
                <span>data per halaman</span>
            </form>
            <div>Total: {{ $data->total() }} pelanggan</div>
        </div>
    </div>

    {{-- Table --}}
    <div class="table-wrapper">
        <table class="caring-table">
            <thead>
                <tr>
                    <th style="width:40px;">No</th>
                    <th style="width:100px;">ID NET (SND)</th>
                    <th style="width:150px;">Nama</th>
                    <th style="width:150px;">Nama Real</th>
                    <th style="width:200px;">Alamat</th>
                    <th style="width:120px;">Kontak</th>
                    <th style="width:120px;">Payment Date</th>
                    <th style="width:100px;">Status Bayar</th>
                    <th style="width:150px;">Nama CA</th>
                    <th style="width:180px;">Status Call</th>
                    <th style="width:200px;">Keterangan</th>
                </tr>
            </thead>
            <tbody>
                @forelse($data as $index => $row)
                    <tr class="{{ $index % 2 == 0 ? 'even' : 'odd' }}">
                        <td>{{ $data->firstItem() + $index }}</td>
                        <td>{{ $row->snd ?? '-' }}</td>
                        <td>{{ $row->nama ?? '-' }}</td>
                        <td>{{ $row->nama_real ?? '-' }}</td>
                        <td>{{ $row->datel ?? '-' }}</td>
                        <td>
                            @php $kontak = $row->cp ?? $row->no_hp ?? '-'; @endphp
                            <div class="kontak">
                                <span>{{ $kontak }}</span>
                                @if($kontak !== '-')
                                    <button type="button" onclick="copyNumber('{{ $kontak }}')">Copy</button>
                                @endif
                            </div>
                        </td>
                        <td>{{ $row->payment_date ?? '-' }}</td>
                        <td>{{ $row->status_bayar ?? '-' }}</td>
                        <td>{{ $row->user->nama_lengkap ?? '-' }}</td>

                        {{-- Status Call --}}
                        <td>
                            <div style="display:flex; flex-direction:column; gap:5px;">
                                <select class="status-type" data-id="{{ $row->id }}">
                                    <option value="">Pilih</option>
                                    <option value="contact" {{ in_array($row->status_call, ['Konfirmasi Pembayaran','Tidak Konfirmasi Pembayaran','Tutup Telpon'])?'selected':'' }}>Contact</option>
                                    <option value="uncontact" {{ in_array($row->status_call, ['RNA','Tidak Aktif','Nomor Luar Jangkauan','Tidak Tersambung'])?'selected':'' }}>Uncontact</option>
                                </select>
                                <select class="status-detail" data-id="{{ $row->id }}">
                                    <option value="">Detail</option>
                                    @if(in_array($row->status_call, ['Konfirmasi Pembayaran','Tidak Konfirmasi Pembayaran','Tutup Telpon']))
                                        <option value="Konfirmasi Pembayaran" {{ $row->status_call=='Konfirmasi Pembayaran'?'selected':'' }}>Konfirmasi Pembayaran</option>
                                        <option value="Tidak Konfirmasi Pembayaran" {{ $row->status_call=='Tidak Konfirmasi Pembayaran'?'selected':'' }}>Tidak Konfirmasi Pembayaran</option>
                                        <option value="Tutup Telpon" {{ $row->status_call=='Tutup Telpon'?'selected':'' }}>Tutup Telpon</option>
                                    @elseif(in_array($row->status_call, ['RNA','Tidak Aktif','Nomor Luar Jangkauan','Tidak Tersambung']))
                                        <option value="RNA" {{ $row->status_call=='RNA'?'selected':'' }}>RNA</option>
                                        <option value="Tidak Aktif" {{ $row->status_call=='Tidak Aktif'?'selected':'' }}>Tidak Aktif</option>
                                        <option value="Nomor Luar Jangkauan" {{ $row->status_call=='Nomor Luar Jangkauan'?'selected':'' }}>Nomor Luar Jangkauan</option>
                                        <option value="Tidak Tersambung" {{ $row->status_call=='Tidak Tersambung'?'selected':'' }}>Tidak Tersambung</option>
                                    @endif
                                </select>
                                <div style="display:flex; gap:5px;">
                                    <button class="btn-status-submit" data-id="{{ $row->id }}">Submit</button>
                                    <button class="btn-status-reset" data-id="{{ $row->id }}">Reset</button>
                                </div>
                            </div>
                        </td>

                        {{-- Keterangan --}}
                        <td>
                            <div style="display:flex; flex-direction:column; gap:5px;">
                                <textarea id="keterangan-{{ $row->id }}" rows="2" placeholder="Keterangan...">{{ $row->keterangan }}</textarea>
                                <div style="display:flex; gap:5px;">
                                    <button class="btn-ket-submit" data-id="{{ $row->id }}">Submit</button>
                                    <button class="btn-ket-reset" data-id="{{ $row->id }}">Reset</button>
                                </div>
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="11" style="text-align:center;">Tidak ada data pelanggan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-container" style="display:flex; justify-content:flex-end; align-items:center; gap:10px; flex-wrap:wrap;">
        {{ $data->withQueryString()->links('pagination::bootstrap-4') }}
    </div>

</div>

{{-- Styles --}}
<style>
.container { max-width: 100%; margin:20px auto; padding:20px; font-family:Arial,sans-serif; background-color:#FDFCF9; }
.search-input { padding:6px 10px; width:300px; border-radius:4px; border:1px solid #ccc; }
.btn-search { padding:6px 10px; background-color:#E60012; color:#fff; border:none; border-radius:4px; cursor:pointer; }
.btn-search:hover { background-color:#B0000E; }
.reset-link { color:#E60012; text-decoration:underline; }
.table-wrapper { overflow-x:auto; margin-bottom:20px; }
.caring-table { width:100%; min-width:1500px; border-collapse:collapse; font-size:14px; table-layout:fixed; }
.caring-table th, .caring-table td { border:1px solid #ccc; padding:8px; text-align:left; vertical-align:top; word-wrap:break-word; }
.caring-table th { background-color:#E60012; color:#fff; text-transform:uppercase; font-weight:bold; font-size:12px; }
.caring-table tr.even { background-color:#F5F5F5; }
.caring-table tr.odd { background-color:#FFF9F5; }
.caring-table tr:hover { background-color:#FFE5E0; }
.kontak { display:flex; align-items:center; gap:5px; }
.kontak button { background-color:#E60012; color:#fff; border:none; padding:3px 6px; font-size:12px; border-radius:3px; cursor:pointer; }
.kontak button:hover { background-color:#B0000E; }
textarea { width:100%; padding:4px; border-radius:3px; border:1px solid #ccc; font-size:12px; resize:vertical; }
select { padding:3px 5px; font-size:12px; border-radius:3px; border:1px solid #ccc; width:100%; }
button { cursor:pointer; }
.pagination-container .page-link { color:#fff; background-color:#E60012; border-radius:4px; padding:4px 8px; text-decoration:none; }
.pagination-container .page-item.active .page-link { background-color:#B0000E; }
.pagination-container .page-link:hover { background-color:#9A000C; }
</style>

{{-- Scripts --}}
<script>
function copyNumber(number) {
    if(!number) return;
    navigator.clipboard.writeText(number).then(()=>alert('Nomor berhasil disalin: '+number));
}

// Status Call dynamic dropdown
document.querySelectorAll('.status-type').forEach(select=>{
    select.addEventListener('change', function(){
        const id = this.dataset.id;
        const detail = document.querySelector(`.status-detail[data-id="${id}"]`);
        detail.innerHTML = '<option value="">Detail</option>';

        if(this.value === 'contact') {
            ['Konfirmasi Pembayaran','Tidak Konfirmasi Pembayaran','Tutup Telpon'].forEach(opt=>{
                const option = document.createElement('option');
                option.value = opt;
                option.textContent = opt;
                detail.appendChild(option);
            });
        } else if(this.value === 'uncontact') {
            ['RNA','Tidak Aktif','Nomor Luar Jangkauan','Tidak Tersambung'].forEach(opt=>{
                const option = document.createElement('option');
                option.value = opt;
                option.textContent = opt;
                detail.appendChild(option);
            });
        }
    });
});

// Status Call submit/reset
document.querySelectorAll('.btn-status-submit').forEach(btn=>{
    btn.addEventListener('click', function(){
        const id = this.dataset.id;
        const type = document.querySelector(`.status-type[data-id="${id}"]`).value;
        const detail = document.querySelector(`.status-detail[data-id="${id}"]`).value;
        if(!type || !detail) { alert('Pilih status dan detail!'); return; }

        fetch('{{ route("caring.telepon.update") }}', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({id:id, status_call:detail})
        }).then(res=>res.json()).then(data=>{
            if(data.success) alert('Status berhasil diupdate!');
        });
    });
});

document.querySelectorAll('.btn-status-reset').forEach(btn=>{
    btn.addEventListener('click', function(){
        const id = this.dataset.id;
        document.querySelector(`.status-type[data-id="${id}"]`).value = '';
        const detail = document.querySelector(`.status-detail[data-id="${id}"]`);
        detail.innerHTML = '<option value="">Detail</option>';
    });
});

// Keterangan submit/reset
document.querySelectorAll('.btn-ket-submit').forEach(btn=>{
    btn.addEventListener('click', function(){
        const id = this.dataset.id;
        const value = document.querySelector(`#keterangan-${id}`).value;
        fetch('{{ route("caring.telepon.update") }}', {
            method:'POST',
            headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
            body: JSON.stringify({id:id, keterangan:value})
        }).then(res=>res.json()).then(data=>{
            if(data.success) alert('Keterangan berhasil diupdate!');
        });
    });
});

document.querySelectorAll('.btn-ket-reset').forEach(btn=>{
    btn.addEventListener('click', function(){
        const id = this.dataset.id;
        document.querySelector(`#keterangan-${id}`).value = '';
    });
});
</script>
@endsection
