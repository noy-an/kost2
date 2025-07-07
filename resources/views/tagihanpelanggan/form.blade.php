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

            <form action="{{ route('tagihanpelanggan.store') }}" method="POST">
                @csrf

                <input type="hidden" name="booking_id" value="{{ $booking->id }}">

                <div class="mb-3">
                    <label class="form-label">Nama Pemesan</label>
                    <input type="text" class="form-control" value="{{ $booking->user->name ?? 'User' }}" disabled>
                </div>

                <div class="mb-3">
                    <label class="form-label">Bayar per Bulan</label>
                    <input type="number" class="form-control" name="bayar_per_bulan" required>
                </div>

                <div class="mb-3">
                    <label class="form-label">Jumlah Bulan</label>
                    <input type="number" class="form-control" name="jumlah_bayar" required>
                </div>

                <div class="d-grid">
                    <button type="submit" class="btn btn-success">Simpan Tagihan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
