@extends('backend.master')

@section('navbar')
    @include('backend.navbar')
@endsection

@section('sidebar')
    @include('backend.sidebar')
@endsection

@section('content')
<style>
    .laporan-title {
        font-size: 26px;
        font-weight: bold;
        color: #34495e;
        margin-bottom: 25px;
    }

    .summary-box {
        display: flex;
        gap: 20px;
        flex-wrap: wrap;
        margin-bottom: 30px;
    }

    .summary-card {
        flex: 1 1 250px;
        background: linear-gradient(to right, #ecf0f1, #ffffff);
        padding: 25px;
        border-radius: 12px;
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.06);
        text-align: center;
        transition: all 0.3s ease;
        position: relative;
    }

    .summary-card:hover {
        transform: translateY(-5px);
    }

    .summary-card h4 {
        font-size: 18px;
        color: #2c3e50;
        margin-bottom: 8px;
    }

    .summary-card p {
        font-size: 16px;
        color: #2d3436;
    }

    .card {
        border-radius: 10px;
        box-shadow: 0 3px 10px rgba(0,0,0,0.05);
        transition: 0.3s ease;
    }

    .card-header {
        background-color: #2e86de;
        color: white;
        font-weight: bold;
        font-size: 16px;
        padding: 12px 20px;
        border-top-left-radius: 10px;
        border-top-right-radius: 10px;
    }

    .table th, .table td {
        text-align: center;
        vertical-align: middle;
        font-size: 14px;
        white-space: nowrap;
    }

    .table thead {
        background-color: #dff3ff;
        color: #2c3e50;
    }

    .table-hover tbody tr:hover {
        background-color: #f2f9ff;
        cursor: pointer;
    }

    .fade-in {
        opacity: 0;
        transform: translateY(20px);
        animation: fadeInUp 0.5s forwards;
    }

    @keyframes fadeInUp {
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    .btn-custom {
        background-color: #2e86de;
        color: white;
        padding: 8px 16px;
        border-radius: 6px;
        font-weight: 600;
        border: none;
        margin: 0 5px;
        transition: background-color 0.3s ease;
    }
    .btn-custom.disabled {
        background-color: #cccccc;
        cursor: not-allowed;
        pointer-events: none;
    }
    .btn-custom:hover:not(.disabled) {
        background-color: #1b4f72;
    }

    /* ============================
       Responsive Design
    ============================= */
    @media (max-width: 768px) {
        .laporan-title {
            font-size: 20px;
            text-align: center;
        }

        .laporan-title a {
            width: 100%;
            text-align: center;
            margin-top: 10px;
        }

        .summary-box {
            flex-direction: column;
            gap: 15px;
        }

        .summary-card {
            flex: 1 1 100%;
            padding: 20px;
        }

        form .row.g-3 {
            flex-direction: column;
        }

        form .col-md-4 {
            width: 100%;
        }

        .card-header {
            font-size: 14px;
            text-align: center;
        }

        .table th, .table td {
            font-size: 12px;
            white-space: normal;
        }

        .btn-custom {
            width: 100%;
            margin-bottom: 8px;
        }
    }

    @media (max-width: 480px) {
        .laporan-title {
            font-size: 18px;
        }
        .summary-card h4 {
            font-size: 16px;
        }
        .summary-card p {
            font-size: 14px;
        }
    }
</style>

<div class="container-fluid pt-4 px-4">
    <div class="row g-4">
        <div class="col-12 fade-in">
            <div class="bg-white rounded h-100 p-4 shadow-sm">

                <!-- Heading -->
                <div class="laporan-title d-flex justify-content-between align-items-center flex-wrap gap-2">
                    <span><i class="fas fa-chart-bar me-2 text-primary"></i> Laporan Booking & DP</span>
                    <a href="{{ route('pdf.laporan') }}" class="btn btn-success btn-sm px-3 py-2 rounded" target="_blank" style="font-size: 14px;">
                        📄 Download Laporan
                    </a>
                </div>

                <!-- Summary -->
                <div class="summary-box">
                    <div class="summary-card">
                        <h4><i class="fas fa-calendar-check me-1 text-info"></i> Total Booking</h4>
                        <p>{{ $totalBooking }} Booking</p>
                    </div>
                    <div class="summary-card">
                        <h4><i class="fas fa-wallet me-1 text-success"></i> Total DP</h4>
                        <p>Rp {{ number_format($totalDP, 2, ',', '.') }}</p>
                    </div>
                </div>

                <!-- Filter Form -->
                <form method="GET" action="{{ route('laporan.index') }}" class="mb-4">
                    <div class="row g-3 align-items-center">
                        <div class="col-md-4">
                            <label for="search_kamar" class="form-label fw-bold">Cari Nomor Kamar</label>
                            <input type="text" name="search_kamar" id="search_kamar" value="{{ request('search_kamar') }}" class="form-control" placeholder="Masukkan nomor kamar">
                        </div>
                        <div class="col-md-4">
                            <label for="tanggal" class="form-label fw-bold">Filter Tanggal Booking</label>
                            <input type="date" name="tanggal" id="tanggal" value="{{ request('tanggal') }}" class="form-control">
                        </div>
                        <div class="col-md-4 mt-4">
                            <button type="submit" class="btn btn-primary mt-2 w-100">
                                🔍 Cari / Filter
                            </button>
                            <a href="{{ route('laporan.index') }}" class="btn btn-secondary mt-2 w-100">
                                ❌ Reset
                            </a>
                        </div>
                    </div>
                </form>

                <!-- Table Card -->
                <div class="card">
                    <div class="card-header">
                        Rangkuman Booking Kamar & DP
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-bordered table-striped table-hover">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Nomor Kamar</th>
                                        <th>DP</th>
                                        <th>Tanggal Booking</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($data as $index => $item)
                                        <tr>
                                            <td>{{ $index + $data->firstItem() }}</td>
                                            <td>{{ $item->kost->nomor_kamar ?? '-' }}</td>
                                            <td>Rp {{ number_format($item->dp, 2, ',', '.') }}</td>
                                            <td>{{ \Carbon\Carbon::parse($item->tgl_booking)->format('d/m/Y') }}</td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4">Tidak ada data booking ditemukan.</td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <!-- Pagination -->
                <div class="mt-4 text-center">
                    {{-- Tombol Previous --}}
                    <a href="{{ $data->previousPageUrl() }}" class="btn btn-custom {{ $data->onFirstPage() ? 'disabled' : '' }}">
                        Previous
                    </a>

                    {{-- Tombol Next --}}
                    <a href="{{ $data->nextPageUrl() }}" class="btn btn-custom {{ $data->hasMorePages() ? '' : 'disabled' }}">
                        Next
                    </a>

                    {{-- Info halaman --}}
                    <div class="mt-2 text-muted" style="font-weight: 500;">
                        Page {{ $data->currentPage() }} of {{ $data->lastPage() }}
                    </div>
                </div>

            </div>
        </div>
    </div>
</div>

<!-- JavaScript Fade-in Animations -->
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const fadeInElements = document.querySelectorAll('.fade-in');
        fadeInElements.forEach((el, index) => {
            el.style.animationDelay = `${index * 0.2}s`;
        });
    });
</script>
@endsection
