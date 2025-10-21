@extends('layouts.app')

@section('title', 'Upload Data - PayColl PT. Telkom')

@section('header-title', 'Upload Data')
@section('header-subtitle', 'Pilih jenis data yang ingin diunggah ke sistem.')

@section('content')
  <div class="upload-container">
    <a href="{{ url('/laporan/harian') }}" class="upload-btn">Harian <span>⭡</span></a>
    <a href="{{ url('/laporan/bulanan') }}" class="upload-btn">Bulanan <span>⭡</span></a>
  </div>
@endsection

@push('styles')
  <link rel="stylesheet" href="{{ asset('css/uploaddata.css') }}">
@endpush

@push('scripts')
  <script src="{{ asset('js/updata.js') }}"></script>
@endpush
