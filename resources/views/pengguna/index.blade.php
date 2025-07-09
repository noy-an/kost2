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
      <button id="bulkDeleteBtn" class="btn btn-danger d-flex align-items-center gap-2 shadow-sm">
        <i class="ti ti-trash"></i> Hapus Terpilih
      </button>
    </div>

    <form action="{{ route('pengguna.index') }}" method="GET" class="mb-4">
      <div class="input-group">
        <span class="input-group-text bg-light border-end-0">
          <i class="ti ti-search text-secondary"></i>
        </span>
        <input type="text" name="search" class="form-control border-start-0"
               placeholder="Cari nama atau email..." value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">Cari</button>
        @if(request('search'))
        <a href="{{ route('pengguna.index') }}" class="btn btn-outline-danger">
          <i class="ti ti-x"></i> Reset
        </a>
        @endif
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
              <th>Email</th>
              <th>Role</th>
              <th>No HP</th>
              <th>Avatar</th>
              <th>ID Card</th>
            </tr>
          </thead>
          <tbody>
            @forelse($users as $index => $user)
            <tr>
              <td><input type="checkbox" name="selected[]" value="{{ $user->id }}" class="selectItem"></td>
              <td>{{ $index + $users->firstItem() }}</td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
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
            </tr>
            @empty
            <tr>
              <td colspan="9" class="text-center text-muted py-4">
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
  // Individual delete
  document.querySelectorAll('.btn-delete').forEach(button => {
    button.addEventListener('click', function () {
      const form = this.closest('form');
      const name = this.dataset.name;
      Swal.fire({
        title: 'Yakin ingin menghapus?',
        text: `Data pengguna ${name} akan dihapus permanen.`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#3085d6',
        confirmButtonText: 'Hapus',
        cancelButtonText: 'Batal'
      }).then(result => {
        if (result.isConfirmed) form.submit();
      });
    });
  });

  // Select all
  document.getElementById('selectAll').addEventListener('change', function () {
    document.querySelectorAll('.selectItem').forEach(cb => cb.checked = this.checked);
  });

  // Bulk delete
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
