@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Tagihan')

@section('content')
<div class="card shadow-sm rounded-3 border-0">
  <div class="card-body">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold text-dark mb-0">📋 Data Tagihan</h4>
      <a href="{{ route('tagihan.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
        <i class="ti ti-plus"></i> Tambah Tagihan
      </a>
    </div>

    <!-- Search Form -->
    <form action="{{ route('tagihan.index') }}" method="GET" class="mb-4">
      <div class="input-group">
        <span class="input-group-text bg-light border-end-0">
          <i class="ti ti-search text-secondary"></i>
        </span>
        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama user / kamar..." value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">
          Cari
        </button>
        @if(request('search'))
        <a href="{{ route('tagihan.index') }}" class="btn btn-outline-danger">
          <i class="ti ti-x"></i> Reset
        </a>
        @endif
      </div>
    </form>>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr class="text-nowrap">
            <th>#</th>
            <th>Nama User</th>
            <th>Kost</th>
            <th>Bayar / Bulan</th>
            <th>Jumlah Bayar</th>
            <th>Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tagihans as $tagihan)
          <tr>
            <td>{{ $tagihan->id }}</td>
            <td>{{ $tagihan->booking->user->name ?? '-' }}</td>
            <td class="text-start">
              <div><strong>Kamar:</strong> {{ $tagihan->booking->kost->nomor_kamar ?? '-' }}</div>
              <div><strong>Lantai:</strong> {{ $tagihan->booking->kost->nomor_lantai ?? '-' }}</div>
            </td>
            <td class="text-end">Rp{{ number_format($tagihan->bayar_per_bulan, 0, ',', '.') }}</td>
            <td class="text-end">Rp{{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</td>
            <td>{{ \Carbon\Carbon::parse($tagihan->created_at)->translatedFormat('d F Y') }}</td>
            <td>
              <div class="d-flex justify-content-center gap-1">
                <a href="{{ route('tagihan.edit', $tagihan->id) }}" class="btn btn-sm btn-warning" title="Edit">
                  <i class="ti ti-edit"></i>
                </a>
           @extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Tagihan')

@section('content')
<div class="card shadow-sm rounded-3 border-0">
  <div class="card-body">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold text-dark mb-0">📋 Data Tagihan</h4>
      <a href="{{ route('tagihan.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
        <i class="ti ti-plus"></i> Tambah Tagihan
      </a>
    </div>

    <!-- Search Form -->
    <form action="{{ route('tagihan.index') }}" method="GET" class="mb-4">
      <div class="input-group">
        <span class="input-group-text bg-light border-end-0">
          <i class="ti ti-search text-secondary"></i>
        </span>
        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nama user / kamar..." value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">
          Cari
        </button>
        @if(request('search'))
        <a href="{{ route('tagihan.index') }}" class="btn btn-outline-danger">
          <i class="ti ti-x"></i> Reset
        </a>
        @endif
      </div>
    </form>>

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr class="text-nowrap">
            <th>#</th>
            <th>Nama User</th>
            <th>Kost</th>
            <th>Bayar / Bulan</th>
            <th>Jumlah Bayar</th>
            <th>Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tagihans as $tagihan)
          <tr>
            <td>{{ $tagihan->id }}</td>
            <td>{{ $tagihan->booking->user->name ?? '-' }}</td>
            <td class="text-start">
              <div><strong>Kamar:</strong> {{ $tagihan->booking->kost->nomor_kamar ?? '-' }}</div>
              <div><strong>Lantai:</strong> {{ $tagihan->booking->kost->nomor_lantai ?? '-' }}</div>
            </td>
            <td class="text-end">Rp{{ number_format($tagihan->bayar_per_bulan, 0, ',', '.') }}</td>
            <td class="text-end">Rp{{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</td>
            <td>{{ \Carbon\Carbon::parse($tagihan->created_at)->translatedFormat('d F Y') }}</td>
            <td>
              <div class="d-flex justify-content-center gap-1">
                <a href="{{ route('tagihan.edit', $tagihan->id) }}" class="btn btn-sm btn-warning" title="Edit">
                  <i class="ti ti-edit"></i>
                </a>
                <form action="{{ route('tagihan.destroy', $item->id) }}" method="POST" class="d-inline">
  @csrf
  @method('DELETE')
  <button type="submit" class="btn btn-sm btn-danger">
    <i class="ti ti-trash"></i>
  </button>
</form>

              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">
              <i class="ti ti-info-circle"></i> Belum ada data tagihan.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
      {{ $tagihans->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection


              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-center text-muted py-4">
              <i class="ti ti-info-circle"></i> Belum ada data tagihan.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
      {{ $tagihans->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection
