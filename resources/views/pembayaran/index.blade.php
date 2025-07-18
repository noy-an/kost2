@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Pembayaran')

@section('content')
<div class="card shadow-sm rounded-3 border-0">
  <div class="card-body">

    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
      <h4 class="fw-bold text-dark mb-0">💰 Data Pembayaran</h4>
      <div class="d-flex gap-2">
        <a href="{{ route('pembayaran.index', ['filter' => 'dp']) }}" class="btn btn-outline-primary {{ $filter == 'dp' ? 'active' : '' }}">Pembayaran DP</a>
        <a href="{{ route('pembayaran.index', ['filter' => 'tagihan']) }}" class="btn btn-outline-primary {{ $filter == 'tagihan' ? 'active' : '' }}">Pembayaran Tagihan</a>
      </div>
    </div>

    <form action="{{ route('pembayaran.index', ['filter' => $filter]) }}" method="GET" class="row g-2 mb-3">
      <input type="hidden" name="filter" value="{{ $filter }}">
      <div class="col-md-3">
        <input type="date" name="dari" class="form-control" value="{{ request('dari') }}">
      </div>
      <div class="col-md-3">
        <input type="date" name="sampai" class="form-control" value="{{ request('sampai') }}">
      </div>
      <div class="col-md-3">
        <input type="text" name="nama" class="form-control" placeholder="Cari nama user..." value="{{ request('nama') }}">
      </div>
      <div class="col-md-3 d-flex gap-2">
        <button type="submit" class="btn btn-outline-primary w-100">Filter</button>
        @if(request('nama') || request('dari') || request('sampai'))
        <a href="{{ route('pembayaran.index', ['filter' => $filter]) }}" class="btn btn-outline-danger">
          <i class="ti ti-x"></i>
        </a>
        @endif
      </div>
    </form>

    @if(request('dari') && request('sampai'))
    <p class="text-muted">
      Menampilkan data dari <strong>{{ request('dari') }}</strong> sampai <strong>{{ request('sampai') }}</strong>
    </p>
    @endif

    @if(request('nama'))
    <p class="text-muted">
      Mencari nama user: <strong>{{ request('nama') }}</strong>
    </p>
    @endif


   @if ($filter == 'dp')
<h6 class="fw-semibold text-dark mb-3">Data Pembayaran DP Booking</h6>

<div class="table-responsive">
  <table class="table table-bordered table-hover align-middle text-center">
    <thead class="table-light">
      <tr class="text-nowrap">
        <th>No</th>
        <th>Nama User</th>
        <th>No. Kamar</th>
        <th>DP</th>
        <th>Jenis Pembayaran</th>
        <th>Tanggal Booking</th>
        <th>Status</th>
      </tr>
    </thead>
    <tbody>
      @forelse($bookings as $index => $booking)
      <tr>
        <td>{{ $index + 1 }}</td>
        <td>{{ $booking->user->name ?? '-' }}</td>
        <td>{{ $booking->kost->nomor_kamar ?? '-' }}</td>
        <td class="text-end">Rp{{ number_format($booking->dp, 0, ',', '.') }}</td>
        <td class="text-end">{{ $booking->jenis_pembayaran }}</td>
        <td>{{ \Carbon\Carbon::parse($booking->tgl_booking)->translatedFormat('d F Y') }}</td>

        @php
        $lastTagihan = $booking->tagihan->where('status_pembayaran', 'success')->sortByDesc('tanggal_tagihan')->first();
        $tglTagihan = $lastTagihan ? \Carbon\Carbon::parse($lastTagihan->tanggal_tagihan) : \Carbon\Carbon::parse($booking->tgl_booking);
        $jatuhTempo = $tglTagihan->copy()->addDays(30);
        $tenggang = $jatuhTempo->copy()->addDay();
        $now = \Carbon\Carbon::now();

        if ($now->gt($tenggang)) {
            $status = 'Expired';
            $badge = 'bg-danger';
        } elseif ($now->gt($jatuhTempo)) {
            $status = 'Masa Tenggang';
            $badge = 'bg-warning text-dark';
        } else {
            $status = 'Aktif';
            $badge = 'bg-success';
        }
        @endphp

        <td><span class="badge {{ $badge }}">{{ $status }}</span></td>
      </tr>
      @empty
      <tr>
        <td colspan="7" class="text-center text-muted py-4">
          <i class="ti ti-info-circle"></i> Tidak ada data DP Booking.
        </td>
      </tr>
      @endforelse
        </tbody>
      </table>
    </div>

    @elseif ($filter == 'tagihan')
    <h6 class="fw-semibold text-dark mb-3">Data Pembayaran Tagihan</h6>
    <div class="table-responsive">
      <table class="table table-bordered align-middle text-center">
        <thead class="table-light">
          <tr>
            <th>No</th>
            <th>User</th>
            <th>Kost</th>
            <th>Bulan Tagihan</th>
            <th>Jumlah Dibayar</th>
          </tr>
        </thead>
        <tbody>
          @forelse($tagihans as $no => $tagihan)
          <tr>
            <td>{{ $no + 1 }}</td>
            <td>{{ $tagihan->booking->user->name ?? '-' }}</td>
            <td class="text-start">
              <strong>Kamar:</strong> {{ $tagihan->booking->kost->nomor_kamar ?? '-' }}<br>
              <strong>Lantai:</strong> {{ $tagihan->booking->kost->nomor_lantai ?? '-' }}
            </td>
            <td>{{ $tagihan->nama_bulan_dinamis }}</td>
            <td class="text-end">Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</td>
          </tr>
          @empty
          <tr>
            <td colspan="6" class="text-center text-muted py-4">
              <i class="ti ti-info-circle"></i> Tidak ada data pembayaran tagihan.
            </td>
          </tr>
          @endforelse
        </tbody>
      </table>
    </div>
    @endif
  </div>
</div>
@endsection
