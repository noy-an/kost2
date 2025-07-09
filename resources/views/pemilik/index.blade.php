@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('content')
<div class="container-fluid py-4 mt-5">

  <!-- Heading & Welcome -->
  <div class="mb-4 text-center">
    <h2 class="fw-bold text-primary">Selamat Datang, Pemilik Kost! 👋</h2>
    <p class="text-muted">Pantau aktivitas kost kamu dengan mudah dan cepat dari satu tempat.</p>
    <hr style="width: 60px; border-top: 3px solid #3498db; margin: auto;">
  </div>

  <!-- Information Cards -->
  <div class="row row-cols-1 row-cols-sm-2 row-cols-md-3 g-4">

    @php
      $cards = [
        ['title' => 'Total Kamar Kost', 'count' => $kostCount, 'icon' => 'ti-building-community', 'color' => 'primary', 'tooltip' => 'Jumlah total kamar kost yang tersedia'],
        ['title' => 'Total Booking', 'count' => $bookingCount, 'icon' => 'ti-calendar-event', 'color' => 'warning', 'tooltip' => 'Jumlah booking yang sudah masuk'],
        ['title' => 'Total Bayar Tagihan', 'count' => $tagihanCount, 'icon' => 'ti-file-invoice', 'color' => 'info', 'tooltip' => 'Pembayaran tagihan yang telah diterima'],
      ];
    @endphp

    @foreach ($cards as $index => $card)
    <div class="col">
      <div class="card border-0 shadow-sm h-100 animate__animated animate__fadeInUp animate__delay-{{ $index }}s"
           style="transition: transform 0.2s; background: linear-gradient(to right, #f8fbff, #ffffff);">
        <div class="card-body d-flex align-items-center hover-zoom">
          <div class="bg-{{ $card['color'] }} text-white rounded-circle d-flex align-items-center justify-content-center me-3"
               style="width: 50px; height: 50px;" data-bs-toggle="tooltip" title="{{ $card['tooltip'] }}">
            <i class="ti {{ $card['icon'] }} fs-5"></i>
          </div>
          <div>
            <small class="text-muted fw-semibold">{{ $card['title'] }}</small>
            <h4 class="mb-0 fw-bold text-dark">{{ $card['count'] }}</h4>
          </div>
        </div>
      </div>
    </div>
    @endforeach

  </div>

  <!-- Motivational Section -->
  <div class="text-center mt-5">
    <p class="text-muted fst-italic">Terus pantau perkembangan kost kamu, dan tingkatkan pelayanan untuk penghuni yang lebih puas. 🚀</p>
  </div>

</div>
@endsection

@section('styles')
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css"/>
<style>
  .hover-zoom:hover {
    transform: scale(1.03);
  }

  .card {
    border-radius: 12px;
  }

  .card-body {
    background-color: #ffffff;
    border-radius: 12px;
  }

  h2.fw-bold {
    font-size: 28px;
  }

  .text-muted.fst-italic {
    font-size: 14px;
    color: #7f8c8d !important;
  }
</style>
@endsection

@section('scripts')
<script>
  // Bootstrap tooltip init
  document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    tooltipTriggerList.forEach(function (tooltipTriggerEl) {
      new bootstrap.Tooltip(tooltipTriggerEl)
    })
  });
</script>
@endsection
