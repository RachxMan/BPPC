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
<div class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 hidden" id="logoutModal">
  <div class="bg-white rounded-lg shadow-lg w-1/3">
    <div class="p-4 border-b">
      <h2 class="text-lg font-semibold">Konfirmasi Logout</h2>
    </div>
    <div class="p-4">
      <p>Apakah Anda yakin ingin logout?</p>
    </div>
    <div class="p-4 border-t flex justify-end space-x-2">
      <button id="cancelLogoutBtn" class="px-4 py-2 bg-gray-500 text-white rounded">Batal</button>
      <button id="confirmLogoutBtn" class="px-4 py-2 bg-red-500 text-white rounded">Logout</button>
    </div>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', () => {





  // Logout modal
  window.confirmLogout = function() {
    document.getElementById('logoutModal').classList.remove('hidden');
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
    document.getElementById('logoutModal').classList.add('hidden');
  });

  document.getElementById('logoutModal').addEventListener('click', function(event) {
    if (event.target === this) {
      this.classList.add('hidden');
    }
  });
});
</script>
