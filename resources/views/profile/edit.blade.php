@extends('fe.master')
@section('content')

<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-9">
            <div class="card shadow rounded-4 p-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h4 class="mb-0"><i class="fa fa-user-edit me-2"></i> Edit Profil Saya</h4>
                </div>

                <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="row">
                        <!-- Kiri: Avatar dan KTP -->
                        <div class="col-md-4 text-center border-end">
                            <div class="mb-4">
                                <label class="fw-bold mb-2">Foto Profil</label><br>
                                <img src="{{ $user->avatar ? asset('storage/' . $user->avatar) : asset('default/avatar.png') }}"
                                     class="rounded-circle shadow mb-2"
                                     width="130" height="130" alt="Avatar">
                                <input type="file" name="avatar" class="form-control mt-2">
                            </div>

                            {{-- <div>
                                <label class="fw-bold mb-2">Foto KTP</label><br>
                                @if ($user->id_card)
                                    <img src="{{ asset('storage/' . $user->id_card) }}" class="rounded shadow-sm mb-2" width="130">
                                @else
                                    <p class="text-muted">Belum diunggah</p>
                                @endif
                                <input type="file" name="id_card" class="form-control mt-2">
                            </div> --}}
                        </div>

                        <!-- Kanan: Form -->
                        <div class="col-md-8">
                            <div class="mb-3">
                                <label class="form-label">Nama Lengkap</label>
                                <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                            </div>

                         

                            <div class="mb-3">
                                <label class="form-label">No. Telepon</label>
                                <input type="text" name="phone" class="form-control" value="{{ old('phone', $user->phone) }}" required>
                            </div>

                            <div class="text-end mt-4">
                                <button type="submit" class="btn btn-success px-4">
                                    <i class="fa fa-save me-1"></i> Simpan Perubahan
                                </button>
                                <a href="{{ route('profile.index') }}" class="btn btn-outline-secondary btn-sm">
                        <i class="fa fa-arrow-left me-1"></i> Kembali
                    </a>
                            </div>
                        </div>
                    </div> <!-- /row -->
                </form>
            </div>
        </div>
    </div>
</div>

@endsection
