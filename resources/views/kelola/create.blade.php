@php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
@endphp

@extends('layouts.app')

@section('title', 'Tambah Akun - PayColl PT. Telkom')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/kelola.css') }}">
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
@endpush

@section('content')
<main class="main" id="main">
  <header class="header">
    <h1>Tambah Akun Baru</h1>
    <p class="subtitle">Buat akun pengguna Administrator atau Collection Agent baru.</p>
  </header>

  <section class="profile-container">
    <div class="header-section">
      <div>
        <h4 class="fw-bold mb-1" style="color: #333;">Form Tambah Akun</h4>
        <p class="text-muted mb-0">Lengkapi data di bawah ini untuk membuat akun baru.</p>
      </div>
      <a href="{{ route('kelola.index') }}" class="btn-back">
        <i class="fa-solid fa-arrow-left me-2"></i> Kembali
      </a>
    </div>

    @php
    $errors = $errors ?? new \Illuminate\Support\ViewErrorBag;
@endphp

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

    {{-- Form tambah akun --}}
    <form action="{{ route('kelola.store') }}" method="POST" class="form-kelola">
      @csrf
      
      <div class="form-group">
        <label for="name">Nama Lengkap <span class="required">*</span></label>
        <input type="text" id="name" name="name" class="form-control" value="{{ old('name') }}" required>
      </div>

      <div class="form-group">
        <label for="username">Username <span class="required">*</span></label>
        <input type="text" id="username" name="username" class="form-control" value="{{ old('username') }}" required>
      </div>

      <div class="form-group">
        <label for="email">Email <span class="required">*</span></label>
        <input type="email" id="email" name="email" class="form-control" value="{{ old('email') }}" required>
      </div>

      <div class="form-group">
        <label for="role">Role <span class="required">*</span></label>
        <select id="role" name="role" class="form-control" required>
          <option value="">-- Pilih Role --</option>
          <option value="Administrator" {{ old('role') == 'Administrator' ? 'selected' : '' }}>Administrator</option>
          <option value="Collection Agent" {{ old('role') == 'Collection Agent' ? 'selected' : '' }}>Collection Agent</option>
        </select>
      </div>

      <div class="form-group">
        <label for="password">Password <span class="required">*</span></label>
        <input type="password" id="password" name="password" class="form-control" required>
        <small class="form-text">Minimal 8 karakter</small>
      </div>

      <div class="form-group">
        <label for="password_confirmation">Konfirmasi Password <span class="required">*</span></label>
        <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" required>
      </div>

      <div class="form-actions">
        <button type="submit" class="btn-red">
          <i class="fa-solid fa-save me-2"></i> Simpan Akun
        </button>
        <a href="{{ route('kelola.index') }}" class="btn-cancel">Batal</a>
      </div>
    </form>
  </section>
</main>
@endsection
