@extends('layouts.app')

@section('title', 'Upload Data Harian - PayColl PT. Telkom')
@section('header-title', 'Upload Data')
@section('header-subtitle', 'Harian')

@section('content')
<div class="upload-container">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
            <button type="button" class="close-btn" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
            <button type="button" class="close-btn" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif

    {{-- Upload Box --}}
    <div class="upload-box">
        <div class="upload-content text-center">
            <img src="{{ asset('img/cloud-upload-14.png') }}" alt="Upload Icon" class="upload-icon">
            <p><strong>Drag and Drop Files Here</strong></p>
            <p class="support">Supported Files: CSV, XLSX</p>

            <form id="uploadForm" action="{{ route('upload.harian.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="file" id="fileInput" name="file" accept=".csv,.xlsx" style="display:none;">

                {{-- Choose File --}}
                <button type="button" id="btnChooseFile" class="btn btn-info">Choose File</button>

                {{-- Upload & Cancel --}}
                <div class="mt-4">
                    <button type="submit" id="btnUpload" class="btn btn-success" disabled>Upload</button>
                    <button type="button" id="btnCancel" class="btn btn-danger" disabled>Cancel</button>
                </div>

                {{-- Nama File Terpilih --}}
                <p id="fileName" class="support mt-3">
                    @if(session('uploaded_file_name'))
                        Selected File: <strong>{{ session('uploaded_file_name') }}</strong>
                    @endif
                </p>
            </form>
        </div>
    </div>

    {{-- Tombol Review Table --}}
    <div class="mt-3 text-center">
        @if(isset($lastFileId))
            <a href="{{ route('upload.harian.review', $lastFileId) }}" id="btnReview" class="btn btn-warning">
                Review Tabel untuk Simpan ke Database
            </a>
        @else
            <button class="btn btn-warning" id="btnReview" disabled>Review Table</button>
        @endif
    </div>

</div>
@endsection

@push('styles')
<style>
/* Container */
.upload-container {
    max-width: 800px;
    margin: 20px auto;
    padding: 0 15px;
}

/* Upload Box */
.upload-box {
    min-height: 400px;
    padding: 50px;
    border: 2px dashed #007bff;
    border-radius: 12px;
    transition: background-color 0.3s;
}
.upload-box:hover {
    background-color: #f7f9fc;
    cursor: pointer;
}

/* Upload Icon */
.upload-icon {
    width: 90px;
    margin-bottom: 20px;
    transition: transform 0.3s;
}
.upload-box:hover .upload-icon {
    transform: scale(1.1);
}

/* Text */
.support {
    font-size: 0.9rem;
    color: #555;
    margin-top: 10px;
}

/* Buttons */
.btn {
    padding: 8px 16px;
    margin: 5px;
    font-size: 0.9rem;
    border-radius: 5px;
    border: none;
    cursor: pointer;
    display: inline-block;
    text-decoration: none;
}
.btn:disabled {
    opacity: 0.6;
    cursor: not-allowed;
}
.btn-info {
    background-color: #17a2b8;
    color: white;
}
.btn-info:hover {
    background-color: #138496;
}
.btn-success {
    background-color: #28a745;
    color: white;
}
.btn-success:hover {
    background-color: #218838;
}
.btn-danger {
    background-color: #dc3545;
    color: white;
}
.btn-danger:hover {
    background-color: #c82333;
}
.btn-warning {
    background-color: #ffc107;
    color: black;
}
.btn-warning:hover {
    background-color: #e0a800;
}

/* Alerts */
.alert {
    position: relative;
    padding: 10px 20px;
    margin-bottom: 15px;
    border-radius: 5px;
    font-size: 0.9rem;
}
.alert-success {
    background-color: #d4edda;
    color: #155724;
}
.alert-danger {
    background-color: #f8d7da;
    color: #721c24;
}
.close-btn {
    position: absolute;
    top: 5px;
    right: 10px;
    background: none;
    border: none;
    font-size: 1.2rem;
    line-height: 1;
    cursor: pointer;
}

/* Margin helpers */
.mt-3 { margin-top: 1rem; }
.mt-4 { margin-top: 1.25rem; }
.mt-12 { margin-top: 3rem; }
.me-3 { margin-right: 1rem; }
.text-center { text-align: center; }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const fileInput = document.getElementById('fileInput');
    const btnChooseFile = document.getElementById('btnChooseFile');
    const fileNameEl = document.getElementById('fileName');
    const btnUpload = document.getElementById('btnUpload');
    const btnCancel = document.getElementById('btnCancel');

    // Klik tombol Choose File → buka file dialog
    btnChooseFile.addEventListener('click', () => {
        fileInput.click();
    });

    // Enable Upload & Cancel saat file dipilih
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            const filename = fileInput.files[0].name;
            fileNameEl.innerHTML = 'Selected File: <strong>' + filename + '</strong>';
            btnUpload.disabled = false;
            btnCancel.disabled = false;
        } else {
            fileNameEl.textContent = '';
            btnUpload.disabled = true;
            btnCancel.disabled = true;
        }
    });

    // Cancel button
    btnCancel.addEventListener('click', () => {
        fileInput.value = '';
        fileNameEl.textContent = '';
        btnUpload.disabled = true;
        btnCancel.disabled = true;
    });
});
</script>
@endpush
