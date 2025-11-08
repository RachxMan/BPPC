{{-- Sidebar (Desktop) --}}
<aside class="sidebar" id="sidebar">
  <div class="sidebar-header">
    <img src="{{ asset('img/logo_telkom.png') }}" alt="Logo" class="sidebar-logo">
    <h2 class="sidebar-title">PayColl</h2>
    <p class="sidebar-subtitle">PT. Telkom Indonesia</p>
  </div>

  <div class="sidebar-profile">
    <img src="{{ Auth::user()->profile_photo ? asset('storage/profile_photos/'.Auth::user()->profile_photo) : asset('img/1594252-200.png') }}" alt="Profile" class="profile-photo">
    <div class="profile-info">
      <p class="profile-name">{{ Auth::user()->nama_lengkap ?? 'Administrator' }}</p>
      <span class="status-badge">{{ strtoupper(Auth::user()->role) }}</span>
    </div>
  </div>

  <div class="sidebar-menu">
    <ul>
      <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
        <a href="{{ url('/dashboard') }}">Dashboard</a>
      </li>

      @if(Auth::user()->role === 'admin')
      {{-- Upload Data --}}
      <li class="{{ request()->is('upload-data*') ? 'active' : '' }}">
        <a href="{{ route('upload.index') }}">Upload Data</a>
      </li>

      {{-- Kelola Akun --}}
      <li class="{{ request()->is('kelola-akun') ? 'active' : '' }}">
        <a href="{{ url('/kelola-akun') }}">Kelola Akun</a>
      </li>
      @endif

      @if(Auth::user()->role === 'admin' || Auth::user()->role === 'ca')
      {{-- Caring Pelanggan --}}
      <li class="dropdown {{ request()->is('caring*') ? 'open' : '' }}">
        <a href="javascript:void(0)" class="dropdown-toggle" id="caring-toggle">
          Caring Pelanggan <span class="arrow">▾</span>
        </a>
        <ul class="dropdown-menu">
          <li class="{{ request()->is('caring/telepon') ? 'active' : '' }}">
            <a href="{{ url('/caring/telepon') }}">Caring Telepon</a>
          </li>
        </ul>
      </li>
      @endif

      <li class="{{ request()->is('profil') ? 'active' : '' }}">
        <a href="{{ url('/profil') }}">Profil & Pengaturan</a>
      </li>
    </ul>
  </div>

  <div class="sidebar-footer">
    <button type="button" class="logout-btn" onclick="confirmLogout()">Logout</button>
  </div>
</aside>

{{-- Mobile Header --}}
<header class="mobile-header">
  <button id="hamburger" class="hamburger">☰</button>
  <div class="mobile-logo">
    <img src="{{ asset('img/logo_telkom.png') }}" alt="Logo" style="height:40px;">
  </div>
</header>

{{-- Mobile Menu --}}
<div id="mobile-menu" class="mobile-menu">
  <ul>
    <li class="{{ request()->is('dashboard') ? 'active' : '' }}">
      <a href="{{ url('/dashboard') }}">Dashboard</a>
    </li>

    @if(Auth::user()->role === 'admin')
    <li class="{{ request()->is('upload-data*') ? 'active' : '' }}">
      <a href="{{ route('upload.index') }}">Upload Data</a>
    </li>

    <li class="{{ request()->is('kelola-akun') ? 'active' : '' }}">
      <a href="{{ url('/kelola-akun') }}">Kelola Akun</a>
    </li>
    @endif

    @if(Auth::user()->role === 'admin' || Auth::user()->role === 'ca')
    <li class="dropdown {{ request()->is('caring*') ? 'open' : '' }}">
      <a href="javascript:void(0)" class="dropdown-toggle" id="mobile-caring-toggle">
        Caring Pelanggan <span class="arrow">▾</span>
      </a>
      <ul class="dropdown-menu">
        <li class="{{ request()->is('caring/telepon') ? 'active' : '' }}">
          <a href="{{ url('/caring/telepon') }}">Caring Telepon</a>
        </li>
      </ul>
    </li>
    @endif

    <li class="{{ request()->is('profil') ? 'active' : '' }}">
      <a href="{{ url('/profil') }}">Profil & Pengaturan</a>
    </li>

    <li>
      <button type="button" class="logout-btn" onclick="confirmLogout()">Logout</button>
    </li>
  </ul>
</div>

{{-- Script toggle sidebar & mobile menu --}}
<script>
document.addEventListener('DOMContentLoaded', () => {
  const caringToggle = document.getElementById('caring-toggle');
  if(caringToggle){
    caringToggle.addEventListener('click', () => {
      const parent = caringToggle.parentElement;
      parent.classList.toggle('open');
      caringToggle.querySelector('.arrow').textContent = parent.classList.contains('open') ? '▲' : '▾';
    });
  }

  const hamburger = document.getElementById('hamburger');
  const mobileMenu = document.getElementById('mobile-menu');
  hamburger.addEventListener('click', () => {
    mobileMenu.classList.toggle('active');
  });

  const mobileCaringToggle = document.getElementById('mobile-caring-toggle');
  if(mobileCaringToggle){
    mobileCaringToggle.addEventListener('click', () => {
      const parent = mobileCaringToggle.parentElement;
      parent.classList.toggle('open');
      mobileCaringToggle.querySelector('.arrow').textContent = parent.classList.contains('open') ? '▲' : '▾';
    });
  }

  window.addEventListener('resize', () => {
    if(window.innerWidth >= 768){
      mobileMenu.classList.remove('active');
      document.querySelectorAll('#mobile-menu .dropdown').forEach(drop => drop.classList.remove('open'));
      document.querySelectorAll('#mobile-menu .arrow').forEach(arrow => arrow.textContent = '▾');
    }
  });

  // Logout confirmation modal
  window.confirmLogout = function() {
    document.getElementById('logoutModal').style.display = 'block';
  };

  document.getElementById('confirmLogoutBtn').addEventListener('click', function() {
    // Create and submit logout form
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = '{{ route("logout") }}';
    const csrf = document.createElement('input');
    csrf.type = 'hidden';
    csrf.name = '_token';
    csrf.value = '{{ csrf_token() }}';
    form.appendChild(csrf);
    document.body.appendChild(form);
    form.submit();
  });

  document.getElementById('cancelLogoutBtn').addEventListener('click', function() {
    document.getElementById('logoutModal').style.display = 'none';
  });

  // Close modal when clicking outside
  document.getElementById('logoutModal').addEventListener('click', function(event) {
    if (event.target === this) {
      this.style.display = 'none';
    }
  });
});
</script>

{{-- Logout Confirmation Modal --}}
<div id="logoutModal" class="modal" style="display: none; position: fixed; z-index: 2000; left: 0; top: 0; width: 100%; height: 100%; background-color: rgba(0,0,0,0.5);">
  <div class="modal-content" style="background-color: #fefefe; margin: 15% auto; padding: 20px; border: 1px solid #888; width: 90%; max-width: 400px; border-radius: 8px; text-align: center;">
    <h3 style="margin-bottom: 20px; color: #333;">Apakah anda yakin ingin Logout?</h3>
    <button id="confirmLogoutBtn" style="background-color: #dc3545; color: white; border: none; padding: 10px 20px; margin: 0 10px; border-radius: 4px; cursor: pointer;">Ya</button>
    <button id="cancelLogoutBtn" style="background-color: #6c757d; color: white; border: none; padding: 10px 20px; margin: 0 10px; border-radius: 4px; cursor: pointer;">Tidak</button>
  </div>
</div>

{{-- Style sidebar & mobile --}}
<style>
/* Desktop sidebar */
.sidebar {
  background:#111;
  color:white;
  width:250px;
  height:100vh;
  position:fixed;
  top:0;
  left:0;
  overflow-y:auto;
  z-index:1000;
}
.sidebar-header, .sidebar-profile, .sidebar-menu, .sidebar-footer { padding:15px; }
.sidebar-menu ul { list-style:none; padding:0; margin:0; }
.sidebar-menu ul li a { display:block; padding:10px 15px; color:white; text-decoration:none; }
.sidebar-menu ul li.active > a, .sidebar-menu ul li.dropdown.open > a { color:white; }
.sidebar-menu ul li.dropdown > .dropdown-menu { display:none; padding-left:15px; }
.sidebar-menu ul li.dropdown.open > .dropdown-menu { display:block; }

/* Mobile header */
.mobile-header {
  display:none;
  background:#111;
  color:white;
  padding:10px 15px;
  align-items:center;
  justify-content:space-between;
  position:fixed;
  width:100%;
  top:0;
  z-index:1001;
}
.hamburger {
  font-size:24px;
  background:none;
  border:none;
  color:white;
  cursor:pointer;
}
.mobile-menu {
  display:none;
  position:fixed;
  top:0;
  left:0;
  width:100%;
  height:100vh;
  background:#111;
  z-index:1000;
  overflow-y:auto;
  padding-top:60px;
  transition:transform 0.3s ease;
  transform:translateY(-100%);
}
.mobile-menu.active { transform:translateY(0); display:block; }
.mobile-menu ul { list-style:none; padding:0; margin:0; }
.mobile-menu ul li a { display:block; padding:15px; color:white; text-decoration:none; }
.mobile-menu ul li.active > a, .mobile-menu ul li.dropdown.open > a { color:white; }
.mobile-menu ul li.dropdown > .dropdown-menu { display:none; padding-left:15px; }
.mobile-menu ul li.dropdown.open > .dropdown-menu { display:block; }

/* Responsive */
@media(max-width:768px){
  .sidebar { display:none; }
  .mobile-header { display:flex; }
}
</style>
