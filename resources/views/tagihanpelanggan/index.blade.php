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
                    <p>Anda belum melakukan booking atau belum ada yang disetujui.</p>
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
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $booking->user->name ?? '-' }}</td>
                                    <td>Kamar {{ $booking->kost->nomor_kamar ?? '-' }}</td>
                                    <td>Rp{{ number_format($booking->kost->harga ?? 0, 0, ',', '.') }}</td>
                                    <td>Rp{{ number_format($booking->dp ?? 0, 0, ',', '.') }}</td>
                                    <td>
                                        @php
                                            $status = strtolower($booking->status_booking);
                                        @endphp
                                        <span class="badge 
                                            {{ $status === 'disetujui' ? 'bg-success' : ($status === 'pending' ? 'bg-warning text-dark' : 'bg-secondary') }}">
                                            {{ ucfirst($status) }}
                                        </span>
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($booking->tgl_booking)->format('d M Y') }}</td>
                                    <td>
                                        @if($status === 'disetujui')
                                            <a href="{{ route('tagihanpelanggan.create', $booking->id) }}"
                                               class="btn btn-sm btn-primary">
                                                Buat Tagihan
                                            </a>
                                        @elseif($booking->payment_url)
                                            <a href="{{ $booking->payment_url }}" 
                                               class="btn btn-sm btn-success"
                                               target="_blank">
                                                Lanjutkan Pembayaran
                                            </a>
                                        @else
                                            <span class="text-muted">Menunggu Persetujuan</span>
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