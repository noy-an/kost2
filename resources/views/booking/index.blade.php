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
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold text-dark mb-0">📅 Data Booking</h4>
      <a href="{{ route('booking.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
        <i class="ti ti-plus"></i> Tambah Booking
      </a>
    </div>

    <form action="{{ route('booking.index') }}" method="GET" class="mb-4">
      <div class="input-group">
        <span class="input-group-text bg-light border-end-0">
          <i class="ti ti-search text-secondary"></i>
        </span>
        <input type="text" name="search" class="form-control border-start-0"
               placeholder="Cari nama user / kamar..." value="{{ request('search') }}">
        <button type="submit" class="btn btn-outline-secondary">Cari</button>
        @if(request('search'))
        <a href="{{ route('booking.index') }}" class="btn btn-outline-danger">
          <i class="ti ti-x"></i> Reset
        </a>
        @endif
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr class="text-nowrap">
            <th>No</th>
            <th>Nama User</th>
            <th>No. Kamar</th>
            <th>DP</th>
            <th>Tanggal Booking</th>
            <th>Status</th>
            <th class="text-nowrap">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bookings as $index => $booking)
          <tr>
            <td>{{ $index + $bookings->firstItem() }}</td>
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

    <div class="d-flex justify-content-end mt-3">
      {{ $bookings->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>

<!-- SweetAlert Delete Confirmation -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    const deleteButtons = document.querySelectorAll('.btn-delete');
    deleteButtons.forEach(button => {
      button.addEventListener('click', function () {
        const form = this.closest('form');
        Swal.fire({
          title: 'Yakin ingin menghapus data ini?',
          text: "Data booking akan dihapus secara permanen.",
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#3085d6',
          confirmButtonText: 'Hapus',
          cancelButtonText: 'Batal'
@push('scripts')
<script>
  document.addEventListener("DOMContentLoaded", function () {
    const deleteButtons = document.querySelectorAll(".btn-delete");

    deleteButtons.forEach(button => {
      button.addEventListener("click", function (e) {
        e.preventDefault();
        const form = this.closest("form");

        Swal.fire({
          title: "Yakin ingin menghapus?",
          text: "Data tidak bisa dikembalikan setelah dihapus!",
          icon: "warning",
          showCancelButton: true,
          confirmButtonColor: "#d33",
          cancelButtonColor: "#6c757d",
          confirmButtonText: "Ya, hapus!",
          cancelButtonText: "Batal"
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });
  });
</script>
@endpush
@endsection
