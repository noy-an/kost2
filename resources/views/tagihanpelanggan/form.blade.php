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

                    <form action="{{ route('tagihanpelanggan.store') }}" method="POST">
                        @csrf
                        <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                        <input type="hidden" name="bayar_per_bulan" value="{{ $nominalBayar }}">
                        <input type="hidden" name="jumlah_bayar" value="1">

                        <div class="row g-3">
                            <!-- Customer Info Section -->
                            <div class="col-md-12">
                                <div class="bg-light p-3 rounded mb-3">
                                    <h5 class="text-primary mb-3"><i ></i>Informasi Pelanggan</h5>
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
                                    <h5 class="text-primary mb-3"><i></i>Ringkasan Pembayaran</h5>
                                    <div class="row">
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label text-muted small">Harga Kost/Bulan</label>
                                            <p class="fw-bold text-success">Rp {{ number_format($hargaKost, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label text-muted small">Total Dibayar (DP+Tagihan)</label>
                                            <p class="fw-bold">Rp {{ number_format($dpAwal + $totalSudahDibayar, 0, ',', '.') }}</p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label text-muted small">Bulan Ke</label>
                                            <p class="fw-bold">{{ $bulanKe }}</p>
                                        </div>
                                        <div class="col-md-6 mb-2">
                                            <label class="form-label text-muted small">Tanggal Tagihan</label>
                                            <p class="fw-bold">{{ date('d F Y', strtotime($tanggalTagihan)) }}</p>
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
                                        <div class="col-md-6">
                                            <label class="form-label text-muted small">Sisa Tagihan</label>
                                            <h4 class="fw-bold text-danger">Rp {{ number_format($sisaTagihan, 0, ',', '.') }}</h4>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Hidden Fields -->
                            <input type="hidden" name="tanggal_tagihan" value="{{ $tanggalTagihan }}">

                            <!-- Submit Button -->
                            <div class="col-md-12 mt-2">
                                <button type="submit" class="btn btn-primary btn-lg w-100 py-3">
                                    <i ></i>Konfirmasi Pembayaran Bulan ke-{{ $bulanKe }}
                                </button>
                                <p class="text-muted small mt-2 text-center">
                                    <i class="fas fa-info-circle me-1"></i>Pastikan pembayaran sudah dilakukan sebelum mengkonfirmasi
                                </p>
                            </div>
                        </div>
                    </form>
                </div>

                <div class="card-footer bg-light py-3">
                    <div class="text-center text-muted small">
                        <i ></i> Transaksi aman dan terenkripsi
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
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
</style>
@endpush