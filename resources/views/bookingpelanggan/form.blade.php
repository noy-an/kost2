@extends('fe.master')
@section('content')

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-8 col-md-10">

            <div class="card shadow rounded-4 border-0">
                <div class="card-header bg-primary text-white rounded-top-4 py-3">
                    <h4 class="mb-0">
                        <i class="fas fa-calendar-check me-2"></i>Form Booking Kamar Kos
                    </h4>
                </div>

                <div class="card-body px-4 py-4">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show">
                            <i class="fas fa-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(isset($kost))
                    <form method="POST" action="{{ route('bookingpelanggan.store') }}" id="booking-form">
                        @csrf
                        <input type="hidden" name="kost_id" value="{{ $kost->id }}">

                        <!-- INFO KAMAR -->
                        <div class="mb-4">
                            <label class="form-label fw-semibold">Kamar Kost</label>
                            <input type="text" class="form-control bg-light" value="Kamar {{ $kost->nomor_kamar }} (Lantai {{ $kost->nomor_lantai }})" disabled>
                        </div>

                        <!-- HARGA -->
                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Harga Kost</label>
                                <input type="text" class="form-control" value="Rp {{ number_format($kost->harga, 0, ',', '.') }}" disabled>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Harga Air</label>
                                <input type="text" class="form-control" value="Rp {{ number_format($kost->air, 0, ',', '.') }}" disabled>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label class="form-label">Harga Kebersihan</label>
                                <input type="text" class="form-control" value="Rp {{ number_format($kost->kebersihan, 0, ',', '.') }}" disabled>
                            </div>
                        </div>

                        <!-- TOTAL NOMINAL -->
                        <div class="mb-4">
                            <label class="form-label">Total yang Harus Dibayar</label>
                            <input type="text" class="form-control bg-light fw-bold text-success" value="Rp {{ number_format($harga_calculations['bayar_penuh'], 0, ',', '.') }}" readonly>
                            <input type="hidden" name="nominal_bayar" value="{{ $harga_calculations['bayar_penuh'] }}">
                        </div>

                        <!-- TANGGAL BOOKING -->
                        <div class="mb-4">
                            <label for="tgl_booking" class="form-label fw-semibold">Tanggal Booking</label>
                            <small class="d-block text-muted mb-1">Tanggal tersedia mulai:
                                <span class="text-success" id="tanggalTersedia"></span>
                            </small>
                            <input type="text" class="form-control" id="tgl_booking" name="tgl_booking" required>
                            <input type="hidden" id="disabledDates" value="{{ implode(',', $disabledDates) }}">
                        </div>

                        <!-- AKSI -->
                        <div class="d-grid gap-2 mt-4">
                            <button type="submit" class="btn btn-success btn-lg">
                                <i class="fas fa-paper-plane me-1"></i>Kirim Booking
                            </button>
                            <a href="{{ route('kamar.show', $kost->id) }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i>Kembali ke Detail Kost
                            </a>
                        </div>
                    </form>
                    @else
                        <div class="alert alert-warning d-flex align-items-center">
                            <i class="fas fa-exclamation-circle fa-2x me-3 text-warning"></i>
                            <div>
                                <strong>Data Kost Tidak Ditemukan</strong><br>
                                Silakan pilih kamar terlebih dahulu untuk melakukan booking.
                                <div class="mt-2">
                                    <a href="{{ route('kamar.index') }}" class="btn btn-sm btn-primary">
                                        <i class="fas fa-bed me-1"></i>Lihat Daftar Kamar
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

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const disabledDatesInput = document.getElementById('disabledDates').value;
    const disabledDates = disabledDatesInput.split(',');

    let tanggalTersedia = new Date();
    while (disabledDates.includes(tanggalTersedia.toISOString().split('T')[0])) {
        tanggalTersedia.setDate(tanggalTersedia.getDate() + 1);
    }
    document.getElementById('tanggalTersedia').textContent = tanggalTersedia.toISOString().split('T')[0];

    flatpickr("#tgl_booking", {
        dateFormat: "Y-m-d",
        disable: disabledDates,
        minDate: "today",
        locale: {
            firstDayOfWeek: 1
        }
    });
});
</script>
@endsection
