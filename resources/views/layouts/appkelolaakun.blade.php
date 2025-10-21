<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'PayColl PT. Telkom')</title>

    <!-- Bootstrap -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Dashboard CSS -->
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">

    <style>
        body {
            background-color: #f8f9fa;
        }
        .sidebar {
            width: 250px;
            min-height: 100vh;
        }
        .sidebar .nav-link {
            color: white;
        }
        .sidebar .nav-link:hover {
            background-color: #dc3545;
            color: white;
        }
        .header-title {
            font-size: 1.75rem;
            font-weight: 600;
        }
        .header-subtitle {
            color: #6c757d;
        }
    </style>
</head>
<body>
    <div class="d-flex">
        {{-- Sidebar --}}
        @include('partials.sidebar') {{-- pastikan partial sidebar ada --}}

        {{-- Konten utama --}}
        <div class="flex-grow-1 p-4">
            <div class="mb-4">
                <h1 class="header-title">@yield('header-title', 'Dashboard')</h1>
                <p class="header-subtitle">@yield('header-subtitle', '')</p>
            </div>

            {{-- Konten halaman spesifik --}}
            @yield('content')
        </div>
    </div>

    {{-- Footer --}}
    <footer class="text-center mt-4 mb-3 text-muted">
        <small>© 2025 Business Process Payment & Collection - PT. Telkom Indonesia Tbk. WilTel Riau</small>
    </footer>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    @stack('scripts')
</body>
</html>
