@extends('fe.master') {{-- Ganti jika pakai layout lain --}}
@section('content')

<section class="contact-section spad bg-light">
    <div class="container">

        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row mb-5">
            <!-- Contact Info -->
            <div class="col-lg-4 mb-4">
                <div class="bg-white p-4 rounded shadow-sm h-100">
                    <h3 class="fw-bold mb-3 text-primary">Hubungi Kami</h3>
                    <p class="text-muted">Silakan hubungi kami kapan saja jika ada pertanyaan.</p>
                    <ul class="list-unstyled text-muted mt-4">
                        <li class="mb-3">
                            <strong class="text-dark">Address:</strong><br>
                            856 Cordia Extension Apt. 356, Lake, US
                        </li>
                        <li class="mb-3">
                            <strong class="text-dark">Phone:</strong><br>
                            (12) 345 67890
                        </li>
                        <li>
                            <strong class="text-dark">Email:</strong><br>
                            info@contoh.com
                        </li>
                    </ul>
                </div>
            </div>

            <!-- Contact Form -->
            <div class="col-lg-8">
                <div class="bg-white p-4 rounded shadow-sm">
                    <h3 class="fw-bold mb-4 text-primary">Form Kontak</h3>
                    <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <input type="text" name="nama" class="form-control rounded" placeholder="Your Name" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <input type="email" name="email" class="form-control rounded" placeholder="Your Email" required>
                            </div>
                            <div class="col-12 mb-3">
                                <input type="text" name="subjek" class="form-control rounded" placeholder="Subject" required>
                            </div>
                            <div class="col-12 mb-4">
                                <textarea name="pesan" class="form-control rounded" rows="5" placeholder="Your Message" required></textarea>
                            </div>
                            <div class="col-12">
                                <button type="submit" class="btn btn-primary px-4 py-2 rounded-pill">Submit Now</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Google Maps -->
        <div class="map rounded shadow-sm overflow-hidden">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18..."
                height="470" width="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>
    </div>
</section>

@endsection
