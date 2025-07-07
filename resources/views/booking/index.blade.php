@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Booking')

@section('content')
<div class="card shadow-sm rounded-3 border-0">
  <div class="card-body">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold text-dark mb-0">📅 Data Booking</h4>
      <a href="{{ route('booking.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
        <i class="ti ti-plus"></i> Tambah Booking
      </a>
    </div>

    <!-- Search Form -->
    <form action="{{ route('booking.index') }}" method="GET" class="mb-4">
      <div class="input-group">
        <span class="input-group-text bg-light border-end-0">
          <i class="ti ti-search text-secondary"></i>
        </span>
        <input type="text" name="search" class="form-control border-start-0"
               placeholder="Cari nama user / kamar..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-outline-secondary">
          Cari
        </button>
        @if(request('search'))
        <a href="{{ route('booking.index') }}" class="btn btn-outline-danger">
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
            <th>Nama User</th>
            <th>No. Kamar</th>
            <th>DP</th>
            <th>Tanggal Booking</th>
            <th>Status</th>
            <th class="text-nowrap">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bookings as $booking)
          <tr>
            <td>{{ $booking->id }}</td>
            <td>{{ $booking->user->name ?? '-' }}</td>
            <td>{{ $booking->kost->nomor_kamar ?? '-' }}</td>
            <td class="text-end">Rp{{ number_format($booking->dp, 0, ',', '.') }}</td>
            <td>{{ \Carbon\Carbon::parse($booking->tgl_booking)->translatedFormat('d F Y') }}</td>
            <td>
              <span class="badge 
                @if($booking->status_booking === 'pending') bg-warning text-dark
                @elseif($booking->status_booking === 'confirmed') bg-success
                @else bg-secondary
                @endif">
                {{ ucfirst($booking->status_booking) }}
              </span>
            </td>
            <td>
              <div class="d-flex justify-content-center gap-1">
                <a href="{{ route('booking.edit', $booking->id) }}" class="btn btn-sm btn-warning" title="Edit">
                  <i class="ti ti-edit"></i>
                </a>
        <form action="{{ route('booking.destroy', $booking->id) }}" method="POST" class="d-inline form-delete">
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
              <i class="ti ti-info-circle"></i> Belum ada data booking.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
      {{ $bookings->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection
