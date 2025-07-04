@extends('fe.master')

@section('content')
<section class="ftco-section bg-light">
    <div class="container">
        <div class="row justify-content-center mb-4">
            <div class="col-md-10">
                <form action="{{ route('kamar.index') }}" method="GET" class="d-flex">
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nomor kamar..."
                        class="form-control me-2 p-3 rounded-start border" style="flex: 1;">
                    <button type="submit" class="btn btn-primary px-4 rounded-end">Cari</button>
                </form>
            </div>
        </div>

        <div class="row">
            @if($kosts->isEmpty())
                <div class="col-12 text-center">
                    <h5>Kost tidak ditemukan.</h5>
                </div>
            @else
                @foreach($kosts as $kost)
                    <div class="col-sm-12 col-md-6 col-lg-4 ftco-animate d-flex">
                        <div class="product d-flex flex-column">
                            <a href="#" class="img-prod">
                                <img class="img-fluid uniform-img" src="{{ asset('storage/' . $kost->foto1) }}" alt="Kamar {{ $kost->nomor_kamar }}">
                                <div class="overlay"></div>
                            </a>
                            <div class="text py-3 pb-4 px-3">
                                <h3>Kamar No {{ $kost->nomor_kamar }}</h3>
                                <p>Lantai: {{ $kost->nomor_lantai }} | Status: {{ ucfirst($kost->status) }}</p>
                                <p>{{ $kost->fasilitas }}</p>
                                <div class="pricing">
                                    <p class="price"><span>{{ 'Rp ' . number_format($kost->harga, 0, ',', '.') }}</span></p>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            @endif
        </div>

        <div class="row justify-content-center mt-4">
            <div class="col-auto">
                {{ $kosts->links() }}
            </div>
        </div>
    </div>
</section>

<style>
.uniform-img {
    width: 100%;
    height: 300px;
    object-fit: cover;
    object-position: center;
}
</style>
@endsection
