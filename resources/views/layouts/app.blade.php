<!DOCTYPE html>
<html lang="id">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width,initial-scale=1" />
  <title>@yield('title', 'PayColl PT. Telkom')</title>

  {{-- Favicon --}}
  <link rel="icon" type="image/png" href="{{ asset('img/1594112895830_compress_PNG Icon Telkom.png') }}">

  {{-- Global CSS --}}
  <link rel="stylesheet" href="{{ asset('css/app.css') }}">
  <link rel="stylesheet" href="{{ asset('css/sidebar.css') }}">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
  @stack('styles')


</head>

<body>

  @include('layouts.sidebar')

  <button id="hamburger" class="hamburger" aria-label="Buka menu" aria-expanded="false">☰</button>
  <div id="overlay" class="overlay"></div>

  <main class="main-wrapper">
    <main class="main" id="main-content">

      @hasSection('header-title')
        <header class="page-header">
          <h1>@yield('header-title')</h1>
          @hasSection('header-subtitle')
            <p>@yield('header-subtitle')</p>
          @endif
        </header>
      @endif

      @yield('content')
    </main>

    @include('layouts.footer')
  </main>

  <script>
    document.addEventListener('DOMContentLoaded', () => {
      const sidebar = document.querySelector('.sidebar');
      const hamburger = document.getElementById('hamburger');
      const overlay = document.getElementById('overlay');
      const body = document.body;

      function toggleSidebar() {
        console.log('Toggling sidebar');
        const isActive = sidebar.classList.toggle('active');
        overlay.classList.toggle('show', isActive);
        body.classList.toggle('no-scroll', isActive);
        hamburger.setAttribute('aria-expanded', isActive.toString());
      }

      function closeSidebar() {
        sidebar.classList.remove('active');
        overlay.classList.remove('show');
        body.classList.remove('no-scroll');
        hamburger.setAttribute('aria-expanded', 'false');
      }

      if (hamburger && sidebar && overlay) {
        hamburger.addEventListener('click', toggleSidebar);
        overlay.addEventListener('click', closeSidebar);
      }

      // Close sidebar when clicking on sidebar links (mobile)
      document.addEventListener('click', (e) => {
        if (e.target.classList.contains('sidebar-link') && window.innerWidth <= 992) {
          closeSidebar();
        }
      });

      // Handle window resize
      window.addEventListener('resize', () => {
        if (window.innerWidth >= 992) {
          closeSidebar();
        }
      });
    });
  </script>

  @stack('scripts')
</body>
</html>
