<!DOCTYPE html>
<html>

<head>
    <title>Kontrak Disetujui</title>
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
            background: #4CAF50;
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
            background: #4CAF50;
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
            border-left: 4px solid #4CAF50;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="header">
            <h1>✅ Kontrak Disetujui</h1>
        </div>

        <div class="content">
            <p>Halo <strong>{{ $pemilik->nama }}</strong>,</p>

            <p>Anda telah berhasil menyetujui pengajuan sewa dari <strong>{{ $kontrak->penghuni->nama }}</strong>.</p>

            <div class="info-box">
                <p><strong>Detail Sewa:</strong></p>
                <ul>
                    <li>Nama Penghuni: {{ $kontrak->penghuni->nama }}</li>
                    <li>Kos: {{ $kontrak->kos->nama_kos }}</li>
                    <li>Kamar: {{ $kontrak->kamar->nomor_kamar }}</li>
                    <li>Mulai Sewa: {{ date('d F Y', strtotime($kontrak->tanggal_mulai)) }}</li>
                    <li>Berakhir: {{ date('d F Y', strtotime($kontrak->tanggal_selesai)) }}</li>
                </ul>
            </div>

            <p>Penghuni telah diberitahu mengenai persetujuan ini. Pastikan kamar sudah siap untuk ditempati.</p>

            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/pemilik/dashboard') }}" class="button">
                    Ke Dashboard
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