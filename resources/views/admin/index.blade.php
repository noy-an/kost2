@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection
@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4">
    <div class="row g-4">
        <!-- Kost -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-primary text-white rounded-circle p-3 me-3">
                        <i class="ti ti-building-community fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Total Kamar Kost</h6>
                        <h4 class="fw-bold">{{ $kostCount }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Booking -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-warning text-white rounded-circle p-3 me-3">
                        <i class="ti ti-calendar-event fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Total Booking</h6>
                        <h4 class="fw-bold">{{ $bookingCount }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tagihan -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-info text-white rounded-circle p-3 me-3">
                        <i class="ti ti-file-invoice fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Total Tagihan</h6>
                        <h4 class="fw-bold">{{ $tagihanCount }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pembayaran -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-success text-white rounded-circle p-3 me-3">
                        <i class="ti ti-credit-card fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Total Pembayaran</h6>
                        <h4 class="fw-bold">{{ $pembayaranCount }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Pengguna -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-dark text-white rounded-circle p-3 me-3">
                        <i class="ti ti-users fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Total Pengguna</h6>
                        <h4 class="fw-bold">{{ $userCount }}</h4>
                    </div>
                </div>
            </div>
        </div>

        <!-- Kontak Masuk -->
        <div class="col-md-4">
            <div class="card shadow-sm border-0">
                <div class="card-body d-flex align-items-center">
                    <div class="bg-danger text-white rounded-circle p-3 me-3">
                        <i class="ti ti-mail fs-4"></i>
                    </div>
                    <div>
                        <h6 class="mb-1">Pesan Kontak</h6>
                        <h4 class="fw-bold">{{ $kontakCount }}</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
