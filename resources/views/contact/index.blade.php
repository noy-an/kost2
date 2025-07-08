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
                    <p class="text-muted">Silakan hubungi kami kapan saja jika ada pertanyaan atau membutuhkan informasi kost.</p>
                    <ul class="list-unstyled text-muted mt-4">
                        <li class="mb-3">
                            <strong class="text-dark">Alamat:</strong><br>
                            <a href="https://www.google.com/maps?q=Gedung+BITC+Cimahi+Jawa+Barat+Indonesia" target="_blank" style="text-decoration: none; color: inherit;">
                                 Gedung BITC, Cimahi, Jawa Barat, Indonesia
                            </a>
                        </li>
                        <li class="mb-3">
                            <strong class="text-dark">WhatsApp:</strong><br>
                            <a href="https://wa.me/6285810537250" target="_blank" style="text-decoration: none; color: inherit;">
                                 0858-1053-7250
                            </a>
                        </li>
                        <li>
                            <strong class="text-dark">Email:</strong><br>
                            <a href="https://mail.google.com/mail/?view=cm&to=hafizhrmuhammad@gmail.com" target="_blank" style="text-decoration: none; color: inherit;">
                                 hafizhrmuhammad@gmail.com
                            </a>
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
                                <input type="text" name="subjek" class="form-control rounded" placeholder="Subject (isi dengan kata Testimoni jika ingin memberi Testimoni)" required>
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
        {{-- <div class="map rounded shadow-sm overflow-hidden">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18..."
                height="470" width="100%" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div> --}}
        <div class="map rounded shadow-sm overflow-hidden">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d3960.904162354329!2d107.54120591431807!3d-6.900548795017979!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x2e68e5ff379e6dcb%3A0x37069c8ffdc2db7d!2sBalai%20Industri%20Kreatif%20TIK%20(BITC)%20Cimahi!5e0!3m2!1sid!2sid!4v1720427513506!5m2!1sid!2sid"
                width="100%" height="470" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade">
            </iframe>
        </div>

    </div>
</section>

@endsection
