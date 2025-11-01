@extends('layouts.app')

@section('title', 'Dashboard PayColl PT. Telkom')
@section('header-title', 'Dashboard')
@section('header-subtitle', "Welcome back! Here's what's happening with your network today.")

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

        {{-- 7 Days Chart - Separate Row --}}
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
        </div>

        {{-- Belum Follow-up --}}
        <div class="table-container card">
            <h3>Belum Follow Up</h3>
            <div class="table-scroll">
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
            <a href="#" class="btn btn-link" onclick="openPopup('belum-followup')">Lihat lebih banyak</a>
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
            <div class="table-scroll">
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
                            <td>{{ $pel->datel }}</td>
                            <td>{{ $pel->cp ?? $pel->no_hp }}</td>
                            <td>{{ $pel->status_bayar }}</td>
                            <td>{{ $pel->payment_date ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="#" class="btn btn-link" onclick="openPopup('detail-pelanggan')">Lihat lebih banyak</a>
        </div>
    </section>

    {{-- Aktivitas Saya --}}
    <section class="aktivitas-saya">
        <h2>Aktivitas Saya</h2>
        {{-- User-specific KPIs would go here, but since it's admin dashboard, perhaps show overall or skip --}}
        <p>Section untuk aktivitas user login. (Implementasi tergantung kebutuhan)</p>
    </section>

</div>

{{-- Pop-up Modal --}}
<div id="popup-modal" class="modal" style="display:none;">
    <div class="modal-content">
        <span class="close" onclick="closePopup()">&times;</span>
        <div id="popup-content"></div>
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
</style>
@endpush

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
    // Pie Chart - Status Pembayaran
    const pieData = {
        labels: ['Paid','Unpaid'],
        datasets: [{
            data: [
                {{ $statusBayar['paid'] ?? 0 }},
                {{ $statusBayar['unpaid'] ?? 0 }}
            ],
            backgroundColor: ['#4CAF50','#F44336']
        }]
    };
    new Chart(document.getElementById('pieChart'), {
        type: 'pie',
        data: pieData,
        options: {
            animation: {
                animateScale: true,
                animateRotate: true,
                duration: 2000,
                easing: 'easeInOutQuart'
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'bottom'
                }
            }
        }
    });

    // Bar Chart - Progress Collection Mingguan
    const barData = {
        labels: ['Senin','Selasa','Rabu','Kamis','Jumat'],
        datasets: [
            {
                label: 'Contacted',
                backgroundColor: '#4CAF50',
                data: [
                    {{ $weekData->where('day',2)->first()['contacted'] ?? 0 }},
                    {{ $weekData->where('day',3)->first()['contacted'] ?? 0 }},
                    {{ $weekData->where('day',4)->first()['contacted'] ?? 0 }},
                    {{ $weekData->where('day',5)->first()['contacted'] ?? 0 }},
                    {{ $weekData->where('day',6)->first()['contacted'] ?? 0 }}
                ]
            },
            {
                label: 'Uncontacted',
                backgroundColor: '#F44336',
                data: [
                    {{ $weekData->where('day',2)->first()['uncontacted'] ?? 0 }},
                    {{ $weekData->where('day',3)->first()['uncontacted'] ?? 0 }},
                    {{ $weekData->where('day',4)->first()['uncontacted'] ?? 0 }},
                    {{ $weekData->where('day',5)->first()['uncontacted'] ?? 0 }},
                    {{ $weekData->where('day',6)->first()['uncontacted'] ?? 0 }}
                ]
            }
        ]
    };
    new Chart(document.getElementById('barChart'), {
        type: 'bar',
        data: barData,
        options: {
            responsive: true,
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart',
                onComplete: function() {
                    // Animation complete callback
                }
            },
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                tooltip: {
                    enabled: true,
                    callbacks: {
                        label: function(context) {
                            return context.dataset.label + ': ' + context.raw;
                        }
                    }
                }
            }
        }
    });

    // Bar Chart - Progress Collection 7 Hari
    const sevenDaysLabels = @json($sevenDaysData->pluck('date'));
    const sevenDaysContacted = @json($sevenDaysData->pluck('contacted'));
    const sevenDaysUncontacted = @json($sevenDaysData->pluck('uncontacted'));
    const sevenDaysDataChart = {
        labels: sevenDaysLabels,
        datasets: [
            {
                label: 'Contacted',
                backgroundColor: '#4CAF50',
                data: sevenDaysContacted
            },
            {
                label: 'Uncontacted',
                backgroundColor: '#F44336',
                data: sevenDaysUncontacted
            }
        ]
    };
    new Chart(document.getElementById('sevenDaysChart'), {
        type: 'bar',
        data: sevenDaysDataChart,
        options: {
            responsive: true,
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart'
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Bar Chart - Paket Terlaris
    const paketLabels = @json($paketTerlaris->pluck('type'));
    const paketData = @json($paketTerlaris->pluck('total'));
    const paketChartData = {
        labels: paketLabels,
        datasets: [{
            label: 'Jumlah',
            backgroundColor: '#2196F3',
            data: paketData
        }]
    };
    new Chart(document.getElementById('paketChart'), {
        type: 'bar',
        data: paketChartData,
        options: {
            responsive: true,
            animation: {
                duration: 2000,
                easing: 'easeInOutQuart',
                delay: function(context) {
                    return context.dataIndex * 200;
                }
            },
            scales: {
                y: { beginAtZero: true }
            }
        }
    });

    // Line Chart - CA Performance (1 bulan)
    const caPerformanceData = {
        labels: [], // Will be set dynamically
        datasets: []
    };

    // Generate labels for 30 days
    for (let i = 1; i <= 30; i++) {
        caPerformanceData.labels.push('Day ' + i);
    }

    // Add datasets for each CA
    @foreach($caMonthlyPerformance as $userId => $data)
        const dataPoints = [];
        for (let i = 1; i <= 30; i++) {
            const dayData = @json($data).find(d => d.day === i);
            dataPoints.push(dayData ? dayData.contacts_per_day : 0);
        }
        caPerformanceData.datasets.push({
            label: 'CA {{ $userId }}',
            data: dataPoints,
            borderColor: '#' + Math.floor(Math.random()*16777215).toString(16),
            fill: false
        });
    @endforeach

    if (caPerformanceData.datasets.length > 0) {
        new Chart(document.getElementById('caPerformanceChart'), {
            type: 'line',
            data: caPerformanceData,
            options: {
                responsive: true,
                animation: {
                    duration: 2500,
                    easing: 'easeInOutQuart'
                },
                scales: {
                    y: { beginAtZero: true }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'top'
                    }
                }
            }
        });
    } else {
        document.getElementById('caPerformanceChart').style.display = 'none';
        document.querySelector('#caPerformanceChart').parentNode.innerHTML += '<p>No CA performance data available</p>';
    }

    // Modal functions
    function openPopup(type) {
        const modal = document.getElementById('popup-modal');
        const content = document.getElementById('popup-content');
        if (type === 'belum-followup') {
            fetch('{{ route("caring.telepon") }}')
                .then(response => response.text())
                .then(html => {
                    content.innerHTML = html;
                });
        } else if (type === 'detail-pelanggan') {
            // Assuming a route for detail pelanggan
            content.innerHTML = `
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
                            <td>{{ $pel->datel }}</td>
                            <td>{{ $pel->cp ?? $pel->no_hp }}</td>
                            <td>{{ $pel->status_bayar }}</td>
                            <td>{{ $pel->payment_date ?? '-' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            `;
        }
        modal.style.display = 'block';
    }

    function closePopup() {
        document.getElementById('popup-modal').style.display = 'none';
    }
</script>
@endpush
