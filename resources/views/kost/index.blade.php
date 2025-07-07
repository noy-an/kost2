@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Kost')

@section('content')
<div class="card shadow-sm rounded-3 border-0">
  <div class="card-body">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold text-dark mb-0">🏠 Data Kamar Kost</h4>
      <a href="{{ route('kost.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
        <i class="ti ti-plus"></i> Tambah Kost
      </a>
    </div>

    <!-- Search Form -->
    <form action="{{ route('kost.index') }}" method="GET" class="mb-4">
      <div class="input-group">
        <span class="input-group-text bg-light border-end-0">
          <i class="ti ti-search text-secondary"></i>
        </span>
        <input type="text" name="search" class="form-control border-start-0"
               placeholder="Cari nomor kamar / status..." value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">
          Cari
        </button>
        @if(request('search'))
        <a href="{{ route('kost.index') }}" class="btn btn-outline-danger">
          <i class="ti ti-x"></i> Reset
        </a>
        @endif
      </div>
    </form>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr class="text-nowrap">
            <th>ID</th>
            <th>No. Kamar</th>
            <th>Lantai</th>
            <th>Harga</th>
            <th>Ukuran</th>
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
            <td class="text-end">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
            <td class="text-wrap text-start">{{ $item->ukuran_ruangan ?? '-' }}</td>
            <td class="text-wrap text-start">{{ $item->deskripsi ?? '-' }}</td>
            <td>
              <span class="badge 
                @if($item->status === 'kosong') bg-success
                @elseif($item->status === 'terisi') bg-danger
                @else bg-warning text-dark
                @endif">
                {{ ucfirst($item->status) }}
              </span>
            </td>
            <td class="text-wrap text-start">{{ $item->fasilitas ?? '-' }}</td>
            <td>
              <img src="{{ asset('storage/' . $item->foto1) }}" alt="Foto 1" width="50" height="50" class="rounded object-fit-cover">
            </td>
            <td>
              <img src="{{ asset('storage/' . $item->foto2) }}" alt="Foto 2" width="50" height="50" class="rounded object-fit-cover">
            </td>
            <td>
              <img src="{{ asset('storage/' . $item->foto3) }}" alt="Foto 3" width="50" height="50" class="rounded object-fit-cover">
            </td>
            <td>
              <div class="d-flex justify-content-center gap-1">
                <a href="{{ route('kost.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                  <i class="ti ti-edit"></i>
                </a>
              <form action="{{ route('kost.destroy', $item->id) }}" method="POST" class="d-inline form-delete">
  @csrf
  @method('DELETE')
  <button type="button" class="btn btn-sm btn-danger btn-delete" data-id="{{ $item->id }}">
    <i class="ti ti-trash"></i>
  </button>
</form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="11" class="text-center text-muted py-4">
              <i class="ti ti-info-circle"></i> Tidak ada data kost tersedia.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
      {{ $kost->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>

@endsection
