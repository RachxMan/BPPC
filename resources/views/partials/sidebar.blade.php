<div class="bg-dark text-white p-3" style="width: 250px; min-height: 100vh;">
    <div class="text-center mb-4">
        <img src="{{ asset('img/logo.png') }}" alt="Logo" width="100">
        <h6 class="mt-2">PayColl PT. Telkom</h6>
        <p class="text-success">Administrator • Online</p>
    </div>
    <ul class="nav flex-column">
        <li class="nav-item"><a href="{{ route('dashboard') }}" class="nav-link text-white {{ request()->is('dashboard') ? 'bg-danger fw-bold' : '' }}">Dashboard</a></li>
        @if(Auth::user()->role === 'admin')
        <li class="nav-item"><a href="{{ route('mailing.index') }}" class="nav-link text-white {{ request()->is('mailing-list') ? 'bg-danger fw-bold' : '' }}">Mailing List Reminder</a></li>
        <li class="nav-item"><a href="{{ route('upload.index') }}" class="nav-link text-white {{ request()->is('upload-data*') ? 'bg-danger fw-bold' : '' }}">Upload Data</a></li>
        <li class="nav-item"><a href="{{ route('user.index') }}" class="nav-link text-white {{ request()->is('kelola-akun*') ? 'bg-danger fw-bold' : '' }}">Kelola Akun</a></li>
        <li class="nav-item"><a href="{{ route('activity-log.index') }}" class="nav-link text-white {{ request()->is('activity-log') ? 'bg-danger fw-bold' : '' }}">Activity Log</a></li>
        @endif
        <li class="nav-item"><a href="{{ route('profile.index') }}" class="nav-link text-white {{ request()->is('profil*') ? 'bg-danger fw-bold' : '' }}">Profil & Pengaturan</a></li>
        <li class="nav-item">
            <form action="{{ route('logout') }}" method="POST">
                @csrf
                <button type="submit" class="btn btn-link nav-link text-white p-0">Logout</button>
            </form>
        </li>
    </ul>
</div>
