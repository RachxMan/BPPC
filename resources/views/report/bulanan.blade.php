@extends('layouts.app')

@section('title', 'Upload Data Harian - PayColl PT. Telkom')

@section('header-title', 'Upload Data')
@section('header-subtitle', 'Bulanan')

@section('content')
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
    <button id="cancelUpload" class="cancel-btn">Cancel</button>
  </div>

  <div class="upload-loading hidden" id="uploadLoading">
    <div class="progress-circle">
      <span id="progressText">0%</span>
    </div>
    <p>Uploading File...</p>
    <button id="cancelUpload" class="cancel-btn">Cancel</button>
  </div>

</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/harian.css') }}">
@endpush

@push('scripts')
<script src="{{ asset('js/upload.js') }}"></script>
@endpush
