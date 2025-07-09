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
          <label class="form-label">Persentase Pembayaran</label>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="persentase_pembayaran" id="dp30" value="30" 
                   {{ (old('persentase_pembayaran') ?? $booking->persentase_pembayaran) == '30' ? 'checked' : '' }}>
            <label class="form-check-label" for="dp30">
              DP 30% - <span id="dp30-amount">Rp 0</span>
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="persentase_pembayaran" id="dp40" value="40"
                   {{ (old('persentase_pembayaran') ?? $booking->persentase_pembayaran) == '40' ? 'checked' : '' }}>
            <label class="form-check-label" for="dp40">
              DP 40% - <span id="dp40-amount">Rp 0</span>
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="persentase_pembayaran" id="dp50" value="50"
                   {{ (old('persentase_pembayaran') ?? $booking->persentase_pembayaran) == '50' ? 'checked' : '' }}>
            <label class="form-check-label" for="dp50">
              DP 50% - <span id="dp50-amount">Rp 0</span>
            </label>
          </div>
          <div class="form-check">
            <input class="form-check-input" type="radio" name="persentase_pembayaran" id="bayar_penuh" value="100"
                   {{ (old('persentase_pembayaran') ?? $booking->persentase_pembayaran) == '100' ? 'checked' : '' }}>
            <label class="form-check-label" for="bayar_penuh">
              Bayar Penuh - <span id="bayar_penuh-amount">Rp 0</span>
            </label>
          </div>
        </div>

        <div class="col-md-6 mb-3">
          <label for="dp_display" class="form-label">Nominal yang akan dibayar</label>
          <input type="text" class="form-control bg-light" id="dp_display" disabled>
        </div>

        <div class="col-md-6 mb-3">
          <label for="jenis_pembayaran" class="form-label">Jenis Pembayaran</label>
          <select class="form-select" name="jenis_pembayaran" required>
            <option value="">-- Pilih Jenis Pembayaran --</option>
            <option value="cash" {{ (old('jenis_pembayaran') ?? $booking->jenis_pembayaran) == 'cash' ? 'selected' : '' }}>
              <i class="fas fa-money-bill-wave"></i> Cash (Tunai)
            </option>
            <option value="transfer_bank" {{ (old('jenis_pembayaran') ?? $booking->jenis_pembayaran) == 'transfer_bank' ? 'selected' : '' }}>
              <i class="fas fa-university"></i> Transfer Bank
            </option>
            <option value="qris" {{ (old('jenis_pembayaran') ?? $booking->jenis_pembayaran) == 'qris' ? 'selected' : '' }}>
              <i class="fas fa-qrcode"></i> QRIS
            </option>
            <option value="debit_card" {{ (old('jenis_pembayaran') ?? $booking->jenis_pembayaran) == 'debit_card' ? 'selected' : '' }}>
              <i class="fas fa-credit-card"></i> Debit Card
            </option>
          </select>
          <small class="form-text text-muted">
            Pilih metode pembayaran offline yang digunakan pelanggan
          </small>
        </div>

        <div class="col-md-6 mb-3">
          <label for="tgl_booking" class="form-label">Tanggal Booking</label>
          <input type="date" class="form-control" name="tgl_booking" value="{{ old('tgl_booking') ?? $booking->tgl_booking }}" required>
        </div>

        <div class="col-md-6 mb-3">
          <label for="status_booking" class="form-label">Status Booking</label>
          <select class="form-select" name="status_booking" required>
            <option value="pending" {{ (old('status_booking') ?? $booking->status_booking) == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="disetujui" {{ (old('status_booking') ?? $booking->status_booking) == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
          </select>
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
    const radioButtons = document.querySelectorAll('input[name="persentase_pembayaran"]');
    
    function updateDPAmounts() {
        const selectedKost = kostSelect.options[kostSelect.selectedIndex];
        
        if (selectedKost.value) {
            const harga = parseFloat(selectedKost.getAttribute('data-harga'));
            
            // Update amounts for each percentage
            document.getElementById('dp30-amount').textContent = 'Rp ' + (harga * 0.3).toLocaleString('id-ID');
            document.getElementById('dp40-amount').textContent = 'Rp ' + (harga * 0.4).toLocaleString('id-ID');
            document.getElementById('dp50-amount').textContent = 'Rp ' + (harga * 0.5).toLocaleString('id-ID');
            document.getElementById('bayar_penuh-amount').textContent = 'Rp ' + harga.toLocaleString('id-ID');
            
            // Update display field
            updateDPDisplay();
        } else {
            // Reset all amounts
            document.getElementById('dp30-amount').textContent = 'Rp 0';
            document.getElementById('dp40-amount').textContent = 'Rp 0';
            document.getElementById('dp50-amount').textContent = 'Rp 0';
            document.getElementById('bayar_penuh-amount').textContent = 'Rp 0';
            dpDisplay.value = '';
        }
    }
    
    function updateDPDisplay() {
        const selectedKost = kostSelect.options[kostSelect.selectedIndex];
        const selectedPercentage = document.querySelector('input[name="persentase_pembayaran"]:checked');
        
        if (selectedKost.value && selectedPercentage) {
            const harga = parseFloat(selectedKost.getAttribute('data-harga'));
            const percentage = parseFloat(selectedPercentage.value);
            const dpAmount = harga * (percentage / 100);
            
            dpDisplay.value = 'Rp ' + dpAmount.toLocaleString('id-ID');
        } else {
            dpDisplay.value = '';
        }
    }
    
    // Event listeners
    kostSelect.addEventListener('change', updateDPAmounts);
    
    radioButtons.forEach(radio => {
        radio.addEventListener('change', updateDPDisplay);
    });
    
    // Initial update
    updateDPAmounts();
    
    // Set minimum date to today
    const today = new Date().toISOString().split('T')[0];
    document.querySelector('input[name="tgl_booking"]').setAttribute('min', today);
});
</script>
@endsection