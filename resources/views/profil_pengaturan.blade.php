@extends('layouts.app')

@section('title', 'Profil & Pengaturan - PayColl PT. Telkom')

@section('header-title', 'Profil & Pengaturan')
@section('header-subtitle', 'Kelola data profil dan preferensi akun Anda.')

@section('content')
<div class="content">
  <section class="profile-container">
    <div class="sidebar-tab">
      <button class="tab active">Profile Settings</button>
      <button class="tab">Password</button>
      <button class="tab">Notifications</button>
    </div>

    <div class="form-container">
      <div class="profile-photo">
        <img src="{{ asset('img/1594252-200.png') }}" alt="Profile">
        <div class="upload-btn">
          <button class="btn-red">Upload Now</button>
          <button class="btn-gray">Delete</button>
        </div>
      </div>

      <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        <div class="form-row">
          <label>First Name*</label>
          <input type="text" name="first_name" value="Eman">
        </div>

        <div class="form-row">
          <label>Last Name*</label>
          <input type="text" name="last_name" value="Tegalakur">
        </div>

        <div class="form-row">
          <label>Email*</label>
          <input type="email" name="email" value="eman@example.com">
        </div>

        <div class="form-row">
          <label>Mobile Number*</label>
          <input type="text" name="mobile" value="(555) 555-5555">
        </div>

        <div class="form-row">
          <label>Gender</label>
          <div class="gender">
            <input type="radio" id="male" name="gender" checked>
            <label for="male">Male</label>
            <input type="radio" id="female" name="gender">
            <label for="female">Female</label>
          </div>
        </div>

        <div class="form-row">
          <label>Address</label>
          <textarea name="address">545 Radja Al-Mota, New Jersey 45453</textarea>
        </div>

        <button type="submit" class="save-btn">Save Changes</button>
      </form>
    </div>
  </section>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/profil.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/profil.js') }}"></script>
@endpush
