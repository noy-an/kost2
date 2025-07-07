@extends('fe.master')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-lg-10">

                <div class="row g-4 align-items-start">
                    {{-- Kolom Gambar --}}
                    <div class="col-md-6">
                        <div class="rounded shadow-sm overflow-hidden border">
                            @if($kosts->foto1)
                                <img src="{{ asset('storage/' . $kosts->foto1) }}" class="img-fluid w-100" alt="Foto Kamar">
                            @else
                                <img src="https://via.placeholder.com/600x400?text=Foto+Tidak+Tersedia" class="img-fluid w-100" alt="No Image">
                            @endif
                        </div>
                    </div>

                    {{-- Kolom Detail --}}
                    <div class="col-md-6">
                        <h2 class="mb-3">Kamar No {{ $kosts->nomor_kamar }}</h2>
                        <p class="text-muted mb-1">Lantai: {{ $kosts->nomor_lantai }}</p>
                        <p class="mb-2"><strong>Ukuran:</strong> {{ $kosts->ukuran ?? '-' }}</p>

                        <h4 class="text-primary mb-3">Rp {{ number_format($kosts->harga, 0, ',', '.') }} / bulan</h4>

                        <p class="mb-3"><strong>Fasilitas:</strong><br> {{ $kosts->fasilitas }}</p>

                        <p><strong>Status:</strong> 
                            @if($kosts->status === 'kosong')
                                <span class="badge bg-success">Kosong</span>
                            @else
                                <span class="badge bg-danger">Sudah Ditempati</span>
                            @endif
                        </p>

                        @if($kosts->status === 'kosong')
                            @if(session()->has('pelanggan_login'))
                                {{-- Jika sudah login sebagai pelanggan --}}
                                <a href="{{ route('bookingpelanggan.form', $kosts->id) }}" class="btn btn-primary mt-3">Booking Sekarang</a>
                            @else
                                {{-- Jika belum login --}}
                                <button onclick="showLoginAlert()" class="btn btn-primary mt-3">Booking Sekarang</button>
                            @endif
                        @endif

                        <div class="mt-4">
                            <a href="{{ route('kamar.index') }}" class="btn btn-outline-secondary">← Kembali ke Daftar</a>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function showLoginAlert() {
        Swal.fire({
            title: 'Silakan Login atau Register',
            text: 'Untuk booking kamar, Anda harus login sebagai pelanggan terlebih dahulu.',
            icon: 'warning',
            showDenyButton: true,
            showCancelButton: true,
            confirmButtonText: 'Login',
            denyButtonText: 'Register',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                // Arahkan ke halaman login
                window.location.href = '{{ route("login") }}';
            } else if (result.isDenied) {
                // Arahkan ke halaman registrasi pelanggan
                window.location.href = '{{ route("registrasipelanggan") }}';
            }
            // Jika cancel, swal hanya tertutup (tidak perlu action)
        });
    }
</script>
@endsection
