@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Pembayaran')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title fw-semibold">Data Pembayaran</h5>
      <a href="{{ route('pembayaran.create') }}" class="btn btn-primary btn-sm">
        <i class="ti ti-plus"></i> Tambah Data
      </a>
    </div>

    <div class="table-responsive" style="overflow-x: auto; white-space:nowrap">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Booking</th>
            <th>Metode Bayar</th>
            <th>Status Bayar</th>
            <th>No Rekening</th>
            <th>Tempat Bayar</th>
            <th>Transaksi ID</th>
            <th>Bukti Bayar</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($pembayarans as $pembayaran)
          <tr>
            <td>{{ $pembayaran->id }}</td>
            <td>
              User: {{ $pembayaran->booking->user->name ?? '-' }}<br>
              Kamar: {{ $pembayaran->booking->kost->nomor_kamar ?? '-' }}<br>
              Tgl Booking: {{ $pembayaran->booking->tgl_booking ?? '-' }}
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
                -
              @endif
            </td>
            <td>
              <a href="{{ route('pembayaran.edit', $pembayaran->id) }}" class="btn btn-sm btn-warning me-1">
                <i class="ti ti-edit"></i>
              </a>
              <form action="{{ route('pembayaran.destroy', $pembayaran->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
            <td colspan="11" class="text-muted text-center">Belum ada data pembayaran tersedia.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $pembayarans->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection
