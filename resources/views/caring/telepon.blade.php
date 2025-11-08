@extends('layouts.app')

@section('title', 'Caring Telepon - PayColl PT. Telkom')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/caring.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<style>
  /* ======================== CUSTOM STYLE ======================== */
  body {
    font-size: 13px;
  }

  h1, h4 {
    font-size: 16px;
  }

  p, label, select, textarea, input, button, table, th, td {
    font-size: 13px !important;
  }

  .btn-add {
    background-color: #e63946;
    color: #fff;
    border: none;
    padding: 4px 10px;
    border-radius: 5px;
    font-weight: 500;
    font-size: 12px;
    transition: all 0.25s ease;
    cursor: pointer;
  }

  .btn-add:hover {
    background-color: #d62828;
    box-shadow: 0 3px 8px rgba(230, 57, 70, 0.4);
    transform: translateY(-1px);
  }

  .btn-add:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(230, 57, 70, 0.3);
  }

  .search-input {
    font-size: 12px !important;
    padding: 5px 8px !important;
  }

  .btn-red {
    font-size: 12px !important;
    padding: 5px 10px !important;
  }

  .user-table th, .user-table td {
    padding: 6px 8px !important;
    font-size: 12px !important;
    white-space: nowrap;
  }

  .user-table th {
    background-color: #f5f5f5;
    font-weight: 600;
  }

  /* modal background */
  .modal {
    display: none;
    position: fixed;
    inset: 0;
    background: rgba(0, 0, 0, 0.45);
    justify-content: center;
    align-items: center;
    z-index: 9999;
    animation: fadeIn 0.25s ease;
  }

  @keyframes fadeIn {
    from {opacity: 0;}
    to {opacity: 1;}
  }

  /* modal content */
  .modal-content {
    background: #fff;
    border-radius: 10px;
    padding: 18px 22px;
    width: 360px;
    max-width: 90%;
    position: relative;
    box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    animation: slideUp 0.3s ease;
    font-size: 13px;
  }

  @keyframes slideUp {
    from {transform: translateY(30px); opacity: 0;}
    to {transform: translateY(0); opacity: 1;}
  }

  .modal-content h4 {
    font-weight: 600;
    color: #1e1e1e;
    margin-bottom: 14px;
    border-bottom: 1px solid #eee;
    padding-bottom: 6px;
    font-size: 14px;
  }

  .modal-content label {
    font-weight: 500;
    color: #333;
    margin-bottom: 3px;
    display: block;
    font-size: 13px;
  }

  .modal-content select,
  .modal-content textarea {
    width: 100%;
    border: 1px solid #ccc;
    border-radius: 6px;
    padding: 6px;
    font-size: 13px;
    transition: border-color 0.2s ease;
  }

  .modal-content select:focus,
  .modal-content textarea:focus {
    border-color: #e63946;
    outline: none;
  }

  .modal-footer {
    display: flex;
    justify-content: flex-end;
    gap: 8px;
    margin-top: 8px;
  }

  .btn-cancel {
    background-color: #ddd;
    border: none;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 12px;
    transition: background-color 0.2s ease;
  }

  .btn-cancel:hover {
    background-color: #ccc;
  }

  .btn-save {
    background-color: #e63946;
    border: none;
    color: #fff;
    padding: 5px 10px;
    border-radius: 6px;
    font-size: 12px;
    transition: all 0.2s ease;
  }

  .btn-save:hover {
    background-color: #d62828;
    box-shadow: 0 3px 8px rgba(230, 57, 70, 0.4);
    transform: translateY(-1px);
  }

  /* Responsive table */
  .table-responsive {
    overflow-x: auto;
  }

  @media (max-width: 1200px) {
    .user-table th, .user-table td {
      font-size: 11.5px !important;
      padding: 5px 6px !important;
    }
  }
</style>
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

    {{-- Search & Filter --}}
    <div class="top-controls" style="display:flex; justify-content:space-between; align-items:center; margin-bottom:16px; flex-wrap:wrap; gap:8px;">
      <form id="search-form" method="GET" style="display:flex; align-items:center; gap:8px;">
        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari pelanggan..." class="search-input">
        <button type="submit" class="btn-red"><i class="fa-solid fa-search me-2"></i> Cari</button>
        @if(request('search'))
          <a href="{{ route('caring.telepon') }}" class="reset-link">Reset</a>
        @endif
      </form>

      <div style="display:flex; align-items:center; gap:16px;">
        <form method="GET" style="display:flex; align-items:center; gap:4px;">
          <label>Sortir:</label>
          <select name="sort" onchange="this.form.submit()">
            <option value="">Default</option>
            <option value="paid" {{ $sort == 'paid' ? 'selected' : '' }}>Paid</option>
            <option value="unpaid" {{ $sort == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
          </select>
        </form>

        <form method="GET" style="display:flex; align-items:center; gap:4px;">
          <label>Tampilkan:</label>
          <select name="limit" onchange="this.form.submit()">
            @foreach([10,20,30,50,100] as $l)
              <option value="{{ $l }}" {{ $limit == $l ? 'selected' : '' }}>{{ $l }}</option>
            @endforeach
          </select>
          <span>data</span>
        </form>
        <div>Total: {{ $totalUnique ?? $data->total() }} pelanggan</div>
      </div>
    </div>

    {{-- Table --}}
    <div class="table-responsive mt-3">
      <table class="user-table align-middle">
        <thead>
          <tr>
            <th>No</th>
            <th>ID NET (SND)</th>
            <th>Nama</th>
            <th>Alamat</th>
            <th>Kontak</th>
            <th>Payment Date</th>
            <th>Status Bayar</th>
            <th>Status & Ket.</th>
          </tr>
        </thead>
        <tbody>
          @forelse($data as $index => $row)
          <tr class="{{ $index % 2 == 0 ? 'even' : 'odd' }}">
            <td>{{ $data->firstItem() + $index }}</td>
            <td>{{ $row->snd ?? '-' }}</td>
            <td>{{ $row->nama ?? '-' }}</td>
            <td>{{ $row->alamat ?? '-' }}</td>
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
            <td>
              <button class="btn-add" data-id="{{ $row->id }}" 
                data-status="{{ $row->status_call ?? '' }}" 
                data-keterangan="{{ $row->keterangan ?? '' }}">
                {{ ($row->status_call || $row->keterangan) ? 'Lihat' : 'Tambah' }}
              </button>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="8" style="text-align:center;">Tidak ada data pelanggan</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    {{-- Pagination --}}
    <div class="pagination-container" style="display:flex; justify-content:center; align-items:center; gap:8px; flex-wrap:wrap; margin-top:16px;">
      {{ $data->withQueryString()->links('pagination::bootstrap-4') }}
    </div>
  </section>
</main>

{{-- ================== POPUP MODAL ================== --}}
<div id="statusModal" class="modal">
  <div class="modal-content">
    <h4>Status Call & Keterangan</h4>
    <input type="hidden" id="modal-id">

    <label>Status Call:</label>
    <select id="modal-status-type" class="form-select mb-2">
      <option value="">Pilih</option>
      <option value="contact">Contact</option>
      <option value="uncontact">Uncontact</option>
    </select>

    <select id="modal-status-detail" class="form-select mb-3">
      <option value="">Detail</option>
    </select>

    <label>Keterangan:</label>
    <textarea id="modal-keterangan" rows="3" class="form-control mb-3" placeholder="Masukkan keterangan..."></textarea>

    <div class="modal-footer">
      <button id="modal-cancel" class="btn-cancel">Batal</button>
      <button id="modal-save" class="btn-save">Simpan</button>
    </div>
  </div>
</div>

{{-- ================== SCRIPT ================== --}}
<script>
function copyNumber(number) {
  navigator.clipboard.writeText(number).then(() => {
    Swal.fire({
      icon: 'success',
      title: 'Nomor disalin!',
      text: number,
      timer: 2000,
      showConfirmButton: false,
      toast: true,
      position: 'top-end'
    });
  });
}

// ======= POPUP LOGIC =======
let currentButton = null;

document.querySelectorAll('.btn-add').forEach(btn => {
  btn.addEventListener('click', function() {
    currentButton = this;
    const id = this.dataset.id;
    const status = this.dataset.status;
    const ket = this.dataset.keterangan;

    document.getElementById('modal-id').value = id;
    document.getElementById('modal-keterangan').value = ket;

    const typeSelect = document.getElementById('modal-status-type');
    const detailSelect = document.getElementById('modal-status-detail');
    typeSelect.value = '';
    detailSelect.innerHTML = '<option value="">Detail</option>';

    const contactOptions = ['Konfirmasi Pembayaran', 'Tidak Konfirmasi Pembayaran', 'Tutup Telpon'];
    const uncontactOptions = ['RNA', 'Tidak Aktif', 'Nomor Luar Jangkauan', 'Tidak Tersambung'];

    if (contactOptions.includes(status)) {
      typeSelect.value = 'contact';
      contactOptions.forEach(opt => {
        const o = document.createElement('option');
        o.value = opt;
        o.textContent = opt;
        if (opt === status) o.selected = true;
        detailSelect.appendChild(o);
      });
    } else if (uncontactOptions.includes(status)) {
      typeSelect.value = 'uncontact';
      uncontactOptions.forEach(opt => {
        const o = document.createElement('option');
        o.value = opt;
        o.textContent = opt;
        if (opt === status) o.selected = true;
        detailSelect.appendChild(o);
      });
    }

    document.getElementById('modal-save').textContent = (status || ket) ? 'Update' : 'Simpan';
    document.getElementById('statusModal').style.display = 'flex';
  });
});

document.getElementById('modal-status-type').addEventListener('change', function() {
  const detail = document.getElementById('modal-status-detail');
  detail.innerHTML = '<option value="">Detail</option>';
  const opt = this.value === 'contact'
    ? ['Konfirmasi Pembayaran', 'Tidak Konfirmasi Pembayaran', 'Tutup Telpon']
    : ['RNA', 'Tidak Aktif', 'Nomor Luar Jangkauan', 'Tidak Tersambung'];
  opt.forEach(o => {
    const el = document.createElement('option');
    el.value = o; el.textContent = o;
    detail.appendChild(el);
  });
});

document.getElementById('modal-cancel').addEventListener('click', () => {
  document.getElementById('statusModal').style.display = 'none';
});

document.getElementById('modal-save').addEventListener('click', () => {
  const id = document.getElementById('modal-id').value;
  const type = document.getElementById('modal-status-type').value;
  const detail = document.getElementById('modal-status-detail').value;
  const ket = document.getElementById('modal-keterangan').value;

  if (!type || !detail) {
    Swal.fire({ icon:'warning', title:'Peringatan', text:'Pilih status dan detail terlebih dahulu!' });
    return;
  }

  fetch('{{ route("caring.telepon.update") }}', {
    method:'POST',
    headers:{'Content-Type':'application/json','X-CSRF-TOKEN':'{{ csrf_token() }}'},
    body: JSON.stringify({id:id, status_call:detail, keterangan:ket})
  })
  .then(res=>res.json())
  .then(data=>{
    if(data.success){
      document.getElementById('statusModal').style.display = 'none';

      currentButton.textContent = 'Lihat';
      currentButton.dataset.status = detail;
      currentButton.dataset.keterangan = ket;

      Swal.fire({
        icon:'success',
        title:'Berhasil!',
        text:'Data berhasil disimpan.',
        timer:1800,
        showConfirmButton:false
      });
    }
  })
  .catch(()=>Swal.fire({icon:'error', title:'Gagal', text:'Terjadi kesalahan server.'}));
});
</script>
@endsection
