<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Telkom Paycoll - Register</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
  <div class="container">
    <div class="branding">
      <img src="{{ asset('img/logo_telkom.png') }}" alt="Telkom Logo" class="logo">
      <h2>PAYCOLL</h2>
      <h3>Telkom Indonesia</h3>
    </div>

    <div class="form-box">
      <form id="registerForm" method="POST" action="{{ route('register.store') }}">
        @csrf
        <h2 class="login-title">Register</h2>

        <div class="input-group">
          <label for="email">Email</label>
          <input type="email" id="email" name="email" placeholder="Email" required>
          <small class="error-message" id="email-error"></small>
        </div>

        <div class="input-group">
          <label for="reg-username">Username</label>
          <input type="text" id="reg-username" name="username" placeholder="Username" required>
          <small class="error-message" id="username-error"></small>
        </div>

        <div class="input-group">
          <label for="reg-password">Password</label>
          <div class="password-wrapper">
            <input type="password" id="reg-password" name="password" placeholder="Password" required>
            <span class="toggle-password" data-target="reg-password">
              <img src="{{ asset('img/eye-close-svgrepo-com.svg') }}" alt="show/hide">
            </span>
          </div>
          <small class="error-message" id="password-error"></small>
        </div>

        <div class="input-group">
          <label for="confirm-password">Confirm Password</label>
          <div class="password-wrapper">
            <input type="password" id="confirm-password" name="password_confirmation" placeholder="Confirm Password" required>
            <span class="toggle-password" data-target="confirm-password">
              <img src="{{ asset('img/eye-close-svgrepo-com.svg') }}" alt="show/hide">
            </span>
          </div>
          <small class="error-message" id="confirm-error"></small>
        </div>

        <div class="input-group">
          <label for="fullname">Nama Lengkap</label>
          <input type="text" id="fullname" name="nama_lengkap" placeholder="Nama Lengkap" required>
        </div>

        <div class="input-group">
          <label for="phone">No. HP</label>
          <div class="phone-input">
            <span class="prefix">+62</span>
            <input type="tel" id="phone" name="no_telp" placeholder="8123456789" required pattern="[0-9]*">
          </div>
          <small class="error-message" id="phone-error"></small>
        </div>

        <div class="input-group">
          <label for="role">Role</label>
          <select name="role" id="role" required>
            <option value="ca">CA</option>
            <option value="admin">Admin</option>
          </select>
        </div>

        <button type="submit" class="btn">Daftar</button>

        <p class="toggle-link">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
      </form>
    </div>
  </div>

  <script src="{{ asset('js/login_register.js') }}"></script>
</body>
</html>
