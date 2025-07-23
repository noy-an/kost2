@extends('fe.master')
@section('title', 'Histori Pesanmu')

@section('content')
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">

<section class="contact-section spad bg-light min-vh-100">
    <div class="container">

        <!-- Header -->
        <div class="mb-5 text-center">
            <h2 class="fw-bold text-dark mb-1"><i class="bi bi-envelope-paper-fill text-primary me-2"></i>Histori Pesanmu</h2>
            <p class="text-muted">Lihat pesan yang telah kamu kirim dan balasan dari admin.</p>
        </div>

        @if($pesans->isEmpty())
            <div class="alert alert-info d-flex align-items-center gap-2 shadow-sm rounded-3">
                <i class="bi bi-info-circle fs-4"></i>
                <span>Kamu belum mengirimkan pesan apapun.</span>
            </div>
        @else
            @foreach($pesans as $pesan)
            <div class="card border-0 shadow-sm rounded-4 mb-4">
                <div class="card-body p-4 d-flex flex-column gap-3">

                    <!-- Header Pesan -->
                    <div class="d-flex align-items-center">
                        <div class="me-3">
                            <div class="bg-primary text-white rounded-circle d-flex justify-content-center align-items-center" style="width: 50px; height: 50px;">
                                <i class="bi bi-chat-dots-fill fs-5"></i>
                            </div>
                        </div>
                        <div>
                            <h5 class="mb-0 text-dark fw-semibold">Subjek: {{ $pesan->subjek }}</h5>
                            <small class="text-muted">Terkirim pada {{ $pesan->created_at->format('d M Y, H:i') }}</small>
                        </div>
                    </div>

                    <!-- Isi Pesan -->
                    <div class="bg-light border-start border-4 border-primary p-3 rounded">
                        <div class="text-muted small">
                            <strong>Pesan:</strong><br>
                            {{ $pesan->pesan }}
                        </div>
                    </div>

                    <!-- Balasan Admin -->
                    @if ($pesan->balasan->isNotEmpty())
                        @foreach($pesan->balasan as $balas)
                        <div class="bg-white border-start border-4 border-success p-3 rounded shadow-sm">
                            <div class="d-flex justify-content-between mb-2">
                                <div class="fw-semibold text-success"><i class="bi bi-reply-fill me-1"></i>Balasan Admin</div>
                                <small class="text-muted">{{ $balas->created_at->format('d M Y, H:i') }}</small>
                            </div>
                            <div class="text-muted small">{{ $balas->pesan }}</div>
                        </div>
                        @endforeach
                    @else
                        <div class="alert alert-warning d-flex align-items-center gap-2 small mb-0 rounded-3">
                            <i class="bi bi-clock-history"></i>
                            Belum ada balasan dari admin.
                        </div>
                    @endif

                </div>
            </div>
            @endforeach
        @endif

        <!-- Tombol Kembali -->
        <div class="text-center mt-5">
            <a href="{{ route('contact.index') }}" class="btn btn-outline-primary rounded-pill px-4 py-2 shadow-sm">
                <i class="bi bi-arrow-left"></i> Kembali ke Form Kontak
            </a>
        </div>
    </div>
</section>
@endsection
