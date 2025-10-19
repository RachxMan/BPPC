<aside class="sidebar" id="sidebar">
  {{-- Logo --}}
  <div class="sidebar-header">
    <img src="{{ asset('img/logo_telkom.png') }}" alt="Logo" class="sidebar-logo">
    <h2 class="sidebar-title">PayColl</h2>
    <p class="sidebar-subtitle">PT. Telkom Indonesia</p>
  </div>

  {{-- Profil Admin --}}
  <div class="sidebar-profile">
    <img src="{{ asset('img/1594252-200.png') }}" alt="Admin" class="profile-photo">
    <div class="profile-info">
      <p class="profile-name">{{ Auth::user()->name ?? 'Administrator' }}</p>
      <span class="status-badge">● Online</span>
    </div>
  </div>

  {{-- Menu Navigasi --}}
  <nav class="sidebar-menu">
    <ul>
      <li class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">
        <a href="{{ route('dashboard') }}">
          <span class="icon">🏠</span> Dashboard
        </a>
      </li>

      <li class="{{ request()->routeIs('mailing*') ? 'active' : '' }}">
        <a href="{{ route('mailing.index') }}">
          <span class="icon">📧</span> Mailing List
        </a>
      </li>

      <li class="{{ request()->routeIs('upload*') ? 'active' : '' }}">
        <a href="{{ route('upload.index') }}">
          <span class="icon">📂</span> Upload Data
        </a>
      </li>

      <li class="{{ request()->routeIs('user*') ? 'active' : '' }}">
        <a href="{{ route('user.index') }}">
          <span class="icon">👥</span> Kelola Akun
        </a>
      </li>

      <li class="{{ request()->routeIs('profile*') ? 'active' : '' }}">
        <a href="{{ route('profile.index') }}">
          <span class="icon">⚙️</span> Profil & Pengaturan
        </a>
      </li>
    </ul>
  </nav>

  {{-- Tombol Logout --}}
  <div class="sidebar-footer">
    <form action="{{ route('logout') }}" method="POST">
      @csrf
      <button type="submit" class="logout-btn">
        <span class="icon">🚪</span> Logout
      </button>
    </form>
  </div>
</aside>

{{-- Tombol Hamburger (Mobile) --}}
<button id="hamburger" class="hamburger" aria-label="Open menu">☰</button>
<div id="overlay" class="overlay hidden"></div>

<style>
  /* ===== Sidebar Dashboard Style ===== */
  .sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 260px;
    height: 100%;
    background: #121212;
    box-shadow: 2px 0 5px rgba(0,0,0,0.3);
    display: flex;
    flex-direction: column;
    z-index: 100;
    color: #f0f0f0;
  }

  .sidebar-header {
    text-align: center;
    padding: 1.5rem 1rem 1rem;
    border-bottom: 1px solid #333;
  }

  .sidebar-logo {
    width: 60px;
    height: auto;
  }

  .sidebar-title {
    font-size: 1.2rem;
    margin: 0.5rem 0 0;
    color: #ff5252;
    font-weight: 700;
  }

  .sidebar-subtitle {
    font-size: 0.8rem;
    color: #bbb;
    margin: 0;
  }

  .sidebar-profile {
    display: flex;
    align-items: center;
    padding: 1rem 1.2rem;
    border-bottom: 1px solid #333;
  }

  .sidebar-profile .profile-photo {
    width: 45px;
    height: 45px;
    border-radius: 50%;
    margin-right: 12px;
  }

  .sidebar-profile .profile-info {
    flex: 1;
  }

  .profile-name {
    margin: 0;
    font-weight: 600;
    font-size: 0.95rem;
    color: #fff;
  }

  .status-badge {
    color: #4caf50;
    font-size: 0.8rem;
  }

  .sidebar-menu {
    flex-grow: 1;
    padding: 1rem 0;
  }

  .sidebar-menu ul {
    list-style: none;
    margin: 0;
    padding: 0;
  }

  .sidebar-menu li {
    margin: 0.2rem 0;
  }

  .sidebar-menu a {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 0.8rem 1.2rem;
    color: #ddd;
    text-decoration: none;
    font-size: 0.95rem;
    transition: all 0.2s ease;
  }

  .sidebar-menu li.active a,
  .sidebar-menu a:hover {
    background: #2a2a2a;
    color: #ff5252;
    font-weight: 600;
  }

  .icon {
    font-size: 1.1rem;
  }

  .sidebar-footer {
    padding: 1rem 1.2rem;
    border-top: 1px solid #333;
  }

  .logout-btn {
    width: 100%;
    border: none;
    background: #ff5252;
    color: white;
    font-weight: 600;
    border-radius: 6px;
    padding: 0.7rem;
    cursor: pointer;
    transition: background 0.3s;
  }

  .logout-btn:hover {
    background: #d32f2f;
  }

  /* ===== Responsif ===== */
  @media (max-width: 992px) {
    .sidebar {
      left: -260px;
      transition: all 0.3s ease;
    }

    .sidebar.active {
      left: 0;
    }

    .hamburger {
      display: block;
      position: fixed;
      top: 1rem;
      left: 1rem;
      background: #ff5252;
      color: #fff;
      border: none;
      font-size: 1.5rem;
      padding: 0.5rem 0.8rem;
      border-radius: 6px;
      cursor: pointer;
      z-index: 200;
    }

    .overlay {
      position: fixed;
      inset: 0;
      background: rgba(0,0,0,0.4);
      z-index: 150;
    }

    .overlay.hidden {
      display: none;
    }
  }
</style>

<script>
  // ===== Toggle Sidebar Mobile =====
  const hamburger = document.getElementById('hamburger');
  const sidebar = document.getElementById('sidebar');
  const overlay = document.getElementById('overlay');

  hamburger.addEventListener('click', () => {
    sidebar.classList.toggle('active');
    overlay.classList.toggle('hidden');
  });

  overlay.addEventListener('click', () => {
    sidebar.classList.remove('active');
    overlay.classList.add('hidden');
  });
</script>
