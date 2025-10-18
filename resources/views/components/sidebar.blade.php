<aside class="sidebar" id="sidebar">
  <div class="logo">
    <img src="{{ asset('img/logo_telkom.png') }}" alt="Logo"/>
    <p>PayColl PT. Telkom</p>
  </div>

  <div class="profile">
    <img src="{{ asset('img/1594252-200.png') }}" alt="Admin"/>
    <p class="profile-name">{{ $name ?? 'Administrator' }}</p>
    <span class="online">● Online</span>
  </div>

  <div class="search-box">
    <input type="text" placeholder="Search..." id="searchInput" />
    <button type="button" aria-label="search">🔍</button>
  </div>

<ul class="menu" id="menu">
  <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
    <a href="{{ url('/dashboard') }}">Dashboard</a>
  </li>
  <li class="{{ request()->is('mailing-list') ? 'active' : '' }}">
    <a href="{{ url('/mailing-list') }}">Mailing List Reminder</a>
  </li>
  <li class="{{ request()->is('upload-data') ? 'active' : '' }}">
    <a href="{{ url('/upload-data') }}">Upload Data</a>
  </li>
  <li class="{{ request()->is('kelola-akun') ? 'active' : '' }}">
    <a href="{{ url('/kelola-akun') }}">Kelola Akun</a>
  </li>
  <li class="{{ request()->is('profil-pengaturan') ? 'active' : '' }}">
    <a href="{{ url('/profil-pengaturan') }}">Profil & Pengaturan</a>
  </li>
  <li>
    <a href="{{ url('/') }}">Logout</a>
  </li>
</ul>
</aside>

<button id="hamburger" class="hamburger" aria-label="Open menu">☰</button>
<div id="overlay" class="overlay hidden"></div>
