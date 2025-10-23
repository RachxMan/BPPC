<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Kelola Akun - PayColl PT. Telkom</title>
  <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
  <link rel="stylesheet" href="{{ asset('css/kelola.css') }}">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
  
  <x-sidebar />

  <main class="main" id="main">
      <header class="header">
        <h1>Kelola Akun</h1>
        <p class="subtitle"></p>
      </header>


    <section class="profile-container">
      <div class="header-section">
        <div>
          <h4 class="fw-bold mb-1" style="color: #333;">Daftar Akun Pengguna</h4>
          <p class="text-muted mb-0">Lihat, ubah, dan kelola data pengguna yang terdaftar dengan mudah.</p>
        </div>
        <a href="{{ route('kelola.create') }}" class="btn-red">
          <i class="fa-solid fa-user-plus me-2"></i> Tambah Akun Baru
        </a>
      </div>

      @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show mt-3" role="alert">
          {{ session('success') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif
      @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show mt-3" role="alert">
          {{ session('error') }}
          <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
      @endif

      <div class="table-responsive mt-4">
        <table class="table user-table align-middle">
          <thead>
            <tr>
              <th>#</th>
              <th>Nama Lengkap</th>
              <th>Username</th>
              <th>Email</th>
              <th>Role</th>
              <th>Status</th>
              <th class="text-center">Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $user)
              <tr>
                <td>{{ $loop->iteration }}</td>
                <td class="fw-semibold">{{ $user->name }}</td>
                <td>{{ $user->username }}</td>
                <td>{{ $user->email }}</td>
                <td>
                  <span class="badge {{ $user->role === 'Administrator' ? 'bg-danger' : 'bg-secondary' }}">
                    {{ $user->role }}
                  </span>
                </td>
                <td>
                  <form action="{{ route('kelola.toggle', $user->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="toggle-btn {{ $user->status === 'Aktif' ? 'active' : '' }}">
                      {{ $user->status === 'Aktif' ? 'Aktif' : 'Nonaktif' }}
                    </button>
                  </form>
                </td>
                <td class="text-center">
                  <a href="{{ route('kelola.edit', $user->id) }}" class="btn btn-sm btn-outline-primary me-2">
                    <i class="fa-solid fa-pen"></i>
                  </a>
                  <form action="{{ route('kelola.destroy', $user->id) }}" method="POST" class="d-inline"
                        onsubmit="return confirm('Yakin ingin menghapus akun ini?');">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </form>
                </td>
              </tr>
            @empty
              <tr>
                <td colspan="7" class="text-center text-muted py-4">
                  Belum ada data pengguna terdaftar.
                </td>
              </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </section>
  </main>

  <x-footer/>

  <script>
    console.log('Halaman daftar akun siap digunakan.');
  </script>
</body>
</html>