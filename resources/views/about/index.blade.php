@extends('fe.master')
@section('content')

<!-- About Us Page Section Begin -->
<section class="aboutus-page-section spad py-5 bg-light">
    <div class="container">
        <div class="about-page-text mb-5">
            <div class="row align-items-center">
                <div class="col-lg-6 mb-4 mb-lg-0">
                    <div class="ap-title">
                        <h2 class="fw-bold text-primary">Selamat Datang di KostKu</h2>
                        <p class="text-muted">KostKu adalah platform kos modern yang dirancang untuk kenyamanan, keamanan, dan kemudahan pencarian tempat tinggal yang ideal. Kami menghadirkan berbagai fasilitas lengkap yang mendukung gaya hidup Anda yang aktif dan produktif.</p>
                    </div>
                </div>
                <div class="col-lg-6">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Akses 24 Jam & CCTV</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Kamar Fully Furnished</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Internet Wifi Kecepatan Tinggi</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Laundry & Kebersihan Rutin</li>
                        <li class="list-group-item"><i class="bi bi-check-circle-fill text-success me-2"></i> Dekat Kampus & Pusat Kota</li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="about-page-services">
            <div class="row g-4">
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('front-end/img/about/about-p1.jpg') }}" class="card-img-top" alt="Kebersihan Harian">
                        <div class="card-body">
                            <h5 class="card-title">Layanan Kebersihan</h5>
                            <p class="card-text">Kami menyediakan layanan kebersihan kamar secara rutin agar Anda tetap nyaman setiap hari.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('front-end/img/about/about-p2.jpg') }}" class="card-img-top" alt="Akses Mudah">
                        <div class="card-body">
                            <h5 class="card-title">Akses Lokasi Strategis</h5>
                            <p class="card-text">Dekat dengan kampus, halte, minimarket, dan pusat kuliner. Mudah dijangkau dari mana saja.</p>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="card shadow-sm border-0">
                        <img src="{{ asset('front-end/img/about/about-p3.jpg') }}" class="card-img-top" alt="Kenyamanan">
                        <div class="card-body">
                            <h5 class="card-title">Kenyamanan Maksimal</h5>
                            <p class="card-text">Kamar luas, AC, meja belajar, lemari pakaian, dan suasana tenang untuk belajar dan istirahat.</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div> 
</section>
<!-- About Us Page Section End -->

<!-- Video Section Begin -->
<section class="video-section position-relative text-white py-5" style="background: url('{{ asset('front-end/img/video-bg.jpg') }}') center center/cover no-repeat;">
    <div class="container text-center">
        <h2 class="mb-3">Lihat Langsung KostKami</h2>
        <p class="mb-4">Tonton video tur kos kami dan temukan kenyamanan tempat tinggal modern untuk Anda.</p>
        <a href="https://www.youtube.com/watch?v=EzKkl64rRbM" class="btn btn-light btn-lg rounded-pill video-popup">
            <i class="bi bi-play-circle me-2"></i> Tonton Video
        </a>
    </div>
</section>
<!-- Video Section End -->

<!-- Gallery Section Begin -->
<section class="gallery-section spad py-5">
    <div class="container">
        <div class="section-title text-center mb-5">
            <span class="text-uppercase text-muted">Galeri</span>
            <h2 class="fw-bold">Lihat Fasilitas Kami</h2>
        </div>
        <div class="row g-4">
            <div class="col-md-6">
                <img src="{{ asset('front-end/img/gallery/gallery-1.jpg') }}" class="img-fluid rounded shadow-sm" alt="Kamar Utama">
                <div class="mt-2 text-center fw-bold">Kamar Utama</div>
            </div>
            <div class="col-md-6">
                <img src="{{ asset('front-end/img/gallery/gallery-2.jpg') }}" class="img-fluid rounded shadow-sm" alt="Tampak Luar">
                <div class="mt-2 text-center fw-bold">Tampak Luar</div>
            </div>
            <div class="col-sm-6 col-md-3">
                <img src="{{ asset('front-end/img/gallery/gallery-3.jpg') }}" class="img-fluid rounded shadow-sm" alt="Kamar Mandi">
                <div class="mt-2 text-center small">Kamar Mandi</div>
            </div>
            <div class="col-sm-6 col-md-3">
                <img src="{{ asset('front-end/img/gallery/gallery-4.jpg') }}" class="img-fluid rounded shadow-sm" alt="Dapur Bersama">
                <div class="mt-2 text-center small">Dapur Bersama</div>
            </div>
        </div>
    </div>
</section>
<!-- Gallery Section End -->

@endsection
