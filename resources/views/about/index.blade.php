@extends('fe.master')

@section('content')

<!-- Hero Section -->
<section class="hero-section d-flex align-items-center text-white" style="height: 55vh; background: linear-gradient(rgba(0,0,0,.5), rgba(0,0,0,.5)), url('https://images.unsplash.com/photo-1600585154340-be6161a56a0c?auto=format&fit=crop&w=1950&q=80') center/cover no-repeat;">
  <div class="container text-center">
    <h1 class="display-4 fw-semibold">Tentang Kost Kami</h1>
    <p class="lead mt-3 text-light">Tempat tinggal nyaman, aman, dan strategis untuk kamu yang butuh kost ideal.</p>
  </div>
</section>

<!-- Tentang Kost -->
<section class="bg-light py-5">
  <div class="container">
    <div class="row align-items-center gy-4">
      <div class="col-lg-6">
        <h2 class="fw-bold">Selamat Datang di Kost Kita</h2>
        <p class="text-muted mt-3">Kost nyaman dan strategis untuk mahasiswa, pekerja, maupun keluarga. Hadir dengan berbagai pilihan kamar dan fasilitas lengkap.</p>
      </div>
      <div class="col-lg-6">
        <ul class="list-unstyled ps-0">
          @foreach([
            'Sarapan tersedia untuk penyewa bulanan.',
            'Layanan laundry 3x dalam seminggu.',
            'Internet Wifi gratis dan stabil.',
            'Bonus menarik untuk penyewa tahunan.',
            'Akses 24 jam dengan keamanan terjamin.'
          ] as $item)
          <li class="d-flex align-items-start mb-2">
            <i class="bi bi-check-circle-fill text-success me-2 fs-5"></i>
            <span>{{ $item }}</span>
          </li>
          @endforeach
        </ul>
      </div>
    </div>
    
    <!-- Fasilitas Highlight -->
    <div class="row mt-5 g-4">
      @foreach([
        ['title' => 'Dapur Bersama', 'img' => 'about1.webp'],
        ['title' => 'Laundry & Parkiran', 'img' => 'about2.jpg'],
        ['title' => 'Tempat Bersantai', 'img' => 'about3.jpg'],
      ] as $facility)
      <div class="col-md-4">
        <div class="position-relative overflow-hidden rounded shadow-sm" style="height: 220px; background: url('{{ asset('front-end/img/'.$facility['img']) }}') center/cover no-repeat;">
          <div class="position-absolute bottom-0 start-0 w-100 bg-dark bg-opacity-50 text-white text-center py-2">
            <h5 class="mb-0">{{ $facility['title'] }}</h5>
          </div>
        </div>
      </div>
      @endforeach
    </div>
  </div>
</section>

<!-- Gallery -->
<section class="py-5 bg-white">
  <div class="container">
    <div class="text-center mb-5">
      <span class="text-uppercase text-muted small">Galeri Kami</span>
      <h2 class="fw-bold mt-2">Suasana Kost & Fasilitas</h2>
    </div>
    <div class="row g-3">
      <div class="col-lg-6">
        <div class="rounded overflow-hidden" style="background: url('{{ asset('front-end/img/about6.jpg') }}') center/cover no-repeat; height: 300px;">
          <div class="bg-dark bg-opacity-50 text-white p-2">
            <h5>Kamar Nyaman</h5>
          </div>
        </div>
        <div class="row pt-3 g-3">
          @foreach([
            ['title' => 'Kamar AC', 'img' => 'about5.jpg'],
            ['title' => 'Kamar Mandi Dalam', 'img' => 'about4.avif'],
          ] as $gallery)
          <div class="col-sm-6">
            <div class="rounded overflow-hidden" style="background: url('{{ asset('front-end/img/'.$gallery['img']) }}') center/cover no-repeat; height: 150px;">
              <div class="bg-dark bg-opacity-50 text-white p-2">
                <h6 class="mb-0">{{ $gallery['title'] }}</h6>
              </div>
            </div>
          </div>
          @endforeach
        </div>
      </div>
      <div class="col-lg-6">
        <div class="rounded overflow-hidden" style="background: url('{{ asset('front-end/img/about4.jpg') }}') center/cover no-repeat; height: 465px;">
          <div class="bg-dark bg-opacity-50 text-white p-2">
            <h5>Area Bersama</h5>
          </div>
        </div>
      </div>
    </div>
  </div>
</section>

@endsection
