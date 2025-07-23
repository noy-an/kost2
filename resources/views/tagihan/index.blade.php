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
    <!-- Header Responsif dengan Tombol Aksi -->
    <div class="row justify-content-between align-items-center mb-4 g-2">
      <div class="col">
        <h4 class="fw-bold text-dark mb-0">📋 Data Tagihan</h4>
      </div>
      <div class="col-auto">
        <div class="d-flex flex-wrap justify-content-end gap-2">
          <a href="{{ route('tagihan.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
            <i class="ti ti-plus"></i> Tambah Tagihan
          </a>
          <button type="button" id="bulkDeleteBtn" class="btn btn-danger d-flex align-items-center gap-2 shadow-sm">
            <i class="ti ti-trash"></i> Hapus Terpilih
          </button>
        </div>
      </div>
    </div>

    <!-- Filter -->
    <form action="{{ route('tagihan.index') }}" method="GET" class="mb-4">
      <div class="row g-2">
        <div class="col-md-3">
          <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
        </div>
        <div class="col-md-3">
          <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
        </div>
        <div class="col-md-3">
          <input type="text" name="search" class="form-control" placeholder="Cari nama / kamar..." value="{{ request('search') }}">
        </div>
        <div class="col-md-3 d-flex gap-2">
          <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
          @if(request('search') || request('dari') || request('sampai'))
          <a href="{{ route('tagihan.index') }}" class="btn btn-outline-danger">
            <i class="ti ti-x"></i>
          </a>
          @endif
        </div>
      </div>
    </form>

    @if(request('dari') && request('sampai'))
    <p class="text-muted">
      Menampilkan tagihan dari <strong>{{ request('dari') }}</strong> sampai <strong>{{ request('sampai') }}</strong>
    </p>
    @endif

    <!-- Bulk Delete Form -->
    <form action="{{ route('tagihan.bulkDelete') }}" method="POST" id="bulkDeleteForm">
      @csrf
      @method('DELETE')

      <!-- Table -->
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
          <thead class="table-light">
            <tr>
              <th><input type="checkbox" id="selectAll"></th>
              <th>No</th>
              <th>Nama User</th>
              <th>Kost</th>
              <th>Bayar DP</th>
              <th>Bulan Tagihan</th>
              <th>Bayar Tagihan</th>
              <th>Metode Bayar</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($tagihans as $no => $tagihan)
            <tr>
              <td><input type="checkbox" name="tagihan_ids[]" value="{{ $tagihan->id }}" class="selectRow"></td>
              <td>{{ $no + $tagihans->firstItem() }}</td>
              <td>{{ $tagihan->booking->user->name ?? '-' }}</td>
              <td class="text-start">
                <strong>Kamar:</strong> {{ $tagihan->booking->kost->nomor_kamar ?? '-' }}<br>
                <strong>Lantai:</strong> {{ $tagihan->booking->kost->nomor_lantai ?? '-' }}
              </td>
                   <td class="text-end">Rp {{ number_format($tagihan->booking->dp, 0, ',', '.') }}</td>
            <td>{{ $tagihan->nama_bulan_dinamis }}</td>
            <td class="text-end">Rp {{ number_format($tagihan->bayar_per_bulan, 0, ',', '.') }}</td>
             <td class="text-end">{{ $tagihan->booking->jenis_pembayaran }}</td>
              <td>
                <div class="d-flex justify-content-center gap-1">
                  <a href="{{ route('tagihan.edit', $tagihan->id) }}" class="btn btn-sm btn-warning" title="Edit">
                    <i class="ti ti-edit"></i>
                  </a>
                  {{-- <form action="{{ route('tagihan.destroy', $tagihan->id) }}" method="POST" class="d-inline">
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
              <td colspan="7" class="text-center text-muted py-4">
                <i class="ti ti-info-circle"></i> Belum ada data tagihan.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </form>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
      {{ $tagihans->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function () {
  const selectAll = document.getElementById("selectAll");
  const checkboxes = document.querySelectorAll(".selectRow");
  const bulkDeleteBtn = document.getElementById("bulkDeleteBtn");

  // Pilih semua checkbox
  selectAll.addEventListener("change", function () {
    checkboxes.forEach(cb => cb.checked = this.checked);
  });

  // SweetAlert Bulk Delete
  const form = document.getElementById("bulkDeleteForm");
  bulkDeleteBtn.addEventListener("click", function (e) {
    e.preventDefault();
    const selected = document.querySelectorAll(".selectRow:checked");
    if (selected.length === 0) {
      Swal.fire('Tidak ada data dipilih!', 'Pilih minimal satu tagihan untuk dihapus.', 'warning');
      return;
    }

    Swal.fire({
      title: 'Yakin ingin menghapus yang dipilih?',
      text: "Data tagihan yang dihapus tidak bisa dikembalikan.",
      icon: 'warning',
      showCancelButton: true,
      confirmButtonText: 'Ya, hapus',
      cancelButtonText: 'Batal',
      confirmButtonColor: '#d33'
    }).then((result) => {
      if (result.isConfirmed) {
        form.submit();
      }
    });
  });

  // SweetAlert individual delete
  document.querySelectorAll(".btn-delete").forEach(button => {
    button.addEventListener("click", function (e) {
      e.preventDefault();
      const form = this.closest("form");

      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: "Tagihan akan dihapus permanen!",
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
});
</script>
@endpush
