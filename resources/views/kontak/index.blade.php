@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Pesan Pelanggan')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1">📬 Pesan Masuk</h4>
            <p class="text-muted mb-0">Daftar pesan yang dikirim oleh pelanggan melalui halaman kontak.</p>
        </div>

        <!-- Search -->
        <form action="{{ route('kontak.index') }}" method="GET" class="d-flex gap-2 flex-wrap shadow-sm">
            <input type="text" name="search" class="form-control rounded-pill" placeholder="Cari nama/email/subjek..."
                   value="{{ request('search') }}">
            <button class="btn btn-outline-secondary rounded-pill" type="submit">🔍</button>
            @if(request('search'))
                <a href="{{ route('kontak.index') }}" class="btn btn-outline-danger rounded-pill">
                    <i class="ti ti-x"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Messages -->
    @forelse ($kontaks as $kontak)
    <div class="card shadow-sm rounded-4 border-0 mb-4">
        <div class="card-body d-flex flex-column flex-lg-row align-items-start justify-content-between gap-3">
            <div class="flex-grow-1">
                <div class="d-flex align-items-center mb-2">
                    <div class="me-3">
                        <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                            {{ strtoupper(substr($kontak->nama, 0, 1)) }}
                        </div>
                    </div>
                    <div>
                        <h5 class="mb-0 text-primary fw-bold">{{ $kontak->nama }}</h5>
                        <small class="text-muted"><i class="ti ti-mail"></i> {{ $kontak->email }}</small>
                    </div>
                </div>

                <p class="mb-1"><strong>Subjek:</strong> {{ $kontak->subjek }}</p>
                <div class="bg-light border rounded p-3 text-muted small">
                  <strong>Isi Pesan:</strong>  {{ $kontak->pesan }}
                </div>

                <small class="text-muted d-block mt-2">
                    Dikirim pada {{ \Carbon\Carbon::parse($kontak->created_at)->translatedFormat('d F Y H:i') }}
                </small>
            </div>

            <!-- Aksi -->
            <div class="d-flex flex-column gap-2">
                <a href="https://mail.google.com/mail/?view=cm&fs=1&to={{ $kontak->email }}&su=Menanggapi pesan yang kamu kirimkan pada aplikasi kostku"
                   target="_blank" class="btn btn-sm btn-outline-primary rounded-pill shadow-sm d-flex align-items-center gap-1">
                    <i class="ti ti-send"></i> Balas
                </a>

                <form action="{{ route('kontak.destroy', $kontak->id) }}" method="POST" class="form-delete">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-sm btn-outline-danger rounded-pill shadow-sm d-flex align-items-center gap-1 btn-delete">
                        <i class="ti ti-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info d-flex align-items-center gap-2">
        <i class="ti ti-info-circle fs-4"></i>
        <span>Belum ada pesan dari pelanggan.</span>
    </div>
    @endforelse

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-4">
        {{ $kontaks->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>
@endsection
