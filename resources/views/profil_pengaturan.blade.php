<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Dashboard PayColl PT. Telkom</title>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}" />
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
      <li><a href="{{ url('/mailing-list') }}"><span>Mailing List Reminder</span></a></li>
      <li><a href="{{ url('/upload-data') }}"><span>Upload Data</span></a></li>
      <li><a href="{{ url('/kelola-akun') }}"><span>Kelola Akun</span></a></li>
      <li class="active"><a href="{{ url('/profil&pengaturan') }}"><span>Profil & Pengaturan</span></a></li>
      <li><a href="{{ url('/') }}"><span>Logout</span></a></li>
    </ul>
  </aside>

  <button id="hamburger" class="hamburger" aria-label="Open menu">☰</button>
  <div id="overlay" class="overlay hidden"></div>

  <script src="{{ asset('js/dashboard.js') }}"></script>
</body>
</html>
