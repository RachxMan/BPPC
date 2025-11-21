<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>@yield('title', 'Telkom Paycoll')</title>
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('img/1594112895830_compress_PNG Icon Telkom.png') }}">
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
      @yield('form-content')
    </div>
  </div>

  <script src="{{ asset('js/regist_login.js') }}"></script>
</body>
</html>
