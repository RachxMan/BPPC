<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard PayColl PT. Telkom</title>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
</head>
<body>
  
  <aside class="sidebar" id="sidebar">
    <div class="logo">
      <img src="{{ asset('img/logo_telkom.png') }}" alt="Logo"/>
      <p>PayColl PT. Telkom</p>
    </div>

    <div class="profile">
      <img src="{{ asset('img/1594252-200.png') }}" alt="Admin"/>
      <p class="profile-name">Administrator</p>
      <span class="online">● Online</span>
    </div>

    <div class="search-box">
      <input type="text" placeholder="Search..." id="searchInput" />
      <button type="button" aria-label="search">🔍</button>
    </div>

    <ul class="menu" id="menu">
      <li class="active"><a href="{{ url('/dashboard') }}"><span>Dashboard</span></a></li>
      <li><a href="{{ url('/mailing') }}"><span>Mailing List Reminder</span></a></li>
      <li><a href="{{ url('/uploaddata') }}"><span>Upload Data</span></a></li>
      <li><a href="{{ url('/kelolaakun') }}"><span>Kelola Akun</span></a></li>
      <li><a href="{{ url('/profil') }}"><span>Profil & Pengaturan</span></a></li>
      <li><a href="{{ url('/login') }}"><span>Logout</span></a></li>
    </ul>
  </aside>

  <button id="hamburger" class="hamburger" aria-label="Open menu">☰</button>
  <div id="overlay" class="overlay hidden"></div>

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
