@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection
@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Tambah Booking')

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-4">Tambah Booking Baru</h4>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('booking.store') }}" method="POST">
      @csrf

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="user_id" class="form-label">User</label>
          <select class="form-select" name="user_id" >
            <option value="">-- Pilih User --</option>
            @foreach($users as $user)
              <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                {{ $user->name }}
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label for="kost_id" class="form-label">Kamar Kost</label>
          <select class="form-select" name="kost_id" >
            <option value="">-- Pilih Kamar --</option>
            @foreach($kosts as $kost)
              <option value="{{ $kost->id }}" {{ old('kost_id') == $kost->id ? 'selected' : '' }}>
                Kamar {{ $kost->nomor_kamar }} (Lantai {{ $kost->nomor_lantai }})
              </option>
            @endforeach
          </select>
        </div>

        <div class="col-md-6 mb-3">
          <label for="dp" class="form-label">DP (Uang Muka)</label>
          <input type="number" class="form-control" name="dp" value="{{ old('dp') }}" >
        </div>

        <div class="col-md-6 mb-3">
          <label for="tgl_booking" class="form-label">Tanggal Booking</label>
          <input type="date" class="form-control" name="tgl_booking" value="{{ old('tgl_booking') }}" >
        </div>

        <div class="col-md-6 mb-3">
          <label for="status_booking" class="form-label">Status Booking</label>
          <select class="form-select" name="status_booking" >
            <option value="pending" {{ old('status_booking') == 'pending' ? 'selected' : '' }}>Pending</option>
            <option value="disetujui" {{ old('status_booking') == 'disetujui' ? 'selected' : '' }}>Disetujui</option>
          </select>
        </div>
      </div>

      <div class="d-flex justify-content-end">
        <a href="{{ route('booking.index') }}" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
