<!DOCTYPE html>
<html>

<head>
    <title>Menunggu Persetujuan</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #FF9800;
            color: white;
            padding: 20px;
            text-align: center;
            border-radius: 5px 5px 0 0;
        }

        .content {
            background: #f9f9f9;
            padding: 20px;
            border-radius: 0 0 5px 5px;
        }

        .button {
            display: inline-block;
            background: #FF9800;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
        }

        .footer {
            margin-top: 20px;
            text-align: center;
            color: #666;
            font-size: 12px;
        }

        .info-box {
            background: white;
            padding: 15px;
            border-left: 4px solid #FF9800;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>⏳ Menunggu Persetujuan</h1>
        </div>

        <div class="content">
            <p>Halo <strong>{{ $penghuni->nama }}</strong>,</p>

            <p>Terima kasih telah mengajukan sewa di <strong>{{ $kontrak->kos->nama_kos }}</strong>.</p>
            <p>Saat ini pengajuan Anda sedang menunggu persetujuan dari pemilik kos. Harap tunggu konfirmasi
                selanjutnya.</p>

            <div class="info-box">
                <p><strong>Detail Pengajuan:</strong></p>
                <ul>
                    <li>Kos: {{ $kontrak->kos->nama_kos }}</li>
                    <li>Kamar: {{ $kontrak->kamar->nomor_kamar }}</li>
                    <li>Tanggal Mulai: {{ date('d F Y', strtotime($kontrak->tanggal_mulai)) }}</li>
                    <li>Durasi: {{ $kontrak->durasi_sewa }} Bulan</li>
                </ul>
            </div>

            <p>Kami akan segera memberitahu Anda melalui WhatsApp dan Email setelah ada keputusan dari pemilik.</p>

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/') }}" class="button">
                    Cari Kos Lainnya
                </a>
            </p>

            <p>Salam,<br>
                <strong>Tim Admin AyoKos</strong>
            </p>
        </div>

        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} AyoKos. All rights reserved.</p>
        </div>
    </div>
</body>

</html>