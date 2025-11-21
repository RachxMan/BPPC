{{-- Sidebar --}}
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
        <a href="{{ url('/dashboard') }}" class="sidebar-link">Dashboard</a>
      </li>

      @if(Auth::user()->role === 'admin')
      <li class="{{ request()->is('upload-data*') ? 'active' : '' }}">
        <a href="{{ route('upload.index') }}" class="sidebar-link">Upload Data</a>
      </li>

      <li class="{{ request()->is('kelola-akun') ? 'active' : '' }}">
        <a href="{{ url('/kelola-akun') }}" class="sidebar-link">Kelola Akun</a>
      </li>
      @endif

      @if(Auth::user()->role === 'admin' || Auth::user()->role === 'ca')
      <li class="dropdown {{ request()->is('caring*') ? 'open' : '' }}">
        <ul class="dropdown-menu">
          <li class="{{ request()->is('caring/telepon') ? 'active' : '' }}">
            <a href="{{ url('/caring/telepon') }}" class="sidebar-link">Caring Telepon</a>
          </li>
        </ul>
      </li>
      @endif

      <li class="{{ request()->is('profil') ? 'active' : '' }}">
        <a href="{{ url('/profil') }}" class="sidebar-link">Profil & Pengaturan</a>
      </li>
    </ul>
  </div>

  <div class="sidebar-footer">
    <button type="button" class="logout-btn" onclick="confirmLogout()">Logout</button>
  </div>
</aside>

{{-- Logout Modal --}}
<div id="logoutModal" class="modal" style="display: none;">
  <div class="modal-content">
    <h3>Konfirmasi Logout</h3>
    <p>Apakah Anda yakin ingin logout?</p>
    <div class="modal-buttons">
      <button id="cancelLogoutBtn" class="btn-cancel">Batal</button>
      <button id="confirmLogoutBtn" class="btn-confirm">Logout</button>
    </div>
  </div>
</div>

{{-- Script --}}
<script>
document.addEventListener('DOMContentLoaded', () => {





  // Logout modal
  window.confirmLogout = function() {
    document.getElementById('logoutModal').style.display = 'block';
  };

  document.getElementById('confirmLogoutBtn').addEventListener('click', function() {
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

  document.getElementById('logoutModal').addEventListener('click', function(event) {
    if (event.target === this) {
      this.style.display = 'none';
    }
  });
});
</script>
