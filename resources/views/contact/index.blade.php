@extends('fe.master') {{-- Ganti jika pakai layout lain --}}
@section('content')

<section class="contact-section spad">
    <div class="container">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            <div class="col-lg-4">
                <div class="contact-text">
                    <h2>Contact Info</h2>
                    <p>Silakan hubungi kami kapan saja jika ada pertanyaan.</p>
                    <table>
                        <tbody>
                            <tr>
                                <td class="c-o">Address:</td>
                                <td>856 Cordia Extension Apt. 356, Lake, US</td>
                            </tr>
                            <tr>
                                <td class="c-o">Phone:</td>
                                <td>(12) 345 67890</td>
                            </tr>
                            <tr>
                                <td class="c-o">Email:</td>
                                <td>info@contoh.com</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="col-lg-7 offset-lg-1">
                <form action="{{ route('contact.store') }}" method="POST" class="contact-form">
                    @csrf
                    <div class="row">
                        <div class="col-lg-6 mb-3">
                            <input type="text" name="nama" placeholder="Your Name" required>
                        </div>
                        <div class="col-lg-6 mb-3">
                            <input type="email" name="email" placeholder="Your Email" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <input type="text" name="subjek" placeholder="Subject" required>
                        </div>
                        <div class="col-lg-12 mb-3">
                            <textarea name="pesan" placeholder="Your Message" required></textarea>
                        </div>
                        <div class="col-lg-12">
                            <button type="submit" class="primary-btn">Submit Now</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="map mt-5">
            <iframe
                src="https://www.google.com/maps/embed?pb=!1m18..."
                height="470" style="border:0;" allowfullscreen=""></iframe>
        </div>
    </div>
</section>

@endsection
