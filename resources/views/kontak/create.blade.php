@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Tambah Pesan Kontak')

@section('content')
<div class="card">
  <div class="card-body">
    <h5 class="card-title fw-semibold mb-4">Tambah Pesan Kontak</h5>

    <form action="{{ route('kontak.store') }}" method="POST">
      @csrf

      <div class="mb-3">
        <label for="nama" class="form-label">Nama</label>
        <input type="text" name="nama" id="nama" class="form-control @error('nama') is-invalid @enderror" value="{{ old('nama') }}" >
        @error('nama')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="email" class="form-label">Email</label>
        <input type="email" name="email" id="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" >
        @error('email')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="subjek" class="form-label">Subjek</label>
        <input type="text" name="subjek" id="subjek" class="form-control @error('subjek') is-invalid @enderror" value="{{ old('subjek') }}" >
        @error('subjek')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <div class="mb-3">
        <label for="pesan" class="form-label">Pesan</label>
        <textarea name="pesan" id="pesan" rows="5" class="form-control @error('pesan') is-invalid @enderror" >{{ old('pesan') }}</textarea>
        @error('pesan')
          <div class="invalid-feedback">{{ $message }}</div>
        @enderror
      </div>

      <button type="submit" class="btn btn-primary">Simpan</button>
      <a href="{{ route('kontak.index') }}" class="btn btn-secondary">Batal</a>
    </form>
  </div>
</div>
@endsection
