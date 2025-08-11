@extends('fe.master')
@section('content')
<div class="container my-4">
    <h2 class="mb-4">Mutasi Deposit</h2>

    @if($depositHistories->isEmpty())
        <div class="alert alert-info">
            Belum ada mutasi deposit.
        </div>
    @else
        <table class="table table-bordered table-striped align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Tanggal</th>
                    <th>Keterangan</th>
                    <th class="text-end">Debit (+)</th>
                    <th class="text-end">Kredit (-)</th>
                </tr>
            </thead>
            <tbody>
                @php
                    $totalDebit = 0;
                    $totalKredit = 0;
                @endphp

                @foreach($depositHistories as $history)
                    <tr>
                        <td>{{ $history->created_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $history->name }}</td>
                        <td class="text-success text-end" style="font-family: monospace;">
                            @if($history->amount > 0)
                                Rp{{ number_format($history->amount, 0, ',', '.') }}
                                @php $totalDebit += $history->amount; @endphp
                            @endif
                        </td>
                        <td class="text-danger text-end" style="font-family: monospace;">
                            @if($history->amount < 0)
                                Rp{{ number_format(abs($history->amount), 0, ',', '.') }}
                                @php $totalKredit += abs($history->amount); @endphp
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
            <tfoot class="table-light fw-bold">
                <tr>
                    <td colspan="2" class="text-end">Total</td>
                    <td class="text-success text-end" style="font-family: monospace;">
                        Rp{{ number_format($totalDebit, 0, ',', '.') }}
                    </td>
                    <td class="text-danger text-end" style="font-family: monospace;">
                        Rp{{ number_format($totalKredit, 0, ',', '.') }}
                    </td>
                </tr>
                <tr>
                    <td colspan="2" class="text-end">Saldo Deposit</td>
                    <td colspan="2" class="text-end" style="font-family: monospace;">
                        Rp{{ number_format($deposit->amount, 0, ',', '.') }}
                    </td>
                </tr>
            </tfoot>
        </table>
    @endif
</div>
@endsection
