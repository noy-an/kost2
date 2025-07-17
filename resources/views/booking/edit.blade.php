@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection
@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Edit Booking')

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-4">Edit Booking</h4>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('booking.update', $booking->id) }}" method="POST" id="booking-form">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="user_id" class="form-label">User</label>
          <select class="form-select" name="user_id" required>
            <option value="">-- Pilih User --</option>
            @foreach($users as $user)
              @if($user->role === 'pelanggan')
                <option value="{{ $user->id }}" {{ (old('user_id') ?? $booking->user_id) == $user->id ? 'selected' : '' }}>
                  {{ $user->name }}
                </option>
              @endif
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label for="kost_id" class="form-label">Kamar Kost</label>
          <select class="form-select" name="kost_id" id="kost_id" required>
            <option value="">-- Pilih Kamar --</option>
            @foreach($kosts as $kost)
              <option value="{{ $kost->id }}" data-harga="{{ $kost->harga }}" {{ (old('kost_id') ?? $booking->kost_id) == $kost->id ? 'selected' : '' }}>
                Kamar {{ $kost->nomor_kamar }} (Lantai {{ $kost->nomor_lantai }}) - Rp {{ number_format($kost->harga, 0, ',', '.') }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label for="dp_display" class="form-label">Nominal yang Harus Dibayar</label>
          <input type="text" class="form-control bg-light" id="dp_display" readonly>
        </div>

        <div class="col-md-6 mb-3">
          <label for="jenis_pembayaran" class="form-label">Jenis Pembayaran</label>
          <select class="form-select" name="jenis_pembayaran" required>
            <option value="">-- Pilih Jenis Pembayaran --</option>
            <option value="cash" {{ (old('jenis_pembayaran') ?? $booking->jenis_pembayaran) == 'cash' ? 'selected' : '' }}>Cash</option>
            <option value="transfer_bank" {{ (old('jenis_pembayaran') ?? $booking->jenis_pembayaran) == 'transfer_bank' ? 'selected' : '' }}>Transfer Bank</option>
            <option value="qris" {{ (old('jenis_pembayaran') ?? $booking->jenis_pembayaran) == 'qris' ? 'selected' : '' }}>QRIS</option>
            <option value="debit_card" {{ (old('jenis_pembayaran') ?? $booking->jenis_pembayaran) == 'debit_card' ? 'selected' : '' }}>Debit Card</option>
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label for="tgl_booking" class="form-label">Tanggal Booking</label>
          <input type="date" class="form-control" name="tgl_booking" value="{{ old('tgl_booking') ?? $booking->tgl_booking }}" required>
        </div>

      </div>

      <div class="d-flex justify-content-end">
        <a href="{{ route('booking.index') }}" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary">Update</button>
      </div>
    </form>
  </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const kostSelect = document.getElementById('kost_id');
    const dpDisplay = document.getElementById('dp_display');

    function updateHarga() {
        const selected = kostSelect.options[kostSelect.selectedIndex];
        if (selected && selected.value) {
            const harga = parseFloat(selected.getAttribute('data-harga')) || 0;
            dpDisplay.value = 'Rp ' + harga.toLocaleString('id-ID');
        } else {
            dpDisplay.value = '';
        }
    }

    kostSelect.addEventListener('change', updateHarga);
    updateHarga();

    // Set min tanggal
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="tgl_booking"]').setAttribute('min', today);
});
</script>
@endsection
