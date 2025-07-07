<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>@yield('title', 'Dashboard')</title>

  <link rel="shortcut icon" type="image/png" href="{{ asset('backend/assets/images/logos/favicon.png') }}" />
  <link rel="stylesheet" href="{{ asset('backend/assets/css/styles.min.css') }}" />
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

  <style>
    @media (max-width: 1199.98px) {
      #main-wrapper .left-sidebar {
        transform: translateX(-100%);
        transition: transform 0.3s ease;
        position: fixed;
        z-index: 1030;
        height: 100vh;
        top: 0;
        left: 0;
        background-color: #fff;
        width: 280px;
      }

      #main-wrapper.show-sidebar .left-sidebar {
        transform: translateX(0);
      }

      .sidebar-overlay {
        display: none;
      }

      #main-wrapper.show-sidebar .sidebar-overlay {
        display: block;
        position: fixed;
        inset: 0;
        background-color: rgba(0, 0, 0, 0.5);
        z-index: 1020;
        cursor: pointer;
      }
    }
  </style>

  @stack('styles')
</head>
<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical"
      >

    @include('backend.sidebar')
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="body-wrapper">
      @include('backend.navbar')
      <div class="container-fluid py-3">
        @yield('content')
      </div>
    </div>
  </div>

  <script src="{{ asset('backend/assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('backend/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('backend/assets/js/app.min.js') }}"></script>
  <script src="{{ asset('backend/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="{{ asset('backend/assets/libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ asset('backend/assets/js/dashboard.js') }}"></script>

  @if(session('success'))
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: 'Succes',
        showConfirmButton: false,
        timer: 2000
      });
    });
  </script>
  @endif

  <script>
    document.addEventListener("DOMContentLoaded", function () {
      const openBtn = document.getElementById("headerCollapse");
      const closeBtn = document.getElementById("sidebarClose");
      const overlay = document.getElementById("sidebarOverlay");
      const wrapper = document.getElementById("main-wrapper");

      if (openBtn) {
        openBtn.addEventListener("click", function () {
          wrapper.classList.add("show-sidebar");
        });
      }

      if (closeBtn) {
        closeBtn.addEventListener("click", function () {
          wrapper.classList.remove("show-sidebar");
        });
      }

      if (overlay) {
        overlay.addEventListener("click", function () {
          wrapper.classList.remove("show-sidebar");
        });
      }
    });
  </script>

  @stack('scripts')
</body>
</html>
