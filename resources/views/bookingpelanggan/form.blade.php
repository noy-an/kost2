@extends('fe.master')
@section('content')
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg rounded-3 overflow-hidden">
                <!-- Card Header with Gradient Background -->
                <div class="card-header bg-gradient-primary py-3 text-black">
                    <div class="d-flex justify-content-between align-items-center">
                        <h3 class="mb-0"><i class="fas fa-calendar-check me-2"></i>Form Booking Kos</h3>
                        <span class="badge bg-white text-primary fs-6">Kamar {{ $kost->nomor_kamar ?? '' }}</span>
                    </div>
                </div>

                <div class="card-body p-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(isset($kost) && $kost)
                    <form method="POST" action="{{ route('bookingpelanggan.store') }}">
                        @csrf
                        <input type="hidden" name="kost_id" id="kost_id" value="{{ $kost->id }}">
                        
                        <!-- Room Information Section -->
                        <div class="mb-4 p-3 bg-light rounded-3">
                            <h5 class="text-primary mb-3"><i class="fas fa-door-open me-2"></i>Informasi Kamar</h5>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small">Nomor Kamar</label>
                                    <p class="fw-bold">Kamar {{ $kost->nomor_kamar }}</p>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label text-muted small">Lantai</label>
                                    <p class="fw-bold">Lantai {{ $kost->nomor_lantai }}</p>
                                </div>
                                <div class="col-12">
                                    <label class="form-label text-muted small">Harga Per Bulan</label>
                                    <h4 class="fw-bold text-success">Rp {{ number_format($kost->harga, 0, ',', '.') }}</h4>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Options Section -->
                        <div class="mb-4 p-3 border rounded-3">
                            <h5 class="text-primary mb-3"><i class="fas fa-credit-card me-2"></i>Pilihan Pembayaran</h5>
                            
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check-card">
                                        <input class="form-check-input" type="radio" name="persentase_pembayaran" id="dp30" value="30" checked 
                                               data-nominal="{{ $harga_calculations['dp_30'] }}">
                                        <label class="form-check-label" for="dp30">
                                            <span class="fw-bold">DP 30%</span>
                                            <span class="d-block text-success">Rp {{ number_format($harga_calculations['dp_30'], 0, ',', '.') }}</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check-card">
                                        <input class="form-check-input" type="radio" name="persentase_pembayaran" id="dp40" value="40"
                                               data-nominal="{{ $harga_calculations['dp_40'] }}">
                                        <label class="form-check-label" for="dp40">
                                            <span class="fw-bold">DP 40%</span>
                                            <span class="d-block text-success">Rp {{ number_format($harga_calculations['dp_40'], 0, ',', '.') }}</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check-card">
                                        <input class="form-check-input" type="radio" name="persentase_pembayaran" id="dp50" value="50"
                                               data-nominal="{{ $harga_calculations['dp_50'] }}">
                                        <label class="form-check-label" for="dp50">
                                            <span class="fw-bold">DP 50%</span>
                                            <span class="d-block text-success">Rp {{ number_format($harga_calculations['dp_50'], 0, ',', '.') }}</span>
                                        </label>
                                    </div>
                                </div>
                                
                                <div class="col-md-6">
                                    <div class="form-check-card bg-light-warning">
                                        <input class="form-check-input" type="radio" name="persentase_pembayaran" id="bayar_penuh" value="100"
                                               data-nominal="{{ $harga_calculations['bayar_penuh'] }}">
                                        <label class="form-check-label" for="bayar_penuh">
                                            <span class="fw-bold">Bayar Penuh</span>
                                            <span class="d-block text-success">Rp {{ number_format($harga_calculations['bayar_penuh'], 0, ',', '.') }}</span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Payment Summary -->
                        <div class="mb-4 p-3 bg-light-primary rounded-3">
                            <div class="row align-items-center">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label text-muted small">Nominal yang akan dibayar</label>
                                    <h3 class="fw-bold text-primary" id="nominal_bayar">Rp {{ number_format($harga_calculations['dp_30'], 0, ',', '.') }}</h3>
                                </div>
                                <div class="col-md-6">
                                    <label for="tgl_booking" class="form-label text-muted small">Tanggal Booking</label>
                                    <input type="date" class="form-control bg-white" id="tgl_booking" name="tgl_booking" required>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-grid gap-3 mt-4">
                            <button type="submit" class="btn btn-primary btn-lg py-3">
                                <i class="fas fa-paper-plane me-2"></i>Kirim Booking
                            </button>
                            <a href="{{ route('kamar.show', $kost->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-2"></i>Kembali ke Detail Kost
                            </a>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-warning">
                        <div class="d-flex">
                            <i class="fas fa-exclamation-triangle fa-2x me-3"></i>
                            <div>
                                <h5 class="alert-heading">Data Kost Tidak Ditemukan</h5>
                                <p>Silakan pilih kost terlebih dahulu sebelum melakukan booking.</p>
                                <a href="{{ route('kamar.index') }}" class="btn btn-primary mt-2">
                                    <i class="fas fa-bed me-2"></i>Kembali ke Daftar Kamar
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3f80ea 0%, #1e3c8b 100%);
    }
    .form-check-card {
        border: 1px solid #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        transition: all 0.3s ease;
    }
    .form-check-card:hover {
        border-color: #3f80ea;
        background-color: rgba(63, 128, 234, 0.05);
    }
    .form-check-card.bg-light-warning {
        background-color: rgba(255, 193, 7, 0.1);
        border-color: rgba(255, 193, 7, 0.3);
    }
    .form-check-card.bg-light-warning:hover {
        background-color: rgba(255, 193, 7, 0.15);
    }
    .bg-light-primary {
        background-color: rgba(63, 128, 234, 0.1);
    }
    .form-check-input {
        margin-top: 0.3rem;
    }
    .btn-primary {
        background-color: #3f80ea;
        border: none;
        transition: all 0.3s;
    }
    .btn-primary:hover {
        background-color: #1e3c8b;
        transform: translateY(-2px);
    }
    .rounded-3 {
        border-radius: 12px !important;
    }
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const radioButtons = document.querySelectorAll('input[name="persentase_pembayaran"]');
    const nominalBayar = document.getElementById('nominal_bayar');

    radioButtons.forEach(radio => {
        radio.addEventListener('change', function () {
            const nominal = parseFloat(this.getAttribute('data-nominal'));
            nominalBayar.textContent = 'Rp ' + nominal.toLocaleString('id-ID');
            
            // Add visual feedback
            document.querySelectorAll('.form-check-card').forEach(card => {
                card.style.borderColor = '#dee2e6';
            });
            this.closest('.form-check-card').style.borderColor = '#3f80ea';
        });
    });

    // Set tanggal booking minimal hari ini
    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tgl_booking').setAttribute('min', today);
    
    // Highlight the first radio by default
    document.querySelector('.form-check-card').style.borderColor = '#3f80ea';
});
</script>
@endpush
@endsection