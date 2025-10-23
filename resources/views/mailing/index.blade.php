@extends('layouts.app')

@section('title', 'Mailing List - PayColl PT. Telkom')

@section('header-title', 'Mailing List')
@section('header-subtitle', 'Halaman untuk mengelola dan mengunduh data Mailing List Reminder.')

@section('content')
<div class="mailing-list-page">
    <div class="mailing-form">
        <label for="no_inet" class="form-label">No. Inet</label>
        <input type="text" id="no_inet" class="form-input" placeholder="Masukkan No. Inet" >
        <button class="btn-enter">Enter</button>
    </div>

    <div class="download-section">
        <button class="btn-download">
            <i class="fa-solid fa-download"></i>
            <span>Unduh Mailing List</span>
        </button>
    </div>
</div>
@endsection

@push('styles')
<link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
<style>
.mailing-list-page {
    background: #fff;
    border-radius: 12px;
    padding: 3rem 2rem;
    box-shadow: 0 2px 8px rgba(0,0,0,0.05);
    color: #333;
    display: flex;
    flex-direction: column;
    align-items: center;
    min-height: 500px;
}

.mailing-form {
    display: flex;
    align-items: center;
    gap: 1rem;
    width: 100%;
    max-width: 700px;
    margin-bottom: 4rem;
}

.form-label {
    font-weight: 600;
    color: #d32f2f;
    white-space: nowrap;
}

.form-input {
    flex: 1;
    padding: 0.75rem 1rem;
    border: 1px solid #ddd;
    border-radius: 6px;
    font-size: 1rem;
}

.btn-enter {
    background-color: #d32f2f;
    border: none;
    color: #fff;
    padding: 0.75rem 1.5rem;
    border-radius: 6px;
    font-weight: 600;
    cursor: pointer;
    transition: 0.2s;
}

.btn-enter:hover {
    background-color: #b71c1c;
}

.download-section {
    display: flex;
    justify-content: center;
}

.btn-download {
    background: #fff;
    border: 1px solid #eee;
    box-shadow: 0 2px 6px rgba(0,0,0,0.1);
    border-radius: 8px;
    padding: 1rem 2rem;
    font-size: 1rem;
    cursor: pointer;
    font-weight: 600;
    color: #333;
    display: flex;
    align-items: center;
    gap: 0.75rem;
    transition: 0.2s ease-in-out;
}

.btn-download:hover {
    transform: translateY(-2px);
    box-shadow: 0 3px 8px rgba(0,0,0,0.15);
}
</style>
@endpush

@push('scripts')
<script>
console.log('Halaman Mailing List siap digunakan.');
</script>
@endpush
