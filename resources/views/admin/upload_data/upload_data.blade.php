@extends('layouts.app')

@section('title', 'Upload Data - PayColl PT. Telkom')

@section('header-title', 'Upload Data')
@section('header-subtitle', 'Pilih jenis data yang ingin diunggah ke sistem.')

@section('content')
  <div class="upload-container">
    {{-- arahkan ke route upload.harian dan upload.bulanan --}}
    <a href="{{ route('upload.harian') }}" class="upload-btn">Harian <span>⭡</span></a>
    <a href="{{ route('upload.bulanan') }}" class="upload-btn">Bulanan <span>⭡</span></a>
  </div>
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/uploaddata.css') }}">
@endpush

@push('scripts')
  <script src="{{ asset('js/updata.js') }}"></script>
@endpush
