@extends('fe.master')
@section('content')

<!-- Flatpickr CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-md-8">

            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Form Booking Kos</h4>
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
                    <form method="POST" action="{{ route('bookingpelanggan.store') }}" id="booking-form">
                        @csrf
                        <input type="hidden" name="kost_id" id="kost_id" value="{{ $kost->id }}">

                        <!-- Tampilkan Info Kamar -->
                        <div class="mb-3">
                            <label class="form-label">Kamar Kost</label>
                            <input type="text" class="form-control" value="Kamar {{ $kost->nomor_kamar }} (Lantai {{ $kost->nomor_lantai }})" disabled>
                        </div>

                        <!-- Harga Kost -->
                        <div class="mb-3">
                            <label class="form-label">Harga Kost</label>
                            <input type="text" class="form-control" id="harga_kost" value="Rp {{ number_format($kost->harga, 0, ',', '.') }}" disabled>
                        </div>

<!-- Tampilan harga ke user -->
<div class="mb-3">
    <label class="form-label">Nominal yang akan dibayar</label>
    <input type="text" class="form-control bg-light" value="Rp {{ number_format($harga_calculations['bayar_penuh'], 0, ',', '.') }}" readonly>
</div>

<!-- Hidden input buat backend -->
<input type="hidden" name="nominal_bayar" value="{{ $harga_calculations['bayar_penuh'] }}">




                        <div class="mb-3">
                            <label for="tgl_booking" class="form-label">
                                Tanggal Booking
                                <br><small class="text-muted">Tanggal tersedia mulai: 
                                    <span id="tanggalTersedia" class="text-success"></span>
                                </small>
                            </label>
                            <input type="text" class="form-control" id="tgl_booking" name="tgl_booking" required>
                            <input type="hidden" id="disabledDates" value="{{ implode(',', $disabledDates) }}">
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success">Kirim Booking</button>
                            <a href="{{ route('kamar.show', $kost->id) }}" class="btn btn-outline-secondary">Kembali ke Detail Kost</a>
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

<!-- Flatpickr JS -->
<script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const radioButtons = document.querySelectorAll('input[name="persentase_pembayaran"]');
    const nominalBayar = document.getElementById('nominal_bayar');

    radioButtons.forEach(radio => {
        radio.addEventListener('change', function () {
            const nominal = parseFloat(this.getAttribute('data-nominal'));
            nominalBayar.value = 'Rp ' + nominal.toLocaleString('id-ID');
        });
    });

const disabledDatesInput = document.getElementById('disabledDates').value;
const disabledDates = disabledDatesInput.split(',');


    // Tentukan tanggal tersedia mulai dari hari ini atau hari setelah terakhir disable
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
