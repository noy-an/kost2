@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection
@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Tagihan')

@section('content')
<div class="card">
  <div class="card-body">
    <div class="d-flex justify-content-between align-items-center mb-4">
      <h4 class="card-title m-0">Data Tagihan</h4>
      <a href="{{ route('tagihan.create') }}" class="btn btn-primary">+ Tambah Tagihan</a>
    </div>

    @if(session('success'))
      <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <div class="table-responsive" style="overflow-x: auto; white-space:nowrap">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>ID</th>
            <th>User</th>
            <th>Kost</th>
            <th>Bayar Per Bulan</th>
            <th>Jumlah Bayar</th>
            <th>Tanggal Dibuat</th>
            <th>Aksi</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tagihans as $tagihan)
          <tr>
            <td>{{ $tagihan->id }}</td>
            <td>{{ $tagihan->booking->user->name ?? '-' }}</td>
            <td>Kamar {{ $tagihan->booking->kost->nomor_kamar ?? '-' }} Lantai {{ $tagihan->booking->kost->nomor_lantai ?? '-' }}</td>
            <td>Rp{{ number_format($tagihan->bayar_per_bulan, 0, ',', '.') }}</td>
            <td>Rp{{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</td>
            <td>{{ $tagihan->created_at->format('d-m-Y') }}</td>
            <td>
              <a href="{{ route('tagihan.edit', $tagihan->id) }}" class="btn btn-sm btn-warning">Edit</a>
              <form action="{{ route('tagihan.destroy', $tagihan->id) }}" method="POST" style="display: inline-block;" onsubmit="return confirm('Yakin ingin hapus?');">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger">Hapus</button>
              </form>
            </td>
          </tr>
          @empty
          <tr>
            <td colspan="7" class="text-muted">Tidak ada data tagihan</td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>

    <div class="d-flex justify-content-end mt-3">
      {{ $tagihans->links() }}
    </div>
  </div>
</div>
@endsection
