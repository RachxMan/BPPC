@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
@endphp

@extends('layouts.app')

@section('title', 'Edit Akun - PayColl PT. Telkom')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="{{ asset('css/kelola.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')

<main class="main" id="main">
  <header class="header">
    <h1>Edit Akun</h1>
    <p class="subtitle">Ubah informasi akun pengguna.</p>
  </header>

  <section class="profile-container">
    <div class="header-section">
      <div>
        <h4 class="fw-bold mb-1" style="color: #333;">Form Edit Akun</h4>
        <p class="text-muted mb-0">Perbarui data akun di bawah ini.</p>
      </div>
      <a href="{{ route('kelola.index') }}" class="btn-back">
        <i class="fa-solid fa-arrow-left me-2"></i> Kembali
      </a>
    </div>

    @if($errors->any())
      <div class="error-card">
        <div class="error-header">
          <i class="fa-solid fa-circle-exclamation"></i>
          <span>Oops! Ada beberapa masalah:</span>
        </div>
        <ul class="error-list">
          @foreach($errors->all() as $error)
            <li><i class="fa-solid fa-triangle-exclamation"></i> {{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('kelola.update', $user->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="form-group">
          <label for="name">Nama Lengkap</label>
          <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->nama_lengkap) }}" required>
        </div>

        <div class="form-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" class="form-control" value="{{ old('username', $user->username) }}" required>
        </div>

        <div class="form-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" required>
        </div>

        <div class="form-group">
          <label for="password">Password Baru (jika diperlukan)</label>
          <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password baru (biarkan kosong jika tidak ingin mengganti)">
        </div>

        <div class="form-group">
          <label for="password_confirmation">Konfirmasi Password Baru</label>
          <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Ulangi password baru">
        </div>

        <div class="form-group">
          <label for="role">Role</label>
          <select id="role" name="role" class="form-control" required>
            <option value="Administrator" {{ old('role', $user->role === 'admin' ? 'Administrator' : 'Collection Agent') == 'Administrator' ? 'selected' : '' }}>Administrator</option>
            <option value="Collection Agent" {{ old('role', $user->role === 'admin' ? 'Administrator' : 'Collection Agent') == 'Collection Agent' ? 'selected' : '' }}>Collection Agent</option>
          </select>
        </div>

        <div class="form-group">
          <label for="status">Status</label>
          <select id="status" name="status" class="form-control" required>
            <option value="Aktif" {{ old('status', $user->status) == 'Aktif' ? 'selected' : '' }}>Aktif</option>
            <option value="Nonaktif" {{ old('status', $user->status) == 'Nonaktif' ? 'selected' : '' }}>Nonaktif</option>
          </select>
        </div>

        <div class="form-actions">
          <button type="submit" class="btn-red">
            <i class="fa-solid fa-save me-2"></i> Simpan Perubahan
          </button>
          <a href="{{ route('kelola.index') }}" class="btn-cancel">Batal</a>
        </div>
    </form>
  </section>
</main>

@endsection
