@extends('layouts.app')

@section('title', 'Review Tabel - PayColl PT. Telkom')
@section('header-title', 'Review Table')
@section('header-subtitle', $type === 'bulanan' ? 'Bulanan' : 'Harian')

@section('content')
<div class="review-container">

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="alert alert-success alert-dismissible">
            {{ session('success') }}
            <button type="button" class="close-btn" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible">
            {{ session('error') }}
            <button type="button" class="close-btn" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif

    {{-- Tombol Navigasi --}}
    <div class="action-buttons mb-3">
        <a href="{{ $type === 'bulanan' ? route('upload.bulanan') : route('upload.harian') }}" 
           id="backBtn" class="btn btn-secondary">⬅️ Back to Upload</a>
        <button type="button" id="combineDataBtn" class="btn btn-info" disabled>
            🔄 Distribusi Data Random ke CA/Admin
        </button>
    </div>

    {{-- Form Submit --}}
    <form id="submitForm" 
          action="{{ $type === 'bulanan' ? route('upload.bulanan.submit', $fileId) : route('upload.harian.submit', $fileId) }}" 
          method="POST">
        @csrf
        <div class="mb-3">
            <p><strong>Preview Data:</strong> <em>{{ count($rows) }} rows</em></p>
        </div>

        {{-- Tombol Submit --}}
        <div class="mb-3">
            <button type="submit" id="submitBtn" class="btn btn-success">
                ✅ Submit Semua Data ke Database
            </button>
        </div>

        {{-- Tabel Review --}}
        <div class="table-wrapper">
            <div class="table-responsive">
                <table class="table table-bordered table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>No</th>
                            <th>Witel</th>
                            <th>Type</th>
                            <th>Produk Bundling</th>
                            <th>FI HOME</th>
                            <th>Account Num</th>
                            <th>SND</th>
                            <th>SND Group</th>
                            <th>Nama</th>
                            <th>CP</th>
                            <th>Datel</th>
                            <th>Payment Date</th>
                            <th>Status Bayar</th>
                            <th>No HP</th>
                            <th>Nama Real</th>
                            <th>Segmen Real</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rows as $index => $row)
                            <tr>
                                <td>{{ $index + 1 }}</td>
                                <td>{{ $row['witel'] ?? '-' }}</td>
                                <td>{{ $row['type'] ?? '-' }}</td>
                                <td>{{ $row['produk_bundling'] ?? '-' }}</td>
                                <td>{{ $row['fi_home'] ?? '-' }}</td>
                                <td>{{ $row['account_num'] ?? '-' }}</td>
                                <td>{{ $row['snd'] ?? '-' }}</td>
                                <td>{{ $row['snd_group'] ?? '-' }}</td>
                                <td>{{ $row['nama'] ?? '-' }}</td>
                                <td>{{ $row['cp'] ?? '-' }}</td>
                                <td>{{ $row['datel'] ?? '-' }}</td>
                                <td>{{ $row['payment_date'] ?? '-' }}</td>
                                <td>{{ $row['status_bayar'] ?? '-' }}</td>
                                <td>{{ $row['no_hp'] ?? '-' }}</td>
                                <td>{{ $row['nama_real'] ?? '-' }}</td>
                                <td>{{ $row['segmen_real'] ?? '-' }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </form>
</div>
@endsection

@push('styles')
<style>
.review-container {
    max-width: 95%;
    margin: 20px auto;
    padding-left: 0;
    padding-right: 20px;
}
.action-buttons {
    display: flex;
    gap: 10px;
    flex-wrap: wrap;
}
.table-wrapper {
    overflow-x: auto;
    margin-top: 15px;
}
table {
    width: 100%;
    border-collapse: collapse;
    font-size: 0.9rem;
}
table th, table td {
    padding: 8px 12px;
    text-align: left;
    white-space: nowrap;
}
.table-striped tbody tr:nth-child(odd) { background-color: #f9f9f9; }
.table-hover tbody tr:hover { background-color: #e9ecef; }
.table-bordered th, .table-bordered td { border: 1px solid #dee2e6; }
.table-dark th { background-color: #343a40; color: #fff; }

.btn {
    padding: 7px 16px;
    border-radius: 6px;
    border: none;
    cursor: pointer;
    text-decoration: none;
    font-weight: 500;
    transition: 0.2s;
}
.btn-success { background-color: #28a745; color: white; }
.btn-success:hover { background-color: #218838; }
.btn-secondary { background-color: #6c757d; color: white; }
.btn-secondary:hover { background-color: #5a6268; }
.btn-info { background-color: #17a2b8; color: white; }
.btn-info:hover { background-color: #138496; }
.alert {
    position: relative;
    padding: 10px 20px;
    margin-bottom: 15px;
    border-radius: 5px;
}
.alert-success { background-color: #d4edda; color: #155724; }
.alert-danger { background-color: #f8d7da; color: #721c24; }
.close-btn {
    position: absolute;
    top: 5px;
    right: 10px;
    background: none;
    border: none;
    font-size: 1.2rem;
    cursor: pointer;
}
@media screen and (max-width: 1200px) {
    .review-container { padding-left: 20px; padding-right: 20px; }
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const submitBtn = document.getElementById('submitBtn');
    const combineBtn = document.getElementById('combineDataBtn');
    const backBtn = document.getElementById('backBtn');
    const form = document.getElementById('submitForm');

    // Submit Semua Data ke Database
    form.addEventListener('submit', async function (e) {
        e.preventDefault();
        if (!confirm('Yakin ingin menyimpan semua data ke database?')) return;

        submitBtn.disabled = true;
        submitBtn.textContent = '⏳ Menyimpan...';

        try {
            const res = await fetch(form.action, {
                method: 'POST',
                body: new FormData(form)
            });

            const text = await res.text();
            let data;

            try {
                data = JSON.parse(text);
            } catch {
                console.warn('Response bukan JSON:', text);
                throw new Error('Response tidak dalam format JSON');
            }

            if (data.success) {
                alert('✅ ' + (data.message || 'Data berhasil disimpan ke database!'));
                combineBtn.disabled = false; // aktifkan tombol distribusi
            } else {
                alert('⚠️ ' + (data.message || 'Gagal menyimpan data.'));
            }
        } catch (err) {
            console.error(err);
            alert('❌ Terjadi kesalahan saat menyimpan data.');
        } finally {
            submitBtn.disabled = false;
            submitBtn.textContent = '✅ Submit Semua Data ke Database';
        }
    });

    // Distribusi Data Random ke CA/Admin
    combineBtn.addEventListener('click', async function () {
        if (!confirm('Yakin ingin mendistribusikan data ke semua CA/Admin secara random?')) return;

        combineBtn.disabled = true;
        combineBtn.textContent = '🔄 Membagi data...';

        try {
            const res = await fetch("{{ route('upload.combineCA') }}", {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({ file_id: '{{ $fileId }}' })
            });

            const data = await res.json();

            if (data.success) {
                alert('✅ ' + (data.message || 'Data berhasil dibagi ke seluruh CA/Admin!'));
                window.location.href = "{{ route('upload.harian') }}";
            } else {
                alert('⚠️ ' + (data.message || 'Distribusi gagal.'));
                combineBtn.disabled = false;
                combineBtn.textContent = '🔄 Distribusi Data Random ke CA/Admin';
            }
        } catch (err) {
            console.error(err);
            alert('❌ Terjadi kesalahan saat mendistribusikan data.');
            combineBtn.disabled = false;
            combineBtn.textContent = '🔄 Distribusi Data Random ke CA/Admin';
        }
    });

    // Tombol Back
    backBtn.addEventListener('click', function (e) {
        e.preventDefault();
        window.location.href = "{{ $type === 'bulanan' ? route('upload.bulanan') : route('upload.harian') }}";
    });
});
</script>
@endpush
