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
      <div class="d-flex gap-2">
        <button type="submit" form="bulkDeleteForm" class="btn btn-danger d-flex align-items-center gap-2 shadow-sm" id="btnBulkDelete">
          <i class="ti ti-trash"></i> Hapus Terpilih
        </button>
        <a href="{{ route('pembayaran.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
          <i class="ti ti-plus"></i> Tambah Data
        </a>
      </div>
    </div>

    <!-- Filter Form -->
    <form action="{{ route('pembayaran.index') }}" method="GET" class="mb-4">
      <div class="row g-2">
        <div class="col-md-3">
          <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
        </div>
        <div class="col-md-3">
          <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
        </div>
        <div class="col-md-3">
          <input type="date" name="tanggal_bayar" class="form-control" value="{{ request('tanggal_bayar') }}">
        </div>
        <div class="col-md-3">
          <input type="text" name="search" class="form-control" placeholder="Cari nama / metode / tempat bayar..." value="{{ request('search') }}">
        </div>
        <div class="col-12 d-flex gap-2 mt-2">
          <button type="submit" class="btn btn-outline-primary">
            <i class="ti ti-filter"></i> Filter
          </button>
          @if(request('search') || request('dari') || request('sampai') || request('tanggal_bayar'))
          <a href="{{ route('pembayaran.index') }}" class="btn btn-outline-danger">
            <i class="ti ti-x"></i> Reset
          </a>
          @endif
        </div>
      </div>
    </form>

    <!-- Bulk Delete Form -->
    <form action="{{ route('pembayaran.bulk-delete') }}" method="POST" id="bulkDeleteForm">
      @csrf
      @method('DELETE')

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
          <thead class="table-light">
            <tr class="text-nowrap">
              <th><input type="checkbox" id="checkAll"></th>
              <th>No</th>
              <th>Booking</th>
              <th>Metode</th>
              <th>Status</th>
              <th>No. Rekening</th>
              <th>Tempat</th>
              <th>ID Transaksi</th>
              <th>Tgl Bayar</th>
              <th>Bukti</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($pembayarans as $no => $pembayaran)
            <tr>
              <td><input type="checkbox" name="ids[]" value="{{ $pembayaran->id }}" class="checkItem"></td>
              <td>{{ $no + $pembayarans->firstItem() }}</td>
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
                {{ $pembayaran->tanggal_bayar ? \Carbon\Carbon::parse($pembayaran->tanggal_bayar)->translatedFormat('d F Y') : '-' }}
              </td>
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
                  {{-- <form action="{{ route('pembayaran.destroy', $pembayaran->id) }}" method="POST" class="d-inline">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus">
                      <i class="ti ti-trash"></i>
                    </button>
                  </form> --}}
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="11" class="text-center text-muted py-4">
                <i class="ti ti-info-circle"></i> Belum ada data pembayaran tersedia.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </form>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
      {{ $pembayarans->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
  // SweetAlert individual delete
  document.querySelectorAll(".btn-delete").forEach(button => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const form = this.closest("form");

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Data pembayaran akan dihapus permanen!",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then((result) => {
        if (result.isConfirmed) {
          form.submit();
        }
      });
    });
  });

  // Checkbox: pilih semua
  document.getElementById('checkAll').addEventListener('change', function () {
    document.querySelectorAll('.checkItem').forEach(cb => cb.checked = this.checked);
  });

  // SweetAlert bulk delete
  document.getElementById('btnBulkDelete').addEventListener('click', function (e) {
    e.preventDefault();
    const selected = document.querySelectorAll('.checkItem:checked');
    if (selected.length === 0) {
      Swal.fire('Tidak ada data terpilih!', 'Pilih minimal satu pembayaran untuk dihapus.', 'warning');
      return;
    }

    Swal.fire({
      title: 'Hapus data terpilih?',
      text: `Sebanyak ${selected.length} data akan dihapus permanen.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#6c757d',
      confirmButtonText: 'Ya, hapus semua!',
      cancelButtonText: 'Batal'
    }).then((result) => {
      if (result.isConfirmed) {
        document.getElementById('bulkDeleteForm').submit();
      }
    });
  });
});
</script>
@endpush
