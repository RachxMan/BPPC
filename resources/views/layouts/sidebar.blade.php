<aside class="sidebar" id="sidebar">
  {{-- Logo --}}
  <div class="sidebar-header">
    <img src="{{ asset('img/logo_telkom.png') }}" alt="Logo" class="sidebar-logo">
    <h2 class="sidebar-title">PayColl</h2>
    <p class="sidebar-subtitle">PT. Telkom Indonesia</p>
  </div>

  <div class="sidebar-profile">
    <img src="{{ asset('img/1594252-200.png') }}" alt="Admin" class="profile-photo">
    <div class="profile-info">
      <p class="profile-name">{{ Auth::user()->nama_lengkap ?? 'Administrator' }}</p>
      <span class="status-badge">● Online</span>
    </div>
  </div>

  <div class="search-box">
    <input type="text" placeholder="Search..." id="searchInput" />
    <button type="button" aria-label="search">🔍</button>
  </div>

  <nav class="sidebar-menu">
    <ul>
      <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
        <a href="{{ url('/dashboard') }}">Dashboard</a>
      </li>
      @if(Auth::user()->role === 'admin')
      <li class="{{ request()->is('mailing-list') ? 'active' : '' }}">
        <a href="{{ url('/mailing-list') }}">Mailing List Reminder</a>
      </li>
      <li class="{{ request()->is('upload-data*') || request()->is('laporan*') ? 'active' : '' }}">
        <a href="{{ url('/upload-data') }}">Upload Data</a>
      </li>
      <li class="{{ request()->is('kelola-akun') ? 'active' : '' }}">
        <a href="{{ url('/kelola-akun') }}">Kelola Akun</a>
      </li>
      @endif
      <li class="{{ request()->is('profil') ? 'active' : '' }}">
        <a href="{{ url('/profil') }}">Profil & Pengaturan</a>
      </li>
    </ul>
  </nav>

  <div class="sidebar-footer">
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="logout-btn">Logout</button>
    </form>
  </div>
</aside>

<button id="hamburger" class="hamburger" aria-label="Open menu">☰</button>
<div id="overlay" class="overlay hidden"></div>

<script>
  document.addEventListener('DOMContentLoaded', () => {
    const sidebar = document.getElementById('sidebar');
    const hamburger = document.getElementById('hamburger');
    const overlay = document.getElementById('overlay');

    if (hamburger && sidebar && overlay) {
      hamburger.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('hidden');
      });

      overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.add('hidden');
      });
    }
  });
</script>
