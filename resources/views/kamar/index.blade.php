@extends('fe.master')

@section('content')
<section class="py-5 bg-light">
    <div class="container">
        {{-- Search --}}
        <div class="row justify-content-center mb-4">
            <div class="col-md-10">
                <form action="{{ route('kamar.index') }}" method="GET" class="d-flex shadow-sm">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nomor kamar atau fasilitas..."
                        class="form-control me-2 p-3 rounded-start border-0"
                        style="flex: 1; box-shadow: inset 0 1px 3px rgba(0,0,0,0.1);">
                    <button type="submit" class="btn btn-primary px-4 rounded-end">Cari</button>
                </form>
            </div>
        </div>

        {{-- Daftar Kamar --}}
        <div class="row g-4">
            @if($kosts->isEmpty())
                <div class="col-12 text-center">
                    <h5 class="text-muted">Kost tidak ditemukan.</h5>
                </div>
            @else
                @foreach($kosts as $kost)
                    <div class="col-sm-12 col-md-6 col-lg-4">
                        <div class="card h-100 border-0 shadow-sm hover-shadow transition">
                            <div class="position-relative overflow-hidden rounded-top">
                                <img src="{{ asset('storage/' . $kost->foto1) }}"
                                     alt="Kamar {{ $kost->nomor_kamar }}"
                                     class="w-100 kost-img">
                                @if($kost->status === 'tersedia')
                                    <span class="badge bg-success position-absolute top-0 end-0 m-2 shadow">Tersedia</span>
                                @else
                                    <span class="badge bg-secondary position-absolute top-0 end-0 m-2 shadow">Tidak Tersedia</span>
                                @endif
                            </div>
                            <div class="card-body d-flex flex-column">
                                <h5 class="card-title mb-1">Kamar No {{ $kost->nomor_kamar }}</h5>
                                <p class="text-muted small mb-2">Lantai {{ $kost->nomor_lantai }}</p>
                                <p class="mb-2" style="min-height: 50px">{{ Str::limit($kost->fasilitas, 100) }}</p>
                                <div class="mt-auto d-flex justify-content-between align-items-center">
                                    <strong class="text-primary fs-5">
                                        {{ 'Rp ' . number_format($kost->harga, 0, ',', '.') }}
                                    </strong>
                                   <a href="{{ route('kamar.show', $kost->id) }}" class="btn btn-sm btn-outline-primary">
                                        Lihat Detail
                                    </a>

                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        {{-- Pagination --}}
        <div class="row justify-content-center mt-5">
            <div class="col-auto">
                {{ $kosts->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</section>

{{-- CSS Tambahan --}}
<style>
.kost-img {
    height: 240px;
    object-fit: cover;
    object-position: center;
    transition: transform 0.3s ease;
}
.card:hover .kost-img {
    transform: scale(1.05);
}
.hover-shadow:hover {
    box-shadow: 0 0 20px rgba(0, 0, 0, 0.08) !important;
}
.transition {
    transition: all 0.3s ease-in-out;
}
</style>
@endsection
