@extends('fe.master')

@section('content')
<section class="py-5" style="background: gray; min-height: 100vh;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-11">
                
                {{-- Hero Section --}}
                <div class="text-center mb-5">
                    <h1 class="display-4 text-white fw-bold mb-3">Detail Kamar </h1>
                    <p class="text-white-50 fs-5">Temukan kenyamanan dalam setiap detail</p>
                </div>

                {{-- Main Content Card --}}
                <div class="card shadow-lg border-0 rounded-4 overflow-hidden backdrop-blur">
                    <div class="card-body p-0">
                        <div class="row g-0">
                            
                            {{-- Image Section --}}
                            <div class="col-lg-7">
                                <div class="position-relative h-100" style="min-height: 500px;">
                                    @if($kosts->foto1)
                                        <img src="{{ asset('storage/' . $kosts->foto1) }}" 
                                             class="img-fluid w-100 h-100 object-fit-cover" 
                                             alt="Foto Kamar"
                                             style="transition: transform 0.3s ease;">
                                    @else
                                        <img src="https://via.placeholder.com/800x600?text=Foto+Tidak+Tersedia" 
                                             class="img-fluid w-100 h-100 object-fit-cover" 
                                             alt="No Image">
                                    @endif
                                    
                                    {{-- Overlay Gradient --}}
                                    <div class="position-absolute top-0 start-0 w-100 h-100" 
                                         style="background: linear-gradient(to bottom, rgba(0,0,0,0.1), rgba(0,0,0,0.3));"></div>
                                    
                                    {{-- Status Badge --}}
                                    <div class="position-absolute top-0 end-0 m-3">
                                        @if($kosts->status === 'kosong')
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
                            </div>

                            {{-- Details Section --}}
                            <div class="col-lg-5">
                                <div class="p-5 h-100 d-flex flex-column">
                                    
                                    {{-- Room Number --}}
                                    <div class="mb-4">
                                        <h2 class="display-5 fw-bold text-primary mb-2">
                                            Kamar No {{ $kosts->nomor_kamar }}
                                        </h2>
                                        <p class="text-muted fs-5 mb-0">
                                            <i class="fas fa-building me-2"></i>Lantai {{ $kosts->nomor_lantai }}
                                        </p>
                                    </div>

                                    {{-- Price --}}
                                    <div class="mb-4 p-3 bg-light rounded-3 border-start border-primary border-4">
                                        <h3 class="text-primary fw-bold mb-1">
                                            Rp {{ number_format($kosts->harga, 0, ',', '.') }}
                                        </h3>
                                        <span class="text-muted">per bulan</span>
                                    </div>

                                    {{-- Room Details --}}
                                    <div class="mb-4">
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <div class="d-flex align-items-center p-3 bg-light rounded-3">
                                                    <i class="fas fa-ruler-combined text-primary me-3 fs-5"></i>
                                                    <div>
                                                        <strong>Ukuran Kamar</strong><br>
                                                        <span class="text-muted">{{ $kosts->ukuran_ruangan ?? 'Tidak disebutkan' }}</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    {{-- Facilities --}}
                                    <div class="mb-4 flex-grow-1">
                                        <h5 class="fw-bold text-dark mb-3">
                                            <i class="fas fa-star text-warning me-2"></i>Fasilitas Premium
                                        </h5>
                                        <div class="bg-light p-3 rounded-3">
                                            <p class="mb-0 text-muted lh-lg">{{ $kosts->fasilitas }}</p>
                                        </div>
                                    </div>

                                    {{-- Action Buttons --}}
                                    <div class="mt-auto">
                                        @if($kosts->status === 'kosong')
                                            @auth('web')
                                                <a href="{{ route('bookingpelanggan.form', $kosts->id) }}" 
                                                   class="btn btn-primary btn-lg w-100 rounded-pill mb-3 shadow-sm">
                                                    <i class="fas fa-calendar-check me-2"></i>Booking Sekarang
                                                </a>
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
                </div>

                {{-- Additional Info Cards --}}
                <div class="row g-4 mt-4">
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 text-center">
                            <div class="card-body p-4">
                                <div class="bg-primary bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-shield-alt text-primary fs-4"></i>
                                </div>
                                <h6 class="fw-bold">Keamanan 24/7</h6>
                                <p class="text-muted small mb-0">Sistem keamanan terdepan untuk kenyamanan Anda</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 text-center">
                            <div class="card-body p-4">
                                <div class="bg-success bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-wifi text-success fs-4"></i>
                                </div>
                                <h6 class="fw-bold">WiFi Unlimited</h6>
                                <p class="text-muted small mb-0">Koneksi internet cepat dan stabil</p>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card border-0 shadow-sm h-100 text-center">
                            <div class="card-body p-4">
                                <div class="bg-warning bg-opacity-10 rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                                     style="width: 60px; height: 60px;">
                                    <i class="fas fa-concierge-bell text-warning fs-4"></i>
                                </div>
                                <h6 class="fw-bold">Layanan Prima</h6>
                                <p class="text-muted small mb-0">Pelayanan terbaik untuk kepuasan Anda</p>
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
    
    .card {
        transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 20px 40px rgba(0,0,0,0.1) !important;
    }
    
    .btn {
        transition: all 0.3s ease;
    }
    
    .btn-primary:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(102, 126, 234, 0.4);
    }
    
    .btn-outline-dark:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.2);
    }
    
    .object-fit-cover {
        object-fit: cover;
    }
    
    .display-5 {
        font-size: 2.5rem;
    }
    
    @media (max-width: 768px) {
        .display-4 {
            font-size: 2rem;
        }
        
        .display-5 {
            font-size: 1.8rem;
        }
        
        .col-lg-7 {
            min-height: 300px;
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

    // Add smooth scrolling and hover effects
    document.addEventListener('DOMContentLoaded', function() {
        // Smooth image hover effect
        const roomImage = document.querySelector('.col-lg-7 img');
        if (roomImage) {
            roomImage.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.05)';
            });
            
            roomImage.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1)';
            });
        }
        
        // Add animation to cards
        const cards = document.querySelectorAll('.card');
        cards.forEach((card, index) => {
            card.style.opacity = '0';
            card.style.transform = 'translateY(20px)';
            
            setTimeout(() => {
                card.style.transition = 'all 0.6s ease';
                card.style.opacity = '1';
                card.style.transform = 'translateY(0)';
            }, index * 100);
        });
    });
</script>
@endsection