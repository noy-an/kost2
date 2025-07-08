@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection
@section('navbar')
  @include('backend.navbar')
@endsection

@section('content')
<div class="container">
    <h1>Edit Tagihan</h1>

    <form action="{{ route('tagihan.update', $tagihan->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="booking_id" class="form-label">Booking</label>
            <select name="booking_id" id="booking_id" class="form-select @error('booking_id') is-invalid @enderror" >
                <option value="">-- Pilih Booking --</option>
                @foreach($booking as $booking)
                    <option value="{{ $booking->id }}" {{ (old('booking_id', $tagihan->booking_id) == $booking->id) ? 'selected' : '' }}>
                        User: {{ $booking->user->name ?? 'Unknown' }} | Kamar: {{ $booking->kost->nomor_kamar ?? '-' }} | Tanggal: {{ $booking->tgl_booking }}
                    </option>
                @endforeach
            </select>
            @error('booking_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="bayar_per_bulan" class="form-label">Bayar Per Bulan</label>
            <input type="number" name="bayar_per_bulan" id="bayar_per_bulan" class="form-control @error('bayar_per_bulan') is-invalid @enderror" value="{{ old('bayar_per_bulan', $tagihan->bayar_per_bulan) }}" >
            @error('bayar_per_bulan')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="jumlah_bayar" class="form-label">Jumlah Bayar</label>
            <input type="number" name="jumlah_bayar" id="jumlah_bayar" class="form-control @error('jumlah_bayar') is-invalid @enderror" value="{{ old('jumlah_bayar', $tagihan->jumlah_bayar) }}" >
            @error('jumlah_bayar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('tagihan.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
