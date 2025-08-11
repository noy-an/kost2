@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Tambah Pengguna')

@section('content')
<div class="card shadow-sm rounded-3 border-0">
  <div class="card-body p-4">
    <h4 class="fw-bold mb-4 text-dark">➕ Tambah Data Pengguna</h4>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('pengguna.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row g-3">
        <div class="col-md-6">
          <label class="form-label">Nama Lengkap</label>
          <input type="text" class="form-control shadow-sm" name="name" value="{{ old('name') }}" required>
        </div>

       

        <div class="col-md-6">
          <label class="form-label">No HP</label>
          <div class="input-group shadow-sm">
            <span class="input-group-text bg-light border-end-0">
              <i class="ti ti-phone text-secondary"></i>
            </span>
            <input type="number"
                   class="form-control border-start-0"
                   name="phone"
                   placeholder="Contoh: 081234567890"
                   pattern="[0-9]{10,15}"
                   title="Nomor HP hanya angka, 10-15 digit"
                   value="{{ old('phone') }}">
          </div>
        </div>

        <div class="col-md-6">
          <label class="form-label">Role</label>
          <select class="form-select shadow-sm" name="role" required>
            <option value="">-- Pilih Role --</option>
            <option value="admin" {{ old('role') === 'admin' ? 'selected' : '' }}>Admin</option>
            <option value="pemilik" {{ old('role') === 'pemilik' ? 'selected' : '' }}>Pemilik</option>
          </select>
        </div>

        <div class="col-md-6">
          <label class="form-label">Password</label>
          <input type="password" class="form-control shadow-sm" name="password" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Konfirmasi Password</label>
          <input type="password" class="form-control shadow-sm" name="password_confirmation" required>
        </div>

        <div class="col-md-6">
          <label class="form-label">Foto Profil (Avatar)</label>
          <input type="file" class="form-control shadow-sm" name="avatar" accept="image/*">
          <small class="text-muted">Maks 5MB, JPG/PNG.</small>
        </div>

        <div class="col-md-6">
          <label class="form-label">Foto KTP/ID Card</label>
          <input type="file" class="form-control shadow-sm" name="id_card" accept="image/*">
          <small class="text-muted">Maks 5MB, JPG/PNG.</small>
        </div>
      </div>

      <div class="d-flex justify-content-end mt-4 gap-2">
        <a href="{{ route('pengguna.index') }}" class="btn btn-outline-secondary">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection
