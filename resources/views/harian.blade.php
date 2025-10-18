<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <meta name="asset-path" content="{{ asset('') }}">
  <title>Upload Data Harian - PayColl PT. Telkom</title>
  <link rel="stylesheet" href="{{ asset('css/harian.css') }}">
</head>
<body>
  
  <x-sidebar />

  <main class="main">
    <h1><span style="color:#e74c3c;">Upload Data</span><br>Harian</h1>

    <div class="upload-box" id="uploadBox">
      <div class="upload-content" id="uploadContent">
        <img src="{{ asset('img/cloud-upload-14.png') }}" alt="Upload Icon" class="upload-icon">
        <p><strong>Drag and Drop Files Here</strong></p>
        <p class="support">File Supported: Csv, Xlsx</p>
        <input type="file" id="fileInput" accept=".csv,.xlsx" hidden>
        <label for="fileInput" class="btn-upload">Choose File</label>
        <p id="fileName" class="support"></p>
      </div>

      <div class="upload-result hidden" id="uploadResult">
        <p id="selectedFileName" class="file-result"></p>
        <button id="btnUpload" class="btn-upload-confirm">Upload</button>
      </div>

      <div class="upload-loading hidden" id="uploadLoading">
        <div class="progress-circle">
          <span id="progressText">0%</span>
        </div>
        <p>Uploading File</p>
        <button id="cancelUpload" class="btn-cancel">Cancel</button>
      </div>
    </div>

    <div class="custom-popup" id="successPopup">
      <div class="popup-content">
        <img src="{{ asset('img/check.png') }}" alt="Success" class="popup-icon">
        <p id="popupMessage">File berhasil diupload</p>
      </div>
    </div>
  </main>

  <x-footer/>
    
  <script src="{{ asset('js/upload.js') }}"></script>
</body>
</html>
