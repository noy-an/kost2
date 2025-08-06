@extends('fe.master')

@section('content')
<section class="py-5" style="background: #f5f5f5; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">

                {{-- Hero --}}
                <div class="text-center mb-5">
                    <h1 class="display-4 fw-bold text-dark mb-2">Detail Kamar</h1>
                    <p class="text-muted fs-5 mb-0">Temukan kenyamanan dalam setiap detail</p>
                </div>

                {{-- Main Card --}}
                <div class="card shadow-lg border-0 rounded-4 backdrop-blur overflow-hidden">
                    <div class="row g-0">

                        {{-- Gambar --}}
                        <div class="col-lg-7">
                            <div class="p-4 h-100 d-flex flex-column">
                                @php
                                    $fotoArray = [
                                        $kosts->foto1,
                                        $kosts->foto2,
                                        $kosts->foto3
                                    ];
                                @endphp

                                <div class="main-image mb-4 position-relative">
                                    <img id="mainPreview" 
                                        src="{{ $kosts->foto1 ? asset('storage/' . $kosts->foto1) : 'https://via.placeholder.com/800x600?text=Foto+Tidak+Tersedia' }}"
                                        class="img-fluid rounded-4 shadow-sm w-100 border object-fit-cover"
                                        style="height: 460px; background-color: #ddd;" 
                                        alt="Preview Kamar">

                                    {{-- Status Badge --}}
                                    <div class="position-absolute top-0 end-0 m-3">
                                        @if(!$sudahBooking)
                                            <span class="badge bg-success fs-6 px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-check-circle me-1"></i>Tersedia
                                            </span>
                                        @else
                                            <span class="badge bg-danger fs-6 px-3 py-2 rounded-pill shadow-sm">
                                                <i class="fas fa-times-circle me-1"></i>Tidak Tersedia
                                            </span>
                                        @endif
                                    </div>
                                </div>

                                {{-- Thumbnails --}}
                                <div class="d-flex gap-3 justify-content-center flex-wrap">
                                    @foreach ($fotoArray as $index => $foto)
                                        <div class="thumbnail-wrapper rounded-3 border @if($index === 0) border-primary @endif" 
                                            style="cursor: pointer; overflow: hidden; width: 100px; height: 70px;"
                                            onclick="document.getElementById('mainPreview').src = '{{ $foto ? asset('storage/' . $foto) : 'https://via.placeholder.com/800x600?text=Foto+Tidak+Tersedia' }}'; highlightThumbnail(this)">
                                            <img src="{{ $foto ? asset('storage/' . $foto) : 'https://via.placeholder.com/800x600?text=No+Image' }}" 
                                                class="img-fluid w-100 h-100 object-fit-cover" alt="Thumb {{ $index + 1 }}">
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- Detail --}}
                        <div class="col-lg-5">
                            <div class="p-5 d-flex flex-column h-100">
                                <h4 class="fw-bold text-primary mb-2">Kamar No {{ $kosts->nomor_kamar }}</h4>
                                <p class="text-muted mb-4"><i class="fas fa-building me-2"></i>Lantai {{ $kosts->nomor_lantai }}</p>

                                <ul class="list-unstyled mb-4">
                                    <li class="mb-3">
                                        <span class="d-block fw-semibold text-muted">Harga</span>
                                        <span class="fs-4 fw-bold text-primary">Rp {{ number_format($kosts->harga, 0, ',', '.') }} <small class="fs-6 text-muted">/bulan</small></span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="d-block fw-semibold text-muted">Ukuran Kamar</span>
                                        <span class="text-dark fw-semibold">{{ $kosts->ukuran_ruangan ?? 'Tidak disebutkan' }}</span>
                                    </li>
                                    <li class="mb-3">
                                        <span class="d-block fw-semibold text-muted">Fasilitas Premium</span>
                                        <span class="text-dark fw-semibold lh-lg">{{ $kosts->fasilitas }}</span>
                                    </li>
                                </ul>

                                <div class="mt-auto">
                                 @if($kosts->status === 'kosong')
    @auth('web')
      @if($sudahBooking)
    @if($bookingUserSendiri)
        <button class="btn btn-secondary btn-lg w-100 rounded-pill mb-3" disabled>
            <i class="fas fa-ban me-2"></i>Anda Sudah Booking Kamar Ini
        </button>
    @else
        <button class="btn btn-secondary btn-lg w-100 rounded-pill mb-3" disabled>
            <i class="fas fa-ban me-2"></i>Sudah Ada yang Booking Kamar Ini
        </button>
    @endif
@else
    <a href="{{ route('bookingpelanggan.form', $kosts->id) }}" 
       class="btn btn-primary btn-lg w-100 rounded-pill mb-3 shadow-sm">
        <i class="fas fa-calendar-check me-2"></i>Booking Sekarang
    </a>
@endif

    @else
        <button onclick="showLoginAlert()" 
                class="btn btn-primary btn-lg w-100 rounded-pill mb-3 shadow-sm">
            <i class="fas fa-calendar-check me-2"></i>Booking Sekarang
        </button>
    @endauth
@else
    <button class="btn btn-secondary btn-lg w-100 rounded-pill mb-3" disabled>
        <i class="fas fa-times-circle me-2"></i>Kamar Tidak Tersedia
    </button>
@endif

                                    <a href="{{ route('kamar.index') }}" 
                                       class="btn btn-outline-dark btn-lg w-100 rounded-pill">
                                        <i class="fas fa-arrow-left me-2"></i>Kembali ke Daftar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Tambahan Info --}}
                <div class="row g-4 mt-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 text-center">
                            <div class="card-body p-4">
                                <div class="icon-circle bg-primary text-white mb-3"><i class="fas fa-shield-alt"></i></div>
                                <h6 class="fw-bold">Keamanan 24/7</h6>
                                <p class="text-muted small">Sistem keamanan terdepan untuk kenyamanan Anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 text-center">
                            <div class="card-body p-4">
                                <div class="icon-circle bg-success text-white mb-3"><i class="fas fa-wifi"></i></div>
                                <h6 class="fw-bold">WiFi Unlimited</h6>
                                <p class="text-muted small">Koneksi internet cepat dan stabil</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 text-center">
                            <div class="card-body p-4">
                                <div class="icon-circle bg-warning text-white mb-3"><i class="fas fa-concierge-bell"></i></div>
                                <h6 class="fw-bold">Layanan Prima</h6>
                                <p class="text-muted small">Pelayanan terbaik untuk kepuasan Anda</p>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>

<style>
    .backdrop-blur {
        backdrop-filter: blur(10px);
        background-color: rgba(255, 255, 255, 0.9);
    }
    .object-fit-cover {
        object-fit: cover;
    }
    .thumbnail-wrapper {
        transition: transform 0.2s ease;
    }
    .thumbnail-wrapper:hover {
        transform: scale(1.05);
        border-color: #0d6efd !important;
    }
    .thumbnail-wrapper.active-thumb {
        border: 2px solid #0d6efd !important;
    }
    .icon-circle {
        width: 60px;
        height: 60px;
        border-radius: 50%;
        display: inline-flex;
        justify-content: center;
        align-items: center;
        font-size: 1.5rem;
    }
    @media (max-width: 768px) {
        #mainPreview {
            height: 300px !important;
        }
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/js/all.min.js"></script>
<script>
    function showLoginAlert() {
        Swal.fire({
            title: 'Silakan Login atau Register',
            text: 'Untuk booking kamar, Anda harus login sebagai pelanggan terlebih dahulu.',
            icon: 'warning',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Login',
            denyButtonText: 'Register',
            cancelButtonText: 'Batal',
            customClass: {
                popup: 'rounded-4',
                confirmButton: 'btn btn-primary rounded-pill px-4',
                denyButton: 'btn btn-success rounded-pill px-4',
                cancelButton: 'btn btn-outline-secondary rounded-pill px-4'
            },
            buttonsStyling: false
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '{{ route("login") }}';
            } else if (result.isDenied) {
                window.location.href = '{{ route("registrasipelanggan") }}';
            }
        });
    }

    function highlightThumbnail(element) {
        document.querySelectorAll('.thumbnail-wrapper').forEach(el => el.classList.remove('active-thumb'));
        element.classList.add('active-thumb');
    }

    document.addEventListener('DOMContentLoaded', () => {
        const firstThumb = document.querySelector('.thumbnail-wrapper');
        if (firstThumb) {
            firstThumb.classList.add('active-thumb');
        }
    });
</script>
@endsection