@extends('layouts.app')

@section('title', 'Profil & Pengaturan - PayColl PT. Telkom')

@section('content')
<div class="content">
  <section class="profile-container">
      <div class="sidebar-tab">
      <button class="tab active" data-target="profile">Pengaturan Profil</button>
      <button class="tab" data-target="password">Password</button>
    </div>

    <div id="profileTab" class="form-container tab-content" style="display:block;">
      <div class="profile-photo-container">
        <div class="profile-photo-wrapper">
          <img id="profilePhoto" class="profile-photo"
               src="{{ $user->profile_photo ? asset('storage/profile_photos/'.$user->profile_photo) : asset('img/1594252-200.png') }}"
               alt="Profile">
          <div class="hover-overlay">
            <button id="editPhotoBtn" type="button">Edit</button>
          </div>
        </div>
      </div>

      <form action="{{ route('profile.update') }}" method="POST" class="profile-form" id="profileForm" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!-- INPUT FOTO (moved inside form) -->
        <input type="file" id="photoInput" name="profile_photo" accept="image/*" style="display:none;" />
        <input type="hidden" id="deletePhotoInput" name="delete_photo" value="0" />
        <div class="form-row">
          <label>Nama Lengkap*</label>
          <input type="text" name="nama_lengkap" value="{{ old('nama_lengkap', $user->nama_lengkap) }}">
        </div>

        <div class="form-row">
          <label>Username*</label>
          <input type="text" name="username" value="{{ old('username', $user->username) }}">
        </div>

        <div class="form-row">
          <label>Email*</label>
          <input type="email" name="email" value="{{ old('email', $user->email) }}">
        </div>

        <div class="form-row">
          <label>No. Telepon</label>
          <input type="text" name="no_telp" value="{{ old('no_telp', $user->no_telp) }}">
        </div>

        <div class="form-row">
          <label>Alamat</label>
          <textarea name="alamat">{{ old('alamat', $user->alamat) }}</textarea>
        </div>

        <button type="submit" class="save-btn" id="saveBtn">Simpan Perubahan</button>
      </form>
    </div>

    <!-- =================== PASSWORD SETTINGS =================== -->
    <div id="passwordTab" class="form-container tab-content" style="display:none;">
      <form action="{{ route('profile.password.update') }}" method="POST" class="profile-form" id="passwordForm">
        @csrf
        <div class="form-row">
          <label>Password Lama*</label>
          <input type="password" name="current_password" required>
        </div>

        <div class="form-row">
          <label>Password Baru*</label>
          <input type="password" name="new_password" required>
        </div>

        <div class="form-row">
          <label>Konfirmasi Password*</label>
          <input type="password" name="new_password_confirmation" required>
        </div>

        <button type="submit" class="save-btn" id="savePasswordBtn">Simpan Perubahan</button>
      </form>
    </div>
  </section>
</div>

<!-- MODAL EDIT FOTO -->
<div id="photoModal" class="modal hidden" aria-hidden="true">
  <div class="modal-content" role="dialog" aria-modal="true">
    <h3>Edit Foto Profil</h3>
    <button id="changePhotoBtn" class="btn btn-red" type="button">Ganti Foto</button>
    <button id="deletePhotoBtn" class="btn btn-gray" type="button">Hapus Foto</button>
    <button id="closeModalBtn" class="btn-close" type="button">Tutup</button>
  </div>
</div>

<!-- MODAL KONFIRMASI -->
<div id="confirmModal" class="modal hidden" aria-hidden="true">
  <div class="modal-content" role="dialog" aria-modal="true">
    <h3 id="confirmTitle">Konfirmasi</h3>
    <p id="confirmMessage">Apakah Anda yakin ingin menyimpan perubahan?</p>
    <button id="confirmYesBtn" class="btn btn-red" type="button">Ya</button>
    <button id="confirmNoBtn" class="btn btn-gray" type="button">Batal</button>
  </div>
</div>

<!-- MODAL NOTIFIKASI -->
<div id="notificationModal" class="modal hidden" aria-hidden="true">
  <div class="modal-content" role="dialog" aria-modal="true">
    <h3 id="notificationTitle">Notifikasi</h3>
    <p id="notificationMessage"></p>
    <button id="notificationCloseBtn" class="btn btn-red" type="button">Tutup</button>
  </div>
</div>


@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profil.css') }}">
@endpush

@push('scripts')
<script>
  const defaultPhotoUrl = "{{ asset('img/1594252-200.png') }}";
</script>
<script src="{{ asset('js/profil.js') }}"></script>
@endpush
