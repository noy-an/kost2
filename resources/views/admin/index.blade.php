@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Dashboard')

@section('content')
<div class="container-fluid py-4 mt-5"> {{-- mt-5 = ruang untuk navbar fixed --}}
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">

    @php
      $cards = [
        ['title' => 'Total Kamar Kost', 'count' => $kostCount, 'icon' => 'ti-building-community', 'color' => 'primary'],
        ['title' => 'Total Booking', 'count' => $bookingCount, 'icon' => 'ti-calendar-event', 'color' => 'warning'],
        ['title' => 'Total Tagihan', 'count' => $tagihanCount, 'icon' => 'ti-file-invoice', 'color' => 'info'],
        ['title' => 'Total Pembayaran', 'count' => $pembayaranCount, 'icon' => 'ti-credit-card', 'color' => 'success'],
        ['title' => 'Total Pengguna', 'count' => $userCount, 'icon' => 'ti-users', 'color' => 'dark'],
        ['title' => 'Pesan Kontak', 'count' => $kontakCount, 'icon' => 'ti-mail', 'color' => 'danger'],
      ];
    @endphp

    @foreach ($cards as $card)
    <div class="col">
      <div class="card border-0 shadow-sm h-100">
        <div class="card-body d-flex align-items-center">
          <div class="bg-{{ $card['color'] }} text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 50px; height: 50px;">
            <i class="ti {{ $card['icon'] }} fs-5"></i>
          </div>
          <div>
            <small class="text-muted">{{ $card['title'] }}</small>
            <h4 class="mb-0">{{ $card['count'] }}</h4>
          </div>
        </div>
      </div>
    </div>
    @endforeach

  </div>
</div>
@endsection
