<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Sona Template">
    <meta name="keywords" content="Sona, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title ?? 'Hotel Booking' }}</title>


    <!-- Google Font -->
    <link href="https://fonts.googleapis.com/css?family=Lora:400,700&display=swap" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css?family=Cabin:400,500,600,700&display=swap" rel="stylesheet">

    <!-- Css Styles -->
    <link rel="stylesheet" href="{{ asset('front-end/css/bootstrap.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('front-end/css/font-awesome.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('front-end/css/elegant-icons.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('front-end/css/flaticon.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('front-end/css/owl.carousel.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('front-end/css/nice-select.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('front-end/css/jquery-ui.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('front-end/css/magnific-popup.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('front-end/css/slicknav.min.css') }}" type="text/css">
    <link rel="stylesheet" href="{{ asset('front-end/css/style.css') }}" type="text/css">
    
</head>

<body>

@if (auth()->check() && auth()->user()->role === 'pelanggan')
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

    <!-- Page Preloder -->
    <div id="preloder">
        <div class="loader"></div>
    </div>

   <!-- Offcanvas Menu Section Begin -->
<div class="offcanvas-menu-overlay"></div>
<div class="offcanvas-menu-wrapper">
    <div class="canvas-close">
        <span class="icon_close"></span>
    </div>
    <nav class="mainmenu mobile-menu">
        <ul>
            <li class="{{ Request::routeIs('home.index') ? 'active' : '' }}">
                <a href="{{ route('home.index') }}">Home</a>
            </li>
            <li class="{{ Request::routeIs('kamar.index') ? 'active' : '' }}">
                <a href="{{ route('kamar.index') }}">Kamar Kost</a>
            </li>
            @auth
                <li class="{{ Request::routeIs('tagihanpelanggan.index') ? 'active' : '' }}">
                    <a href="{{ route('tagihanpelanggan.index') }}">Tagihan</a>
                </li>
            @endauth
            <li class="{{ Request::routeIs('about.index') ? 'active' : '' }}">
                <a href="{{ route('about.index') }}">About Us</a>
            </li>
            @auth
                @if (Auth::user()->role === 'pelanggan')
                    <li class="{{ Request::routeIs('profile.index') ? 'active' : '' }}">
                        <a href="{{ route('profile.index') }}">Profil Saya</a>
                    </li>
                    <li class="dropdown {{ Request::routeIs('contact.index') || Request::routeIs('pesan_balasan.index') ? 'active' : '' }}">
                        <a href="javascript:void(0)">Contact ▾</a>
                        <ul class="dropdown-content">
                            <li class="{{ Request::routeIs('contact.index') ? 'active' : '' }}">
                                <a href="{{ route('contact.index') }}">Contact</a>
                            </li>
                            <li class="{{ Request::routeIs('pesan_balasan.index') ? 'active' : '' }}">
                                <a href="{{ route('pesan_balasan.index') }}">Balasan</a>
                            </li>
                        </ul>
                    </li>
                @endif
                <li>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" style="background:none; border:none; color:white;">Logout</button>
                    </form>
                </li>
            @endauth
            @guest
                <li><a href="{{ route('login') }}">Login</a></li>
                <li><a href="{{ route('registrasipelanggan') }}">Register</a></li>
            @endguest
        </ul>
    </nav>
</div>
<div class="canvas-open">
    <i class="fa fa-bars"></i>
</div>
<!-- Offcanvas Menu Section End -->

<!-- Desktop Menu Section Begin -->
<div class="menu-item">
    <div class="container">
        <div class="row align-items-center">
            <div class="col-lg-2">
                <div class="logo">
                    <a href="{{ route('home.index') }}">
                        <img src="{{ asset('front-end/img/logo.png') }}" alt="">
                    </a>
                </div>
            </div>
            <div class="col-lg-10">
                <div class="nav-menu">
     

                    <nav class="mainmenu d-none d-lg-block" id="mobile-menu">
                        <ul>
                            
                            <li class="{{ Request::routeIs('home.index') ? 'active' : '' }}">
                                <a href="{{ route('home.index') }}">Home</a>
                            </li>
                            <li class="{{ Request::routeIs('kamar.index') ? 'active' : '' }}">
                                <a href="{{ route('kamar.index') }}">Kamar Kost</a>
                            </li>
                             @auth
                                <li class="{{ Request::routeIs('tagihanpelanggan.index') ? 'active' : '' }}">
                                    <a href="{{ route('tagihanpelanggan.index') }}">Tagihan</a>
                                </li>
                             @endauth
                            <li class="{{ Request::routeIs('about.index') ? 'active' : '' }}">
                                <a href="{{ route('about.index') }}">About Us</a>
                            </li>

                            @auth
                                @if (Auth::user()->role === 'pelanggan')
                                    <li class="{{ Request::routeIs('profile.index') ? 'active' : '' }}">
                                        <a href="{{ route('profile.index') }}">Profil Saya</a>
                                    </li>
<li class="nav-item">
  <a href="#" class="dropdown-toggle {{ request()->is('contact*') ? 'active' : '' }}">
    Contact
  </a>
  <ul class="dropdown-menu">
    <li><a class="{{ request()->routeIs('contact.index') ? 'active' : '' }}" href="{{ route('contact.index') }}">Contact</a></li>
    <li><a class="{{ request()->routeIs('pesan_balasan.index') ? 'active' : '' }}" href="{{ route('pesan_balasan.index') }}">Balasan</a></li>
  </ul>
</li>

                                @endif
                                <li>
                                    <form action="{{ route('logout') }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" style="background-color:#28a745; color:white; border:none; padding:6px 12px; border-radius:5px; cursor:pointer;">
                                            Logout
                                        </button>
                                    </form>
                                </li>
                            @endauth

                            @guest
                                <li>
                                    <a href="{{ route('login') }}" style="background-color:#28a745; color:white; padding:6px 12px; border-radius:5px;">Login</a>
                                </li>
                                <li>
                                    <a href="{{ route('registrasipelanggan') }}" style="background-color:#28a745; color:white; padding:6px 12px; border-radius:5px;">Register</a>
                                </li>
                            @endguest
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Desktop Menu Section End -->

<!-- Styling untuk dropdown -->
<style>
  /* Untuk navigasi utama */
  .nav-item {
      position: relative;
  }

  .dropdown-toggle {
      cursor: pointer;
  }

  .dropdown-menu {
      display: none;
      position: absolute;
      background-color: white;
      box-shadow: 0 0 10px rgba(0,0,0,0.1);
      list-style: none;
      margin: 0;
      padding: 0;
      z-index: 999;
  }

  .dropdown-menu li a {
      display: block;
      padding: 10px 20px;
      color: #333;
      text-decoration: none;
      white-space: nowrap;
  }

  .dropdown-menu li a:hover {
      background-color: #f0f0f0;
  }

  .nav-item.open .dropdown-menu {
      display: block;
  }

  /* Responsive fix */
  @media (max-width: 768px) {
      .dropdown-menu {
          position: static;
          box-shadow: none;
      }
  }
</style>



            </div>
        </div>
    </div>

    @yield('content')
    

    <!-- Footer Section Begin -->
    <footer class="footer-section">
        <div class="container">
            <div class="footer-text">
                <div class="row">
                    <div class="col-lg-4">
                        <div class="ft-about">
                            <div class="logo">
                                <a href="#">
                                    <img src="{{ asset('front-end/img/footer-logo.png') }}" alt="">
                                </a>
                            </div>
                            <p>Kami membantu para pencari kost menemukan tempat tinggal yang nyaman, aman, dan strategis.<br />Temukan kost idealmu bersama kami.</p>
                            {{-- <div class="fa-social">
                                <a href="#"><i class="fa fa-facebook"></i></a> --}}
                                {{-- <a href="#"><i class="fa fa-twitter"></i></a> --}}
                                {{-- <a href="#"><i class="fa fa-tripadvisor"></i></a> --}}
                                {{-- <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-youtube-play"></i></a>
                            </div> --}}
                        </div>
                    </div>
            <div class="col-lg-3 offset-lg-1">
    <div class="ft-contact">
        <h6>Contact Us</h6>
        <ul style="list-style: none; padding-left: 0;">
            <li class="mb-2">
                <a href="https://wa.me/6285810537250" target="_blank" style="color: inherit; text-decoration: none;">
                    (No.Telp) 0858-1053-7250
                </a>
            </li>
            <li class="mb-2">
                <a href="https://mail.google.com/mail/?view=cm&to=hafizhrmuhammad@gmail.com" target="_blank" style="color: inherit; text-decoration: none;">
                    (Email) hafizhrmuhammad@gmail.com
                </a>
            </li>
            <li class="mb-2">
                <a href="https://www.google.com/maps?q=Gedung+BITC+Cimahi+Jawa+Barat+Indonesia" target="_blank" style="color: inherit; text-decoration: none;">
                    (Lokasi) Gedung BITC, Cimahi, Jawa Barat
                </a>
            </li>
        </ul>
    </div>
</div>



                    <div class="col-lg-3 offset-lg-1">
                        <div class="ft-newslatter">
                             <h6>Butuh Bantuan?</h6>
                            <p>Jika kamu memiliki pertanyaan seputar kost atau kesulitan dalam pencarian, kami siap membantu.</p>
                            {{-- <form action="#" class="fn-form">
                                <input type="text" placeholder="Email">
                                <button type="submit"><i class="fa fa-send"></i></button>
                            </form> --}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="copyright-option">
            <div class="container">
                <div class="row">
                    <div class="col-lg-7">
                        <ul>
                            <li><a href="#">Contact</a></li>
                            <li><a href="#">Terms of use</a></li>
                            <li><a href="#">Privacy</a></li>
                            <li><a href="#">Environmental Policy</a></li>
                        </ul>
                    </div>
                    <div class="col-lg-5">
                        <div class="co-text">
                            <p>
                                Copyright &copy;<script>document.write(new Date().getFullYear());</script>
                                All rights reserved | This template is made with
                                <i class="fa fa-heart" aria-hidden="true"></i>
                                by <a href="https://colorlib.com" target="_blank">Colorlib</a>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>
    <!-- Footer Section End -->

    <!-- Search model Begin -->
    <div class="search-model">
        <div class="h-100 d-flex align-items-center justify-content-center">
            <div class="search-close-switch"><i class="icon_close"></i></div>
            <form class="search-model-form">
                <input type="text" id="search-input" placeholder="Search here.....">
            </form>
        </div>
    </div>
    <!-- Search model end -->

    @yield('scripts')

    <!-- Js Plugins -->
    <script src="{{ asset('front-end/js/jquery-3.3.1.min.js') }}"></script>
    <script src="{{ asset('front-end/js/bootstrap.min.js') }}"></script>
    <script src="{{ asset('front-end/js/jquery.magnific-popup.min.js') }}"></script>
    <script src="{{ asset('front-end/js/jquery.nice-select.min.js') }}"></script>
    <script src="{{ asset('front-end/js/jquery-ui.min.js') }}"></script>
    <script src="{{ asset('front-end/js/jquery.slicknav.js') }}"></script>
    <script src="{{ asset('front-end/js/owl.carousel.min.js') }}"></script>
    <script src="{{ asset('front-end/js/main.js') }}"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

    <!-- Init Hamburger Menu -->
    <script>
        $(document).ready(function () {
            $("#mobile-menu").slicknav({
                prependTo: '.offcanvas-menu-wrapper',
                allowParentLinks: true,
                label: ''
            });

            $(".canvas-open").on('click', function () {
                $(".offcanvas-menu-wrapper").addClass("active");
                $(".offcanvas-menu-overlay").addClass("active");
            });

            $(".canvas-close, .offcanvas-menu-overlay").on('click', function () {
                $(".offcanvas-menu-wrapper").removeClass("active");
                $(".offcanvas-menu-overlay").removeClass("active");
            });
        });
    </script>

    <script>
  document.addEventListener("DOMContentLoaded", function() {
    const toggles = document.querySelectorAll(".dropdown-toggle");

    toggles.forEach(toggle => {
      toggle.addEventListener("click", function(e) {
        e.preventDefault();
        const parent = this.parentElement;
        parent.classList.toggle("open");
      });
    });

    // Tutup dropdown lain saat buka satu
    document.addEventListener("click", function(e) {
      if (!e.target.closest(".nav-item")) {
        document.querySelectorAll(".nav-item.open").forEach(item => {
          item.classList.remove("open");
        });
      }
    });
  });
</script>

</body>

</html>
