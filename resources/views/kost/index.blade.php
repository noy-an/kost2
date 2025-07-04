@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection
@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Kost')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title fw-semibold">Data Kamar Kost</h5>
      <a href="{{ route('kost.create') }}" class="btn btn-primary btn-sm">
        <i class="ti ti-plus"></i> Tambah Data
      </a>
    </div>

    <div class="table-responsive" style="overflow-x: auto; white-space:nowrap">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Nomor Kamar</th>
            <th>Nomor Lantai</th>
            <th>Harga</th>
            <th>Deskripsi</th>
            <th>Status</th>
            <th>Fasilitas</th>
            <th>Foto 1</th>
            <th>Foto 2</th>
            <th>Foto 3</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kost as $item)
  <tr>
    <td>{{ $item->id }}</td>
    <td>{{ $item->nomor_kamar }}</td>
    <td>{{ $item->nomor_lantai }}</td>
    <td>Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
    <td>{{ $item->deskripsi ?? '-' }}</td>
    <td>
      <span class="badge 
        @if($item->status === 'kosong') bg-success 
        @elseif($item->status === 'terisi') bg-danger 
        @else bg-warning text-dark 
        @endif">
        {{ ucfirst($item->status) }}
      </span>
    </td>
    <td>{{ $item->fasilitas ?? '-' }}</td>
    <td><img src="{{ asset('storage/' . $item->foto1) }}" alt="foto1" width="50" height="50" class="rounded object-fit-cover"></td>
    <td><img src="{{ asset('storage/' . $item->foto2) }}" alt="foto2" width="50" height="50" class="rounded object-fit-cover"></td>
    <td><img src="{{ asset('storage/' . $item->foto3) }}" alt="foto3" width="50" height="50" class="rounded object-fit-cover"></td>
    <td>
      <a href="{{ route('kost.edit', $item->id) }}" class="btn btn-sm btn-warning me-1">
        <i class="ti ti-edit"></i>
      </a>
      <form action="{{ route('kost.destroy', $item->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-sm btn-danger">
          <i class="ti ti-trash"></i>
        </button>
      </form>
    </td>
  </tr>
@empty
<tr>
  <td colspan="13" class="text-muted text-center">Belum ada data kost tersedia.</td>
</tr>
@endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $kost->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection
