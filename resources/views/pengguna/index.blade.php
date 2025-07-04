@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Pengguna')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title fw-semibold">Data Pengguna</h5>
      <a href="{{ route('pengguna.create') }}" class="btn btn-primary btn-sm">
        <i class="ti ti-plus"></i> Tambah Data
      </a>
    </div>

    <div class="table-responsive" style="overflow-x: auto; white-space:nowrap">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Role</th>
            <th>No HP</th>
            <th>Avatar</th>
            <th>ID Card</th>
            <th>Aksi</th>
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
                @endif">
                {{ ucfirst($user->role) }}
              </span>
            </td>
            <td>{{ $user->phone }}</td>
            <td>
              @if($user->avatar)
                <img src="{{ asset('storage/' . $user->avatar) }}" alt="Avatar" width="40" height="40" class="rounded-circle object-fit-cover">
              @else
                -
              @endif
            </td>
            <td>
              @if($user->id_card)
                <img src="{{ asset('storage/' . $user->id_card) }}" alt="ID Card" width="40" height="40" class="rounded object-fit-cover">
              @else
                -
              @endif
            </td>
            <td>
              <a href="{{ route('pengguna.edit', $user->id) }}" class="btn btn-sm btn-warning me-1">
                <i class="ti ti-edit"></i>
              </a>
              <form action="{{ route('pengguna.destroy', $user->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus pengguna ini?')">
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
            <td colspan="8" class="text-muted text-center">Belum ada data pengguna tersedia.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $users->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection
