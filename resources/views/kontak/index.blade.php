@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Pesan Pelanggan')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<div class="container-fluid">
    <!-- Header -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-md-center mb-4 gap-3">
        <div>
            <h4 class="fw-bold text-dark mb-1"><i class="ti ti-mail fs-5 text-primary"></i> Pesan Masuk</h4>
            <p class="text-muted mb-0">Daftar pesan yang dikirim oleh pelanggan melalui halaman kontak.</p>
        </div>

        <!-- Filter dan Search -->
        <form action="{{ route('kontak.index') }}" method="GET" class="d-flex gap-2 flex-wrap shadow-sm align-items-center">
            <input type="text" name="search" class="form-control rounded-pill" placeholder="Cari nama/email/subjek..."
                   value="{{ request('search') }}">
            <input type="date" name="tanggal" class="form-control rounded-pill" value="{{ request('tanggal') }}">
            <button class="btn btn-outline-primary rounded-pill px-4" type="submit">
                <i class="bi bi-search"></i> Cari
            </button>

            @if(request('search') || request('tanggal'))
                <a href="{{ route('kontak.index') }}" class="btn btn-outline-danger rounded-pill">
                    <i class="ti ti-x"></i> Reset
                </a>
            @endif
        </form>
    </div>

    <!-- Tombol Bersihkan Semua -->
    @if ($kontaks->count())
    <form id="form-clear-all" action="{{ route('kontak.destroyAll')}}" method="POST" class="mb-3">
        @csrf
        @method('DELETE')
        <button type="button" class="btn btn-danger rounded-pill" id="btn-clear-all">
            <i class="ti ti-trash"></i> Bersihkan Semua Pesan
        </button>
    </form>
    @endif

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
                    <button type="button" class="btn btn-sm btn-outline-danger rounded-pill shadow-sm d-flex align-items-center gap-1 btn-delete">
                        <i class="ti ti-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>
    </div>
    @empty
    <div class="alert alert-info d-flex align-items-center gap-2">
        <i class="ti ti-info-circle fs-4"></i>
        @if(request('search') || request('tanggal'))
            <span>Data yang dicari tidak ada.</span>
        @else
            <span>Belum ada pesan dari pelanggan.</span>
        @endif
    </div>
    @endforelse

    <!-- Pagination -->
    <div class="d-flex justify-content-end mt-4">
        {{ $kontaks->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.querySelectorAll('.btn-delete').forEach(button => {
        button.addEventListener('click', function () {
            Swal.fire({
                title: 'Yakin ingin menghapus pesan ini?',
                text: 'Data akan terhapus secara permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    this.closest('form').submit();
                }
            });
        });
    });

    const clearBtn = document.getElementById('btn-clear-all');
    if (clearBtn) {
        clearBtn.addEventListener('click', function () {
            Swal.fire({
                title: 'Hapus semua pesan?',
                text: 'Semua pesan akan dihapus permanen!',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Ya, hapus semua!',
                cancelButtonText: 'Batal'
            }).then((result) => {
                if (result.isConfirmed) {
                    fetch("{{ route('kontak.destroyAll') }}", {
                        method: 'DELETE',
                        headers: {
                            'X-CSRF-TOKEN': '{{ csrf_token() }}',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Gagal menghapus');
                        return response.json();
                    })
                    .then(data => {
                        Swal.fire('Sukses', 'Semua pesan berhasil dihapus.', 'success');

                        document.querySelectorAll('.card').forEach(card => card.remove());

                        const alertInfo = document.createElement('div');
                        alertInfo.className = 'alert alert-info d-flex align-items-center gap-2 mt-3';
                        alertInfo.innerHTML = '<i class="ti ti-info-circle fs-4"></i><span>Belum ada pesan dari pelanggan.</span>';
                        document.querySelector('.container-fluid').appendChild(alertInfo);

                        clearBtn.closest('form')?.remove();
                    })
                    .catch(error => {
                        Swal.fire('Gagal', 'Terjadi kesalahan saat menghapus.', 'error');
                    });
                }
            });
        });
    }
</script>
@endsection
