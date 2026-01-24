<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi Pembayaran</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }
        .content {
            background: #f9f9f9;
            padding: 30px;
            border-radius: 0 0 10px 10px;
        }
        .payment-info {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .status-pending {
            border-left: 4px solid #ff9800;
        }
        .status-approved {
            border-left: 4px solid #4caf50;
        }
        .status-rejected {
            border-left: 4px solid #f44336;
        }
        .amount {
            font-size: 24px;
            font-weight: bold;
            color: #667eea;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: #667eea;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>🏠 AyoKos</h1>
        <h2>Notifikasi Pembayaran</h2>
    </div>
    
    <div class="content">
        <p>Hai <strong>{{ $userName ?? 'Pengguna' }}</strong>,</p>
        
        <p>{!! $emailMessage !!}</p>
        
        <div class="payment-info {{ $type == 'pending_penghuni' ? 'status-pending' : ($type == 'approved_penghuni' ? 'status-approved' : 'status-rejected') }}">
            <h3>Detail Pembayaran:</h3>
            <p><strong>Kos:</strong> {{ $kosName }} @if($roomNumber) (Kamar {{ $roomNumber }}) @endif</p>
            <p><strong>Jumlah:</strong> <span class="amount">Rp {{ number_format($amount, 0, ',', '.') }}</span></p>
            <p><strong>Periode:</strong> {{ $period }}</p>
            <p><strong>Tanggal Bayar:</strong> {{ $paymentDate }}</p>
            <p><strong>Metode:</strong> {{ ucfirst($metodePembayaran) }}</p>
            
            @if($type == 'approved_penghuni')
                <p><strong>Status:</strong> ✅ Lunas</p>
                <p><strong>Tanggal Disetujui:</strong> {{ $approvedDate ?? '-' }}</p>
            @elseif($type == 'rejected_penghuni')
                <p><strong>Status:</strong> ❌ Ditolak</p>
            @else
                <p><strong>Status:</strong> ⏳ Menunggu Verifikasi</p>
            @endif
        </div>
        
        @if($type == 'rejected_penghuni')
            <p>Silakan login ke akun Anda untuk mengupload ulang bukti pembayaran yang valid.</p>
            <a href="{{ url('/penghuni/login') }}" class="btn">Login ke AyoKos</a>
        @elseif($type == 'pending_penghuni')
            <p>Kami akan menginformasikan Anda segera setelah pemilik melakukan verifikasi.</p>
        @else
            <p>Terima kasih telah melakukan pembayaran tepat waktu!</p>
        @endif
    </div>
    
    <div class="footer">
        <p>© 2026 AyoKos - Platform Sewa Kos Terpercaya</p>
        <p>Email ini dikirim secara otomatis, jangan membalas email ini.</p>
    </div>
</body>
</html>