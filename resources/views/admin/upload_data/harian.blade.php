@extends('layouts.app')

@section('title', 'Upload Data Harian - PayColl PT. Telkom')
@section('header-title', 'Upload Data')
@section('header-subtitle', 'Harian')

@push('styles')
<link rel="stylesheet" href="{{ asset('css/uploaddata.css') }}">
<style>
.page-header {
    margin-bottom: 20px;
}
.page-header h1 {
    color: #e74c3c;
    font-size: 1.8rem;
    font-weight: 600;
    margin-bottom: 8px;
}
.page-header p {
    color: #666;
    font-size: 1rem;
    margin: 0;
}
</style>
@endpush

@section('content')
<div class="upload-container">

    {{-- Flash Messages --}}
    @if(session('success'))
        <div class="alert alert-success" id="successAlert">
            {{ session('success') }}
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger" id="errorAlert">
            {{ session('error') }}
            <button type="button" class="close-btn" onclick="this.parentElement.style.display='none'">&times;</button>
        </div>
    @endif

    {{-- Modal for Duplicate Confirmation --}}
    @if(session('duplicates'))
        <div id="duplicateModal" class="modal" style="display: block;">
            <div class="modal-content">
                <h3>Duplikat Data Ditemukan</h3>
                <p>Ada <strong>{{ count(session('duplicates')) }}</strong> data yang sama sudah ada di database.</p>
                <p>Apakah Anda ingin mengganti data yang sudah ada?</p>
                <div class="modal-buttons">
                    <form method="POST" action="{{ route('upload.harian.import.replace') }}" style="display: inline;">
                        @csrf
                        <input type="hidden" name="file_id" value="{{ session('file_id') }}">
                        <button type="submit" class="btn btn-danger">Ya, Ganti Data</button>
                    </form>
                    <button type="button" class="btn btn-secondary" onclick="closeDuplicateModal()">Batal</button>
                </div>
            </div>
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
                Review Tabel (Opsional)
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
    height: 400px;
    padding: 50px;
    border: 2px dashed #007bff;
    border-radius: 12px;
    transition: background-color 0.3s, border-color 0.3s;
}
.upload-box:hover, .upload-box.dragover {
    background-color: #e3f2fd;
    border-color: #2196f3;
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
    opacity: 0;
    transform: translateY(-20px);
    transition: all 0.3s ease;
}
.alert.show {
    opacity: 1;
    transform: translateY(0);
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

/* Responsive Design */

/* Tablet Styles */
@media (max-width: 1024px) {
    .upload-container {
        max-width: 700px;
        padding: 0 20px;
    }

    .upload-box {
        height: 350px;
        padding: 40px;
    }

    .upload-icon {
        width: 80px;
    }

    .btn {
        padding: 10px 18px;
        font-size: 1rem;
    }
}

/* Mobile Styles */
@media (max-width: 768px) {
    .upload-container {
        max-width: 100%;
        margin: 15px auto;
        padding: 0 10px;
    }

    .upload-box {
        height: 300px;
        padding: 30px 20px;
        border-radius: 8px;
    }

    .upload-icon {
        width: 70px;
        margin-bottom: 15px;
    }

    .upload-content p {
        font-size: 1rem;
    }

    .support {
        font-size: 0.8rem;
    }

    .btn {
        padding: 12px 20px;
        margin: 8px 2px;
        font-size: 1rem;
        width: 100%;
        max-width: 200px;
    }

    .mt-4 {
        margin-top: 1.5rem;
    }

    .alert {
        padding: 12px 15px;
        font-size: 1rem;
    }
}

/* Modal Styles */
.modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.5);
    display: flex;
    justify-content: center;
    align-items: center;
    z-index: 1000;
    padding: 20px;
    box-sizing: border-box;
}

.modal-content {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    max-width: 500px;
    width: 100%;
    max-height: 90vh;
    overflow-y: auto;
    text-align: center;
    margin: auto;
}

.modal-content h3 {
    margin-top: 0;
    color: #333;
}

/* Removed ul styles since we no longer show the list */

.modal-buttons {
    margin-top: 20px;
}

.modal-buttons .btn {
    margin: 0 10px;
}

/* Small Mobile Styles */
@media (max-width: 480px) {
    .upload-container {
        padding: 0 5px;
    }

    .upload-box {
        height: 250px;
        padding: 20px 15px;
    }

    .upload-icon {
        width: 60px;
        margin-bottom: 10px;
    }

    .upload-content p {
        font-size: 0.9rem;
    }

    .support {
        font-size: 0.75rem;
    }

    .btn {
        padding: 10px 16px;
        font-size: 0.9rem;
        margin: 5px 1px;
    }

    .alert {
        padding: 10px 12px;
        font-size: 0.9rem;
    }

    .modal-content {
        padding: 15px;
        max-width: 90%;
    }

/* Removed ul responsive styles since we no longer show the list */
}
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
    const uploadBox = document.querySelector('.upload-box');

    // Function to validate file
    function isValidFile(file) {
        const validExtensions = ['.csv', '.xlsx'];
        const fileName = file.name.toLowerCase();
        return validExtensions.some(ext => fileName.endsWith(ext));
    }

    // Function to show error alert
    function showErrorAlert(message) {
        // Remove existing dynamic error alert if any
        const existingAlert = document.getElementById('dynamicErrorAlert');
        if (existingAlert) {
            existingAlert.remove();
        }

        // Create new alert
        const alertDiv = document.createElement('div');
        alertDiv.id = 'dynamicErrorAlert';
        alertDiv.className = 'alert alert-danger';
        alertDiv.innerHTML = message + '<button type="button" class="close-btn" onclick="this.parentElement.remove()">&times;</button>';

        // Insert after upload-container
        const container = document.querySelector('.upload-container');
        container.insertBefore(alertDiv, container.firstChild);

        // Fade in
        setTimeout(() => {
            alertDiv.classList.add('show');
        }, 100);

        // Fade out after 3 seconds
        setTimeout(() => {
            alertDiv.classList.remove('show');
            setTimeout(() => {
                alertDiv.remove();
            }, 300);
        }, 3000);
    }

    // Klik tombol Choose File → buka file dialog
    btnChooseFile.addEventListener('click', () => {
        fileInput.click();
    });

    // Enable Upload & Cancel saat file dipilih
    fileInput.addEventListener('change', () => {
        if (fileInput.files.length > 0) {
            const file = fileInput.files[0];
            if (isValidFile(file)) {
                const filename = file.name;
                fileNameEl.innerHTML = 'Selected File: <strong>' + filename + '</strong>';
                btnUpload.disabled = false;
                btnCancel.disabled = false;
            } else {
                showErrorAlert('Format file salah. Hanya file CSV atau XLSX yang diperbolehkan.');
                fileInput.value = '';
                fileNameEl.textContent = '';
                btnUpload.disabled = true;
                btnCancel.disabled = true;
            }
        } else {
            fileNameEl.textContent = '';
            btnUpload.disabled = true;
            btnCancel.disabled = true;
        }
    });

    // Drag and Drop functionality
    uploadBox.addEventListener('dragover', (e) => {
        e.preventDefault();
        uploadBox.classList.add('dragover');
    });

    uploadBox.addEventListener('dragleave', (e) => {
        e.preventDefault();
        uploadBox.classList.remove('dragover');
    });

    uploadBox.addEventListener('drop', (e) => {
        e.preventDefault();
        uploadBox.classList.remove('dragover');

        const files = e.dataTransfer.files;
        if (files.length > 0) {
            const file = files[0];
            if (isValidFile(file)) {
                // Set file to input
                const dt = new DataTransfer();
                dt.items.add(file);
                fileInput.files = dt.files;

                const filename = file.name;
                fileNameEl.innerHTML = 'Selected File: <strong>' + filename + '</strong>';
                btnUpload.disabled = false;
                btnCancel.disabled = false;
            } else {
                showErrorAlert('Format file salah. Hanya file CSV atau XLSX yang diperbolehkan.');
            }
        }
    });

    // Cancel button
    btnCancel.addEventListener('click', () => {
        fileInput.value = '';
        fileNameEl.textContent = '';
        btnUpload.disabled = true;
        btnCancel.disabled = true;
    });

    // Show success alert with fade in/out
    const successAlert = document.getElementById('successAlert');
    if (successAlert) {
        setTimeout(() => {
            successAlert.classList.add('show');
        }, 100);

        setTimeout(() => {
            successAlert.classList.remove('show');
            setTimeout(() => {
                successAlert.style.display = 'none';
            }, 300);
        }, 3000);
    }
});

// Function to close duplicate modal
function closeDuplicateModal() {
    document.getElementById('duplicateModal').style.display = 'none';
    // Clear session data by redirecting without parameters
    window.location.href = '{{ route("upload.harian") }}';
}
</script>
@endpush
