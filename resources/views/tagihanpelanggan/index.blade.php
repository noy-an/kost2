@php use Carbon\Carbon; @endphp

@extends('fe.master')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Daftar Booking Saya</h4>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            @if($data->isEmpty())
                <div class="alert alert-info">
                    <strong>Belum ada data booking.</strong>
                    <p>Anda belum melakukan booking atau semua data tidak tersedia.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama</th>
                                <th>Kamar</th>
                                <th>Harga/Bulan</th>
                                <th>DP Dibayar</th>
                                <th>Status</th>
                                <th>Keterangan</th>
                                <th>Tanggal Booking</th>
                                <th>Tanggal Tagihan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $booking)
                               @php
    // Hitung total pembayaran
    $totalPaidKost = $booking->tagihan()->where('component', 'kost')->sum('amount_paid');
    $totalPaidAir = $booking->tagihan()->where('component', 'air')->sum('amount_paid');
    $totalPaidKebersihan = $booking->tagihan()->where('component', 'kebersihan')->sum('amount_paid');

    $hargaKost = $booking->kost->harga;
    $dpAwal = $booking->dp ?? 0;
    $air = $booking->kost->air ?? 100000;
    $kebersihan = $booking->kost->kebersihan ?? 50000;

    $sisaKost = max(0, $hargaKost - $dpAwal - $totalPaidKost);
    $sisaAir = max(0, $air - $totalPaidAir);
    $sisaKebersihan = max(0, $kebersihan - $totalPaidKebersihan);

    $totalSisa = $sisaKost + $sisaAir + $sisaKebersihan;

    $status = $booking->status_sewa;
   if ($status === 'masa_tenggang') {
    $keterangan = 'bayar ulang';
} elseif ($sisaKost <= 0 && $sisaAir <= 0 && $sisaKebersihan <= 0) {
    $keterangan = 'paid';
} else {
    $keterangan = 'unpaid';
}



   $bolehBayar = $status === 'masa_tenggang' || (
    in_array($status, ['aktif']) && ($sisaKost > 0 || $sisaAir > 0 || $sisaKebersihan > 0)
);


    $tagihanTerakhir = $booking->tagihan->sortByDesc('tanggal_tagihan')->first();
@endphp
<tr>
    <td>{{ $loop->iteration }}</td>
    <td>{{ $booking->user->name ?? '-' }}</td>
    <td>Kamar {{ $booking->kost->nomor_kamar ?? '-' }}</td>
    <td>Rp{{ number_format($booking->kost->harga ?? 0, 0, ',', '.') }}</td>
    <td>Rp{{ number_format($booking->dp ?? 0, 0, ',', '.') }}</td>
    <td>
        <span class="badge 
            {{ $status === 'aktif' ? 'bg-success' : 
               ($status === 'masa_tenggang' ? 'bg-warning text-dark' : 
               ($status === 'expired' ? 'bg-danger' : 'bg-secondary')) }}">
            {{ ucfirst($status) }}
        </span>
    </td>
    <td>
    @if($keterangan === 'paid')
        <span class="badge bg-success">Lunas</span>
    @elseif($keterangan === 'bayar ulang')
        <span class="badge bg-warning text-dark">Bayar Ulang</span>
    @else
        <span class="badge bg-danger">Belum Lunas</span>
        <br><small class="text-muted">
            Sisa: Rp{{ number_format($totalSisa, 0, ',', '.') }}
        </small>
    @endif
</td>

    <td>{{ \Carbon\Carbon::parse($booking->tgl_booking)->format('d M Y') }}</td>
    <td>{{ $tagihanTerakhir ? \Carbon\Carbon::parse($tagihanTerakhir->tanggal_tagihan)->format('d M Y') : '-' }}</td>
    <td>
        @if($bolehBayar)
            <a href="{{ route('cicilan.create', $booking->id) }}" class="btn btn-sm btn-primary">
                Bayar
            </a>
        @elseif($status === 'expired')
            <span class="text-danger small">Kamar sudah expired</span>
        @elseif($totalSisa <= 0)
            <span class="text-success small">Semua tagihan lunas</span>
        @else
            <span class="text-muted small">Belum jatuh tempo</span>
        @endif
    </td>
</tr>

                            @endforeach
                        </tbody>
                    </table>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection