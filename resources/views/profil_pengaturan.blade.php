@extends('layouts.app')

@section('title', 'Profil & Pengaturan - PayColl PT. Telkom')

@section('header-title', 'Profil & Pengaturan')
@section('header-subtitle', 'Kelola data profil dan preferensi akun Anda.')

@section('content')
  <section class="profile-container">
    {{-- Sidebar Tab --}}
    <div class="sidebar-tab">
      <button class="tab active">Profile Settings</button>
      <button class="tab">Password</button>
      <button class="tab">Notifications</button>
    </div>

    {{-- Form Container --}}
    <div class="form-container">
      {{-- Foto Profil --}}
      <div class="profile-photo">
        <img src="{{ asset('img/1594252-200.png') }}" alt="Profile"/>
        <div class="upload-btn">
          <button class="btn-red">Upload Now</button>
          <button class="btn-gray">Delete</button>
        </div>
      </div>

      {{-- Form Profil --}}
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
@endsection

@push('styles')
<style>
/* Hanya style khusus untuk profil yang berbeda */
.profile-container {
  display: flex;
  gap: 2rem;
  align-items: flex-start;
  flex-wrap: wrap;
}

.sidebar-tab {
  display: flex;
  flex-direction: column;
  gap: 1rem;
  width: 220px;
}

.sidebar-tab .tab {
  padding: 0.8rem 1rem;
  background: #fff;
  border: 1px solid #ddd;
  border-radius: 8px;
  cursor: pointer;
  text-align: left;
  transition: 0.3s;
  font-weight: 500;
}

.sidebar-tab .tab.active {
  background: #ff4d4f;
  color: #fff;
  border-color: #ff4d4f;
}

.form-container {
  flex-grow: 1;
  background: #fff;
  border-radius: 12px;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  padding: 2rem;
  max-width: 700px;
  width: 100%;
}

.profile-photo {
  display: flex;
  align-items: center;
  gap: 1.5rem;
  margin-bottom: 1.5rem;
}

.profile-photo img {
  width: 100px;
  height: 100px;
  border-radius: 50%;
  object-fit: cover;
  border: 2px solid #eee;
}

.upload-btn button {
  margin-right: 0.5rem;
  padding: 0.6rem 1.2rem;
  border: none;
  border-radius: 6px;
  cursor: pointer;
  font-size: 0.9rem;
}

.btn-red { background: #ff4d4f; color: #fff; }
.btn-gray { background: #f0f0f0; color: #333; }

.form-row {
  display: flex;
  flex-direction: column;
  margin-bottom: 1rem;
}

.form-row label {
  font-weight: 600;
  margin-bottom: 0.3rem;
}

.form-row input,
.form-row textarea {
  padding: 0.7rem;
  border-radius: 6px;
  border: 1px solid #ccc;
  font-size: 0.9rem;
}

.save-btn {
  background: #ff4d4f;
  color: #fff;
  padding: 0.8rem 1.5rem;
  border: none;
  border-radius: 8px;
  cursor: pointer;
  margin-top: 1rem;
  font-weight: 600;
}

@media (max-width: 992px) {
  .profile-container {
    flex-direction: column;
  }
  .sidebar-tab {
    flex-direction: row;
    width: 100%;
    overflow-x: auto;
  }
}
</style>
@endpush

@push('scripts')
<script src="{{ asset('js/profil.js') }}"></script>
@endpush
