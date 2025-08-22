@php use Carbon\Carbon; @endphp

@extends('fe.master')

@section('content')
<div class="container mt-5">
    <div class="card shadow-sm">
        <div class="card-header bg-dark text-white">
            <h4 class="mb-0">Pembayaran Cicilan,Full atau Perpanjang</h4>
        </div>

        <div class="card-body">
            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-danger">{{ session('error') }}</div>
            @endif

            <form id="paymentForm">
                @csrf
                <input type="hidden" name="booking_id" value="{{ $booking->id }}">
                <input type="hidden" name="order_id" id="order_id">

                <div class="row mb-4">
                    <div class="col-md-6">
                        <h5>Detail Booking</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Nama</th>
                                <td>{{ $booking->user->name ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Kamar</th>
                                <td>Kamar {{ $booking->kost->nomor_kamar ?? '-' }}</td>
                            </tr>
                            <tr>
                                <th>Harga/Bulan</th>
                                <td>Rp{{ number_format($booking->kost->harga ?? 0, 0, ',', '.') }}</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-6">
                        <h5>Komponen Tagihan</h5>
                        <table class="table table-bordered">
                            <tr>
                                <th>Komponen</th>
                                <th>Nominal</th>
                                <th>Sisa Tagihan</th>
                            </tr>
                            @if($sisaKost > 0)
                            <tr>    
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input component-checkbox" type="checkbox" name="components[]" value="kost" id="kostCheckbox" checked>
                                        <label class="form-check-label" for="kostCheckbox">
                                            Sewa Kamar
                                        </label>
                                    </div>
                                </td>
                                <td>Rp{{ number_format($booking->kost->harga, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($sisaKost, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($sisaAir > 0)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input component-checkbox" type="checkbox" name="components[]" value="air" id="airCheckbox">
                                        <label class="form-check-label" for="airCheckbox">
                                            Air
                                        </label>
                                    </div>
                                </td>
                                <td>Rp{{ number_format($air, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($sisaAir, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                            @if($sisaKebersihan > 0)
                            <tr>
                                <td>
                                    <div class="form-check">
                                        <input class="form-check-input component-checkbox" type="checkbox" name="components[]" value="kebersihan" id="kebersihanCheckbox">
                                        <label class="form-check-label" for="kebersihanCheckbox">
                                            Kebersihan
                                        </label>
                                    </div>
                                </td>
                                <td>Rp{{ number_format($kebersihan, 0, ',', '.') }}</td>
                                <td>Rp{{ number_format($sisaKebersihan, 0, ',', '.') }}</td>
                            </tr>
                            @endif
                        </table>
                        <div class="alert alert-info">
                            <strong>Total Terpilih: </strong>
                            <span id="totalTerpilih">Rp0</span>
                        </div>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="nominal_pembayaran" class="form-label">Nominal Pembayaran</label>
                    <div class="input-group mb-3">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="nominal_pembayaran" name="nominal_pembayaran" 
                               min="5000" required>
                    </div>
                    <small class="text-muted">Minimum pembayaran: Rp5.000</small>
                    <div class="mt-2">
                        <small class="text-info">Note: Jika nominal melebihi total tagihan, kelebihan akan masuk sebagai deposit.</small>
                    </div>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" id="payButton" class="btn btn-primary">Lanjutkan ke Pembayaran</button>
                </div>
            </form>

            <!-- Modal untuk menampilkan info QR Code -->
            <div id="qrInfoModal" class="modal fade" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">📱 QR Code Dikirim ke WhatsApp</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div class="alert alert-success">
                                <i class="fas fa-check-circle fa-2x mb-2"></i>
                                <h5>QR Code Berhasil Dikirim!</h5>
                                <p>QR Code pembayaran telah dikirim ke nomor WhatsApp Anda. Silakan cek pesan WhatsApp untuk melihat QR code.</p>
                            </div>
                            <div class="alert alert-warning">
                                <strong>⚠️ Penting:</strong>
                                <p class="mb-0">QR code di WhatsApp hanya untuk referensi. Untuk menyelesaikan pembayaran, gunakan halaman Midtrans yang akan terbuka.</p>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Mengerti</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Tambahkan script Snap Midtrans -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="{{ env('MIDTRANS_CLIENT_KEY') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
window.snap = window.snap || null;
$(document).ready(function() {

    // Panggil sekali saat halaman pertama load
    updateTotalSelected();

    // Panggil lagi setiap checkbox berubah
    $('.component-checkbox').change(function() {
        updateTotalSelected();
    });

    function updateTotalSelected() {
        let total = 0;
        $('.component-checkbox:checked').each(function() {
            const comp = $(this).val();
            if (comp === 'kost') total += {{ $sisaKost }};
            else if (comp === 'air') total += {{ $sisaAir }};
            else if (comp === 'kebersihan') total += {{ $sisaKebersihan }};
        });

        // Update teks total
        $('#totalTerpilih').text('Rp' + new Intl.NumberFormat('id-ID').format(total));

        // Isi otomatis nominal pembayaran
        $('#nominal_pembayaran').val(total);
    }

    $('#payButton').click(function() {
        const nominal = $('#nominal_pembayaran').val();
        const components = $('.component-checkbox:checked').map(function() {
            return $(this).val();
        }).get();

        if (components.length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Pilih minimal satu komponen pembayaran'
            });
            return;
        }

        if (!nominal || nominal < 5000) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Nominal pembayaran minimal Rp5.000'
            });
            return;
        }

        processPayment(nominal, components);
    });

    function processPayment(nominal, components) {
        $('#payButton').prop('disabled', true).html('<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...');

        $.ajax({
            url: '{{ route("cicilan.process") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                booking_id: '{{ $booking->id }}',
                nominal_pembayaran: nominal,
                components: components
            },
            success: function(response) {
                $('#payButton').prop('disabled', false).html('Lanjutkan ke Pembayaran');
                
                if (response.success) {
                    // Tampilkan modal info QR Code jika QR berhasil dikirim
                    if (response.qr_code_url) {
                        $('#qrInfoModal').modal('show');
                        
                        // Auto tutup modal setelah 5 detik dan lanjut ke Snap
                        setTimeout(function() {
                            $('#qrInfoModal').modal('hide');
                            openSnapPayment(response);
                        }, 5000);
                        
                        // Atau user bisa langsung tutup modal dan lanjut
                        $('#qrInfoModal').on('hidden.bs.modal', function() {
                            openSnapPayment(response);
                        });
                    } else {
                        // Langsung ke Snap jika tidak ada QR
                        openSnapPayment(response);
                    }
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Terjadi kesalahan'
                    });
                }
            },
            error: function(xhr) {
                $('#payButton').prop('disabled', false).html('Lanjutkan ke Pembayaran');
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan: ' + (xhr.responseJSON?.message || 'Server error')
                });
            }
        });
    }

    function openSnapPayment(response) {
        if (typeof snap !== 'undefined' && response.snap_token) {
            snap.pay(response.snap_token, {
                onSuccess: function(result) {
                    console.log('Payment success:', result);
                    savePayment(response.order_id, $('#nominal_pembayaran').val(), $('.component-checkbox:checked').map(function() {
                        return $(this).val();
                    }).get());
                },
                onPending: function(result) {
                    console.log('Payment pending:', result);
                    Swal.fire({
                        icon: 'info',
                        title: 'Pembayaran Pending',
                        text: 'Pembayaran Anda sedang diproses. Mohon tunggu konfirmasi.'
                    }).then(() => {
                        window.location.href = '{{ route("tagihanpelanggan.index") }}';
                    });
                },
                onError: function(result) {
                    console.log('Payment error:', result);
                    Swal.fire({
                        icon: 'error',
                        title: 'Pembayaran Gagal',
                        text: 'Pembayaran gagal diproses. Silakan coba lagi.'
                    });
                },
                onClose: function() {
                    console.log('Payment popup closed');
                    Swal.fire({
                        icon: 'warning',
                        title: 'Pembayaran Dibatalkan',
                        text: 'Anda menutup popup pembayaran. Transaksi dibatalkan.'
                    });
                }
            });
        } else {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Snap JS tidak terload atau token tidak valid. Silakan refresh halaman.'
            });
        }
    }

    function savePayment(orderId, nominal, components) {
        $.ajax({
            url: '{{ route("cicilan.store") }}',
            method: 'POST',
            data: {
                _token: '{{ csrf_token() }}',
                booking_id: '{{ $booking->id }}',
                nominal_pembayaran: nominal,
                components: components,
                order_id: orderId
            },
            success: function(response) {
                if (response.success) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Pembayaran Berhasil!',
                        text: 'Pembayaran Anda telah berhasil diproses.',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        window.location.href = response.redirect || '{{ route("tagihanpelanggan.index") }}';
                    });
                } else {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response.message || 'Gagal menyimpan data pembayaran'
                    });
                }
            },
            error: function(xhr) {
                console.log('Save payment error:', xhr.responseJSON);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error menyimpan data: ' + (xhr.responseJSON?.message || 'Server error')
                });
            }
        });
    }
});
</script>

@endsection