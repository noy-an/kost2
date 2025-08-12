@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Deposit')

@section('content')
<div class="card shadow-sm rounded-3 border-0">
  <div class="card-body">
    <div class="row justify-content-between align-items-center mb-4 g-2">
      <div class="col">
        <h4 class="fw-bold text-dark mb-0">💰 Data Deposit</h4>
      </div>
      <div class="col-auto">
        <a href="{{ route('deposit.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
          <i class="ti ti-plus"></i> Tambah Deposit
        </a>
      </div>
    </div>

    <form action="{{ route('deposit.index') }}" method="GET" class="mb-4">
      <div class="input-group">
        <span class="input-group-text bg-light border-end-0"><i class="ti ti-search text-secondary"></i></span>
        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari user ID / nominal..." value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">Cari</button>
        @if(request('search'))
          <a href="{{ route('deposit.index') }}" class="btn btn-outline-danger"><i class="ti ti-x"></i> Reset</a>
        @endif
      </div>
    </form>

    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr class="text-nowrap">
            <th>No</th>
            <th>User</th>
            <th>Nominal Deposit</th>
            <th>Tanggal Deposit</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($deposit as $index => $item)
          <tr>
            <td>{{ $index + $deposit->firstItem() }}</td>
            <td>{{ $item->user ? $item->user->name : 'User tidak ditemukan' }}</td>
            <td class="text-end">Rp{{ number_format($item->amount, 0, ',', '.') }}</td>
            <td>{{ $item->created_at->format('d-m-Y H:i') }}</td>
            <td>
              <div class="d-flex justify-content-center gap-1">
                {{-- <a href="{{ route('deposit.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                  <i class="ti ti-edit"></i>
                </a> --}}
                <form action="{{ route('deposit.destroy', $item->id) }}" method="POST" class="d-inline">
                  @csrf
                  @method('DELETE')
                  <button type="button" class="btn btn-sm btn-danger btn-delete" title="Hapus">
                    <i class="ti ti-trash"></i>
                  </button>
                </form>
              </div>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="5" class="text-center text-muted py-4">
              <i class="ti ti-info-circle"></i> Tidak ada data deposit tersedia.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
      {{ $deposit->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Individual delete
    document.querySelectorAll('.btn-delete').forEach(button => {
      button.addEventListener('click', function () {
        const form = this.closest('form');
        Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: 'Data deposit akan dihapus permanen.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Hapus',
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
@endsection
