@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection
@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Booking')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="card-title m-0">Daftar Booking</h4>
      <a href="{{ route('booking.create') }}" class="btn btn-primary">+ Tambah Booking</a>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive" style="overflow-x: auto; white-space:nowrap">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Nama User</th>
            <th>No. Kamar</th>
            <th>DP (Rp)</th>
            <th>Tanggal Booking</th>
            <th>Status</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($bookings as $booking)
          <tr>
            <td>{{ $booking->id }}</td>
            <td>{{ $booking->user->name ?? '-' }}</td>
            <td>{{ $booking->kost->nomor_kamar ?? '-' }}</td>
            <td>Rp{{ number_format($booking->dp, 0, ',', '.') }}</td>
            <td>{{ \Carbon\Carbon::parse($booking->tgl_booking)->translatedFormat('d F Y') }}</td>
            <td>
              <span class="badge 
                @if($booking->status_booking === 'pending') bg-warning text-dark 
                @else bg-success 
                @endif">
                {{ ucfirst($booking->status_booking) }}
              </span>
            </td>
            <td>
              <a href="{{ route('booking.edit', $booking->id) }}" class="btn btn-sm btn-warning">Edit</a>
              <form action="{{ route('booking.destroy', $booking->id) }}" method="POST" style="display: inline-block;">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm('Yakin ingin menghapus data ini?')">Hapus</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-muted">Belum ada data booking.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    
    <div class="d-flex justify-content-end mt-3">
      {{ $bookings->links() }}
    </div>
  </div>
</div>
@endsection
