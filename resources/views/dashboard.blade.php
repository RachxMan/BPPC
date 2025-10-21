@extends('layouts.app')

@section('title', 'Dashboard PayColl PT. Telkom')

@section('header-title', 'Dashboard')
@section('header-subtitle', "Welcome back! Here's what's happening with your network today.")

@section('content')

  {{-- Area KPI --}}
  <section class="cards" id="kpiArea">
    {{-- Data KPI dimuat lewat JS --}}
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
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@push('scripts')
  <script src="{{ asset('js/dashboard.js') }}"></script>
@endpush
