<aside class="sidebar" id="sidebar">
  <div class="logo">
    <img src="{{ asset('img/logo_telkom.png') }}" alt="Logo"/>
    <p>PayColl PT. Telkom</p>
  </div>

  <div class="profile">
    <img src="{{ asset('img/1594252-200.png') }}" alt="Admin"/>
    <p class="profile-name">{{ Auth::user()->name ?? 'Administrator' }}</p>
    <span class="online">● Online</span>
  </div>

  <div class="search-box">
    <input type="text" placeholder="Search..." id="searchInput" />
    <button type="button" aria-label="search">🔍</button>
  </div>

  <ul class="menu" id="menu">
    <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
      <a href="{{ route('dashboard') }}">Dashboard</a>
    </li>
    <li class="{{ request()->routeIs('mailing*') ? 'active' : '' }}">
      <a href="{{ route('mailing.index') }}">Mailing List Reminder</a>
    </li>
    <li class="{{ request()->routeIs('upload*') ? 'active' : '' }}">
      <a href="{{ route('upload.index') }}">Upload Data</a>
    </li>
    <li class="{{ request()->routeIs('user*') ? 'active' : '' }}">
      <a href="{{ route('user.index') }}">Kelola Akun</a>
    </li>
    <li class="{{ request()->routeIs('profile*') ? 'active' : '' }}">
      <a href="{{ route('profile.index') }}">Profil & Pengaturan</a>
    </li>
    <li>
      <form action="{{ route('logout') }}" method="POST">
        @csrf
        <button type="submit" class="logout-btn">Logout</button>
      </form>
    </li>
  </ul>
</aside>

<button id="hamburger" class="hamburger" aria-label="Open menu">☰</button>
<div id="overlay" class="overlay hidden"></div>
