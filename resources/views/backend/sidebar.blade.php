<!-- Sidebar Start -->
<aside class="left-sidebar">
  <!-- Sidebar scroll-->
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between">
      <a href="{{ route('home.index') }}" class="text-nowrap logo-img">
        <img src="{{ asset('backend/assets/images/logos/dark-logo.svg') }}" width="180" alt="logo" />
      </a>
      <div class="close-btn d-xl-none d-block sidebartoggler cursor-pointer" id="sidebarCollapse">
        <i class="ti ti-x fs-8"></i>
      </div>
    </div>
    <!-- Sidebar navigation-->
    <nav class="sidebar-nav scroll-sidebar" data-simplebar="">
      <ul id="sidebarnav">
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">MAIN MENU</span>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('home.index') }}" aria-expanded="false">
            <span><i class="ti ti-home"></i></span>
            <span class="hide-menu">Home</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('kost.index') }}" aria-expanded="false">
            <span><i class="ti ti-building"></i></span>
            <span class="hide-menu">Kost</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('booking.index') }}" aria-expanded="false">
            <span><i class="ti ti-calendar-event"></i></span>
            <span class="hide-menu">Booking</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('kontak.index') }}" aria-expanded="false">
            <span><i class="ti ti-mail"></i></span>
            <span class="hide-menu">Kontak</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('pembayaran.index') }}" aria-expanded="false">
            <span><i class="ti ti-credit-card"></i></span>
            <span class="hide-menu">Pembayaran</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link" href="{{ route('tagihan.index') }}" aria-expanded="false">
            <span><i class="ti ti-file-invoice"></i></span>
            <span class="hide-menu">Tagihan</span>
          </a>
        </li>

      </ul>

      <div class="unlimited-access hide-menu bg-light-primary position-relative mb-7 mt-5 rounded">
        <div class="d-flex">
          <div class="unlimited-access-title me-3">
            <h6 class="fw-semibold fs-4 mb-6 text-dark w-85">Upgrade to pro</h6>
            <a href="https://adminmart.com/product/modernize-bootstrap-5-admin-template/" target="_blank" class="btn btn-primary fs-2 fw-semibold lh-sm">Buy Pro</a>
          </div>
          <div class="unlimited-access-img">
            <img src="{{ asset('backend/assets/images/backgrounds/rocket.png') }}" alt="" class="img-fluid">
          </div>
        </div>
      </div>
    </nav>
    <!-- End Sidebar navigation -->
  </div>
  <!-- End Sidebar scroll-->
</aside>
<!--  Sidebar End -->
