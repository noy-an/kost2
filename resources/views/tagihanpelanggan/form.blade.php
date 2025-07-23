@extends('fe.master')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card border-0 shadow-lg">
                <div class="card-header bg-gradient-primary text-black py-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <h4 class="mb-0"><i class="fas fa-file-invoice-dollar me-2"></i>Form Pembayaran Bulanan</h4>
                        <span class="badge bg-light text-primary fs-6">Bulan ke-{{ $bulanKe }}</span>
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

                    @if(isset($paymentData) && $paymentData['success'])
                        <!-- QR Code Payment Section -->
                        <div class="text-center mb-4">
                            <h5 class="text-primary mb-3"><i class="fas fa-qrcode me-2"></i>Scan QR Code untuk Pembayaran</h5>
                            <img src="{{ $paymentData['qr_code_url'] }}" alt="QR Code Gopay" class="img-fluid mb-3" style="max-width: 250px;">
                            <p class="text-muted small mb-3">Scan QR code di atas menggunakan aplikasi GoPay</p>
                            
                            <div class="d-flex justify-content-center gap-3 mb-4">
                                <a href="{{ $paymentData['deeplink_url'] }}" class="btn btn-outline-success">
                                    <i class="fab fa-google-play me-2"></i>Bayar dengan GoPay
                                </a>
                                <button onclick="checkStatus()" class="btn btn-outline-primary">
                                    <i class="fas fa-sync-alt me-2"></i>Cek Status Pembayaran
                                </button>
                            </div>
                        </div>
                    @else
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>Gagal memproses pembayaran. Silakan coba lagi.
                        </div>
                    @endif

                    <form action="{{ route('tagihanpelanggan.store') }}" method="POST" id="payment-form">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        <input type="hidden" name="bayar_per_bulan" value="{{ $nominalBayar }}">
                        <input type="hidden" name="jumlah_bayar" value="1">
                        <input type="hidden" name="tanggal_tagihan" value="{{ $tanggalTagihan }}">
                        <input type="hidden" name="order_id" value="{{ $paymentData['order_id'] ?? '' }}">

                        <div class="row g-3">
                            <!-- Customer Info Section -->
                            <div class="col-md-12">
                                <div class="bg-light p-3 rounded mb-3">
                                    <h5 class="text-primary mb-3"><i class="fas fa-user-circle me-2"></i>Informasi Pelanggan</h5>
                                    <div class="row">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small">Nama Pemesan</label>
                                            <p class="fw-bold">{{ $booking->user->name ?? '-' }}</p>
                                        </div>
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small">Kamar</label>
                                            <p class="fw-bold">{{ $booking->kost->nomor_kamar ?? '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Summary Section -->
                            <div class="col-md-12">
                                <div class="border p-3 rounded mb-3">
                                    <h5 class="text-primary mb-3"><i class="fas fa-receipt me-2"></i>Ringkasan Pembayaran</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label text-muted small">Harga Kost/Bulan</label>
                                            <p class="fw-bold text-success">Rp {{ number_format($hargaKost, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label text-muted small">Bulan Ke</label>
                                            <p class="fw-bold">{{ $bulanKe }}</p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label text-muted small">Tanggal Tagihan</label>
                                            <p class="fw-bold">{{ isset($tanggalTagihan) ? date('d F Y', strtotime($tanggalTagihan)) : '-' }}</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Payment Amount Section -->
                            <div class="col-md-12">
                                <div class="bg-light-warning p-3 rounded border border-warning mb-4">
                                    <div class="row align-items-center">
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small">Nominal yang Harus Dibayar</label>
                                            <h4 class="fw-bold text-danger">Rp {{ number_format($nominalBayar, 0, ',', '.') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>

                    <!-- Kembali Button Container (hidden by default) -->
                    <div class="text-center mt-4" id="kembali-container" style="display: none;">
                        <a href="{{ route('tagihanpelanggan.index') }}" class="btn btn-success" id="kembali-button">
                            <i class="fas fa-arrow-left me-2"></i>Kembali ke Halaman Utama
                        </a>
                    </div>
                </div>

                <div class="card-footer bg-light py-3">
                    <div class="text-center text-muted small">
                        <i class="fas fa-lock me-1"></i> Transaksi aman dan terenkripsi
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@if(isset($paymentData) && $paymentData['success'])
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
function checkStatus() {
    fetch("{{ route('payment.status') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            order_id: '{{ $paymentData['order_id'] }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            if (data.data.transaction_status === 'settlement' || data.data.transaction_status === 'capture') {
                // Show success alert
                Swal.fire({
                    icon: 'success',
                    title: 'Pembayaran Berhasil',
                    text: 'Status pembayaran: ' + data.data.transaction_status + 
                          '\nTanggal: ' + data.data.transaction_time,
                    confirmButtonText: 'OK'
                }).then((result) => {
                    if (result.isConfirmed) {
                        // Submit form and redirect to index
                        submitAndRedirect();
                    }
                });
            } else {
                alert('Status Pembayaran: ' + data.data.transaction_status + 
                      '\nTanggal: ' + data.data.transaction_time);
            }
        } else {
            alert('Gagal memeriksa status: ' + data.message);
        }
    })
    .catch(error => {
        alert('Terjadi kesalahan saat memeriksa status');
        console.error(error);
    });
}

function submitAndRedirect() {
    fetch("{{ route('tagihanpelanggan.store') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            booking_id: '{{ $booking->id }}',
            bayar_per_bulan: '{{ $nominalBayar }}',
            jumlah_bayar: 1,
            tanggal_tagihan: '{{ $tanggalTagihan }}',
            order_id: '{{ $paymentData['order_id'] ?? '' }}'
        })
    })
    .then(response => {
        if (response.redirected) {
            window.location.href = response.url;
        } else {
            return response.json();
        }
    })
    .then(data => {
        if (data && data.redirect) {
            window.location.href = data.redirect;
        } else {
            window.location.href = "{{ route('tagihanpelanggan.index') }}";
        }
    })
    .catch(error => {
        console.error('Error:', error);
        window.location.href = "{{ route('tagihanpelanggan.index') }}";
    });
}

// Auto check payment status every 5 seconds
let paymentCheckInterval = setInterval(function() {
    fetch("{{ route('payment.status') }}", {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        },
        body: JSON.stringify({
            order_id: '{{ $paymentData['order_id'] }}'
        })
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && 
           (data.data.transaction_status === 'settlement' || 
            data.data.transaction_status === 'capture')) {
            clearInterval(paymentCheckInterval);
            
            // Show success alert
            Swal.fire({
                icon: 'success',
                title: 'Pembayaran Berhasil',
                text: 'Status pembayaran: ' + data.data.transaction_status + 
                      '\nTanggal: ' + data.data.transaction_time,
                confirmButtonText: 'OK'
            }).then((result) => {
                if (result.isConfirmed) {
                    // Submit form and redirect to index
                    submitAndRedirect();
                }
            });
        }
    })
    .catch(error => console.error('Error checking payment status:', error));
}, 5000);
</script>
@endif
@endsection

@push('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #3f80ea 0%, #1e3c8b 100%);
    }
    .card {
        border-radius: 10px;
        overflow: hidden;
    }
    .card-header {
        border-bottom: none;
    }
    .form-label {
        font-weight: 500;
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
    #qrcode-container {
        background: white;
        padding: 20px;
        border-radius: 8px;
        display: inline-block;
        box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .btn-success {
        background-color: #28a745;
        border: none;
        padding: 10px 20px;
        font-size: 16px;
    }
    .btn-success:hover {
        background-color: #218838;
    }
</style>
@endpush