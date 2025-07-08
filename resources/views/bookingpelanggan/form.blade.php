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
                    <form method="POST" action="{{ route('bookingpelanggan.store') }}" id="booking-form">
                        @csrf
                        <input type="hidden" name="kost_id" id="kost_id" value="{{ $kost->id }}">
                        
                        <div class="mb-3">
                            <label class="form-label">Kamar Kost</label>
                            <input type="text" class="form-control" value="Kamar {{ $kost->nomor_kamar }} (Lantai {{ $kost->nomor_lantai }})" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Harga Kost</label>
                            <input type="text" class="form-control" id="harga_kost" value="Rp {{ number_format($kost->harga, 0, ',', '.') }}" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Pilihan Pembayaran</label>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="persentase_pembayaran" id="dp30" value="30" checked 
                                       data-nominal="{{ $harga_calculations['dp_30'] }}">
                                <label class="form-check-label" for="dp30">
                                    DP 30% - <span>Rp {{ number_format($harga_calculations['dp_30'], 0, ',', '.') }}</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="persentase_pembayaran" id="dp40" value="40"
                                       data-nominal="{{ $harga_calculations['dp_40'] }}">
                                <label class="form-check-label" for="dp40">
                                    DP 40% - <span>Rp {{ number_format($harga_calculations['dp_40'], 0, ',', '.') }}</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="persentase_pembayaran" id="dp50" value="50"
                                       data-nominal="{{ $harga_calculations['dp_50'] }}">
                                <label class="form-check-label" for="dp50">
                                    DP 50% - <span>Rp {{ number_format($harga_calculations['dp_50'], 0, ',', '.') }}</span>
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="persentase_pembayaran" id="bayar_penuh" value="100"
                                       data-nominal="{{ $harga_calculations['bayar_penuh'] }}">
                                <label class="form-check-label" for="bayar_penuh">
                                    Bayar Penuh - <span>Rp {{ number_format($harga_calculations['bayar_penuh'], 0, ',', '.') }}</span>
                                </label>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label class="form-label">Nominal yang akan dibayar</label>
                            <input type="text" class="form-control bg-light" id="nominal_bayar" value="Rp {{ number_format($harga_calculations['dp_30'], 0, ',', '.') }}" disabled>
                        </div>
                        
                        <div class="mb-3">
                            <label for="tgl_booking" class="form-label">Tanggal Booking</label>
                            <input type="date" class="form-control" id="tgl_booking" name="tgl_booking" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-success" id="submit-button">Kirim Booking</button>
                            <a href="{{ route('kamar.show', $kost->id) }}" class="btn btn-outline-secondary">Kembali ke Detail Kost</a>
                        </div>
                    </form>
                    @else
                    <div class="alert alert-warning">
                        <h5>Data Kost Tidak Ditemukan</h5>
                        <p>Silakan pilih kost terlebih dahulu sebelum melakukan booking.</p>
                        <a href="{{ route('kamar.index') }}" class="btn btn-primary">Kembali ke Daftar Kamar</a>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
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

    const today = new Date().toISOString().split('T')[0];
    document.getElementById('tgl_booking').setAttribute('min', today);

    document.getElementById('booking-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const button = document.getElementById('submit-button');
        button.disabled = true;
        button.innerHTML = 'Memproses...';
        this.submit();
    });
});
</script>
@endsection