<footer class="footer">
  © 2025 Business Process Payment & Collection - PT. Telkom Indonesia Tbk. Witel Riau
</footer>

<style>
  html, body {
    height: 100%;
    margin: 0;
  }

  body {
    display: flex;
  }

  /* ====== Sidebar ====== */
  .sidebar {
    width: 260px; /* sesuaikan dengan sidebar kamu */
    background: #111;
    color: white;
    flex-shrink: 0;
    display: flex;
    flex-direction: column;
  }

  /* ====== Wrapper konten utama ====== */
  .main-wrapper {
    display: flex;
    flex-direction: column;
    flex: 1;
    min-height: 100vh;
  }

  .main-content {
    flex: 1;
    padding: 1rem;
  }

  /* ====== Footer ====== */
  .footer {
    background: #000;
    color: #fff;
    text-align: center;
    padding: 1rem 0;
    font-size: 0.9rem;
    border-top: 1px solid #333;
  }

  @media (max-width: 768px) {
    body {
      flex-direction: column;
    }
    .sidebar {
      width: 100%;
    }
    .footer {
      font-size: 0.8rem;
      padding: 0.8rem 0;
    }
  }
</style>
