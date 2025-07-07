<!DOCTYPE html>
<html lang="zxx">

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Sona Template">
    <meta name="keywords" content="Sona, unica, creative, html">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>{{ $title }}</title>

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
                <li class="{{ Request::routeIs('tagihanpelanggan.index') ? 'active' : '' }}">
                    <a href="{{ route('tagihanpelanggan.index') }}">Kamar Kost</a>
                </li>
                <li class="{{ Request::routeIs('about.index') ? 'active' : '' }}">
                    <a href="{{ route('about.index') }}">About Us</a>
                </li>
                <li class="{{ Request::routeIs('contact.index') ? 'active' : '' }}">
                    <a href="{{ route('contact.index') }}">Contact</a>
                </li>
                @guest
                    <li><a href="{{ route('login') }}">Login</a></li>
                    <li><a href="{{ route('registrasipelanggan') }}">Register</a></li>
                @endguest
           @auth
    @if (Auth::user()->role === 'pelanggan')
        <li class="{{ Request::routeIs('profile.index') ? 'active' : '' }}">
            <a href="{{ route('profile.index') }}">Profil Saya</a>
        </li>
    @endif
    <li>
        <form action="{{ route('logout') }}" method="POST">
            @csrf
            <button type="submit" style="background:none; border:none; color:white;">Logout</button>
        </form>
    </li>
@endauth

            </ul>
        </nav>
    </div>
    <div class="canvas-open">
        <i class="fa fa-bars"></i>
    </div>
    <!-- Offcanvas Menu Section End -->

    <div class="menu-item">
        <div class="container">
            <div class="row">
                <div class="col-lg-2">
                    <div class="logo">
                        <a href="./index.html">
                            <img src="{{ asset('front-end/img/logo.png') }}" alt="">
                        </a>
                    </div>
                </div>
                <div class="col-lg-10">
                    <div class="nav-menu">
                        <nav class="mainmenu" id="mobile-menu">
                            <ul>
                                <li class="{{ Request::routeIs('home.index') ? 'active' : '' }}">
                                    <a href="{{ route('home.index') }}">Home</a>
                                </li>
                                <li class="{{ Request::routeIs('kamar.index') ? 'active' : '' }}">
                                    <a href="{{ route('kamar.index') }}">Kamar Kost</a>
                                </li>
                                <li class="{{ Request::routeIs('tagihanpelanggan.index') ? 'active' : '' }}">
                                    <a href="{{ route('tagihanpelanggan.index') }}">Tagihan</a>
                                </li>
                                <li class="{{ Request::routeIs('about.index') ? 'active' : '' }}">
                                    <a href="{{ route('about.index') }}">About Us</a>
                                </li>
                                <li class="{{ Request::routeIs('contact.index') ? 'active' : '' }}">
                                    <a href="{{ route('contact.index') }}">Contact</a>
                                </li>

                                @guest
                                    <li>
                                        <a href="{{ route('login') }}" style="background-color:#28a745; color:white; padding:6px 12px; border-radius:5px;">Login</a>
                                    </li>
                                    <li>
                                        <a href="{{ route('registrasipelanggan') }}" style="background-color:#28a745; color:white; padding:6px 12px; border-radius:5px;">Register</a>
                                    </li>
                                @endguest

                                @auth
                                @if (Auth::user()->role === 'pelanggan')
                            <li class="{{ Request::routeIs('profile.index') ? 'active' : '' }}">
                                <a href="{{ route('profile.index') }}">Profil Saya</a>
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
                            </ul>
                        </nav>
                    </div>
                </div>
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
                            <p>We inspire and reach millions of travelers<br /> across 90 local websites</p>
                            <div class="fa-social">
                                <a href="#"><i class="fa fa-facebook"></i></a>
                                <a href="#"><i class="fa fa-twitter"></i></a>
                                <a href="#"><i class="fa fa-tripadvisor"></i></a>
                                <a href="#"><i class="fa fa-instagram"></i></a>
                                <a href="#"><i class="fa fa-youtube-play"></i></a>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-3 offset-lg-1">
                        <div class="ft-contact">
                            <h6>Contact Us</h6>
                            <ul>
                                <li>(12) 345 67890</li>
                                <li>info.colorlib@gmail.com</li>
                                <li>856 Cordia Extension Apt. 356, Lake, United State</li>
                            </ul>
                        </div>
                    </div>
                    <div class="col-lg-3 offset-lg-1">
                        <div class="ft-newslatter">
                            <h6>New latest</h6>
                            <p>Get the latest updates and offers.</p>
                            <form action="#" class="fn-form">
                                <input type="text" placeholder="Email">
                                <button type="submit"><i class="fa fa-send"></i></button>
                            </form>
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
</body>

</html>
