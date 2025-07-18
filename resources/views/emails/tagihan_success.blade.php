<!DOCTYPE html>
<html>
<head>
    <title>Tagihan Sukses</title>
</head>
<body>
    <h2>Hai, {{ $tagihan->booking->user->name }}!</h2>
    <p>Pembayaran tagihan kamu untuk kamar <strong>{{ $tagihan->booking->kost->nomor_kamar }}</strong> berhasil.</p>
    <p>Tanggal tagihan: {{ \Carbon\Carbon::parse($tagihan->tanggal_tagihan)->format('d M Y') }}</p>
    <p>Nominal dibayar: Rp{{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</p>
    <p>Status: {{ $tagihan->status_pembayaran }}</p>

    <p>Terima kasih udah bayar, semoga betah ya tinggal di kost ini! 😎</p>
</body>
</html>
