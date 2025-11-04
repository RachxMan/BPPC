@extends('layouts.app')

@section('title', 'Dashboard PayColl PT. Telkom')
@section('header-title', 'Dashboard')
@section('header-subtitle', "Welcome back! Here's what's happening with your network today.")

@push('styles')
<link rel="stylesheet" href="{{ asset('css/caring.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<div class="dashboard-container">

    {{-- Aktivitas CA --}}
    <section class="aktivitas-ca">
        <h2>Aktivitas CA</h2>

        {{-- KPI Cards --}}
        <div class="cards">
            <div class="card kpi">
                <h3>Jumlah CA Aktif</h3>
                <p>{{ $jumlahCA }}</p>
            </div>
            <div class="card kpi">
                <h3>Jumlah Admin Aktif</h3>
                <p>{{ $jumlahAdmin }}</p>
            </div>
            <div class="card kpi">
                <h3>Total Pelanggan</h3>
                <p>{{ $totalPelanggan }}</p>
            </div>
            <div class="card kpi">
                <h3>Total Follow-up</h3>
                <p>{{ $totalFollowUp }}</p>
            </div>
            <div class="card kpi">
                <h3>Recent Follow-up (Hari Ini)</h3>
                <p>{{ $recentFollowUp }}</p>
            </div>
            <div class="card kpi">
                <h3>Progress Collection</h3>
                <p>{{ $progressCollection }}%</p>
            </div>
        </div>

        {{-- CA Performance Card with Search --}}
        <div class="card ca-performance">
            <h3>Kinerja CA</h3>
            <form method="GET" class="search-form">
                <input type="text" name="search_ca" value="{{ $searchCA }}" placeholder="Cari CA...">
                <button type="submit">Cari</button>
            </form>
            <canvas id="caPerformanceChart"></canvas>
        </div>

        {{-- Charts --}}
        <div class="charts">
            <div class="chart card">
                <h3>Status Pembayaran</h3>
                <canvas id="pieChart"></canvas>
            </div>
            <div class="chart card">
                <h3>Progress Collection (Minggu Ini)</h3>
                <div class="chart-stats">
                    <div class="stat-item">
                        <span class="stat-label">Total Contacted:</span>
                        <span class="stat-value">{{ $weekData->sum('contacted') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Total Uncontacted:</span>
                        <span class="stat-value">{{ $weekData->sum('uncontacted') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Success Rate:</span>
                        <span class="stat-value">
                            @php
                                $total = $weekData->sum('contacted') + $weekData->sum('uncontacted');
                                $rate = $total > 0 ? round(($weekData->sum('contacted') / $total) * 100, 1) : 0;
                            @endphp
                            {{ $rate }}%
                        </span>
                    </div>
                </div>
                <canvas id="barChart"></canvas>
            </div>
        </div>

        <!-- {{-- 7 Days Chart - Separate Row --}}
        <div class="seven-days-section">
            <div class="chart card full-width">
                <h3>Progress Collection (7 Hari Terakhir)</h3>
                <div class="chart-stats">
                    <div class="stat-item">
                        <span class="stat-label">Total Contacted:</span>
                        <span class="stat-value">{{ $sevenDaysData->sum('contacted') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Total Uncontacted:</span>
                        <span class="stat-value">{{ $sevenDaysData->sum('uncontacted') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Success Rate:</span>
                        <span class="stat-value">
                            @php
                                $total = $sevenDaysData->sum('contacted') + $sevenDaysData->sum('uncontacted');
                                $rate = $total > 0 ? round(($sevenDaysData->sum('contacted') / $total) * 100, 1) : 0;
                            @endphp
                            {{ $rate }}%
                        </span>
                    </div>
                </div>
                <canvas id="sevenDaysChart"></canvas>
            </div>
        </div> -->

        {{-- Belum Follow-up --}}
        <div class="table-container card">
            <h3>Belum Follow Up</h3>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <div>Total: {{ $belumFollowUp->total() }} data</div>
            </div>
            <div class="table-scroll">
                <table class="interactive-table">
                    <thead>
                        <tr>
                            <th>ID NET</th>
                            <th>NAMA PERUSAHAAN</th>
                            <th>USER</th>
                            <th>STATUS</th>
                            <th>MASALAH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($belumFollowUp as $item)
                        <tr>
                            <td>{{ $item->snd }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->ca_name ?? '-' }}</td>
                            <td>
                                <select class="status-dropdown" data-id="{{ $item->id }}" data-type="followup">
                                    <option value="belum" {{ ($item->status_call == null || !in_array($item->status_call, ['Konfirmasi Pembayaran','Tidak Konfirmasi Pembayaran','Tutup Telpon'])) ? 'selected' : '' }}>Belum</option>
                                    <option value="sudah" {{ in_array($item->status_call, ['Konfirmasi Pembayaran','Tidak Konfirmasi Pembayaran','Tutup Telpon']) ? 'selected' : '' }}>Sudah</option>
                                </select>
                            </td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="display:flex; justify-content:center; margin-top:10px;">
                {{ $belumFollowUp->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </section>

    {{-- Detail Pelanggan --}}
    <section class="detail-pelanggan">
        <h2>Detail Pelanggan</h2>

        {{-- Paket Terlaris --}}
        <div class="chart card">
            <h3>Paket Terlaris</h3>
            <canvas id="paketChart"></canvas>
        </div>

        {{-- Data Pelanggan --}}
        <div class="table-container card">
            <h3>Data Pelanggan</h3>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                <div>Total: {{ $dataPelanggan->total() }} data</div>
            </div>
            <div class="table-scroll">
                <table class="interactive-table">
                    <thead>
                        <tr>
                            <th>ID NET</th>
                            <th>NAMA PIC</th>
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
                            <td>{{ $pel->datel }}</td>
                            <td>{{ $pel->cp ?? $pel->no_hp }}</td>
                            <td>
                                <select class="status-dropdown" data-id="{{ $pel->snd }}" data-type="payment">
                                    <option value="UNPAID" {{ strtolower($pel->status_bayar) == 'unpaid' ? 'selected' : '' }}>UNPAID</option>
                                    <option value="PAID" {{ strtolower($pel->status_bayar) == 'paid' ? 'selected' : '' }}>PAID</option>
                                </select>
                            </td>
                            <td>{{ $pel->payment_date ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="display:flex; justify-content:center; margin-top:10px;">
                {{ $dataPelanggan->withQueryString()->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </section>

</div>

{{-- Pop-up Modal --}}
<div id="popup-modal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <div id="popup-content"></div>
    </div>
</div>

{{-- Verification Modal for PAID --}}
<div id="verification-modal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closeVerificationModal()">&times;</span>
        <h3>Verifikasi Pembayaran</h3>
        <p>Apakah pelanggan dengan ID NET <span id="verify-snd"></span> sudah benar-benar membayar?</p>
        <div style="display:flex; gap:10px; justify-content:flex-end; margin-top:20px;">
            <button onclick="closeVerificationModal()" style="padding:8px 16px; background:#6c757d; color:white; border:none; border-radius:4px;">Batal</button>
            <button id="confirm-paid" style="padding:8px 16px; background:#28a745; color:white; border:none; border-radius:4px;">Ya, Sudah Bayar</button>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
/* General */
.dashboard-container {
    padding: 15px;
    display: flex;
    flex-direction: column;
    gap: 15px;
}

/* Sections */
.aktivitas-ca, .detail-pelanggan, .aktivitas-saya {
    margin-bottom: 40px;
}
.aktivitas-ca h2, .detail-pelanggan h2, .aktivitas-saya h2 {
    margin-bottom: 20px;
    color: #333;
    font-size: 1.4rem;
    font-weight: 600;
}

/* KPI Cards */
.cards {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(140px, 1fr));
    gap: 12px;
    margin-bottom: 20px;
}
.card.kpi {
    background: #fff;
    padding: 12px;
    border-radius: 6px;
    box-shadow: 0 2px 6px rgba(0,0,0,0.08);
    text-align: center;
    transition: transform 0.2s ease;
}
.card.kpi:hover {
    transform: translateY(-2px);
}
.card.kpi h3 {
    font-size: 0.75rem;
    color: #666;
    margin-bottom: 4px;
    line-height: 1.2;
    font-weight: 500;
}
.card.kpi p {
    font-size: 1.1rem;
    font-weight: 700;
    color: #111;
    margin: 0;
}

/* CA Performance */
.ca-performance {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}
.search-form {
    display: flex;
    gap: 10px;
    margin-bottom: 20px;
}
.search-form input {
    flex: 1;
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}
.search-form button {
    padding: 8px 16px;
    background: #007BFF;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

/* Charts */
.charts {
    display: grid;
    grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
    gap: 20px;
    margin-bottom: 20px;
}
.chart.card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
}

/* 7 Days Section */
.seven-days-section {
    margin-bottom: 20px;
}
.full-width {
    width: 100%;
    max-width: none;
}
.chart-stats {
    display: flex;
    justify-content: space-around;
    margin-bottom: 15px;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 8px;
    gap: 20px;
}
.stat-item {
    text-align: center;
    flex: 1;
}
.stat-label {
    display: block;
    font-size: 0.85rem;
    color: #666;
    margin-bottom: 5px;
    font-weight: 500;
}
.stat-value {
    display: block;
    font-size: 1.4rem;
    font-weight: 700;
    color: #333;
}
.legend-text {
    margin-top: 8px;
    font-size: 0.9rem;
}

/* Tables */
.table-container.card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    margin-bottom: 20px;
}
.table-container h3 {
    margin-bottom: 15px;
}
.table-scroll {
    overflow-x: auto;
}
.interactive-table {
    width: 100%;
    border-collapse: collapse;
}
.interactive-table thead {
    background: #f4f4f4;
}
.interactive-table th, .interactive-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
}
.interactive-table tbody tr:hover {
    background: #f1faff;
}
.btn-link {
    display: inline-block;
    margin-top: 10px;
    color: #007BFF;
    text-decoration: none;
}
.btn-link:hover {
    text-decoration: underline;
}

/* Status Dropdown Styling */
.status-dropdown {
    padding: 6px 10px;
    border: 1px solid #ddd;
    border-radius: 4px;
    background: white;
    font-size: 0.9rem;
    min-width: 100px;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

.status-dropdown:focus {
    outline: none;
    border-color: #007bff;
    box-shadow: 0 0 0 2px rgba(0, 123, 255, 0.25);
}

.status-dropdown:hover {
    border-color: #007bff;
}

/* Modal */
.modal {
    display: none;
    position: fixed;
    z-index: 1000;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
}
.modal-content {
    background-color: #fff;
    margin: 5% auto;
    padding: 20px;
    border-radius: 12px;
    width: 80%;
    max-height: 80%;
    overflow-y: auto;
}
.close {
    color: #aaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
    cursor: pointer;
}
.close:hover {
    color: black;
}

/* Notification */
.notification {
    position: fixed;
    top: 20px;
    right: 20px;
    padding: 15px 20px;
    border-radius: 8px;
    color: white;
    font-weight: 500;
    z-index: 1001;
    box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    display: flex;
    align-items: center;
    gap: 10px;
    max-width: 400px;
}

.notification.success {
    background-color: #28a745;
}

.notification.error {
    background-color: #dc3545;
}

.notification button {
    background: none;
    border: none;
    color: white;
    font-size: 20px;
    cursor: pointer;
    padding: 0;
    margin-left: auto;
}
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// ====== GLOBAL FIXED CHART HEIGHT ======
const chartCanvases = document.querySelectorAll('canvas');
chartCanvases.forEach(c => {
    c.style.maxHeight = '360px';
    c.style.width = '100%';
});

// ===== PIE CHART =====
new Chart(document.getElementById('pieChart'), {
    type: 'pie',
    data: {
        labels: ['Paid','Unpaid'],
        datasets: [{
            data: [{{ $statusBayar['paid'] ?? 0 }}, {{ $statusBayar['unpaid'] ?? 0 }}],
            backgroundColor: ['#4CAF50','#F44336']
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        animation: { duration: 1200, easing: 'easeOutQuart' },
        plugins: { legend: { display: true, position: 'bottom' } }
    }
});

// ===== BAR CHART - WEEKLY =====
new Chart(document.getElementById('barChart'), {
    type: 'bar',
    data: {
        labels: ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'],
        datasets: [
            {
                label: 'Contacted',
                backgroundColor: '#4CAF50',
                data: [
                    {{ $weekData->where('day',1)->first()['contacted'] ?? 0 }},
                    {{ $weekData->where('day',2)->first()['contacted'] ?? 0 }},
                    {{ $weekData->where('day',3)->first()['contacted'] ?? 0 }},
                    {{ $weekData->where('day',4)->first()['contacted'] ?? 0 }},
                    {{ $weekData->where('day',5)->first()['contacted'] ?? 0 }},
                    {{ $weekData->where('day',6)->first()['contacted'] ?? 0 }},
                    {{ $weekData->where('day',7)->first()['contacted'] ?? 0 }}
                ]
            },
            {
                label: 'Uncontacted',
                backgroundColor: '#F44336',
                data: [
                    {{ $weekData->where('day',1)->first()['uncontacted'] ?? 0 }},
                    {{ $weekData->where('day',2)->first()['uncontacted'] ?? 0 }},
                    {{ $weekData->where('day',3)->first()['uncontacted'] ?? 0 }},
                    {{ $weekData->where('day',4)->first()['uncontacted'] ?? 0 }},
                    {{ $weekData->where('day',5)->first()['uncontacted'] ?? 0 }},
                    {{ $weekData->where('day',6)->first()['uncontacted'] ?? 0 }},
                    {{ $weekData->where('day',7)->first()['uncontacted'] ?? 0 }}
                ]
            }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true } },
        plugins: {
            legend: { position: 'top' },
            tooltip: { callbacks: { label: ctx => `${ctx.dataset.label}: ${ctx.formattedValue}` } }
        }
    }
});

// ===== BAR CHART - 7 DAYS =====
new Chart(document.getElementById('sevenDaysChart'), {
    type: 'bar',
    data: {
        labels: @json($sevenDaysData->pluck('date')),
        datasets: [
            { label: 'Contacted', backgroundColor: '#4CAF50', data: @json($sevenDaysData->pluck('contacted')) },
            { label: 'Uncontacted', backgroundColor: '#F44336', data: @json($sevenDaysData->pluck('uncontacted')) }
        ]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true } },
        plugins: { legend: { position: 'top' } }
    }
});

// ===== BAR CHART - Paket Terlaris =====
new Chart(document.getElementById('paketChart'), {
    type: 'bar',
    data: {
        labels: @json($paketTerlaris->pluck('type')),
        datasets: [{
            label: 'Jumlah',
            backgroundColor: '#2196F3',
            data: @json($paketTerlaris->pluck('total'))
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        scales: { y: { beginAtZero: true } }
    }
});

// ===== LINE CHART - CA Performance =====
// ===== LINE CHART - CA Performance =====
const allDates = [...new Set(@json(
    collect($caMonthlyPerformance)->flatten(1)->pluck('date')
))];
const caPerformanceData = { labels: allDates, datasets: [] };

@foreach($caMonthlyPerformance as $userId => $data)
    const caData{{ $userId }} = @json($data);
    const dataPoints{{ $userId }} = allDates.map(date => {
        const found = caData{{ $userId }}.find(d => d.date === date);
        return found ? found.contacts_per_day : 0;
    });
    caPerformanceData.datasets.push({
        label: 'CA {{ $userId }}',
        data: dataPoints{{ $userId }},
        borderColor: '#' + Math.floor(Math.random() * 16777215).toString(16),
        borderWidth: 2,
        tension: 0.3,
        fill: false
    });
@endforeach

if (caPerformanceData.datasets.length > 0) {
    new Chart(document.getElementById('caPerformanceChart'), {
        type: 'line',
        data: caPerformanceData,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true },
                x: { ticks: { maxRotation: 45, minRotation: 45 } }
            },
            plugins: { legend: { position: 'top' } }
        }
    });
} else {
    document.getElementById('caPerformanceChart').outerHTML =
        '<p class="text-muted">Tidak ada data CA performance</p>';
}

// ===== EVENT HANDLERS (AJAX, MODAL, NOTIF) =====
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-dropdown').forEach(drop => {
        drop.addEventListener('change', function() {
            const id = this.getAttribute('data-id');
            const type = this.getAttribute('data-type');
            const value = this.value;

            if (type === 'payment' && value === 'PAID') {
                openVerificationModal(id);
                this.value = 'UNPAID';
                return;
            }

            updateStatus(id, type, value);
        });
    });

    document.getElementById('confirm-paid').addEventListener('click', function() {
        const snd = this.getAttribute('data-snd');
        updateStatus(snd, 'payment', 'PAID');
        closeVerificationModal();
    });
});

function updateStatus(id, type, value) {
    let url, data;
    if (type === 'followup') {
        url = '{{ route("admin.dashboard.updateFollowupStatus") }}';
        data = { id: id, status: value };
    } else {
        url = '{{ route("admin.dashboard.updatePaymentStatus") }}';
        data = { snd: id, status: value };
    }

    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify(data)
    })
    .then(res => res.json())
    .then(res => {
        if (res.success) {
            showNotification(res.message || 'Status berhasil diperbarui', 'success');
            setTimeout(() => location.reload(), 1200);
        } else {
            showNotification('Gagal memperbarui status', 'error');
        }
    })
    .catch(() => showNotification('Terjadi kesalahan server', 'error'));
}

function showNotification(msg, type) {
    const exist = document.querySelector('.notification');
    if (exist) exist.remove();
    const n = document.createElement('div');
    n.className = `notification ${type}`;
    n.innerHTML = `<span>${msg}</span><button onclick="this.parentElement.remove()">&times;</button>`;
    document.body.appendChild(n);
    setTimeout(() => n.remove(), 3000);
}

function openVerificationModal(snd) {
    document.getElementById('verify-snd').textContent = snd;
    document.getElementById('verification-modal').style.display = 'block';
    document.getElementById('confirm-paid').setAttribute('data-snd', snd);
}
function closeVerificationModal() {
    document.getElementById('verification-modal').style.display = 'none';
}
</script>
@endpush
