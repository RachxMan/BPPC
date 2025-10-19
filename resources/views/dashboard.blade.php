@extends('layouts.app')

@section('title', 'Dashboard PayColl PT. Telkom')

@section('header-title', 'Dashboard')
@section('header-subtitle', "Welcome back! Here's what's happening with your network today.")

@section('content')
  {{-- Area KPI --}}
  <section class="cards" id="kpiArea">
    {{-- Data KPI akan dimuat lewat JS --}}
  </section>

  {{-- Grafik --}}
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

  {{-- Tabel --}}
  <section class="tables">
    <div class="table-container card">
      <h3>Belum Follow Up</h3>
      <div class="table-scroll">
        <table id="orderTable">
          <thead>
            <tr>
              <th>ID NET</th>
              <th>NAMA</th>
              <th>MASALAH</th>
              <th>AKSI</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>

    <div class="table-container card">
      <h3>Data Pelanggan</h3>
      <div class="table-scroll">
        <table id="customerTable">
          <thead>
            <tr>
              <th>ID NET</th>
              <th>NAMA</th>
              <th>ALAMAT</th>
              <th>KONTAK</th>
              <th>STATUS</th>
            </tr>
          </thead>
          <tbody></tbody>
        </table>
      </div>
    </div>
  </section>
@endsection

@push('styles')
<style>
/* ===== Layout khusus konten dashboard ===== */
.main {
  padding: 2rem;
  background: #f8f9fa;
}

.cards {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.card {
  background: #fff;
  border-radius: 10px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.08);
  padding: 1.2rem;
}

.charts {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(350px, 1fr));
  gap: 1.5rem;
  margin-bottom: 2rem;
}

.chart h3 {
  margin-bottom: 1rem;
  font-weight: 600;
  color: #333;
}

.tables {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(400px, 1fr));
  gap: 1.5rem;
}

.table-container h3 {
  margin-bottom: 1rem;
  font-weight: 600;
  color: #333;
}

.table-scroll {
  overflow-x: auto;
}

table {
  width: 100%;
  border-collapse: collapse;
  font-size: 0.9rem;
}

table thead {
  background: #d32f2f;
  color: white;
}

table th, table td {
  padding: 0.8rem 1rem;
  text-align: left;
  border-bottom: 1px solid #eee;
}

table tbody tr:hover {
  background: #fff3f3;
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
