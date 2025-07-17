@extends('fe.master')
@section('title', 'Pesan Balasan')

@section('content')
<style>
    .modern-card {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        border-radius: 1rem;
        border: 1px solid #e0e0e0;
        background-color: #fff;
    }

    .modern-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 28px rgba(0, 0, 0, 0.06);
    }

    .card-title {
        font-size: 1.2rem;
        font-weight: 600;
        color: #1e40af;
        margin-bottom: 0.5rem;
    }

    .card-text {
        font-size: 0.95rem;
        color: #333;
        margin-bottom: 0.5rem;
    }

    .card-time {
        font-size: 0.8rem;
        color: #6b7280;
    }

    .no-message {
        background-color: #f9fafb;
        border-left: 5px solid #1e40af;
        padding: 14px 20px;
        border-radius: 0.75rem;
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.95rem;
        color: #374151;
        box-shadow: 0 3px 8px rgba(0, 0, 0, 0.03);
    }

    .section-title {
        font-size: 1.4rem;
        font-weight: bold;
        color: #111827;
        display: flex;
        align-items: center;
        gap: 10px;
        margin-bottom: 1.5rem;
    }

    .section-title i {
        color: #1e40af;
        font-size: 1.5rem;
    }

    .reply-form {
        margin-top: 1rem;
        display: none;
    }

    .reply-form.active {
        display: block;
    }

    @media (max-width: 576px) {
        .card-title {
            font-size: 1rem;
        }

        .card-text {
            font-size: 0.9rem;
        }

        .section-title {
            font-size: 1.2rem;
        }
    }
</style>

<div class="container-fluid">
    <h4 class="section-title">
        <i class="ti ti-message-circle"></i> Pesan Balasan dari Admin
    </h4>

    @if(session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @forelse ($balasan as $item)
        <div class="card modern-card mb-4">
            <div class="card-body">
                <h5 class="card-title">{{ $item->subjek }}</h5>
                <p class="card-text">{{ $item->pesan }}</p>
                <small class="card-time">Dikirim pada {{ $item->created_at->translatedFormat('d F Y H:i') }}</small>

                <div class="mt-3">
                    <button class="btn btn-sm btn-outline-primary toggle-reply-btn">Balas</button>

                    <!-- Form Balas -->
                    <form action="{{ route('contact.store') }}" method="POST" class="reply-form mt-3">
                        @csrf
                        <input type="hidden" name="nama" value="{{ Auth::user()->name }}">
                        <input type="hidden" name="email" value="{{ Auth::user()->email }}">

                        <div class="mb-2">
                            <input type="text" name="subjek" class="form-control rounded" placeholder="Subjek" required>
                        </div>
                        <div class="mb-2">
                            <textarea name="pesan" class="form-control rounded" rows="4" placeholder="Tulis pesan balasan..." required></textarea>
                        </div>
                        <button type="submit" class="btn btn-sm btn-success">Kirim</button>
                    </form>
                </div>
            </div>
        </div>
    @empty
        <div class="no-message">
            <i class="ti ti-info-circle"></i>
            <span>Belum ada pesan balasan dari admin.</span>
        </div>
    @endforelse
</div>

<script>
    document.addEventListener('DOMContentLoaded', () => {
        const buttons = document.querySelectorAll('.toggle-reply-btn');

        buttons.forEach(btn => {
            btn.addEventListener('click', function () {
                const form = this.nextElementSibling;
                form.classList.toggle('active');
            });
        });
    });
</script>
@endsection
