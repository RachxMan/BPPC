@extends('layouts.app')

@section('title', 'Kelola Akun - PayColl PT. Telkom')

@section('header-title', 'Kelola Akun')
@section('header-subtitle', 'Daftar akun pengguna sistem PayColl')

@section('content')
@php
    // Ambil tab aktif dari session, default 'daftar'
    $activeTab = session('active_tab', 'daftar');
@endphp

<div class="container-fluid">
    <!-- Tabs -->
    <ul class="nav nav-tabs mb-4" id="kelolaAkunTab" role="tablist">
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $activeTab === 'daftar' ? 'active' : '' }}" id="daftar-tab"
                    data-bs-toggle="tab" data-bs-target="#daftarAkun" type="button" role="tab">
                Daftar Akun
            </button>
        </li>
        <li class="nav-item" role="presentation">
            <button class="nav-link {{ $activeTab === 'registrasi' ? 'active' : '' }}" id="registrasi-tab"
                    data-bs-toggle="tab" data-bs-target="#registrasiAkun" type="button" role="tab">
                Registrasi Akun Baru
            </button>
        </li>
    </ul>

    <!-- Tab Content -->
    <div class="tab-content" id="kelolaAkunTabContent">

        <!-- Daftar Akun -->
        <div class="tab-pane fade {{ $activeTab === 'daftar' ? 'show active' : '' }}" id="daftarAkun" role="tabpanel">
            <div class="d-flex justify-content-end mb-3">
                <button id="btnTambahAkun" class="btn btn-danger">Tambah Akun Baru</button>
            </div>

            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead class="table-dark">
                        <tr>
                            <th>Nama</th>
                            <th>Email</th>
                            <th>Username</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                        <tr>
                            <td>{{ $user['nama'] }}</td>
                            <td>{{ $user['email'] }}</td>
                            <td>{{ $user['username'] }}</td>
                            <td>{{ $user['role'] }}</td>
                            <td>
                                <span class="badge {{ $user['status'] === 'Aktif' ? 'bg-success' : 'bg-danger' }}">
                                    {{ $user['status'] }}
                                </span>
                            </td>
                            <td>
                                <button class="btn btn-sm btn-warning">Edit</button>
                                <button class="btn btn-sm btn-danger">Hapus</button>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Registrasi Akun -->
        <div class="tab-pane fade {{ $activeTab === 'registrasi' ? 'show active' : '' }}" id="registrasiAkun" role="tabpanel">
            <div class="card">
                <div class="card-body">
                    <h5 class="fw-bold">Registrasi Akun Baru</h5>
                    <form action="{{ route('kelola-akun.store') }}" method="POST" class="mt-3">
                        @csrf
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Nama Lengkap</label>
                                <input type="text" name="nama" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" required>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label>Role</label><br>
                            <input type="radio" name="role" value="Administrator" required> Administrator
                            <input type="radio" name="role" value="Collection Agent" class="ms-3"> Collection Agent
                        </div>

                        <div class="d-flex justify-content-end">
                            <button type="reset" class="btn btn-secondary me-2">Batal</button>
                            <button type="submit" class="btn btn-success">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/dashboard.js') }}"></script>
<script>
    // Tombol "Tambah Akun Baru" otomatis pindah ke tab Registrasi
    document.getElementById('btnTambahAkun')?.addEventListener('click', function() {
        var tab = new bootstrap.Tab(document.getElementById('registrasi-tab'));
        tab.show();
    });
</script>
@endpush
