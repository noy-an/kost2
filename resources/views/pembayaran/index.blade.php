@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Pembayaran')

@section('content')
<div class="card shadow-sm rounded-3 border-0">
  <div class="card-body">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold text-dark mb-0">💰 Data Pembayaran</h4>
      <a href="{{ route('pembayaran.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
        <i class="ti ti-plus"></i> Tambah Data
      </a>
    </div>

    <!-- Search Form -->
    <form action="{{ route('pembayaran.index') }}" method="GET" class="mb-4">
      <div class="input-group">
        <span class="input-group-text bg-light border-end-0">
          <i class="ti ti-search text-secondary"></i>
        </span>
        <input type="text" name="search" class="form-control border-start-0"
               placeholder="Cari nama / metode / tempat bayar..." value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">Cari</button>
        @if(request('search'))
        <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-danger">
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
            <th>Booking</th>
            <th>Metode</th>
            <th>Status</th>
            <th>No. Rekening</th>
            <th>Tempat</th>
            <th>ID Transaksi</th>
            <th>Bukti</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pembayarans as $pembayaran)
          <tr>
            <td>{{ $pembayaran->id }}</td>
            <td class="text-start text-nowrap">
              <strong>User:</strong> {{ $pembayaran->booking->user->name ?? '-' }}<br>
              <strong>Kamar:</strong> {{ $pembayaran->booking->kost->nomor_kamar ?? '-' }}<br>
              <strong>Tgl:</strong> {{ \Carbon\Carbon::parse($pembayaran->booking->tgl_booking)->translatedFormat('d F Y') }}
            </td>
            <td>{{ $pembayaran->metode_bayar }}</td>
            <td>
              <span class="badge 
                @if($pembayaran->status_bayar === 'paid') bg-success
                @elseif($pembayaran->status_bayar === 'unpaid') bg-danger
                @else bg-warning text-dark
                @endif">
                {{ ucfirst($pembayaran->status_bayar) }}
              </span>
            </td>
            <td>{{ $pembayaran->no_rekening }}</td>
            <td>{{ $pembayaran->tempat_bayar }}</td>
            <td>{{ $pembayaran->transaksi_id }}</td>
            <td>
              @if($pembayaran->bukti_bayar)
                <img src="{{ asset('storage/' . $pembayaran->bukti_bayar) }}" alt="Bukti Bayar" width="50" height="50" class="rounded object-fit-cover">
              @else
                <span class="text-muted">-</span>
              @endif
            </td>
            <td>
              <div class="d-flex justify-content-center gap-1">
                <a href="{{ route('pembayaran.edit', $pembayaran->id) }}" class="btn btn-sm btn-warning" title="Edit">
                  <i class="ti ti-edit"></i>
                </a>
         <form action="{{ route('pembayaran.destroy', $pembayaran->id) }}" method="POST" class="d-inline form-delete">
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
            <td colspan="9" class="text-center text-muted py-4">
              <i class="ti ti-info-circle"></i> Belum ada data pembayaran tersedia.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
      {{ $pembayarans->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection
