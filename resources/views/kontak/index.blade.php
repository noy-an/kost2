@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Kontak')

@section('content')
<div class="card shadow-sm rounded-3 border-0">
  <div class="card-body">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold text-dark mb-0">📩 Data Kontak</h4>
      <a href="{{ route('kontak.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
        <i class="ti ti-plus"></i> Tambah Pesan
      </a>
    </div>

    <!-- Search Form -->
    <form action="{{ route('kontak.index') }}" method="GET" class="mb-4">
      <div class="input-group">
        <span class="input-group-text bg-light border-end-0">
          <i class="ti ti-search text-secondary"></i>
        </span>
        <input type="text" name="search" class="form-control border-start-0"
               placeholder="Cari nama / email / subjek..." value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">Cari</button>
        @if(request('search'))
          <a href="{{ route('kontak.index') }}" class="btn btn-outline-danger">
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
            <th>Nama</th>
            <th>Email</th>
            <th>Subjek</th>
            <th>Pesan</th>
            <th>Dibuat</th>
            <th class="text-nowrap">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kontaks as $kontak)
          <tr>
            <td>{{ $kontak->id }}</td>
            <td>{{ $kontak->nama }}</td>
            <td>{{ $kontak->email }}</td>
            <td>{{ $kontak->subjek }}</td>
            <td class="text-start">{{ Str::limit(strip_tags($kontak->pesan), 60) }}</td>
            <td>{{ \Carbon\Carbon::parse($kontak->created_at)->translatedFormat('d F Y H:i') }}</td>
            <td>
              <div class="d-flex justify-content-center gap-1">
                <a href="{{ route('kontak.edit', $kontak->id) }}" class="btn btn-sm btn-warning" title="Edit">
                  <i class="ti ti-edit"></i>
                </a>
              <form action="{{ route('kontak.destroy', $kontak->id) }}" method="POST" class="d-inline form-delete">
  @csrf
  @method('DELETE')
  <button type="button" class="btn btn-sm btn-danger btn-delete">
    <i class="ti ti-trash"></i>
  </button>
</form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">
              <i class="ti ti-info-circle"></i> Belum ada pesan tersedia.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
      {{ $kontaks->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection
