@extends('fe.master')

@section('content')
<div class="container py-5 px-3 px-md-5" style="max-width: 900px;">

    <!-- Heading -->
    <div class="text-center mb-5">
        <h2 class="fw-bold text-dark">Mutasi Deposit</h2>
        <p class="text-muted small">Riwayat transaksi deposit Anda</p>
    </div>

    @if($depositHistories->isEmpty())
        <!-- Empty State -->
        <div class="d-flex flex-column align-items-center justify-content-center p-5 bg-light rounded-4 shadow-sm text-center">
            <i class="bi bi-wallet2 fs-1 text-secondary mb-3"></i>
            <p class="text-muted mb-0">Belum ada mutasi deposit</p>
        </div>
    @else
        <!-- Saldo Card -->
        <div class="card border-0 shadow rounded-4 mb-4 bg-gradient bg-dark text-white">
            <div class="card-body text-center py-4">
                <p class="mb-1 small opacity-75">Saldo Deposit</p>
                <h2 class="fw-bold mb-0" style="font-family: monospace;">
                    Rp{{ number_format($deposit->amount, 0, ',', '.') }}
                </h2>
            </div>
        </div>

        <!-- Mutasi List -->
        <div class="list-group rounded-4 shadow-sm overflow-hidden">
            @foreach($depositHistories as $history)
                <div class="list-group-item list-group-item-action d-flex justify-content-between align-items-center py-3 flex-wrap">
                    <div class="me-3">
                        <div class="fw-semibold text-dark">{{ $history->name }}</div>
                        <small class="text-muted d-block">{{ $history->created_at->format('d M Y, H:i') }}</small>
                    </div>
                    <div class="text-end">
                        @if($history->amount > 0)
                            <span class="fw-bold text-success d-block" style="font-family: monospace;">
                                + Rp{{ number_format($history->amount, 0, ',', '.') }}
                            </span>
                        @else
                            <span class="fw-bold text-danger d-block" style="font-family: monospace;">
                                - Rp{{ number_format(abs($history->amount), 0, ',', '.') }}
                            </span>
                        @endif
                    </div>
                </div>
                @if(!$loop->last)
                    <hr class="my-0">
                @endif
            @endforeach
        </div>
    @endif
</div>
@endsection
