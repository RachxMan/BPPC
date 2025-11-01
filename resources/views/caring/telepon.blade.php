@extends('layouts.app')

@section('title', 'Caring Telepon - PayColl PT. Telkom')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/caring.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<main class="main" id="main">
  <header class="header">
    <h1>Caring Telepon</h1>
    <p class="subtitle">Daftar pelanggan yang harus dihubungi oleh CA/Admin.</p>
  </header>

  <section class="profile-container">
    <div class="header-section">
      <div>
        <h4 class="fw-bold mb-1" style="color: #333;">Daftar Caring Telepon</h4>
        <p class="text-muted mb-0">Kelola dan pantau status panggilan pelanggan.</p>
      </div>
    </div>

    {{-- Search & Limit & Total --}}
    <div class="top-controls" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px; flex-wrap:wrap; gap:10px;">
        <form id="search-form" method="GET" style="display:flex; align-items:center; gap:10px;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelanggan..." class="search-input">
            <button type="submit" class="btn-red">
              <i class="fa-solid fa-search me-2"></i> Cari Pelanggan
            </button>
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
    <div class="table-responsive mt-4">
        <table class="user-table align-middle">
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
                                    <i class="fas fa-copy copy-icon" onclick="copyNumber('{{ $kontak }}')" title="Copy nomor"></i>
                                @endif
                            </div>
                        </td>
                        <td>{{ $row->payment_date ?? '-' }}</td>
                        <td>{{ $row->status_bayar ?? '-' }}</td>

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
                        <td colspan="10" style="text-align:center;">Tidak ada data pelanggan</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-container" style="display:flex; justify-content:center; align-items:center; gap:10px; flex-wrap:wrap; margin-top:20px;">
        {{ $data->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
  </section>
</main>



{{-- Scripts --}}
<script>
function copyNumber(number) {
    if(!number) return;
    navigator.clipboard.writeText(number).then(()=>{
        showNotification('Nomor berhasil disalin: ' + number);
    });
}

function showNotification(message) {
    // Remove existing notification if any
    const existing = document.querySelector('.copy-notification');
    if (existing) existing.remove();

    // Create notification element
    const notification = document.createElement('div');
    notification.className = 'copy-notification';
    notification.textContent = message;
    notification.style.cssText = `
        position: fixed;
        top: 20px;
        right: -350px;
        background: #28a745;
        color: white;
        padding: 10px 15px;
        border-radius: 5px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.2);
        z-index: 10000;
        font-size: 14px;
        max-width: 300px;
        opacity: 0;
        transition: all 0.3s ease-in-out;
    `;

    document.body.appendChild(notification);

    // Trigger slide-in animation
    setTimeout(() => {
        notification.style.right = '20px';
        notification.style.opacity = '1';
    }, 10);

    // Auto remove after 2 seconds with slide-out
    setTimeout(() => {
        if (notification.parentNode) {
            notification.style.right = '-350px';
            notification.style.opacity = '0';
            setTimeout(() => {
                if (notification.parentNode) {
                    notification.remove();
                }
            }, 300);
        }
    }, 2000);
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
