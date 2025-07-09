@extends('fe.master')

@section('content')
<div class="container mt-5 d-flex justify-content-center">
    <div class="card shadow-sm p-4" style="max-width: 500px; width: 100%;">
        <div class="text-center mb-4">
            <i class="fas fa-envelope fa-3x text-primary mb-3"></i>
            <h3 class="fw-bold">Verifikasi Email Kamu</h3>
            <p class="text-muted">Kami telah mengirim link verifikasi ke email kamu. Silakan cek dan klik link tersebut untuk menyelesaikan proses pendaftaran.</p>
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="alert alert-success" role="alert">
                Link verifikasi baru telah dikirim ke email kamu.
            </div>
        @endif

        <form method="POST" action="{{ route('verification.send') }}">
            @csrf
            <button type="submit" class="btn btn-primary w-100">
                Kirim Ulang Email Verifikasi
            </button>
        </form>

        <form method="POST" action="{{ route('logout') }}" class="mt-3">
            @csrf
            <button type="submit" class="btn btn-link w-100 text-decoration-none text-danger">
                Logout
            </button>
        </form>
    </div>
</div>
@endsection
