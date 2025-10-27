@extends('layouts.app')

@section('title', 'Dashboard PayColl PT. Telkom')
@section('header-title', 'Dashboard')
@section('header-subtitle', "Welcome back! Here's what's happening with your network today.")

@section('content')
<div class="dashboard-container">

    {{-- KPI Cards --}}
    <section class="cards">
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
    </section>

    {{-- Charts --}}
    <section class="charts">
        <div class="chart card">
            <h3>Status Pembayaran</h3>
            <canvas id="pieChart"></canvas>
        </div>
        <div class="chart card">
            <h3>Progress Collection (Minggu Ini)</h3>
            <canvas id="barChart"></canvas>
        </div>
    </section>

    {{-- Tables --}}
    <section class="tables">

        {{-- Belum Follow-up --}}
        <div class="table-container card">
            <h3>Belum Follow Up</h3>
            <div class="table-scroll">
                <table class="interactive-table">
                    <thead>
                        <tr>
                            <th>ID NET</th>
                            <th>NAMA</th>
                            <th>MASALAH</th>
                            <th>AKSI</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($belumFollowUp as $item)
                        <tr>
                            <td>{{ $item->snd }}</td>
                            <td>{{ $item->nama }}</td>
                            <td>{{ $item->keterangan ?? '-' }}</td>
                            <td>{{ $item->status_call ?? 'Belum' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <a href="{{ route('caring.telepon') }}" class="btn btn-link">Lihat lebih banyak</a>
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
            <a href="{{ route('caring.telepon') }}" class="btn btn-link">Lihat lebih banyak</a>
        </div>

    </section>
</div>
@endsection

@push('styles')
<style>
/* General */
.dashboard-container {
    padding: 20px;
    display: flex;
    flex-direction: column;
    gap: 20px;
}

/* KPI Cards */
.cards {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.card.kpi {
    flex: 1 1 200px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
    text-align: center;
    transition: transform 0.2s ease;
}
.card.kpi:hover {
    transform: translateY(-5px);
}
.card.kpi h3 {
    font-size: 1rem;
    color: #555;
    margin-bottom: 10px;
}
.card.kpi p {
    font-size: 1.5rem;
    font-weight: 600;
    color: #111;
}

/* Charts */
.charts {
    display: flex;
    gap: 20px;
    flex-wrap: wrap;
}
.chart.card {
    flex: 1 1 400px;
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
}
.legend-text {
    margin-top: 8px;
    font-size: 0.9rem;
}

/* Tables */
.tables {
    display: flex;
    flex-direction: column;
    gap: 20px;
}
.table-container.card {
    background: #fff;
    padding: 20px;
    border-radius: 12px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.1);
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
            responsive:true,
            scales:{
                y:{ beginAtZero:true }
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
</script>
@endpush