@extends('layouts.app')

@section('title', 'Judul Halaman - PayColl PT. Telkom')

{{-- Optional: Header halaman --}}
@section('header-title', 'Judul Halaman')
@section('header-subtitle', 'Deskripsi singkat halaman jika ada.')

@section('content')
  {{-- Konten utama bisa ditambahkan di sini --}}
  <div class="blank-page">

  </div>
@endsection

@push('styles')
<style>
.blank-page {
  background: #fff;
  border-radius: 12px;
  padding: 2rem;
  box-shadow: 0 2px 8px rgba(0,0,0,0.05);
  min-height: 300px;
  font-size: 1rem;
  color: #333;
}
</style>
@endpush

@push('scripts')
<script>

console.log('Halaman kosong siap digunakan.');
</script>
@endpush
