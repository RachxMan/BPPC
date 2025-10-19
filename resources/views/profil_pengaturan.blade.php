@extends('layouts.app')

@section('title', 'Profil & Pengaturan - PayColl PT. Telkom')

@section('content')
<main class="content">
  <h1>Profil & Pengaturan</h1>
  <div class="profile-container">
    
    <!-- Sidebar Tab -->
    <div class="sidebar-tab">
      <button class="tab active">Profile Settings</button>
      <button class="tab">Password</button>
      <button class="tab">Notifications</button>
    </div>

    <!-- Form Container -->
    <div class="form-container">
      
      <!-- Foto Profil -->
      <div class="profile-photo">
        <img src="{{ asset('img/1594252-200.png') }}" alt="Profile"/>
        <div class="upload-btn">
          <button class="btn-red">Upload Now</button>
          <button class="btn-gray">Delete</button>
        </div>
      </div>

      <!-- Form Profil -->
      <form action="{{ route('profile.update') }}" method="POST">
        @csrf
        <div class="form-row">
          <label>First Name*</label>
          <input type="text" name="first_name" value="Eman">
          <label>Last Name*</label>
          <input type="text" name="last_name" value="Tegalakur">
        </div>

        <div class="form-row">
          <label>Email*</label>
          <input type="email" name="email" value="eman@example.com">
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
  </div>
</main>

<button id="hamburger" class="hamburger">☰</button>
<div id="overlay" class="overlay hidden"></div>
@endsection

@push('scripts')
<script src="{{ asset('js/profil.js') }}"></script>
@endpush
