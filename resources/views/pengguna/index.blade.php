@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Pengguna')

@section('content')
<div class="card shadow-sm rounded-3 border-0">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold text-dark mb-0">👥 Data Pengguna</h4>

      <div class="d-flex gap-2">
        @if (Auth::user()->role === 'admin' || Auth::user()->role === 'pemilik')
          <a href="{{ route('pengguna.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
            <i class="ti ti-plus"></i> Tambah Pengguna
          </a>
        @endif
        <button id="bulkDeleteBtn" class="btn btn-danger d-flex align-items-center gap-2 shadow-sm">
          <i class="ti ti-trash"></i> Hapus Terpilih
        </button>
      </div>
    </div>

    <form method="GET" class="row row-cols-lg-auto g-2 align-items-center mb-4">
      <div class="col-12">
        <input type="text" name="search" class="form-control shadow-sm" placeholder="Cari nama atau email..." value="{{ request('search') }}">
      </div>
      <div class="col-12">
        <select name="role" class="form-select shadow-sm">
          <option value="">-- Semua Role --</option>
          <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
          <option value="pemilik" {{ request('role') === 'pemilik' ? 'selected' : '' }}>Pemilik</option>
          <option value="pelanggan" {{ request('role') === 'pelanggan' ? 'selected' : '' }}>Pelanggan</option>
        </select>
      </div>
      <div class="col-12">
        <button type="submit" class="btn btn-outline-primary">
          <i class="ti ti-search"></i> Filter
        </button>
      </div>
      <div class="col-12">
        <a href="{{ route('pengguna.index') }}" class="btn btn-outline-secondary">
          <i class="ti ti-x"></i> Reset
        </a>
      </div>
    </form>

    <form id="bulkDeleteForm" method="POST" action="{{ route('pengguna.bulkDelete') }}">
      @csrf
      @method('DELETE')

      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
          <thead class="table-light">
            <tr class="text-nowrap">
              <th><input type="checkbox" id="selectAll"></th>
              <th>No</th>
              <th>Nama</th>
             
              <th>Role</th>
              <th>No HP</th>
              <th>Avatar</th>
              <th>ID Card</th>
              @if (Auth::user()->role === 'admin' || Auth::user()->role === 'pemilik')
              <th>Aksi</th>
              @endif
            </tr>
          </thead>
          <tbody>
            @forelse($users as $index => $user)
            <tr>
              <td><input type="checkbox" name="selected[]" value="{{ $user->id }}" class="selectItem"></td>
              <td>{{ $index + $users->firstItem() }}</td>
              <td>{{ $user->name }}</td>
             
              <td>
                <span class="badge 
                  @if($user->role === 'admin') bg-primary
                  @elseif($user->role === 'pelanggan') bg-success
                  @elseif($user->role === 'pemilik') bg-warning text-dark
                  @else bg-secondary
                  @endif">
                  {{ ucfirst($user->role) }}
                </span>
              </td>
              <td>{{ $user->phone }}</td>
              <td>
                @if($user->avatar)
                  <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" width="40" height="40" class="rounded-circle object-fit-cover">
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              <td>
                @if($user->id_card)
                  <img src="{{ asset('storage/' . $user->id_card) }}" alt="ID Card" width="40" height="40" class="rounded object-fit-cover">
                @else
                  <span class="text-muted">-</span>
                @endif
              </td>
              @if (Auth::user()->role === 'admin' || Auth::user()->role === 'pemilik')
              <td>
                @if ($user->role !== 'pelanggan')
                  <a href="{{ route('pengguna.edit', $user->id) }}" class="btn btn-sm btn-warning">
                    <i class="ti ti-pencil"></i> Edit
                  </a>
                @endif
              </td>
              @endif
            </tr>
            @empty
            <tr>
              <td colspan="{{ (Auth::user()->role === 'admin' || Auth::user()->role === 'pemilik') ? 9 : 8 }}" class="text-center text-muted py-4">
                <i class="ti ti-info-circle"></i> Belum ada data pengguna tersedia.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </form>

    <div class="d-flex justify-content-end mt-3">
      {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function () {
  document.getElementById('selectAll').addEventListener('change', function () {
    document.querySelectorAll('.selectItem').forEach(cb => cb.checked = this.checked);
  });

  document.getElementById('bulkDeleteBtn').addEventListener('click', function () {
    const selected = document.querySelectorAll('.selectItem:checked');
    if (selected.length === 0) {
      Swal.fire('Tidak ada yang dipilih', 'Pilih minimal satu pengguna untuk dihapus.', 'warning');
      return;
    }

    Swal.fire({
      title: 'Yakin ingin menghapus?',
      text: `Sebanyak ${selected.length} pengguna akan dihapus permanen.`,
      icon: 'warning',
      showCancelButton: true,
      confirmButtonColor: '#d33',
      cancelButtonColor: '#3085d6',
      confirmButtonText: 'Ya, hapus!',
      cancelButtonText: 'Batal'
    }).then(result => {
      if (result.isConfirmed) {
        document.getElementById('bulkDeleteForm').submit();
      }
    });
  });
});
</script>
@endsection
