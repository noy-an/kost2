<!DOCTYPE html>
<html>
<head>
    <title>Booking Sukses</title>
</head>
<body>
    <h2>Hai, {{ $booking->user->name }}!</h2>
    <p>Booking kamu untuk kamar <strong>{{ $booking->kost->nomor_kamar }}</strong> berhasil.</p>
    <p>Tanggal booking: {{ \Carbon\Carbon::parse($booking->tgl_booking)->format('d M Y') }}</p>
    <p>Nominal dibayar: Rp{{ number_format($booking->dp, 0, ',', '.') }}</p>
    <p>Status: {{ $booking->status_booking }}</p>

    <p>Terima kasih udah booking. Jangan lupa bayar lunas nanti ya! 😎</p>
</body>
</html>
