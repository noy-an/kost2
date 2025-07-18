@php
  use App\Models\Notifbook;
  $notifs = Notifbook::where('is_read', false)->latest()->get();
@endphp

<header class="app-header shadow-sm bg-white border-bottom">
  <nav class="navbar navbar-expand-lg navbar-light px-3 py-2">
    <div class="d-flex align-items-center w-100 justify-content-between">
      <a class="nav-link d-block d-xl-none text-muted fs-4" id="headerCollapse" href="javascript:void(0)">
        <i class="ti ti-menu-2"></i>
      </a>

      <div class="navbar-collapse justify-content-end d-flex" id="navbarNav">
        <ul class="navbar-nav align-items-center gap-3">
               <!-- Simulasi Tanggal -->
@if (auth()->check() && auth()->user()->role === 'admin')
    <div style="background: #f8d7da; padding: 10px; border-bottom: 2px solid #f5c6cb;">
        <form method="POST" action="/simulasi/tanggal" style="display:inline-block; margin-right: 10px;">
            @csrf
            <input type="date" name="tanggal" required value="{{ session('simulasi_tanggal') }}">
            <button type="submit" style="padding: 5px 10px;">Set</button>
        </form>

        <form method="POST" action="/simulasi/reset" style="display:inline-block;">
            @csrf
            <button type="submit" style="padding: 5px 10px;">Reset</button>
        </form>

        @if (session('simulasi_tanggal'))
            <span style="margin-left: 15px;">🕒 Simulasi aktif: <strong>{{ session('simulasi_tanggal') }}</strong></span>
        @endif
    </div>
@endif

          <!-- Notifikasi -->
          <li class="nav-item dropdown position-relative">
            <a class="nav-link text-muted" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
              <i class="ti ti-bell fs-5"></i>
              @if($notifs->count() > 0)
                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                  {{ $notifs->count() }}
                </span>
              @endif
            </a>

            <ul class="dropdown-menu dropdown-menu-end shadow-sm animate__animated animate__fadeIn" style="min-width: 300px;">
              @forelse($notifs as $notif)
              <li>
                <form action="{{ route('notifbook.read', $notif->id) }}" method="POST">
                  @csrf
                  @method('PATCH')
                  <button type="submit" class="dropdown-item text-wrap text-start">
                    🔔 {{ $notif->message }}
                  </button>
                </form>
              </li>
              @empty
              <li>
                <span class="dropdown-item text-muted">Tidak ada notifikasi baru.</span>
              </li>
              @endforelse
            </ul>
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
