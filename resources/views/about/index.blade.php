@extends('fe.master')

@section('content')

{{-- Hero Atas Halaman About --}}
<section class="hero-section text-white d-flex align-items-center" style="background: linear-gradient(rgba(0,0,0,.6), rgba(0,0,0,.6)), url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1950&q=80') center/cover no-repeat; height: 55vh;">
    <div class="container text-center">
        <h1 class="display-5 fw-bold">Tentang Kost Kami</h1>
        <p class="lead mt-2">Tempat tinggal nyaman, aman, dan strategis untuk kamu yang butuh kost ideal.</p>
    </div>
</section>

<!-- Tentang Kost Section Begin -->
<section class="aboutus-page-section spad bg-light">
    <div class="container">
        <div class="about-page-text">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <div class="ap-title">
                        <h2 class="fw-bold">Selamat Datang di Kost Kita</h2>
                        <p class="text-muted">
                            Kost nyaman, aman, dan strategis untuk mahasiswa, pekerja, maupun keluarga. Kami hadir memberikan solusi tempat tinggal terbaik dengan berbagai pilihan kamar dan fasilitas yang lengkap.
                        </p>
                    </div>
                </div>
                <div class="col-lg-5 offset-lg-1">
                    <ul class="ap-services list-unstyled">
                        <li><i class="icon_check text-success me-2"></i> Sarapan tersedia untuk penyewa bulanan.</li>
                        <li><i class="icon_check text-success me-2"></i> Layanan laundry 3x dalam seminggu.</li>
                        <li><i class="icon_check text-success me-2"></i> Internet Wifi gratis dan stabil.</li>
                        <li><i class="icon_check text-success me-2"></i> Bonus menarik untuk penyewa tahunan.</li>
                        <li><i class="icon_check text-success me-2"></i> Akses 24 jam dengan keamanan terjamin.</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="about-page-services mt-5">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="ap-service-item set-bg rounded shadow-sm overflow-hidden" data-setbg="{{ asset('front-end/img/about1.webp') }}">
                        <div class="api-text bg-dark bg-opacity-50 text-white text-center p-2">
                            <h4 class="mb-0">Dapur Bersama</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ap-service-item set-bg rounded shadow-sm overflow-hidden" data-setbg="{{ asset('front-end/img/about2.jpg') }}">
                        <div class="api-text bg-dark bg-opacity-50 text-white text-center p-2">
                            <h4 class="mb-0">Laundry & Parkiran</h4>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="ap-service-item set-bg rounded shadow-sm overflow-hidden" data-setbg="{{ asset('front-end/img/about3.jpg') }}">
                        <div class="api-text bg-dark bg-opacity-50 text-white text-center p-2">
                            <h4 class="mb-0">Tempat Bersantai</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Tentang Kost Section End -->

<!-- Gallery Section Begin -->
<section class="gallery-section spad">
    <div class="container">
        <div class="row mb-4">
            <div class="col-lg-12 text-center">
                <div class="section-title">
                    <span class="text-uppercase text-secondary">Galeri Kami</span>
                    <h2 class="fw-bold">Suasana Kost & Fasilitas</h2>
                </div>
            </div>
        </div>
        <div class="row g-3">
            <div class="col-lg-6">
                <div class="gallery-item set-bg rounded overflow-hidden" data-setbg="{{ asset('front-end/img/about6.jpg') }}">
                    <div class="gi-text bg-dark bg-opacity-50 text-white p-2">
                        <h5>Kamar Nyaman</h5>
                    </div>
                </div>
                <div class="row pt-3">
                    <div class="col-sm-6">
                        <div class="gallery-item set-bg rounded overflow-hidden" data-setbg="{{ asset('front-end/img/about5.jpg') }}">
                            <div class="gi-text bg-dark bg-opacity-50 text-white p-2">
                                <h5>Kamar AC</h5>
                            </div>
                        </div>
                    </div>
                    <div class="col-sm-6">
                        <div class="gallery-item set-bg rounded overflow-hidden" data-setbg="{{ asset('front-end/img/about4.avif') }}">
                            <div class="gi-text bg-dark bg-opacity-50 text-white p-2">
                                <h5>Kamar Mandi Dalam</h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="gallery-item large-item set-bg rounded overflow-hidden" data-setbg="{{ asset('front-end/img/about4.jpg') }}">
                    <div class="gi-text bg-dark bg-opacity-50 text-white p-2">
                        <h5>Area Bersama</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- Gallery Section End -->

@endsection
