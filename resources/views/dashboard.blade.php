<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard PayColl PT. Telkom</title>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
  
    <x-sidebar />

  <main class="main" id="main">
    <header class="header">
      <h1>Dashboard</h1>
      <p class="subtitle">Welcome back! Here's what's happening with your network today.</p>
    </header>

    <section class="cards" id="kpiArea"></section>

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
  </main>

  <footer class="footer">
    © 2025 Business Process Payment & Collection - PT. Telkom Indonesia Tbk. Witel Riau
  </footer>

  <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
