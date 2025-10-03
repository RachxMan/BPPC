<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Upload Data Harian - PayColl PT. Telkom</title>
  <link rel="stylesheet" href="{{ asset('css/harian.css') }}">
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
      <li><a href="{{ url('/dashboard') }}"><span>Dashboard</span></a></li>
      <li><a href="{{ url('/mailing') }}"><span>Mailing List Reminder</span></a></li>
      <li class="active"><a href="{{ url('/uploaddata') }}"><span>Upload Data</span></a></li>
      <li><a href="{{ url('/kelolaakun') }}"><span>Kelola Akun</span></a></li>
      <li><a href="{{ url('/profil') }}"><span>Profil & Pengaturan</span></a></li>
      <li><a href="{{ url('/') }}"><span>Logout</span></a></li>
    </ul>
  </aside>

  <button id="hamburger" class="hamburger" aria-label="Open menu">☰</button>
  <div id="overlay" class="overlay hidden"></div>

  <main class="main">
    <h1><span style="color:#e74c3c;">Upload Data</span><br>Harian</h1>

    <div class="upload-box" id="uploadBox">
      <div class="upload-content">
        <img src="{{ asset('img/cloud-upload-14.png') }}" alt="Upload Icon" class="upload-icon">
        <p><strong>Drag and Drop Files Here</strong></p>
        <p class="support">File Supported: Csv, Xlsx</p>
        <input type="file" id="fileInput" accept=".csv,.xlsx" hidden>
        <label for="fileInput" class="btn-upload">Choose File</label>
        <p id="fileName" class="support"></p>
      </div>
    </div>
  </main>

  <footer>
    © 2025 Business Process Payment & Collection - PT. Telkom Indonesia Tbk. Witel Riau
  </footer>

  <script src="{{ asset('js/upload.js') }}"></script>
</body>
</html>
