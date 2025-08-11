@extends('fe.master')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-10 col-lg-8">
            <div class="card shadow rounded-4 p-4">
                <div class="row">
                    <!-- Avatar + Info -->
                    <div class="col-md-4 text-center border-end">
                        <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default/avatar.png') }}"
                             alt="Avatar"
                             class="rounded-circle shadow mb-3"
                             width="140" height="140">
                        <h5 class="fw-bold">{{ $user->name }}</h5>
                        <span class="badge bg-success text-capitalize">{{ $user->role }}</span>
                        <div class="mt-3">
                            <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm px-4">
                                <i class="fa fa-edit me-1"></i> Edit Profil
                            </a>
                        </div>
                    </div>

                    <!-- Detail Informasi -->
                    <div class="col-md-8">
                        <h5 class="mb-3">Informasi Akun</h5>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="fa fa-user text-primary me-2"></i> Nama Lengkap</span>
                                <span class="fw-semibold">{{ $user->name }}</span>
                            </li>
                       
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="fa fa-phone text-primary me-2"></i> No. Telepon</span>
                                <span class="fw-semibold">{{ $user->phone }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="fa fa-id-card text-primary me-2"></i> KTP Terupload</span>
                                <span>
                                    @if ($user->id_card)
                                        <!-- Tombol untuk membuka modal -->
                                        <a href="#" class="text-decoration-underline text-primary" data-bs-toggle="modal" data-bs-target="#ktpModal">
                                            Lihat KTP
                                        </a>
                                    @else
                                        <span class="text-muted">Belum diunggah</span>
                                    @endif
                                </span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span><i class="fa fa-calendar text-primary me-2"></i> Terdaftar Sejak</span>
                                <span class="fw-semibold">{{ $user->created_at->format('d M Y') }}</span>
                            </li>
                        </ul>

                        <div class="text-end mt-4">
                            <a href="{{ route('profile.edit') }}" class="btn btn-primary px-4">
                                <i class="fa fa-edit me-1"></i> Edit Profil
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>  
</div>

<!-- Modal KTP -->
@if ($user->id_card)
<div class="modal fade" id="ktpModal" tabindex="-1" aria-labelledby="ktpModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content border-0 shadow rounded-3">
      <div class="modal-header">
        <h5 class="modal-title" id="ktpModalLabel"><i class="fa fa-id-card me-2"></i> KTP Anda</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
      </div>
      <div class="modal-body text-center">
        <img src="{{ asset('storage/' . $user->id_card) }}"
             alt="KTP Image"
             class="img-fluid"
             style="max-width: 90%; max-height: 80vh; border-radius: 8px;">
      </div>
    </div>
  </div>
</div>
@endif
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

@endsection
