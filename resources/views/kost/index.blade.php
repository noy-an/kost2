@extends('backend.master')

@section('sidebar')
  @include('backend.sidebar')
@endsection

@section('navbar')
  @include('backend.navbar')
@endsection

@section('title', 'Data Kost')

@section('content')
<div class="card shadow-sm rounded-3 border-0">
  <div class="card-body">
   <div class="row justify-content-between align-items-center mb-4 g-2">
  <div class="col">
    <h4 class="fw-bold text-dark mb-0">🏠 Data Kamar Kost</h4>
  </div>
  <div class="col-auto">
    <div class="d-flex flex-wrap justify-content-end gap-2">
      <a href="{{ route('kost.create') }}" class="btn btn-primary d-flex align-items-center gap-2 shadow-sm">
        <i class="ti ti-plus"></i> Tambah Kost
      </a>
      <button type="button" class="btn btn-danger d-flex align-items-center gap-2 shadow-sm" id="bulk-delete-btn">
        <i class="ti ti-trash"></i> Hapus Terpilih
      </button>
    </div>
  </div>
</div>
    <form action="{{ route('kost.index') }}" method="GET" class="mb-4">
      <div class="input-group">
        <span class="input-group-text bg-light border-end-0"><i class="ti ti-search text-secondary"></i></span>
        <input type="text" name="search" class="form-control border-start-0" placeholder="Cari nomor kamar / status..." value="{{ request('search') }}">
        <button class="btn btn-outline-secondary" type="submit">Cari</button>
        @if(request('search'))
        <a href="{{ route('kost.index') }}" class="btn btn-outline-danger"><i class="ti ti-x"></i> Reset</a>
        @endif
      </div>
    </form>

    <form id="bulk-delete-form" action="{{ route('kost.bulk-delete') }}" method="POST">
      @csrf
      @method('DELETE')
      <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle text-center">
          <thead class="table-light">
            <tr class="text-nowrap">
              <th><input type="checkbox" id="select-all"></th>
              <th>No</th>
              <th>No. Kamar</th>
              <th>Lantai</th>
              <th>Harga</th>
              <th>Air</th>
              <th>Kebersihan</th>
              <th>Ukuran</th>
              <th>Deskripsi</th>
              <th>Status</th>
              <th>Fasilitas</th>
              <th>Foto 1</th>
              <th>Foto 2</th>
              <th>Foto 3</th>
              <th>Aksi</th>
            </tr>
          </thead>
          <tbody>
            @forelse($kost as $index => $item)
            <tr>
              <td><input type="checkbox" name="ids[]" value="{{ $item->id }}" class="select-row"></td>
              <td>{{ $index + $kost->firstItem() }}</td>
              <td>{{ $item->nomor_kamar }}</td>
              <td>{{ $item->nomor_lantai }}</td>
              <td class="text-end">Rp{{ number_format($item->harga, 0, ',', '.') }}</td>
              <td class="text-end">Rp{{ number_format($item->air, 0, ',', '.') }}</td>
              <td class="text-end">Rp{{ number_format($item->kebersihan, 0, ',', '.') }}</td>
              <td class="text-wrap text-start">{{ $item->ukuran_ruangan ?? '-' }}</td>
              <td class="text-wrap text-start">{{ $item->deskripsi ?? '-' }}</td>
              <td>
                <span class="badge 
                  @if($item->status === 'kosong') bg-success
                  @elseif($item->status === 'terisi') bg-danger
                  @else bg-warning text-dark
                  @endif">
                  {{ ucfirst($item->status) }}
                </span>
              </td>
              <td class="text-wrap text-start">{{ $item->fasilitas ?? '-' }}</td>
              @for($i = 1; $i <= 3; $i++)
              <td>
                @php $foto = 'foto'.$i; @endphp
                @if($item->$foto)
                <img src="{{ asset('storage/' . $item->$foto) }}" alt="Foto {{ $i }}" width="50" height="50"
                     class="rounded object-fit-cover" data-bs-toggle="modal" data-bs-target="#fotoModal{{ $item->id }}_{{ $i }}">
                <div class="modal fade" id="fotoModal{{ $item->id }}_{{ $i }}" tabindex="-1" aria-hidden="true">
                  <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content text-center">
                      <div class="modal-body p-3">
                        <img src="{{ asset('storage/' . $item->$foto) }}" class="img-fluid rounded" style="max-height: 400px;" alt="Foto {{ $i }}">
                      </div>
                      <div class="modal-footer border-0">
                        <button type="button" class="btn btn-sm btn-secondary" data-bs-dismiss="modal">Tutup</button>
                      </div>
                    </div>
                  </div>
                </div>
                @endif
              </td>
              @endfor
              <td>
                <div class="d-flex justify-content-center gap-1">
                  <a href="{{ route('kost.edit', $item->id) }}" class="btn btn-sm btn-warning" title="Edit">
                    <i class="ti ti-edit"></i>
                  </a>
                </div>
              </td>
            </tr>
            @empty
            <tr>
              <td colspan="15" class="text-center text-muted py-4">
                <i class="ti ti-info-circle"></i> Tidak ada data kost tersedia.
              </td>
            </tr>
            @endforelse
          </tbody>
        </table>
      </div>
    </form>

    <div class="d-flex justify-content-end mt-3">
      {{ $kost->appends(request()->query())->links('pagination::bootstrap-5') }}
    </div>
  </div>
</div>

<!-- SweetAlert -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
  document.addEventListener('DOMContentLoaded', function () {
    // Individual delete
    document.querySelectorAll('.btn-delete').forEach(button => {
      button.addEventListener('click', function () {
        const form = this.closest('form');
        Swal.fire({
          title: 'Yakin ingin menghapus?',
          text: 'Data kost akan dihapus permanen.',
          icon: 'warning',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#6c757d',
          confirmButtonText: 'Hapus',
          cancelButtonText: 'Batal'
        }).then((result) => {
          if (result.isConfirmed) {
            form.submit();
          }
        });
      });
    });

    // Select All
    document.getElementById('select-all').addEventListener('click', function () {
      document.querySelectorAll('.select-row').forEach(cb => cb.checked = this.checked);
    });

    // Bulk delete
    document.getElementById('bulk-delete-btn').addEventListener('click', function () {
      const selected = document.querySelectorAll('.select-row:checked');
      if (selected.length === 0) {
        Swal.fire('Tidak ada data dipilih!', 'Pilih minimal satu kamar untuk dihapus.', 'warning');
        return;
      }

      Swal.fire({
        title: 'Hapus semua yang dipilih?',
        text: 'Data kost akan dihapus permanen!',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Ya, hapus!',
        cancelButtonText: 'Batal'
      }).then(result => {
        if (result.isConfirmed) {
          document.getElementById('bulk-delete-form').submit();
        }
      });
    });
  });
</script>
@endsection