@extends('fe.master')
@section('title', 'Histori Pesanmu')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<section class="contact-section spad bg-light">
    <div class="container">

        <!-- Header -->
        <div class="mb-4">
            <h4 class="fw-bold text-dark mb-1"><i class="bi bi-envelope-fill text-primary"></i> Histori Pesanmu</h4>
            <p class="text-muted mb-0">Berikut adalah daftar pesan yang pernah kamu kirimkan melalui form kontak kami.</p>
        </div>

        @if($pesans->isEmpty())
            <div class="alert alert-info d-flex align-items-center gap-2">
                <i class="bi bi-info-circle fs-4"></i>
                <span>Kamu belum mengirimkan pesan apapun.</span>
            </div>
        @else
            @foreach($pesans as $pesan)
            <div class="card shadow-sm border-0 rounded-4 mb-4">
                <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start gap-3">
                    <div class="flex-grow-1">
                        <div class="d-flex align-items-center mb-2">
                            <div class="me-3">
                                <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 48px; height: 48px;">
                                    <i class="bi bi-chat-left-text-fill fs-5"></i>
                                </div>
                            </div>
                            <div>
                                <h5 class="mb-0 text-primary fw-bold">Subjek: {{ $pesan->subjek }}</h5>
                                <small class="text-muted">Tanggal kirim: {{ $pesan->created_at->format('d M Y, H:i') }}</small>
                            </div>
                        </div>

                        <div class="bg-light border rounded p-3 text-muted small">
                           Isi Pesan: <br> <br>
                            {{ $pesan->pesan }}
                        </div>
                    </div>
                </div>
            </div>
            @endforeach
        @endif

        <!-- Tombol Kembali -->
        <div class="text-center mt-4">
            <a href="{{ route('contact.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2">
                <i class="bi bi-arrow-left"></i> Kembali ke Form Kontak
            </a>
        </div>
    </div>
</section>
@endsection
