@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection
@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Edit Kost')

@section('content')
<div class="card">
  <div class="card-body">
    <h4 class="card-title mb-4">Edit Data Kost</h4>

    @if($errors->any())
      <div class="alert alert-danger">
        <ul class="mb-0">
          @foreach($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('kost.update', $kost->id) }}" method="POST" enctype="multipart/form-data">
      @csrf
      @method('PUT')

      <div class="row">
        <div class="col-md-6 mb-3">
          <label for="nomor_kamar" class="form-label">Nomor Kamar</label>
          <input type="number" class="form-control" name="nomor_kamar" value="{{ old('nomor_kamar', $kost->nomor_kamar) }}" required>
        </div>

        <div class="col-md-6 mb-3">
          <label for="nomor_lantai" class="form-label">Nomor Lantai</label>
          <input type="number" class="form-control" name="nomor_lantai" value="{{ old('nomor_lantai', $kost->nomor_lantai) }}" required>
        </div>

        <div class="col-md-6 mb-3">
          <label for="harga" class="form-label">Harga (Rp)</label>
          <input type="number" class="form-control" name="harga" value="{{ old('harga', $kost->harga) }}" required>
        </div>

        <div class="col-md-6 mb-3">
          <label for="status" class="form-label">Status</label>
          <select class="form-select" name="status" required>
            <option value="">-- Pilih Status --</option>
            <option value="kosong" {{ old('status', $kost->status) === 'kosong' ? 'selected' : '' }}>Kosong</option>
            <option value="terisi" {{ old('status', $kost->status) === 'terisi' ? 'selected' : '' }}>Terisi</option>
            <option value="dibooking" {{ old('status', $kost->status) === 'dibooking' ? 'selected' : '' }}>Dibooking</option>
          </select>
        </div>

        <div class="col-md-12 mb-3">
          <label for="deskripsi" class="form-label">Deskripsi</label>
          <textarea class="form-control" name="deskripsi" rows="3">{{ old('deskripsi', $kost->deskripsi) }}</textarea>
        </div>

        <div class="col-md-12 mb-3">
          <label for="fasilitas" class="form-label">Fasilitas</label>
          <input type="text" class="form-control" name="fasilitas" value="{{ old('fasilitas', $kost->fasilitas) }}">
        </div>

        @foreach (['foto1', 'foto2', 'foto3'] as $foto)
        <div class="col-md-4 mb-3">
          <label class="form-label">{{ ucfirst($foto) }}</label><br>
          <img src="{{ asset('storage/' . $kost->$foto) }}" alt="{{ $foto }}" class="img-fluid rounded mb-2" style="max-height: 150px;">
          <input type="file" class="form-control" name="{{ $foto }}">
        </div>
        @endforeach
      </div>

      <div class="d-flex justify-content-end">
        <a href="{{ route('kost.index') }}" class="btn btn-secondary me-2">Batal</a>
        <button type="submit" class="btn btn-success">Update</button>
      </div>
    </form>
  </div>
</div>
@endsection
