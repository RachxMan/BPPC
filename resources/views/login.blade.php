<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Telkom Paycoll - Login/Register</title>
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

      <div class="form-header">
        <button type="button" class="role-btn active" data-role="Admin">Admin</button>
      </div>

<form id="loginForm" class="form active" method="POST" action="{{ route('login.submit') }}">
    @csrf
    <h2 class="login-title">Log In</h2>
    <div class="input-group">
      <label for="username">Username</label>
      <input type="text" id="username" name="username" placeholder="Enter username" value="{{ old('username') }}" required>
    </div>
    <div class="input-group">
      <label for="password">Password</label>
      <div class="password-wrapper">
        <input type="password" id="password" name="password" placeholder="Enter password" required>
        <span class="toggle-password" data-target="password">
          <img src="{{ asset('img/eye-close-svgrepo-com.svg') }}" alt="show/hide">
        </span>
      </div>
    </div>
    <div class="options">
      <label><input type="checkbox" name="remember"> Ingat saya</label>
      <a href="#">Lupa password?</a>
    </div>
    <button type="submit" class="btn">Login</button>

    @if($errors->any())
        <div style="color:red; margin-top:10px;">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <p class="toggle-link">Belum punya akun? <a href="{{ route('register.show') }}">Daftar</a></p>
</form>


    </div>
  </div>

  <script src="{{ asset('js/regist_login.js') }}"></script>
</body>
</html>