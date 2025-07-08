@extends('fe.master')
@section('content')

<!-- Hero Section Begin -->
<section class="hero-section">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="hero-text">
                    <h1>Cari Kost Nyamanmu</h1>
                    <p>Temukan kost terbaik dengan fasilitas lengkap dan lokasi strategis untuk kebutuhan tinggal harian, bulanan, maupun tahunan.</p>
                    <a href="{{ route('kamar.index') }}" class="primary-btn">Cari Kost Sekarang</a>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-slider owl-carousel">
        <div class="hs-item set-bg" data-setbg="{{ asset('front-end/img/home1.jpg') }}"></div>
        <div class="hs-item set-bg" data-setbg="{{ asset('front-end/img/home2.jpg') }}"></div>
        <div class="hs-item set-bg" data-setbg="{{ asset('front-end/img/home3.png') }}"></div>
    </div>
</section>
<!-- Hero Section End -->

<!-- About Us Section Begin -->
<section class="aboutus-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-6">
                <div class="about-text">
                    <div class="section-title">
                        <span>Tentang Kami</span>
                        <h2>KostKuy<br />Platform Kost Modern</h2>
                    </div>
                    <p class="f-para">KostKuy adalah platform pencarian kost terpercaya. Kami memudahkan kamu menemukan tempat tinggal yang nyaman dan sesuai dengan kebutuhanmu.</p>
                    <p class="s-para">Dari kost putri, kost campur, hingga kost eksklusif dengan fasilitas lengkap, semua tersedia di sini.</p>
                    {{-- <a href="#" class="primary-btn about-btn">Selengkapnya</a> --}}
                </div>
            </div>
            <div class="col-lg-6">
                <div class="about-pic">
                    <div class="row">
                        <div class="col-sm-6">
                            <img src="{{ asset('front-end/img/home4.jpg') }}" alt="">
                        </div>
                        <div class="col-sm-6">
                            <img src="{{ asset('front-end/img/home5.jpg') }}" alt="">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- About Us Section End -->

<!-- Services Section Begin -->
<section class="services-section spad">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-title">
                    <span>Layanan Kami</span>
                    <h2>Fasilitas dan Kemudahan</h2>
                </div>
            </div>
        </div>
        <div class="row">
            <!-- Fitur layanan ditampilkan di sini -->
        </div>
    </div>
</section>
<!-- Services Section End -->

<!-- Home Room Section End -->
<div class="container">
        {{-- <div class="row"><div class="col-lg-12"><div class="section-title"><span>Tips & Info</span><h2>Artikel Seputar Kost</h2></div></div></div> --}}
       <div class="row">
    <div class="col-lg-4">
        <div class="blog-item set-bg" data-setbg="{{ asset('front-end/img/h1.jpg') }}">
            <div class="bi-text" style="background: rgba(0, 0, 0, 0.5); padding: 20px; color: #ffffff;">
                <span class="b-tag" style="color: white;">Fasilitas Kost</span>
                <h4><a href="#" style="color: #ffffff;">Kost Nyaman dan Bersih</a></h4>
                <p style="color:white;" >Kost eksklusif dengan kamar luas, tempat tidur nyaman, dan pencahayaan alami.</p>
                {{-- <div class="b-time"><i class="icon_clock_alt"></i> 10 Juli, 2025</div> --}}
                <a href="{{ route('kamar.index') }}" class="btn btn-sm" style="background-color: #004085; color: #ffffff; margin-top: 10px;">Cek Kost</a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="blog-item set-bg" data-setbg="{{ asset('front-end/img/h2.webp') }}">
            <div class="bi-text" style="background: rgba(0, 0, 0, 0.5); padding: 20px; color: #ffffff;">
                <span class="b-tag" style="color: white;">Fasilitas Umum</span>
                <h4><a href="#" style="color: #ffffff;">Dapur, Laundry & Area Bersama</a></h4>
                <p style="color:white;">Tersedia dapur bersama, mesin cuci, area makan dan ruang santai yang nyaman.</p>
                {{-- <div class="b-time"><i class="icon_clock_alt"></i> 8 Juli, 2025</div> --}}
                <a href="{{ route('kamar.index') }}" class="btn btn-sm" style="background-color: #004085; color: #ffffff; margin-top: 10px;">Cek Kost</a>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="blog-item set-bg" data-setbg="{{ asset('front-end/img/h3.webp') }}">
            <div class="bi-text" style="background: rgba(0, 0, 0, 0.5); padding: 20px; color: #ffffff;">
                <span class="b-tag" style="color: white;">Akses Lokasi</span>
                <h4><a href="#" style="color: #ffffff;">Dekat Kampus dan Transportasi Umum</a></h4>
                <p style="color:white;">Lokasi strategis, dekat kampus, halte bus, minimarket, dan tempat makan.</p>
                {{-- <div class="b-time"><i class="icon_clock_alt"></i> 5 Juli, 2025</div> --}}
                <a href="{{ route('kamar.index') }}" class="btn btn-sm" style="background-color: #004085; color: #ffffff; margin-top: 10px;">Cek Kost</a>
            </div>
        </div>
    </div>
</div>

    </div>

<!-- Testimonial Section Begin -->
<section class="testimonial-section spad">
    <div class="container">
        <div class="row"><div class="col-lg-12"><div class="section-title"><span>Testimoni</span><h2>Kata Pengguna Kost</h2></div></div></div>
        <div class="row">
            <div class="col-lg-8 offset-lg-2">
                <div class="testimonial-slider owl-carousel">
                    @forelse($testimonials as $t)
                        <div class="ts-item">
                            <p>{{ $t->pesan }}</p>
                            <div class="ti-author">
                                <div class="rating">
                                    <i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star"></i><i class="icon_star-half_alt"></i>
                                </div>
                                <h5> - {{ $t->nama }}</h5>
                            </div>
                            <img src="{{ asset('front-end/img/testimonial-logo.png') }}" alt="">
                        </div>
                    @empty
                        <p class="text-center">Belum ada testimoni dari pengguna.</p>
                    @endforelse
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Testimonial Section End -->

<!-- Blog Section Begin -->

<!-- Blog Section End -->
@endsection
