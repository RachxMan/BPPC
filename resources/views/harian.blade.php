<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>Upload Data Harian - PayColl PT. Telkom</title>
  <link rel="stylesheet" href="{{ asset('css/harian.css') }}">
</head>
<body>
  
    <x-sidebar />


  <main class="main">
    <h1><span style="color:#e74c3c;">Upload Data</span><br>Harian</h1>

    <div class="upload-box" id="uploadBox">
      <div class="upload-content">
        <img src="{{ asset('img/cloud-upload-14.png') }}" alt="Upload Icon" class="upload-icon">
        <p><strong>Drag and Drop Files Here</strong></p>
        <p class="support">File Supported: Csv, Xlsx</p>
        <input type="file" id="fileInput" accept=".csv,.xlsx" hidden>
        <label for="fileInput" class="btn-upload">Choose File</label>
        <p id="fileName" class="support"></p>
      </div>
    </div>
  </main>

    <x-footer/>
    
  <script src="{{ asset('js/upload.js') }}"></script>
</body>
</html>
