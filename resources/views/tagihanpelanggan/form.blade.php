@extends('fe.master')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-primary text-white">
            <h4 class="mb-0">Form Tagihan Bulanan</h4>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form action="{{ route('tagihanpelanggan.store') }}" method="POST">
                @csrf

                {{-- Hidden untuk dikirim ke backend --}}
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <input type="hidden" name="bayar_per_bulan" value="{{ $nominalBayar }}">
                <input type="hidden" name="jumlah_bayar" value="1">

                {{-- Informasi Pemesan --}}
                <div class="mb-3">
                    <label class="form-label">Nama Pemesan</label>
                    <input type="text" class="form-control" value="{{ $booking->user->name ?? '-' }}" disabled>
                </div>

                {{-- Harga Kos per Bulan --}}
                <div class="mb-3">
                    <label class="form-label">Harga Kost / Bulan</label>
                    <input type="text" class="form-control" value="Rp {{ number_format($hargaKost, 0, ',', '.') }}" disabled>
                </div>

                {{-- Total Sudah Dibayar --}}
                <div class="mb-3">
                    <label class="form-label">Total Sudah Dibayar (DP + Tagihan)</label>
                    <input type="text" class="form-control" 
                        value="Rp {{ number_format($dpAwal + $totalSudahDibayar, 0, ',', '.') }}" disabled>
                </div>

                {{-- Sisa Tagihan --}}
                <div class="mb-3">
                    <label class="form-label text-danger fw-bold">Sisa Tagihan</label>
                    <input type="text" class="form-control fw-bold" 
                        value="Rp {{ number_format($sisaTagihan, 0, ',', '.') }}" disabled>
                </div>

                {{-- Info Bulan Ke-berapa --}}
                <div class="mb-3">
                    <label class="form-label">Pembayaran untuk Bulan Ke</label>
                    <input type="text" class="form-control" value="{{ $bulanKe }}" disabled>
                </div>

                {{-- Nominal Tagihan Bulan Ini --}}
                <div class="mb-3">
                    <label class="form-label">Nominal yang Harus Dibayar</label>
                    <input type="text" class="form-control" 
                        value="Rp {{ number_format($nominalBayar, 0, ',', '.') }}" disabled>
                </div>

                {{-- Tombol Submit --}}
                <div class="d-grid">
                    <button type="submit" class="btn btn-success">
                        Bayar Bulan ke-{{ $bulanKe }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
