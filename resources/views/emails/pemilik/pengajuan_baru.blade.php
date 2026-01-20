<!DOCTYPE html>
<html>
<head>
    <title>Pengajuan Sewa Baru</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #9C27B0; color: white; padding: 20px; text-align: center; border-radius: 5px 5px 0 0; }
        .content { background: #f9f9f9; padding: 20px; border-radius: 0 0 5px 5px; }
        .button { display: inline-block; background: #9C27B0; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px; }
        .footer { margin-top: 20px; text-align: center; color: #666; font-size: 12px; }
        .applicant-info { background: white; padding: 15px; border-left: 4px solid #9C27B0; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📋 Pengajuan Sewa Baru</h1>
        </div>
        
        <div class="content">
            <p>Halo <strong>{{ $pemilik->nama }}</strong>,</p>
            
            <p>Anda memiliki pengajuan sewa baru untuk kos <strong>{{ $kontrak->kos->nama_kos }}</strong>.</p>
            
            <div class="applicant-info">
                <p><strong>Data Calon Penghuni:</strong></p>
                <ul>
                    <li>Nama: {{ $kontrak->penghuni->nama }}</li>
                    <li>Email: {{ $kontrak->penghuni->email }}</li>
                    <li>No. HP: {{ $kontrak->penghuni->no_hp }}</li>
                    <li>Jenis Kelamin: {{ $kontrak->penghuni->jenis_kelamin == 'L' ? 'Laki-laki' : 'Perempuan' }}</li>
                </ul>
            </div>
            
            <div style="background: white; padding: 15px; border-left: 4px solid #2196F3; margin: 20px 0;">
                <p><strong>Detail Pengajuan:</strong></p>
                <ul>
                    <li>Kos: {{ $kontrak->kos->nama_kos }}</li>
                    <li>Kamar: {{ $kontrak->kamar->nomor_kamar }}</li>
                    <li>Tipe Kamar: {{ $kontrak->kamar->tipe_kamar }}</li>
                    <li>Durasi Sewa: {{ $kontrak->durasi_sewa }} bulan</li>
                    <li>Harga Sewa: Rp {{ number_format($kontrak->harga_sewa, 0, ',', '.') }}/bulan</li>
                    <li>Tanggal Daftar: {{ date('d F Y', strtotime($kontrak->tanggal_daftar)) }}</li>
                </ul>
            </div>
            
            <p>Silakan tinjau pengajuan ini dan berikan keputusan.</p>
            
            <p style="text-align: center; margin: 30px 0;">
                <a href="{{ url('/pemilik/kontrak/' . $kontrak->id_kontrak . '/review') }}" class="button">
                    Tinjau Pengajuan
                </a>
            </p>
            
            <p>Salam,<br>
            <strong>Tim Admin AyoKos</strong></p>
        </div>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis. Mohon tidak membalas email ini.</p>
            <p>&copy; {{ date('Y') }} AyoKos. All rights reserved.</p>
        </div>
    </div>
</body>
</html>