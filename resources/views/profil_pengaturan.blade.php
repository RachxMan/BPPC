@extends('layouts.app')

@section('title', 'Profil & Pengaturan - PayColl PT. Telkom')

@section('content')
<div class="content">
  <section class="profile-container">
    <div class="sidebar-tab">
      <button class="tab active" data-target="profile">Profile Settings</button>
      <button class="tab" data-target="password">Password</button>
    </div>

    <!-- =================== PROFILE SETTINGS =================== -->
    <div id="profileTab" class="form-container tab-content" style="display:block;">
      <div class="profile-photo-container">
        <div class="profile-photo-wrapper">
          <img id="profilePhoto"
               src="{{ $user->profile_photo ? asset('storage/profile_photos/'.$user->profile_photo) : asset('img/1594252-200.png') }}"
               alt="Profile">
          <div class="hover-overlay">
            <button id="editPhotoBtn" type="button">Edit</button>
          </div>
        </div>
      </div>

      <form action="{{ route('profile.update') }}" method="POST" class="profile-form">
        @csrf
        <div class="form-row">
          <label>First Name*</label>
          <input type="text" name="first_name" value="{{ old('first_name', $user->first_name) }}">
        </div>

        <div class="form-row">
          <label>Last Name*</label>
          <input type="text" name="last_name" value="{{ old('last_name', $user->last_name) }}">
        </div>

        <div class="form-row">
          <label>Email*</label>
          <input type="email" name="email" value="{{ old('email', $user->email) }}">
        </div>

        <div class="form-row">
          <label>Mobile Number*</label>
          <input type="text" name="mobile" value="{{ old('mobile', $user->mobile) }}">
        </div>

        <div class="form-row">
          <label>Address</label>
          <textarea name="address">{{ old('address', $user->address) }}</textarea>
        </div>

        <button type="submit" class="save-btn">Save Changes</button>
      </form>
    </div>

    <!-- =================== PASSWORD SETTINGS =================== -->
    <div id="passwordTab" class="form-container tab-content" style="display:none;">
      <form action="{{ route('profile.password.update') }}" method="POST" class="profile-form">
        @csrf
        <div class="form-row">
          <label>New Password*</label>
          <input type="password" name="new_password" required>
        </div>

        <div class="form-row">
          <label>Confirm Password*</label>
          <input type="password" name="confirm_password" required>
        </div>

        <button type="submit" class="save-btn">Save Changes</button>
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

<!-- FORM UPLOAD FOTO -->
<form id="photoForm" action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data" style="display:none;">
  @csrf
  <input type="file" id="photoInput" name="profile_photo" accept="image/*" />
</form>

<!-- FORM HAPUS FOTO -->
<form id="deletePhotoForm" action="{{ route('profile.update') }}" method="POST" style="display:none;">
  @csrf
  <input type="hidden" name="delete_photo" value="1">
</form>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profil.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/profil.js') }}"></script>
@endpush
