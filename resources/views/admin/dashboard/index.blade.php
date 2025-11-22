@extends('layouts.app')

@section('title', 'Dashboard PayColl PT. Telkom')
@section('header-title', 'Dashboard')
@section('header-subtitle', "Welcome back! Here's what's happening with your network today.")

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
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

        {{-- CA Performance Card with Search + Month --}}
        <div class="card ca-performance">
            <h3>Kinerja CA</h3>

            <div style="display:flex; gap:12px; align-items:center; margin-bottom:12px; flex-wrap:wrap;">
                <form method="GET" class="search-form" style="display:flex; gap:8px; align-items:center;">
                    <input type="text" name="search_ca" value="{{ request()->get('search_ca', '') }}" placeholder="Cari CA..." />
                    <input type="hidden" name="month" value="{{ $selectedMonth ?? now()->format('Y-m') }}">
                    <button type="submit">Cari</button>
                </form>

                <form method="GET" style="display:flex; gap:8px; align-items:center;">
                    {{-- Keep search_ca in month form so both can work together --}}
                    <input type="hidden" name="search_ca" value="{{ request()->get('search_ca', '') }}">
                    <label for="month">Pilih Bulan:</label>
                    <input type="month" id="month" name="month" value="{{ $selectedMonth ?? now()->format('Y-m') }}" onchange="this.form.submit()">
                </form>
            </div>

            <canvas id="caPerformanceChart"></canvas>
            <div class="legend-text" style="margin-top:8px; font-size:0.9rem; color:#666;">
                Garis menunjukkan jumlah kontak yang tercatat per user (CA/Admin) pada tanggal di bulan terpilih.
            </div>
        </div>

        {{-- Charts --}}
        <div class="charts" style="margin-top:16px;">
            <div class="chart card">
                <h3>Status Pembayaran</h3>
                <canvas id="pieChart"></canvas>
            </div>
            <div class="chart card">
                <h3>Progress Collection ({{ $weekLabel ?? 'Minggu Ini' }})</h3>
                <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px;">
                    <button id="prevWeek" class="btn btn-secondary btn-sm">&laquo; Previous Week</button>
                    <span id="weekPeriod">{{ $weekPeriod ?? 'Periode: ' . \Carbon\Carbon::now()->startOfWeek()->format('d M Y') . ' – ' . \Carbon\Carbon::now()->endOfWeek()->format('d M Y') }}</span>
                    <button id="nextWeek" class="btn btn-secondary btn-sm">Next Week &raquo;</button>
                </div>
                <div class="chart-stats">
                    <div class="stat-item">
                        <span class="stat-label">Total Contacted:</span>
                        <span class="stat-value">{{ $weekData->sum('contacted') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Total Paid:</span>
                        <span class="stat-value">{{ $weekData->sum('paid') }}</span>
                    </div>
                    <div class="stat-item">
                        <span class="stat-label">Success Rate:</span>
                        <span class="stat-value">
                            @php
                                $totalContacted = $weekData->sum('contacted');
                                $rateWeek = $totalContacted > 0 ? round(($weekData->sum('paid') / $totalContacted) * 100, 1) : 0;
                            @endphp
                            {{ $rateWeek }}%
                        </span>
                    </div>
                </div>
                <canvas id="barChart"></canvas>
            </div>
        </div>

        {{-- Follow Up --}}
        <div id="belum-followup" class="table-container card" style="margin-top:18px;">
            <h3>Follow Up</h3>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; flex-wrap:wrap; gap:10px;">
                <div>Total: {{ $totalBelumFollowUp ?? $belumFollowUp->total() }} data</div>
                <div style="display:flex; gap:8px; align-items:center; flex-wrap:wrap;">
                    <form method="GET" style="display:flex; gap:8px; align-items:center;">
                        <label>Filter Follow Up:</label>
                        <select name="filter_followup" onchange="this.form.submit()">
                            <option value="">Semua</option>
                            <option value="sudah" {{ request('filter_followup') == 'sudah' ? 'selected' : '' }}>Sudah Follow Up</option>
                            <option value="belum " {{ request('filter_followup') == 'belum' ? 'selected' : '' }}>Belum Follow Up</option>
                        </select>
                    </form>
                    @if(auth()->user()->role !== 'ca')
                    <form method="GET" style="display:flex; gap:8px; align-items:center;">
                        <input type="hidden" name="filter_followup" value="{{ request('filter_followup') }}">
                        <label>Filter User:</label>
                        <select name="filter_user_belum" onchange="this.form.submit()">
                            <option value="">Semua User</option>
                            @foreach($activeUsers as $user)
                            <option value="{{ $user->id }}" {{ request('filter_user_belum') == $user->id ? 'selected' : '' }}>{{ $user->nama_lengkap }}</option>
                            @endforeach
                        </select>
                    </form>
                    @endif
                </div>
            </div>
            <div class="table-scroll">
                <table class="interactive-table">
                    <thead>
                        <tr>
                            <th>ID NET</th>
                            <th>NAMA PERUSAHAAN</th>
                            @if(auth()->user()->role !== 'ca')
                            <th>USER</th>
                            @endif
                            <th>STATUS</th>
                            <th>TANGGAL FOLLOW UP</th>
                            <th>MASALAH</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($belumFollowUp as $item)
                        <tr>
                            <td>{{ $item->snd }}</td>
                            <td>{{ $item->nama }}</td>
                            @if(auth()->user()->role !== 'ca')
                            <td>{{ $item->ca_name ?? '-' }}</td>
                            @endif
                            <td>{{ $item->contact_date ? 'Sudah Follow Up' : 'Belum Follow Up' }}</td>
                            <td>{{ $item->contact_date ?? '-' }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="display:flex; justify-content:center; margin-top:10px;">
                {{ $belumFollowUp->withQueryString()->fragment('belum-followup')->links('pagination::bootstrap-4') }}
            </div>
        </div>
    </section>

    {{-- Detail Pelanggan --}}
    <section class="detail-pelanggan" style="margin-top:18px;">
        <h2>Detail Pelanggan</h2>



        {{-- Data Pelanggan --}}
        <div id="data-pelanggan" class="table-container card" style="margin-top:14px;">
            <h3>Data Pelanggan</h3>
            <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:10px; flex-wrap:wrap; gap:10px;">
                <div>Total: {{ $totalDataPelanggan ?? $dataPelanggan->total() }} data</div>
                @if(auth()->user()->role !== 'ca')
                <form method="GET" style="display:flex; gap:8px; align-items:center;">
                    <label>Filter User:</label>
                    <select name="filter_user_pelanggan" onchange="this.form.submit()">
                        <option value="">Semua User</option>
                        @foreach($activeUsers as $user)
                        <option value="{{ $user->id }}" {{ request('filter_user_pelanggan') == $user->id ? 'selected' : '' }}>{{ $user->nama_lengkap }}</option>
                        @endforeach
                    </select>
                </form>
                @endif
            </div>
            <div class="table-scroll">
                <table class="interactive-table">
                    <thead>
                        <tr>
                            <th>ID NET</th>
                            <th>NAMA PIC</th>
                            <th>ALAMAT</th>
                            <th>KONTAK</th>
                            @if(auth()->user()->role !== 'ca')
                            <th>USER</th>
                            @endif
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
                            @if(auth()->user()->role !== 'ca')
                            <td>{{ $pel->ca_name ?? '-' }}</td>
                            @endif
                            <td>{{ $pel->status_bayar }}</td>
                            <td>{{ $pel->payment_date ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="display:flex; justify-content:center; margin-top:10px;">
                {{ $dataPelanggan->withQueryString()->fragment('data-pelanggan')->links('pagination::bootstrap-4') }}
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
        <p>Apakah pelanggan dengan ID NET <strong><span id="verify-snd"></span></strong> sudah benar-benar membayar?</p>
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

/* Responsive KPI Cards */
@media (max-width: 768px) {
    .cards {
        grid-template-columns: repeat(auto-fit, minmax(120px, 1fr));
        gap: 8px;
        margin-bottom: 16px;
    }
    .card.kpi {
        padding: 10px;
    }
    .card.kpi h3 {
        font-size: 0.7rem;
    }
    .card.kpi p {
        font-size: 1rem;
    }
}

@media (max-width: 480px) {
    .cards {
        grid-template-columns: repeat(2, 1fr);
        gap: 6px;
    }
    .card.kpi {
        padding: 8px;
    }
    .card.kpi h3 {
        font-size: 0.65rem;
        margin-bottom: 2px;
    }
    .card.kpi p {
        font-size: 0.9rem;
    }
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
.search-form input[type="text"], .search-form input[type="month"] {
    padding: 8px;
    border: 1px solid #ddd;
    border-radius: 4px;
}
.search-form button {
    padding: 8px 16px;
    background: #e74c3c;
    color: white;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}
.search-form button:hover {
    background: #c0392b;
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

/* Responsive Charts */
@media (max-width: 768px) {
    .charts {
        grid-template-columns: 1fr;
        gap: 16px;
        margin-bottom: 16px;
    }
    .chart.card {
        padding: 16px;
    }
}

@media (max-width: 480px) {
    .charts {
        gap: 12px;
    }
    .chart.card {
        padding: 12px;
    }
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

/* Responsive 7 Days Section */
@media (max-width: 768px) {
    .seven-days-section {
        margin-bottom: 16px;
    }
    .chart-stats {
        flex-direction: column;
        gap: 12px;
        padding: 12px;
    }
    .stat-item {
        text-align: left;
    }
    .stat-label {
        font-size: 0.8rem;
    }
    .stat-value {
        font-size: 1.2rem;
    }
}

@media (max-width: 480px) {
    .chart-stats {
        padding: 10px;
        gap: 8px;
    }
    .stat-label {
        font-size: 0.75rem;
    }
    .stat-value {
        font-size: 1rem;
    }
}

/* Tables */
.table-container.card {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 3px 10px rgba(0,0,0,0.08);
    margin-bottom: 20px;
    overflow-x: auto;
}
.interactive-table {
    width: 100%;
    border-collapse: collapse;
    min-width: 600px; /* Ensure minimum width for proper display */
}
.interactive-table thead {
    background: #f4f4f4;
}
.interactive-table th, .interactive-table td {
    padding: 12px 15px;
    text-align: left;
    border-bottom: 1px solid #eee;
    white-space: nowrap;
}
.interactive-table tbody tr:hover {
    background: #f1faff;
}

/* Responsive Tables */
@media (max-width: 768px) {
    .table-container.card {
        padding: 16px;
        margin-bottom: 16px;
    }
    .interactive-table th, .interactive-table td {
        padding: 10px 8px;
        font-size: 0.9rem;
    }
    .interactive-table {
        min-width: 500px;
    }
}

@media (max-width: 480px) {
    .table-container.card {
        padding: 12px;
    }
    .interactive-table th, .interactive-table td {
        padding: 8px 6px;
        font-size: 0.8rem;
    }
    .interactive-table {
        min-width: 400px;
    }
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
}

/* Override btn-secondary for red color */
.btn-secondary {
    background-color: #e74c3c;
    color: #fff;
    border: none;
}

.btn-secondary:hover {
    background-color: #c0392b;
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
.notification.success { background-color: #28a745; }
.notification.error { background-color: #dc3545; }
.notification button { background: none; border: none; color: white; font-size: 20px; cursor: pointer; padding: 0; margin-left: auto; }
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>

// ====== FIXED CHART HEIGHT ======
document.querySelectorAll('canvas').forEach(c => {
    c.style.maxHeight = '360px';
    c.style.width = '100%';
});

// ===== PIE CHART =====
const pieCtx = document.getElementById('pieChart');
if (pieCtx) {
    new Chart(pieCtx, {
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
            animation: { duration: 1000 },
            plugins: { legend: { position: 'bottom' } }
        }
    });
}

// ===== WEEKLY BAR CHART =====
let barChart;
const barCtx = document.getElementById('barChart');
if (barCtx) {
    barChart = new Chart(barCtx, {
        type: 'bar',
        data: {
            labels: ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'],
            datasets: [
                {
                    label: 'Contacted',
                    data: [
                        {{ $weekData->where('day',1)->first()['contacted'] ?? 0 }},
                        {{ $weekData->where('day',2)->first()['contacted'] ?? 0 }},
                        {{ $weekData->where('day',3)->first()['contacted'] ?? 0 }},
                        {{ $weekData->where('day',4)->first()['contacted'] ?? 0 }},
                        {{ $weekData->where('day',5)->first()['contacted'] ?? 0 }},
                        {{ $weekData->where('day',6)->first()['contacted'] ?? 0 }},
                        {{ $weekData->where('day',7)->first()['contacted'] ?? 0 }}
                    ],
                    backgroundColor: '#4CAF50'
                },
                {
                    label: 'Paid',
                    data: [
                        {{ $weekData->where('day',1)->first()['paid'] ?? 0 }},
                        {{ $weekData->where('day',2)->first()['paid'] ?? 0 }},
                        {{ $weekData->where('day',3)->first()['paid'] ?? 0 }},
                        {{ $weekData->where('day',4)->first()['paid'] ?? 0 }},
                        {{ $weekData->where('day',5)->first()['paid'] ?? 0 }},
                        {{ $weekData->where('day',6)->first()['paid'] ?? 0 }},
                        {{ $weekData->where('day',7)->first()['paid'] ?? 0 }}
                    ],
                    backgroundColor: '#FFC107'
                }
            ]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: { y: { beginAtZero: true } },
            plugins: { legend: { position: 'top' } }
        }
    });
}

// ===== WEEK NAVIGATION =====
let currentWeekOffset = {{ $weekOffset ?? 0 }};
document.getElementById('prevWeek').addEventListener('click', function() {
    currentWeekOffset--;
    updateWeekData();
});
document.getElementById('nextWeek').addEventListener('click', function() {
    currentWeekOffset++;
    updateWeekData();
});

function updateWeekData() {
    const url = '{{ route("dashboard") }}?week_offset=' + currentWeekOffset + '&month={{ $selectedMonth }}&search_ca={{ urlencode($searchCA) }}';
    window.location.href = url;
}





// ===== LINE CHART - CA Performance (Bulanan) =====
(function(){
    // selectedMonth e.g. "2025-11" (YYYY-MM)
    const selectedMonth = "{{ $selectedMonth ?? now()->format('Y-m') }}";

    // make sure selectedMonth exists and is valid
    function safeDatesFromMonth(ym) {
        const parts = ym.split('-');
        if (parts.length !== 2) return [];
        const year = parseInt(parts[0],10);
        const month = parseInt(parts[1],10);
        // JS months for Date constructor: 0-based for month when creating new Date(year, monthIndex+1, 0)
        const daysInMonth = new Date(year, month, 0).getDate();
        const arr = [];
        for (let d=1; d<=daysInMonth; d++) {
            const dd = String(d).padStart(2,'0');
            arr.push(`${ym}-${dd}`);
        }
        return arr;
    }

    const allDates = safeDatesFromMonth(selectedMonth);

    // Build datasets from server-side grouped data.
    // We will produce a JS array for each user group. Server-side we embed the groups and lookup user name.
    const caPerformanceData = { labels: allDates, datasets: [] };

    @php
        // Prepare an array of user_id => nama_lengkap to embed (lookup in view)
        $userNameMap = [];
        foreach($activeUsers as $user) {
            $userNameMap[$user->id] = $user->nama_lengkap ?? 'User '.$user->id;
        }

        // Static color palette for consistent colors
        $colorPalette = ['#FF6384', '#36A2EB', '#FFCE56', '#4BC0C0', '#9966FF', '#FF9F40'];
    @endphp

    const userNameMap = @json($userNameMap);
    const colorPalette = @json($colorPalette);

    @foreach($activeUsers as $index => $user)
        @php
            $userId = $user->id;
            $rows = $caDailyPerformance->get($userId, collect());
        @endphp
        // rows contain objects like { date: 'YYYY-MM-DD', contacts_per_day: n }
        const caRows_{{ $userId }} = @json($rows->values());
        // map to full month dates
        const dataPoints_{{ $userId }} = allDates.map(dt => {
            const found = caRows_{{ $userId }}.find(r => {
                // some DB date strings may be 'YYYY-MM-DD' — ensure compare
                return r.date && (String(r.date).startsWith(dt) || String(r.date) === dt);
            });
            return found ? Number(found.contacts_per_day) : 0;
        });
        caPerformanceData.datasets.push({
            label: userNameMap["{{ $userId }}"] ? userNameMap["{{ $userId }}"] : 'User {{ $userId }}',
            data: dataPoints_{{ $userId }},
            borderColor: colorPalette[{{ $index }} % colorPalette.length],
            borderWidth: 2,
            tension: 0.25,
            fill: false,
            spanGaps: true
        });
    @endforeach

    // Optionally if user filtered by search_ca, we could hide other datasets client-side.
    const searchCA = "{{ addslashes(request()->get('search_ca', '')) }}".trim().toLowerCase();
    if (searchCA) {
        // filter datasets by label that includes searchCA
        caPerformanceData.datasets = caPerformanceData.datasets.filter(ds => ds.label.toLowerCase().includes(searchCA));
    }

    // render chart
    const caCtx = document.getElementById('caPerformanceChart');
    if (caCtx) {
        if (caPerformanceData.datasets.length > 0) {
            new Chart(caCtx, {
                type: 'line',
                data: caPerformanceData,
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    scales: {
                        y: { beginAtZero: true },
                        x: {
                            ticks: {
                                maxRotation: 45,
                                minRotation: 45,
                                callback: function(value, index, ticks) {
                                    // show only few ticks when many days
                                    const total = allDates.length;
                                    if (total > 15) {
                                        // show every 3rd label approx
                                        return (index % Math.ceil(total / 10) === 0) ? this.getLabelForValue(value) : '';
                                    }
                                    return this.getLabelForValue(value);
                                }
                            }
                        }
                    },
                    plugins: {
                        legend: { position: 'top', labels: { boxWidth: 12 } },
                        tooltip: {
                            callbacks: {
                                title: function(items) {
                                    return items[0].label;
                                },
                                label: function(ctx) {
                                    return ctx.dataset.label + ': ' + ctx.parsed.y;
                                }
                            }
                        }
                    }
                }
            });
        } else {
            caCtx.outerHTML = '<p class="text-muted">Tidak ada data user performance untuk bulan ini.</p>';
        }
    }
})();

// ===== EVENT HANDLERS (AJAX, MODAL, NOTIF) =====
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.status-dropdown').forEach(drop => {
        drop.addEventListener('change', function() {
            const id = this.getAttribute('data-id');
            const type = this.getAttribute('data-type');
            const value = this.value;

            if (type === 'payment' && value === 'PAID') {
                openVerificationModal(id);
                // revert selection while waiting confirmation
                this.value = 'UNPAID';
                return;
            }

            updateStatus(id, type, value);
        });
    });

    const confirmBtn = document.getElementById('confirm-paid');
    if (confirmBtn) {
        confirmBtn.addEventListener('click', function() {
            const snd = this.getAttribute('data-snd');
            if (snd) updateStatus(snd, 'payment', 'PAID');
            closeVerificationModal();
        });
    }
});

function updateStatus(id, type, value) {
    let url, data;
    if (type === 'followup') {
        // Follow-up status updates are now only allowed in Caring Telepon
        showNotification('Status follow-up hanya bisa diubah di halaman Caring Telepon', 'error');
        return;
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
            setTimeout(() => location.reload(), 900);
        } else {
            showNotification(res.message || 'Gagal memperbarui status', 'error');
        }
    })
    .catch(err => {
        console.error(err);
        showNotification('Terjadi kesalahan server', 'error');
    });
}

function showNotification(msg, type) {
    const exist = document.querySelector('.notification');
    if (exist) exist.remove();
    const n = document.createElement('div');
    n.className = `notification ${type}`;
    n.innerHTML = `<span>${msg}</span><button onclick="this.parentElement.remove()">&times;</button>`;
    document.body.appendChild(n);
    setTimeout(() => n.remove(), 3500);
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
