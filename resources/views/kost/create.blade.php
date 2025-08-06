@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection
@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Tambah Kost')

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-4">Tambah Data Kost</h4>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('kost.store') }}" method="POST" enctype="multipart/form-data">
      @csrf

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="nomor_kamar" class="form-label">Nomor Kamar</label>
          <input type="number" class="form-control" name="nomor_kamar" value="{{ old('nomor_kamar') }}" >
        </div>

        <div class="col-md-6 mb-3">
          <label for="nomor_lantai" class="form-label">Nomor Lantai</label>
          <input type="number" class="form-control" name="nomor_lantai" value="{{ old('nomor_lantai') }}" >
        </div>
        
        <div class="col-md-6 mb-3">
          <label for="harga" class="form-label">Harga (Rp)</label>
          <input type="number" class="form-control" name="harga" value="{{ old('harga') }}" >
        </div>

        <div class="col-md-6 mb-3">
          <label for="air" class="form-label">Biaya Air (Rp)</label>
          <input type="number" class="form-control" name="air" value="{{ old('air') }}" >
        </div>

        <div class="col-md-6 mb-3">
          <label for="kebersihan" class="form-label">Biaya Kebersihan (Rp)</label>
          <input type="number" class="form-control" name="kebersihan" value="{{ old('kebersihan') }}" >
        </div>

        <div class="col-md-6 mb-3">
          <label for="ukuran_ruangan" class="form-label">Ukuran Ruangan</label>
          <input type="text" class="form-control" name="ukuran_ruangan" value="{{ old('ukuran_ruangan') }}" >
        </div>

        <div class="col-md-6 mb-3">
          <label for="status" class="form-label">Status</label>
          <select class="form-select" name="status" >
            <option value="">-- Pilih Status --</option>
            <option value="kosong" {{ old('status') === 'kosong' ? 'selected' : '' }}>Kosong</option>
            <option value="terisi" {{ old('status') === 'terisi' ? 'selected' : '' }}>Terisi</option>
            <option value="dibooking" {{ old('status') === 'dibooking' ? 'selected' : '' }}>Dibooking</option>
          </select>
        </div>

        <div class="col-md-12 mb-3">
          <label for="deskripsi" class="form-label">Deskripsi</label>
          <textarea class="form-control" name="deskripsi" rows="3">{{ old('deskripsi') }}</textarea>
        </div>

        <div class="col-md-12 mb-3">
          <label for="fasilitas" class="form-label">Fasilitas</label>
          <input type="text" class="form-control" name="fasilitas" value="{{ old('fasilitas') }}">
        </div>

        <div class="col-md-4 mb-3">
          <label for="foto1" class="form-label">Foto 1</label>
          <input type="file" class="form-control" name="foto1" >
        </div>

        <div class="col-md-4 mb-3">
          <label for="foto2" class="form-label">Foto 2</label>
          <input type="file" class="form-control" name="foto2" >
        </div>

        <div class="col-md-4 mb-3">
          <label for="foto3" class="form-label">Foto 3</label>
          <input type="file" class="form-control" name="foto3" >
        </div>
      </div>

      <div class="d-flex justify-content-end">
        <a href="{{ route('kost.index') }}" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-primary">Simpan</button>
      </div>
    </form>
  </div>
</div>
@endsection