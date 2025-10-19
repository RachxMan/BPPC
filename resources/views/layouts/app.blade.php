<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>@yield('title', 'PayColl PT. Telkom')</title>

  {{-- Global CSS --}}
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  @stack('styles')

  <style>
    /* ===== Struktur Dasar ===== */
    html, body {
      margin: 0;
      padding: 0;
      height: 100%;
      font-family: 'Poppins', sans-serif;
      background: #f7f8fa;
      color: #333;
    }

    body {
      display: flex;
      flex-direction: row; /* sidebar + main content */
      min-height: 100vh;
    }

    /* Sidebar */
    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 260px;
      height: 100vh;
      background: #121212;
      color: #f0f0f0;
      display: flex;
      flex-direction: column;
      z-index: 100;
      transition: all 0.3s ease;
    }

    /* Konten utama */
    main.main-wrapper {
      margin-left: 260px; /* beri ruang sidebar */
      flex: 1;
      display: flex;
      flex-direction: column;
      min-height: 100vh;
      transition: all 0.3s ease;
    }

    main.main {
      flex: 1;
      display: flex;
      flex-direction: column;
      padding: 2rem 3rem;
    }

    /* Header halaman */
    .page-header {
      margin-bottom: 2rem;
    }
    .page-header h1 {
      font-size: 1.8rem;
      font-weight: 700;
      color: #d32f2f;
      margin: 0;
    }
    .page-header p {
      color: #666;
      margin-top: 0.3rem;
      font-size: 0.95rem;
    }

    /* Tombol hamburger mobile */
    .hamburger {
      display: none;
      position: fixed;
      top: 1rem;
      left: 1rem;
      background: #ff4d4f;
      color: white;
      border: none;
      font-size: 1.5rem;
      padding: 0.4rem 0.8rem;
      border-radius: 6px;
      z-index: 200;
      cursor: pointer;
    }

    .overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.3);
      z-index: 150;
    }

    .overlay.hidden {
      display: none;
    }

    /* Responsif */
    @media (max-width: 992px) {
      .sidebar {
        left: -260px;
      }

      .sidebar.active {
        left: 0;
      }

      main.main-wrapper {
        margin-left: 0;
      }

      main.main {
        padding: 1.5rem;
      }

      .hamburger {
        display: block;
      }
    }
  </style>
</head>

<body>

  {{-- Sidebar --}}
  @include('layouts.sidebar')

  {{-- Tombol Hamburger & Overlay --}}
  <button id="hamburger" class="hamburger" aria-label="Buka menu">☰</button>
  <div id="overlay" class="overlay hidden"></div>

  {{-- Konten + Footer wrapper --}}
  <main class="main-wrapper">
    <main class="main" id="main-content">
      {{-- Header Halaman --}}
      @hasSection('header-title')
        <header class="page-header">
          <h1>@yield('header-title')</h1>
          @hasSection('header-subtitle')
            <p>@yield('header-subtitle')</p>
          @endif
        </header>
      @endif

      {{-- Konten Halaman --}}
      @yield('content')
    </main>

    {{-- Footer --}}
    @include('layouts.footer')
  </main>

  {{-- Script Global --}}
  <script>
    const sidebar = document.querySelector('.sidebar');
    const hamburger = document.getElementById('hamburger');
    const overlay = document.getElementById('overlay');

    if (hamburger && sidebar && overlay) {
      hamburger.addEventListener('click', () => {
        sidebar.classList.toggle('active');
        overlay.classList.toggle('hidden');
      });

      overlay.addEventListener('click', () => {
        sidebar.classList.remove('active');
        overlay.classList.add('hidden');
      });
    }
  </script>

  @stack('scripts')
</body>
</html>
