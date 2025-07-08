@extends('fe.master')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Booking yang Disetujui</h4>
        </div>

        <div class="card-body">
            @if($data->isEmpty())
                <div class="alert alert-info">
                    <strong>Tidak ada booking yang disetujui.</strong>
                    <p>Booking Anda masih dalam proses atau belum ada booking yang dibuat.</p>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>#</th>
                                <th>Nama Pemesan</th>
                                <th>Kamar</th>
                                <th>Harga per Bulan</th>
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
                                    <td>Rp{{ number_format($booking->kost->harga ?? 0) }}</td>
                                    <td>
                                        <span class="badge bg-success">{{ ucfirst($booking->status_booking) }}</span>
                                    </td>
                                    <td>{{ $booking->created_at->format('d M Y') }}</td>
                                    <td>
                                        <a href="{{ route('tagihanpelanggan.create', $booking->id) }}" 
                                           class="btn btn-sm btn-primary">
                                            Buat Tagihan
                                        </a>
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