<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>@yield('title', 'PayColl PT. Telkom')</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/profil.css') }}">
    @stack('styles')
</head>
<body>
    <!-- Sidebar -->
    @include('layouts.sidebar')

    <!-- Konten utama -->
    <main class="content">
        @yield('content')
    </main>

    <!-- Hamburger button untuk mobile -->
    <button id="hamburger" class="hamburger">☰</button>
    <div id="overlay" class="overlay hidden"></div>

    <!-- JS -->
    <script src="{{ asset('js/profil.js') }}"></script>
    @stack('scripts')
</body>
</html>
