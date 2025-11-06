@extends('layouts.app')

@section('title', 'Upload Data')
@section('header-title', 'Upload Data')
@section('header-subtitle', 'Manage your data uploads here')

@section('content')
<style>
    body {
        background: #f7f9fc;
    }

    .upload-container {
        max-width: 1200px;
        margin: 2rem auto;
        padding: 0 1rem;
    }

    .upload-title {
        text-align: center;
        margin-bottom: 3rem;
    }

    .upload-title h1 {
        font-size: 2.2rem;
        font-weight: 700;
        color: #333;
    }

    .upload-options {
        display: flex;
        justify-content: center;
        gap: 2rem;
        flex-wrap: wrap;
        margin-bottom: 3rem;
    }

    .option-card {
        flex: 1 1 200px;
        max-width: 250px;
        text-align: center;
        padding: 2rem 1rem;
        border-radius: 12px;
        background: #fff;
        cursor: pointer;
        transition: all 0.3s ease;
        font-weight: 600;
        color: #444;
        box-shadow: 0 4px 10px rgba(0,0,0,0.08);
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 1rem;
    }

    .option-card:hover {
        background: #007bff;
        color: #fff;
        transform: translateY(-5px) scale(1.05);
        box-shadow: 0 8px 20px rgba(0,0,0,0.15);
    }

    .option-icon {
        font-size: 3rem;
    }

    /* Uploaded Data Table */
    .uploaded-data-box {
        background: #fff;
        padding: 1.5rem;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.1);
        margin-bottom: 3rem;
        overflow-x: auto;
    }

    .uploaded-data-box h5 {
        margin-bottom: 1rem;
        font-weight: 600;
        color: #333;
    }

    .uploaded-table {
        width: 100%;
        border-collapse: collapse;
    }

    .uploaded-table th, .uploaded-table td {
        padding: 0.75rem 1rem;
        text-align: left;
        border-bottom: 1px solid #e0e0e0;
        font-size: 0.95rem;
        white-space: nowrap;
    }

    .uploaded-table th {
        font-weight: 600;
        color: #555;
    }

    .uploaded-table tbody tr:hover {
        background: #f0f8ff;
    }

    .download-btn {
        background: #28a745;
        color: #fff;
        padding: 0.3rem 0.6rem;
        border-radius: 6px;
        font-size: 0.8rem;
        text-decoration: none;
        transition: 0.2s;
    }

    .download-btn:hover {
        background: #218838;
    }

    .alert {
        border-radius: 8px;
    }

/* Responsive Design */

/* Tablet Styles */
@media (max-width: 1024px) {
    .upload-container {
        padding: 1rem;
    }

    .upload-title h1 {
        font-size: 2rem;
    }

    .upload-options {
        gap: 1.5rem;
    }

    .option-card {
        flex: 1 1 180px;
        max-width: 220px;
        padding: 1.5rem 0.75rem;
    }

    .option-icon {
        font-size: 2.5rem;
    }

    .uploaded-table th,
    .uploaded-table td {
        padding: 0.5rem 0.75rem;
        font-size: 0.85rem;
    }
}

/* Mobile Styles */
@media (max-width: 768px) {
    .upload-container {
        padding: 0.75rem;
    }

    .upload-title {
        margin-bottom: 2rem;
    }

    .upload-title h1 {
        font-size: 1.75rem;
    }

    .upload-options {
        flex-direction: column;
        gap: 1rem;
        align-items: center;
    }

    .option-card {
        flex: 1 1 250px;
        max-width: 300px;
        width: 100%;
        padding: 1.5rem 1rem;
    }

    .option-icon {
        font-size: 2.5rem;
    }

    .uploaded-data-box {
        padding: 1rem;
    }

    .uploaded-data-box h5 {
        margin-bottom: 0.75rem;
        font-size: 1.1rem;
    }

    .uploaded-table {
        font-size: 0.8rem;
    }

    .uploaded-table th,
    .uploaded-table td {
        padding: 0.4rem 0.5rem;
    }

    .download-btn {
        padding: 0.25rem 0.5rem;
        font-size: 0.75rem;
    }
}

/* Small Mobile Styles */
@media (max-width: 480px) {
    .upload-container {
        padding: 0.5rem;
    }

    .upload-title h1 {
        font-size: 1.5rem;
    }

    .option-card {
        padding: 1.25rem 0.75rem;
        max-width: 280px;
    }

    .option-icon {
        font-size: 2rem;
    }

    .uploaded-data-box {
        padding: 0.75rem;
    }

    .uploaded-data-box h5 {
        font-size: 1rem;
    }

    .uploaded-table {
        font-size: 0.75rem;
    }

    .uploaded-table th,
    .uploaded-table td {
        padding: 0.3rem 0.4rem;
    }

    .download-btn {
        padding: 0.2rem 0.4rem;
        font-size: 0.7rem;
    }
}

</style>

<div class="upload-container">

    {{-- Title --}}
    <div class="upload-title">
        <h1>Upload Data</h1>
    </div>

    {{-- Pilihan Harian / Bulanan --}}
    <div class="upload-options">
        <div class="option-card" onclick="window.location='{{ route('upload.harian') }}'">
            <div class="option-icon"><i class="bi bi-calendar"></i></div>
            <div>Harian</div>
        </div>
        <div class="option-card" onclick="window.location='{{ route('upload.bulanan') }}'">
            <div class="option-icon"><i class="bi bi-calendar"></i></div>
            <div>Bulanan</div>
        </div>
    </div>

    {{-- Uploaded Data --}}
    <div class="uploaded-data-box">
        <h5>Uploaded Data</h5>

        @if($uploads->isEmpty())
            <p>No data uploaded yet.</p>
        @else
            <table class="uploaded-table">
                <thead>
                    <tr>
                        <th>No</th>
                        <th>File Name</th>
                        <th>Type</th>
                        <th>Uploaded By</th>
                        <th>Uploaded At</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($uploads as $index => $upload)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $upload->filename }}</td>
                            <td>{{ ucfirst($upload->type) }}</td>
                            <td>{{ optional(\App\Models\User::find($upload->uploaded_by))->nama_lengkap ?? 'N/A' }}</td>
<td>{{ \Carbon\Carbon::parse($upload->created_at)->timezone('Asia/Jakarta')->format('d-m-Y H:i') }}</td>
                            <td>
                                <a href="{{ asset('storage/' . $upload->path) }}" target="_blank" class="download-btn">Download</a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @endif
    </div>

</div>
@endsection
