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
                                <th>Tanggal Booking</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($data as $booking)
                                @php
                                    $status = strtolower($booking->status_booking);
                                    $lastTagihan = $booking->tagihan
                                        ->where('status_pembayaran', 'success')
                                        ->sortByDesc('tanggal_tagihan')
                                        ->first();

                                    $tglTerakhir = $lastTagihan
                                        ? Carbon::parse($lastTagihan->tanggal_tagihan)
                                        : Carbon::parse($booking->tgl_booking);

                                    $jatuhTempo = $tglTerakhir->copy()->addDays(30);
                                    $tenggang = $jatuhTempo->copy()->addDay();

                                    $bolehBayar = $status === 'masa_tenggang' && now()->between($jatuhTempo, $tenggang);
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
                                    <td>{{ Carbon::parse($booking->tgl_booking)->format('d M Y') }}</td>
                                    <td>
                                        @if($bolehBayar)
                                            <a href="{{ route('tagihanpelanggan.create', $booking->id) }}"
                                               class="btn btn-sm btn-primary">
                                                Buat Tagihan
                                            </a>
                                        @elseif($status === 'expired')
                                            <span class="text-danger small">Kamar sudah expired</span>
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
