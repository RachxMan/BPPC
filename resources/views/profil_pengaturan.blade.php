<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Profil & Pengaturan - PayColl PT. Telkom</title>
  <link rel="stylesheet" href="{{ asset('css/profil.css') }}" />
</head>
<body>
  <aside class="sidebar" id="sidebar">
    <div class="logo">
      <img src="{{ asset('assets/logo_telkom.png') }}" alt="Logo"/>
      <p>PayColl PT. Telkom</p>
    </div>

    <div class="profile">
      <img src="{{ asset('assets/1594252-200.png') }}" alt="Admin"/>
      <p class="profile-name">Administrator</p>
      <span class="online">● Online</span>
    </div>

    <div class="search-box">
      <input type="text" placeholder="Search..." id="searchInput" />
      <button type="button" aria-label="search">🔍</button>
    </div>

    <ul class="menu" id="menu">
      <li><a href="{{ route('dashboard') }}"><span>Dashboard</span></a></li>
      <li><a href="{{ route('mailing') }}"><span>Mailing List Reminder</span></a></li>
      <li><a href="{{ route('uploaddata') }}"><span>Upload Data</span></a></li>
      <li><a href="{{ route('kelolaakun') }}"><span>Kelola Akun</span></a></li>
      <li class="active"><a href="{{ route('profil') }}"><span>Profil & Pengaturan</span></a></li>
      <li><a href="{{ route('logout') }}"><span>Logout</span></a></li>
    </ul>
  </aside>

  <main class="content">
    <h1>Profil & Pengaturan</h1>
    <div class="profile-container">
      <div class="sidebar-tab">
        <button class="tab active">Profile Settings</button>
        <button class="tab">Password</button>
        <button class="tab">Notifications</button>
      </div>

      <div class="form-container">
        <div class="profile-photo">
          <img src="{{ asset('assets/1594252-200.png') }}" alt="Profile"/>
          <div class="upload-btn">
            <button class="btn-red">Upload Now</button>
            <button class="btn-gray">Delete</button>
          </div>
        </div>

        <form action="{{ route('profil.update') }}" method="POST">
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

  <script src="{{ asset('js/profil.js') }}"></script>
</body>
</html>
