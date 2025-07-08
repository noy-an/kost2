@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection
@section('navbar')
  @include('backend.navbar')
@endsection

@section('content')
<div class="container">
    <h1>Edit Pembayaran</h1>

    <form action="{{ route('pembayaran.update', $pembayaran->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="booking_id" class="form-label">Booking</label>
            <select name="booking_id" id="booking_id" class="form-select @error('booking_id') is-invalid @enderror" >
                <option value="">-- Pilih Booking --</option>
                @foreach($bookings as $booking)
                    <option value="{{ $booking->id }}" {{ old('booking_id', $pembayaran->booking_id) == $booking->id ? 'selected' : '' }}>
                        User: {{ $booking->user->name ?? 'Unknown' }} | Kamar: {{ $booking->kost->nomor_kamar ?? '-' }} | Tanggal: {{ $booking->tgl_booking }}
                    </option>
                @endforeach
            </select>
            @error('booking_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="metode_bayar" class="form-label">Metode Bayar</label>
            <input type="text" name="metode_bayar" id="metode_bayar" class="form-control @error('metode_bayar') is-invalid @enderror" 
                value="{{ old('metode_bayar', $pembayaran->metode_bayar) }}">
            @error('metode_bayar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="status_bayar" class="form-label">Status Bayar</label>
            <select name="status_bayar" id="status_bayar" class="form-select @error('status_bayar') is-invalid @enderror" >
                <option value="">-- Pilih Status --</option>
                <option value="paid" {{ old('status_bayar', $pembayaran->status_bayar) == 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="unpaid" {{ old('status_bayar', $pembayaran->status_bayar) == 'unpaid' ? 'selected' : '' }}>Unpaid</option>
            </select>
            @error('status_bayar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="no_rekening" class="form-label">No Rekening</label>
            <input type="number" name="no_rekening" id="no_rekening" class="form-control @error('no_rekening') is-invalid @enderror" 
                value="{{ old('no_rekening', $pembayaran->no_rekening) }}">
            @error('no_rekening')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="tempat_bayar" class="form-label">Tempat Bayar</label>
            <input type="text" name="tempat_bayar" id="tempat_bayar" class="form-control @error('tempat_bayar') is-invalid @enderror" 
                value="{{ old('tempat_bayar', $pembayaran->tempat_bayar) }}">
            @error('tempat_bayar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <div class="mb-3">
            <label for="transaksi_id" class="form-label">Transaksi ID</label>
            <input type="text" name="transaksi_id" id="transaksi_id" class="form-control @error('transaksi_id') is-invalid @enderror" 
                value="{{ old('transaksi_id', $pembayaran->transaksi_id) }}">
            @error('transaksi_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Tanggal Bayar -->
        <div class="mb-3">
            <label for="tanggal_bayar" class="form-label">Tanggal Bayar</label>
            <input type="date" name="tanggal_bayar" id="tanggal_bayar" class="form-control @error('tanggal_bayar') is-invalid @enderror" 
                value="{{ old('tanggal_bayar', $pembayaran->tanggal_bayar) }}">
            @error('tanggal_bayar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Bukti Bayar -->
        <div class="mb-3">
            <label for="bukti_bayar" class="form-label">Bukti Bayar</label>
            <br>
            @if($pembayaran->bukti_bayar)
                <img src="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" alt="Bukti Bayar" style="max-width: 200px; margin-bottom: 10px;">
            @endif
            <input type="file" name="bukti_bayar" id="bukti_bayar" class="form-control @error('bukti_bayar') is-invalid @enderror">
            <small class="form-text text-muted">Kosongkan jika tidak ingin mengubah bukti bayar.</small>
            @error('bukti_bayar')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-primary">Update</button>
        <a href="{{ route('pembayaran.index') }}" class="btn btn-secondary">Batal</a>
    </form>
</div>
@endsection
