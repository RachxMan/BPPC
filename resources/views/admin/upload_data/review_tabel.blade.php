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
        @if($type === 'harian')
            <button type="button" id="combineDataBtn" class="btn btn-info">
                🔄 Distribusi Data Random ke CA/Admin
            </button>
        @endif
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
                @if($type === 'harian')
                    & Distribusi Otomatis
                @endif
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
                            <th>Alamat</th>
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
                                <td>{{ $row['alamat'] ?? '-' }}</td>
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

<!-- MODAL KONFIRMASI -->
<div id="confirmModal" class="modal hidden" aria-hidden="true">
  <div class="modal-content" role="dialog" aria-modal="true">
    <h3 id="confirmTitle">Konfirmasi</h3>
    <p id="confirmMessage">Apakah Anda yakin ingin menyimpan perubahan?</p>
    <button id="confirmYesBtn" class="btn btn-red" type="button">Ya</button>
    <button id="confirmNoBtn" class="btn btn-gray" type="button">Batal</button>
  </div>
</div>

<!-- MODAL NOTIFIKASI -->
<div id="notificationModal" class="modal hidden" aria-hidden="true">
  <div class="modal-content" role="dialog" aria-modal="true">
    <h3 id="notificationTitle">Notifikasi</h3>
    <p id="notificationMessage"></p>
    <button id="notificationCloseBtn" class="btn btn-red" type="button">Tutup</button>
  </div>
</div>

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
/* Modal Styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}
.modal.hidden {
    display: none;
}
.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    max-width: 400px;
    width: 90%;
    text-align: center;
}
.modal-content h3 {
    margin-top: 0;
}
.btn {
    padding: 10px 20px;
    margin: 5px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.btn-red {
    background-color: #dc3545;
    color: white;
}
.btn-gray {
    background-color: #6c757d;
    color: white;
}
/* Responsive Design */

/* Tablet Styles */
@media (max-width: 1024px) {
    .review-container {
        max-width: 100%;
        padding-left: 15px;
        padding-right: 15px;
    }

    .action-buttons {
        flex-direction: column;
        align-items: stretch;
    }

    .action-buttons .btn {
        width: 100%;
        margin-bottom: 5px;
    }

    table {
        font-size: 0.8rem;
    }

    table th, table td {
        padding: 6px 8px;
    }

    .table-wrapper {
        margin-top: 10px;
    }
}

/* Mobile Styles */
@media (max-width: 768px) {
    .review-container {
        margin: 15px auto;
        padding-left: 10px;
        padding-right: 10px;
    }

    .action-buttons {
        gap: 8px;
    }

    .action-buttons .btn {
        padding: 10px 16px;
        font-size: 1rem;
    }

    .table-wrapper {
        margin-top: 10px;
        border-radius: 6px;
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }

    table {
        min-width: 800px;
        font-size: 0.75rem;
    }

    table th, table td {
        padding: 4px 6px;
        white-space: nowrap;
    }

    .table-dark th {
        font-size: 0.7rem;
        padding: 6px 8px;
    }

    .alert {
        padding: 12px 15px;
        font-size: 1rem;
    }

    .mb-3 {
        margin-bottom: 1rem;
    }
}

/* Small Mobile Styles */
@media (max-width: 480px) {
    .review-container {
        padding-left: 5px;
        padding-right: 5px;
    }

    .action-buttons .btn {
        padding: 8px 12px;
        font-size: 0.9rem;
    }

    table {
        min-width: 600px;
        font-size: 0.7rem;
    }

    table th, table td {
        padding: 3px 4px;
    }

    .table-dark th {
        font-size: 0.65rem;
        padding: 4px 6px;
    }

    .alert {
        padding: 10px 12px;
        font-size: 0.9rem;
    }
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
    const combineBtn = document.getElementById('combineDataBtn'); // may be null if not harian
    const backBtn = document.getElementById('backBtn');
    const form = document.getElementById('submitForm');

    // Modal elements
    const confirmModal = document.getElementById('confirmModal');
    const confirmTitle = document.getElementById('confirmTitle');
    const confirmMessage = document.getElementById('confirmMessage');
    const confirmYesBtn = document.getElementById('confirmYesBtn');
    const confirmNoBtn = document.getElementById('confirmNoBtn');
    const notificationModal = document.getElementById('notificationModal');
    const notificationTitle = document.getElementById('notificationTitle');
    const notificationMessage = document.getElementById('notificationMessage');
    const notificationCloseBtn = document.getElementById('notificationCloseBtn');

    // Save original button texts to restore later
    const originalSubmitText = submitBtn ? submitBtn.textContent.trim() : 'Submit';
    const originalCombineText = combineBtn ? combineBtn.textContent.trim() : '';

    // Function to show notification modal
    function showNotification(title, message) {
        notificationTitle.textContent = title;
        notificationMessage.textContent = message;
        notificationModal.classList.remove('hidden');
        notificationModal.setAttribute('aria-hidden', 'false');
    }

    // Function to hide notification modal
    function hideNotification() {
        notificationModal.classList.add('hidden');
        notificationModal.setAttribute('aria-hidden', 'true');
    }

    // Function to show confirm modal
    function showConfirm(title, message, onYes) {
        confirmTitle.textContent = title;
        confirmMessage.textContent = message;
        confirmModal.classList.remove('hidden');
        confirmModal.setAttribute('aria-hidden', 'false');

        const handleYes = () => {
            confirmModal.classList.add('hidden');
            confirmModal.setAttribute('aria-hidden', 'true');
            confirmYesBtn.removeEventListener('click', handleYes);
            onYes();
        };

        confirmYesBtn.addEventListener('click', handleYes);
    }

    // Hide confirm modal on No
    confirmNoBtn.addEventListener('click', () => {
        confirmModal.classList.add('hidden');
        confirmModal.setAttribute('aria-hidden', 'true');
    });

    // Hide notification modal
    notificationCloseBtn.addEventListener('click', hideNotification);

    // Click outside to close modals
    confirmModal.addEventListener('click', (e) => {
        if (e.target === confirmModal) {
            confirmModal.classList.add('hidden');
            confirmModal.setAttribute('aria-hidden', 'true');
        }
    });

    notificationModal.addEventListener('click', (e) => {
        if (e.target === notificationModal) {
            hideNotification();
        }
    });

    // Submit Semua Data ke Database
    if (form) {
        form.addEventListener('submit', async function (e) {
            e.preventDefault();
            showConfirm('Konfirmasi', 'Yakin ingin menyimpan semua data ke database?', async () => {
                if (!submitBtn) return;
                submitBtn.disabled = true;
                submitBtn.textContent = '⏳ Menyimpan...';

                try {
                    // Prepare fetch options: include X-Requested-With and Accept to hint Laravel to return JSON
                    const headers = {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    };

                    const res = await fetch(form.action, {
                        method: 'POST',
                        headers: headers,
                        body: new FormData(form),
                        credentials: 'same-origin'
                    });

                    // Try to detect content type
                    const contentType = res.headers.get('content-type') || '';

                    let data = null;
                    if (contentType.includes('application/json')) {
                        // If server returned JSON, parse it
                        data = await res.json();
                    } else {
                        // If not JSON, try text and attempt JSON.parse; otherwise fallback
                        const txt = await res.text();
                        try {
                            data = JSON.parse(txt);
                        } catch (err) {
                            // Not JSON — but if response OK (2xx), treat as success.
                            data = null;
                            console.warn('Response bukan JSON:', txt);
                        }
                    }

                    // Decision logic:
                    if (data && typeof data === 'object') {
                        if (data.success) {
                            showNotification('Berhasil', data.message || 'Data berhasil disimpan ke database!');
                            @if($type === 'harian')
                                // kalau harian, redirect ke halaman upload.harian jika server menyarankan
                                window.location.href = "{{ route('upload.harian') }}";
                            @else
                                // aktifkan tombol distribusi jika ada
                                if (combineBtn) combineBtn.disabled = false;
                            @endif
                        } else {
                            // Server returned JSON but success = false
                            const msg = data.message || 'Gagal menyimpan data.';
                            showNotification('Gagal', msg);
                        }
                    } else {
                        // Tidak ada JSON — fallback: gunakan status HTTP
                        if (res.ok) {
                            // Anggap berhasil (server mungkin melakukan redirect atau mengembalikan HTML)
                            showNotification('Berhasil', 'Data berhasil disimpan ke database!');
                            @if($type === 'harian')
                                window.location.href = "{{ route('upload.harian') }}";
                            @else
                                if (combineBtn) combineBtn.disabled = false;
                            @endif
                        } else {
                            showNotification('Gagal', 'Gagal menyimpan data. (Response tidak valid dari server)');
                        }
                    }
                } catch (err) {
                    console.error(err);
                    showNotification('Kesalahan', 'Terjadi kesalahan saat menyimpan data.');
                } finally {
                    if (submitBtn) {
                        submitBtn.disabled = false;
                        submitBtn.textContent = originalSubmitText;
                    }
                }
            });
        });
    }

    // Distribusi Data Random ke CA/Admin
    if (combineBtn) {
        combineBtn.addEventListener('click', async function () {
            showConfirm('Konfirmasi', 'Yakin ingin mendistribusikan data ke semua CA/Admin secara random?', async () => {
                combineBtn.disabled = true;
                combineBtn.textContent = '🔄 Membagi data...';

                try {
                    const res = await fetch("{{ route('upload.combineCA') }}", {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json',
                            'Content-Type': 'application/json'
                        },
                        credentials: 'same-origin',
                        body: JSON.stringify({ file_id: '{{ $fileId }}' })
                    });

                    let data = null;
                    const contentType = res.headers.get('content-type') || '';
                    if (contentType.includes('application/json')) {
                        data = await res.json();
                    } else {
                        const txt = await res.text();
                        try {
                            data = JSON.parse(txt);
                        } catch (err) {
                            data = null;
                            console.warn('Combine response bukan JSON:', txt);
                        }
                    }

                    if (data && data.success) {
                        showNotification('Berhasil', data.message || 'Data berhasil dibagi ke seluruh CA/Admin!');
                        window.location.href = "{{ route('upload.harian') }}";
                    } else if (res.ok && !data) {
                        // fallback treat OK as success
                        showNotification('Berhasil', 'Data berhasil dibagi ke seluruh CA/Admin!');
                        window.location.href = "{{ route('upload.harian') }}";
                    } else {
                        showNotification('Gagal', (data && data.message) ? data.message : 'Distribusi gagal.');
                        combineBtn.disabled = false;
                        combineBtn.textContent = originalCombineText;
                    }
                } catch (err) {
                    console.error(err);
                    showNotification('Kesalahan', 'Terjadi kesalahan saat mendistribusikan data.');
                    combineBtn.disabled = false;
                    combineBtn.textContent = originalCombineText;
                }
            });
        });
    }

    // Tombol Back
    if (backBtn) {
        backBtn.addEventListener('click', function (e) {
            e.preventDefault();
            window.location.href = "{{ $type === 'bulanan' ? route('upload.bulanan') : route('upload.harian') }}";
        });
    }
});
</script>
@endpush
