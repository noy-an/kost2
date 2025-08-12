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
        <li class="nav-small-cap">
          <i class="ti ti-dots nav-small-cap-icon fs-4"></i>
          <span class="hide-menu">MAIN MENU</span>
        </li>
   @if(Auth::check() && Auth::user()->role === 'admin')
        <!-- Home -->
        <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('admin.index') ? 'active' : '' }}" href="{{ route('admin.index') }}">
            <i class="ti ti-home"></i><span class="hide-menu">Home</span>
          </a>
        </li>

        <!-- Kost -->
        <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('kost.*') ? 'active' : '' }}" href="{{ route('kost.index') }}">
            <i class="ti ti-building-community"></i><span class="hide-menu">Kost</span>
            @if($kostBaru)
              <span class="badge bg-danger rounded-pill small ms-2">NEW</span>
            @endif
          </a>
        </li>

        <!-- Booking -->
        <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('booking.*') ? 'active' : '' }}" href="{{ route('booking.index') }}">
            <i class="ti ti-calendar-event"></i><span class="hide-menu">Booking</span>
            @if($bookingBaru)
              <span class="badge bg-danger rounded-pill small ms-2">NEW</span>
            @endif
          </a>
        </li>

        <!-- Tagihan -->
        <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('tagihan.*') ? 'active' : '' }}" href="{{ route('tagihan.index') }}">
            <i class="ti ti-file-invoice"></i><span class="hide-menu">Pembayaran</span>
            @if($tagihanBaru)
              <span class="badge bg-danger rounded-pill small ms-2">NEW</span>
            @endif
          </a>
        </li>

         <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('deposit.*') ? 'active' : '' }}" href="{{ route('deposit.index') }}">
            <i class="ti ti-file-invoice"></i><span class="hide-menu">Deposit</span>
            @if($depositBaru)
              <span class="badge bg-danger rounded-pill small ms-2">NEW</span>
            @endif
          </a>
        </li>

        <!-- Pembayaran -->
        {{-- <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('pembayaran.*') ? 'active' : '' }}" href="{{ route('pembayaran.index') }}">
            <i class="ti ti-credit-card"></i><span class="hide-menu">Pembayaran</span>
            @if($pembayaranBaru)
              <span class="badge bg-danger rounded-pill small ms-2">NEW</span>
            @endif
          </a>
        </li> --}}

        <!-- Pengguna -->
        <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('pengguna.*') ? 'active' : '' }}" href="{{ route('pengguna.index') }}">
            <i class="ti ti-users"></i><span class="hide-menu">Pengguna</span>
            @if($penggunaBaru)
              <span class="badge bg-danger rounded-pill small ms-2">NEW</span>
            @endif
          </a>
        </li>

        <!-- Kontak -->
        <li class="sidebar-item">
          <a class="sidebar-link {{ request()->routeIs('kontak.*') ? 'active' : '' }}" href="{{ route('kontak.index') }}">
            <i class="ti ti-mail"></i><span class="hide-menu">Kontak</span>
            @if($kontakBaru)
              <span class="badge bg-danger rounded-pill small ms-2">NEW</span>
            @endif
          </a>
        </li>
        @if(Auth::user() && Auth::user()->role == 'pemilik')
    <li class="sidebar-item">
        <a class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
            <i class="ti ti-chart-bar"></i><span class="hide-menu">Laporan</span>
        </a>
    </li>
@endif

        {{-- Hanya tampil untuk role pemilik --}}
    @elseif(Auth::check() && Auth::user()->role == 'pemilik')
<li class="sidebar-item">
  <a class="sidebar-link {{ request()->routeIs('pemilik.*') ? 'active' : '' }}" href="{{ route('pemilik.index') }}">
    <i class="ti ti-layout-dashboard"></i><span class="hide-menu">Dashboard</span>
  </a>
</li>
<li class="sidebar-item">
  <a class="sidebar-link {{ request()->routeIs('laporan.*') ? 'active' : '' }}" href="{{ route('laporan.index') }}">
    <i class="ti ti-report"></i><span class="hide-menu">Laporan</span>
  </a>
</li>
        @endif
      </ul>
    </nav>
  </div>
</aside>
