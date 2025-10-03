<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Registrasi Berhasil</title>
  <link rel="stylesheet" href="{{ asset('css/login.css') }}">
</head>
<body>
  <div class="container">
    <div class="branding">
      <img src="{{ asset('img/logo_telkom.png') }}" alt="Telkom Indonesia Logo" class="logo">
      <h2>PAYCOLL</h2>
      <h3>Telkom Indonesia</h3>
    </div>

    <div class="form-box">
      <div class="form active">
        <h2 class="login-title">Registrasi Berhasil</h2>
        <p class="success-text">
          Akun Anda berhasil didaftarkan.<br>
          Silakan lakukan verifikasi melalui email Anda sebelum login.
        </p>
        <button onclick="window.location.href='{{ url('/') }}'" class="btn">
          Kembali ke Login
        </button>
      </div>
    </div>
  </div>
</body>
</html>