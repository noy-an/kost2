@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Kontak')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-3">
      <h5 class="card-title fw-semibold">Data Kontak</h5>
      <a href="{{ route('kontak.create') }}" class="btn btn-primary btn-sm">
        <i class="ti ti-plus"></i> Tambah Pesan
      </a>
    </div>

    <div class="table-responsive" style="overflow-x: auto; white-space:nowrap">
      <table class="table table-bordered table-hover align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>Nama</th>
            <th>Email</th>
            <th>Subjek</th>
            <th>Pesan</th>
            <th>Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($kontaks as $kontak)
          <tr>
            <td>{{ $kontak->id }}</td>
            <td>{{ $kontak->nama }}</td>
            <td>{{ $kontak->email }}</td>
            <td>{{ $kontak->subjek }}</td>
            <td>{{ Str::limit($kontak->pesan, 50) }}</td>
            <td>{{ $kontak->created_at->format('d-m-Y H:i') }}</td>
            <td>
              <a href="{{ route('kontak.edit', $kontak->id) }}" class="btn btn-sm btn-warning me-1">
                <i class="ti ti-edit"></i>
              </a>
              <form action="{{ route('kontak.destroy', $kontak->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Yakin ingin menghapus data ini?')">
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
            <td colspan="7" class="text-muted text-center">Belum ada pesan tersedia.</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="mt-3">
      {{ $kontaks->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>
@endsection
