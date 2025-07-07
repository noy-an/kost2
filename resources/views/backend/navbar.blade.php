<header class="app-header shadow-sm bg-white border-bottom">
  <nav class="navbar navbar-expand-lg navbar-light px-3 py-2">
    <div class="d-flex align-items-center w-100 justify-content-between">
      <!-- Sidebar Toggler (hamburger menu) -->
      <a class="nav-link d-block d-xl-none text-muted fs-4" id="headerCollapse" href="javascript:void(0)">
        <i class="ti ti-menu-2"></i>
      </a>

      <div class="navbar-collapse justify-content-end d-flex" id="navbarNav">
        <ul class="navbar-nav align-items-center gap-3">
          <li class="nav-item position-relative">
            <a class="nav-link text-muted" href="#"><i class="ti ti-bell fs-5"></i>
              <span class="position-absolute top-0 start-100 translate-middle p-1 bg-danger border border-light rounded-circle"></span>
            </a>
          </li>

          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#" id="userDropdown" role="button" data-bs-toggle="dropdown">
              <img src="{{ asset(auth()->user()->avatar ?? 'backend/assets/images/profile/user-1.jpg') }}" class="rounded-circle" width="35" height="35" alt="avatar">
              <span class="d-none d-md-inline fw-semibold text-dark">{{ auth()->user()->name }}</span>
            </a>
            <ul class="dropdown-menu dropdown-menu-end shadow-sm animate__animated animate__fadeIn">
              <li><a class="dropdown-item" href="#"><i class="ti ti-user"></i> Profil Saya</a></li>
              <li><a class="dropdown-item" href="#"><i class="ti ti-settings"></i> Pengaturan</a></li>
              <li><a class="dropdown-item" href="#"><i class="ti ti-mail"></i> Pesan Masuk</a></li>
              <li><hr class="dropdown-divider"></li>
              <li>
                <form action="{{ route('logout') }}" method="POST">@csrf
                  <button class="dropdown-item text-danger" type="submit"><i class="ti ti-logout"></i> Keluar</button>
                </form>
              </li>
            </ul>
          </li>
        </ul>
      </div>
    </div>
  </nav>
</header>
