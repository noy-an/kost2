<aside class="left-sidebar">
  <div>
    <div class="brand-logo d-flex align-items-center justify-content-between px-3 py-2">
      <a href="{{ route('home.index') }}" class="text-nowrap logo-img">
        <img src="{{ asset('backend/assets/images/logos/dark-logo.svg') }}" width="180" alt="logo" />
      </a>
      <div class="close-btn d-xl-none d-block cursor-pointer" id="sidebarClose">
        <i class="ti ti-x fs-8"></i>
      </div>
    </div>

    <nav class="sidebar-nav scroll-sidebar" data-simplebar>
      <ul id="sidebarnav">
        <li class="nav-small-cap"><i class="ti ti-dots nav-small-cap-icon fs-4"></i><span class="hide-menu">MAIN MENU</span></li>
        
        <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('admin.index') ? 'active' : '' }}" href="{{ route('admin.index') }}">
            <i class="ti ti-home"></i><span class="hide-menu">Home</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('kost.*') ? 'active' : '' }}" href="{{ route('kost.index') }}">
            <i class="ti ti-building-community"></i><span class="hide-menu">Kost</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('booking.*') ? 'active' : '' }}" href="{{ route('booking.index') }}">
            <i class="ti ti-calendar-event"></i><span class="hide-menu">Booking</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('tagihan.*') ? 'active' : '' }}" href="{{ route('tagihan.index') }}">
            <i class="ti ti-file-invoice"></i><span class="hide-menu">Tagihan</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}" href="{{ route('pembayaran.index') }}">
            <i class="ti ti-credit-card"></i><span class="hide-menu">Pembayaran</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}" href="{{ route('pengguna.index') }}">
            <i class="ti ti-users"></i><span class="hide-menu">Pengguna</span>
          </a>
        </li>

        <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('kontak.*') ? 'active' : '' }}" href="{{ route('kontak.index') }}">
            <i class="ti ti-mail"></i><span class="hide-menu">Kontak</span>
          </a>
        </li>
      </ul>

      <div class="unlimited-access hide-menu bg-light-primary position-relative mt-5 rounded mx-3 mb-4">
        <div class="d-flex">
          <div class="unlimited-access-title me-3">
            <h6 class="fw-semibold fs-4 mb-2 text-dark w-85">Upgrade to Pro</h6>
            <a href="https://adminmart.com/product/modernize-bootstrap-5-admin-template/" target="_blank" class="btn btn-primary fs-2 fw-semibold">Buy Pro</a>
          </div>
          <div class="unlimited-access-img">
            <img src="{{ asset('backend/assets/images/backgrounds/rocket.png') }}" class="img-fluid" alt="">
          </div>
        </div>
      </div>
    </nav>
  </div>
</aside>
