@extends('layouts.app')

@section('title', 'Kelola Akun - PayColl PT. Telkom')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kelola.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
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
              <td class="fw-semibold">{{ $user->nama_lengkap }}</td>
              <td>{{ $user->username }}</td>
              <td>{{ $user->email }}</td>
              <td>
                <span class="badge {{ $user->role === 'admin' ? 'bg-danger' : 'bg-secondary' }}">
                  {{ $user->role === 'admin' ? 'Administrator' : 'Collection Agent' }}
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
              <td>
                <div class="action-buttons">
                  <a href="{{ route('kelola.edit', $user->id) }}" class="btn-action edit" title="Edit Akun">
                    <i class="fa-solid fa-pen"></i>
                  </a>
                  <form action="{{ route('kelola.destroy', $user->id) }}" method="POST" class="d-inline delete-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn-action delete delete-btn" title="Hapus Akun">
                      <i class="fa-solid fa-trash"></i>
                    </button>
                  </form>
                </div>
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

    {{-- Pagination --}}
    <div class="d-flex justify-content-center mt-4">
      {{ $users->links() }}
    </div>
  </section>
</main>
@endsection

@push('scripts')
{{-- SweetAlert2 --}}
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
  // 🔹 Tampilkan SweetAlert jika ada session success
  @if (session('success'))
    Swal.fire({
      icon: 'success',
      title: 'Berhasil!',
      text: '{{ session('success') }}',
      confirmButtonColor: '#3085d6',
      timer: 2000,
      showConfirmButton: false
    });
  @endif

  // 🔹 Tampilkan SweetAlert jika ada session error
  @if (session('error'))
    Swal.fire({
      icon: 'error',
      title: 'Gagal!',
      text: '{{ session('error') }}',
      confirmButtonColor: '#d33'
    });
  @endif

  // 🔹 SweetAlert konfirmasi sebelum hapus data
  document.querySelectorAll('.delete-btn').forEach(button => {
    button.addEventListener('click', function(e) {
      e.preventDefault();
      const form = this.closest('.delete-form');
      Swal.fire({
        title: 'Yakin ingin menghapus akun ini?',
        text: "Data yang dihapus tidak dapat dikembalikan.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });
</script>
@endpush
