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
                    </div>
                </div>

                <div class="mb-3">
                    <label for="nominal_pembayaran" class="form-label">Nominal Pembayaran</label>
                    <div class="input-group mb-3">
                        <input type="number" class="form-control" id="nominal_pembayaran" name="nominal_pembayaran" 
                               min="1" max="{{ $totalTagihan }}" required readonly>
                        <div class="btn-group" role="group">
                            <button class="btn btn-outline-secondary percentage-btn" type="button" data-percentage="1">1%</button>
                            <button class="btn btn-outline-secondary percentage-btn" type="button" data-percentage="5">5%</button>
                            <button class="btn btn-outline-secondary percentage-btn" type="button" data-percentage="10">10%</button>
                            <button class="btn btn-outline-secondary percentage-btn" type="button" data-percentage="25">25%</button>
                            <button class="btn btn-outline-secondary percentage-btn" type="button" data-percentage="50">50%</button>
                            <button class="btn btn-outline-secondary percentage-btn" type="button" data-percentage="75">75%</button>
                            <button class="btn btn-outline-secondary percentage-btn" type="button" data-percentage="100">100%</button>
                        </div>
                    </div>
                    <small class="text-muted">Maksimal: Rp{{ number_format($totalTagihan, 0, ',', '.') }}</small>
                </div>

                <div class="d-grid gap-2">
                    <button type="button" id="payButton" class="btn btn-primary">Lanjutkan ke Pembayaran</button>
                </div>
            </form>

            <div id="paymentModal" class="modal fade" tabindex="-1" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header">
                            <h5 class="modal-title">Pembayaran</h5>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                        <div class="modal-body text-center">
                            <div id="paymentContent"></div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
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
    // Update max payment when components are selected
    $('.component-checkbox').change(function() {
        updateMaxPayment();
    });

    function calculateComponentTotals() {
        let components = {};
        $('.component-checkbox:checked').each(function() {
            const component = $(this).val();
            if (component === 'kost') components.kost = {{ $sisaKost }};
            if (component === 'air') components.air = {{ $sisaAir }};
            if (component === 'kebersihan') components.kebersihan = {{ $sisaKebersihan }};
        });
        return components;
    }

    function updateMaxPayment() {
        let total = 0;
        const components = calculateComponentTotals();
        for (const component in components) {
            total += components[component];
        }
        
        $('#nominal_pembayaran').attr('max', total);
        $('.percentage-btn').prop('disabled', false);
        
        // Reset nominal pembayaran jika melebihi max baru
        const currentNominal = parseInt($('#nominal_pembayaran').val()) || 0;
        if (currentNominal > total) {
            $('#nominal_pembayaran').val('');
        }
    }

    // Percentage buttons handler - FIXED CALCULATION
    $('.percentage-btn').click(function() {
        const percentage = parseInt($(this).data('percentage'));
        const components = calculateComponentTotals();
        
        let totalAmount = 0;
        for (const component in components) {
            totalAmount += components[component];
        }
        
        const calculatedAmount = Math.floor(totalAmount * (percentage / 100));
        $('#nominal_pembayaran').val(calculatedAmount);
    });

    $('#payButton').click(function() {
        const nominal = $('#nominal_pembayaran').val();
        const maxAmount = parseInt($('#nominal_pembayaran').attr('max'));
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

        if (!nominal || nominal <= 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Masukkan nominal pembayaran yang valid'
            });
            return;
        }

        if (parseInt(nominal) > maxAmount) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Nominal melebihi jumlah tagihan yang tersisa'
            });
            return;
        }

        processPayment(nominal, components);
    });

    function processPayment(nominal, components) {
        // Tampilkan loading indicator
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
                    // Pastikan snap telah terload
                    if (typeof snap !== 'undefined') {
                        snap.pay(response.snap_token, {
                            onSuccess: function(result) {
                                // Simpan data pembayaran setelah sukses
                                savePayment(response.order_id, nominal, components);
                            },
                            onPending: function(result) {
                                alert('Menunggu pembayaran...');
                                window.location.href = '{{ route("tagihanpelanggan.index") }}';
                            },
                            onError: function(result) {
                                alert('Pembayaran gagal: ' + JSON.stringify(result));
                            },
                            onClose: function() {
                                alert('Anda menutup popup pembayaran');
                            }
                        });
                    } else {
                        alert('Error: Snap JS tidak terload. Silakan refresh halaman.');
                    }
                } else {
                    alert('Error: ' + response.message);
                }
            },
            error: function(xhr) {
                $('#payButton').prop('disabled', false).html('Lanjutkan ke Pembayaran');
                alert('Terjadi kesalahan: ' + xhr.responseJSON.message);
            }
        });
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
                    window.location.href = '{{ route("tagihanpelanggan.index") }}';
                } else {
                    alert('Gagal menyimpan data pembayaran: ' + response.message);
                }
            },
            error: function(xhr) {
                alert('Error menyimpan data: ' + xhr.responseJSON.message);
            }
        });
    }

    // Initialize on page load
    updateMaxPayment();
});
</script>
@endsection