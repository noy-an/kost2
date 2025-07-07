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
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold text-dark mb-0">👥 Data Pengguna</h4>
    </div>

    <!-- Search Form -->
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

    <!-- Table -->
    <div class="table-responsive">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr class="text-nowrap">
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>No HP</th>
            <th>Avatar</th>
            <th>ID Card</th>
            <th class="text-nowrap">Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($users as $user)
          <tr>
            <td>{{ $user->id }}</td>
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
            <td>
              <div class="d-flex justify-content-center gap-1">
                <a href="{{ route('pengguna.edit', $user->id) }}" class="btn btn-sm btn-warning" title="Edit">
                  <i class="ti ti-edit"></i>
                </a>
       <form action="{{ route('pengguna.destroy', $user->id) }}" method="POST" class="d-inline form-delete">
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
            <td colspan="8" class="text-center text-muted py-4">
              <i class="ti ti-info-circle"></i> Belum ada data pengguna tersedia.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-3">
      {{ $users->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection
