<!DOCTYPE html>
<html>
<head>
    <title>Laporan Booking & Tagihan</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            font-size: 13px;
            margin: 30px;
            color: #2c3e50;
        }

        .header {
            text-align: center;
            margin-bottom: 30px;
        }

        .header h2 {
            font-size: 22px;
            color: #154360;
            margin: 0;
        }

        .printed-date {
            text-align: right;
            font-size: 12px;
            color: #7f8c8d;
            margin-top: -20px;
            margin-bottom: 20px;
        }

        .section-title {
            background-color: #21618C;
            color: white;
            padding: 8px 12px;
            font-size: 14px;
            font-weight: bold;
            border-radius: 4px;
            margin: 25px 0 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
            box-shadow: 0 0 5px rgba(0,0,0,0.05);
        }

        th {
            background-color: #3498db;
            color: white;
            padding: 8px;
            font-weight: bold;
            font-size: 13px;
        }

        td {
            padding: 8px;
            font-size: 13px;
            border: 1px solid #ddd;
        }

        tr:nth-child(even) {
            background-color: #f4f9ff;
        }

        tr:hover {
            background-color: #eaf3fb;
        }

        footer {
            margin-top: 30px;
            text-align: center;
            font-size: 11px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="header">
        <h2>Laporan Booking dan Tagihan Kost</h2>
    </div>

    <div class="printed-date">
        Dicetak pada: {{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}
    </div>

    {{-- SECTION: Data Booking --}}
    <div class="section-title">Data Booking</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nama User</th>
                <th>Nomor Kamar</th>
                <th>DP</th>
                <th>Presentase Pembayaran</th>
            </tr>
        </thead>
        <tbody>
            @foreach($bookings as $index => $booking)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $booking->user->name ?? '-' }}</td>
                <td>{{ $booking->kost->nomor_kamar ?? '-' }}</td>
                <td>Rp {{ number_format($booking->dp, 0, ',', '.') }}</td>
                <td>{{ number_format(($booking->dp / 1000000) * 100, 2) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    {{-- SECTION: Data Tagihan --}}
    <div class="section-title">Data Tagihan</div>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nomor Kamar</th>
                <th>Bayar per Bulan</th>
                <th>Jumlah Bayar</th>
                <th>Tanggal Tagihan</th>
            </tr>
        </thead>
        <tbody>
            @foreach($tagihans as $index => $tagihan)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $tagihan->booking->kost->nomor_kamar ?? '-' }}</td>
                <td>Rp {{ number_format($tagihan->bayar_per_bulan, 0, ',', '.') }}</td>
                <td>Rp {{ number_format($tagihan->jumlah_bayar, 0, ',', '.') }}</td>
                <td>{{ \Carbon\Carbon::parse($tagihan->tanggal_tagihan)->format('d/m/Y') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <footer>
        &copy; {{ date('Y') }} Sistem Kost Pemilik. Semua hak dilindungi.
    </footer>
</body>
</html>
