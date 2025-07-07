@extends('fe.master')
@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
           
            <div class="card shadow-sm rounded">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Form Booking Kos</h4>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif
                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif
                    @if(isset($kost) && $kost)
                    <form method="POST" action="{{ route('bookingpelanggan.store',['kost_id' => $kost->id]) }}">
                        @csrf
                      <!-- Simpan ID kost secara tersembunyi -->
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
                        <!-- Pilihan Persentase Pembayaran -->
                        <div class="mb-3">
                            <label class="form-label">Pilihan Pembayaran</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="persentase_pembayaran" id="dp30" value="30" checked>
                                <label class="form-check-label" for="dp30">
                                    DP 30% - <span id="nominal_30">Rp {{ number_format($nominal_pembayaran['30'], 0, ',', '.') }}</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="persentase_pembayaran" id="dp40" value="40">
                                <label class="form-check-label" for="dp40">
                                    DP 40% - <span id="nominal_40">Rp {{ number_format($nominal_pembayaran['40'], 0, ',', '.') }}</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="persentase_pembayaran" id="dp50" value="50">
                                <label class="form-check-label" for="dp50">
                                    DP 50% - <span id="nominal_50">Rp {{ number_format($nominal_pembayaran['50'], 0, ',', '.') }}</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="persentase_pembayaran" id="bayar_penuh" value="100">
                                <label class="form-check-label" for="bayar_penuh">
                                    Bayar Penuh - <span id="nominal_100">Rp {{ number_format($nominal_pembayaran['100'], 0, ',', '.') }}</span>
                                </label>
                            </div>
                        </div>
                        <!-- Nominal yang akan dibayar -->
                        <div class="mb-3">
                            <label class="form-label">Nominal yang akan dibayar</label>
                            <input type="text" class="form-control bg-light" id="nominal_bayar" value="Rp {{ number_format($nominal_pembayaran['30'], 0, ',', '.') }}" disabled>
                        </div>
                        <div class="mb-3">
                            <label for="tgl_booking" class="form-label">Tanggal Booking</label>
                            <input type="date" class="form-control" id="tgl_booking" name="tgl_booking" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-success">Kirim Booking</button>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-warning">
                        <h5>Data Kost Tidak Ditemukan</h5>
                        <p>Silakan pilih kost terlebih dahulu sebelum melakukan booking.</p>
                        <a href="{{ route('home.index') }}" class="btn btn-primary">Kembali ke Beranda</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const radioButtons = document.querySelectorAll('input[name="persentase_pembayaran"]');
    const nominalBayar = document.getElementById('nominal_bayar');
    
    // Data nominal dari PHP
    const nominalData = {
        '30': 'Rp {{ number_format($nominal_pembayaran["30"], 0, ",", ".") }}',
        '40': 'Rp {{ number_format($nominal_pembayaran["40"], 0, ",", ".") }}',
        '50': 'Rp {{ number_format($nominal_pembayaran["50"], 0, ",", ".") }}',
        '100': 'Rp {{ number_format($nominal_pembayaran["100"], 0, ",", ".") }}'
    };
    
    radioButtons.forEach(radio => {
        radio.addEventListener('change', function() {
            const persentase = this.value;
            nominalBayar.value = nominalData[persentase];
        });
    });
});
</script>
@endsection