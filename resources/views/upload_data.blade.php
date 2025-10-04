<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Upload Data - PayColl PT. Telkom</title>
  <link rel="stylesheet" href="{{ asset('css/uploaddata.css') }}">
</head>
<body>

    <x-sidebar /> 

  <div class="main-wrapper">
    <main class="main">
      <h1>Upload Data</h1>
      <div class="upload-container">
        <a href="{{ url('/harian') }}" class="upload-btn">Harian <span>⭡</span></a>
        <a href="{{ url('/bulanan') }}" class="upload-btn">Bulanan <span>⭡</span></a>
      </div>
    </main>

    <x-footer/>
    
  </div>

  <script src="{{ asset('js/updata.js') }}"></script>
</body>
</html>
