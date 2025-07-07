<!doctype html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>@yield('title', 'Dashboard')</title>

  <!-- Favicon -->
  <link rel="shortcut icon" type="image/png" href="{{ asset('backend/assets/images/logos/favicon.png') }}" />

  <!-- Core CSS -->
  <link rel="stylesheet" href="{{ asset('backend/assets/css/styles.min.css') }}" />

  <!-- SweetAlert2 CDN -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11" defer></script>

  <!-- Optional CSS Stack -->
  @stack('styles')
</head>
<body>
  <div class="page-wrapper" id="main-wrapper" data-layout="vertical"
       data-navbarbg="skin6" data-sidebartype="full"
       data-sidebar-position="fixed" data-header-position="fixed">

    {{-- Sidebar --}}
    @yield('sidebar')

    <!-- Main Wrapper -->
    <div class="body-wrapper">
      {{-- Navbar --}}
      @yield('navbar')

      <div class="container-fluid">
        @yield('content')
      </div>
    </div>
  </div>

  <!-- Core JS -->
  <script src="{{ asset('backend/assets/libs/jquery/dist/jquery.min.js') }}"></script>
  <script src="{{ asset('backend/assets/libs/bootstrap/dist/js/bootstrap.bundle.min.js') }}"></script>
  <script src="{{ asset('backend/assets/js/sidebarmenu.js') }}"></script>
  <script src="{{ asset('backend/assets/js/app.min.js') }}"></script>
  <script src="{{ asset('backend/assets/libs/apexcharts/dist/apexcharts.min.js') }}"></script>
  <script src="{{ asset('backend/assets/libs/simplebar/dist/simplebar.js') }}"></script>
  <script src="{{ asset('backend/assets/js/dashboard.js') }}"></script>

  <!-- SweetAlert: session success -->
  @if(session('success'))
  <script>
    document.addEventListener("DOMContentLoaded", function () {
      Swal.fire({
        icon: 'success',
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        showConfirmButton: false,
        timer: 2000
      });
    });
  </script>
  @endif

  <!-- SweetAlert: konfirmasi delete -->
 <script>
  document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".btn-delete");

    deleteButtons.forEach(function (button) {
      button.addEventListener("click", function (e) {
        e.preventDefault();
        const form = button.closest("form");

        Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: "Data akan dihapus secara permanen!",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Ya, hapus!',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  });
</script>


  <!-- Optional JS Stack -->
  @stack('scripts')
</body>
</html>
