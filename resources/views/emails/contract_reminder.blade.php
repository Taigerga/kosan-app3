<!DOCTYPE html>
<html>
<head>
    <title>{{ $subject ?? 'AyoKos Notification' }}</title>
    <style>
        body { font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; line-height: 1.6; color: #333; background: #f5f5f5; }
        .container { max-width: 600px; margin: 0 auto; background: white; border-radius: 10px; overflow: hidden; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
        .header { background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 30px; text-align: center; }
        .header h1 { margin: 0; font-size: 24px; }
        .content { padding: 40px; }
        .greeting { font-size: 18px; color: #555; margin-bottom: 30px; }
        .contract-info { background: #f8f9fa; border-left: 4px solid #667eea; padding: 20px; margin: 25px 0; border-radius: 5px; }
        .contract-info h3 { margin-top: 0; color: #333; }
        .contract-detail { display: flex; margin: 10px 0; }
        .contract-label { font-weight: 600; min-width: 150px; color: #555; }
        .contract-value { color: #333; }
        .message-box { background: #e8f5e9; border: 1px solid #c8e6c9; padding: 20px; border-radius: 5px; margin: 25px 0; }
        .message-box p { margin: 0; color: #2e7d32; }
        .action-buttons { text-align: center; margin: 30px 0; }
        .button { display: inline-block; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-weight: 600; margin: 0 10px; transition: transform 0.2s; }
        .button:hover { transform: translateY(-2px); }
        .contact-info { background: #fff3cd; border: 1px solid #ffeaa7; padding: 15px; border-radius: 5px; margin: 25px 0; }
        .footer { text-align: center; padding: 20px; color: #666; font-size: 12px; border-top: 1px solid #eee; }
        .status-badge { display: inline-block; padding: 5px 15px; border-radius: 20px; font-size: 12px; font-weight: 600; margin-left: 10px; }
        .status-before { background: #ffeb3b; color: #333; }
        .status-today { background: #ff9800; color: white; }
        .status-overdue { background: #f44336; color: white; }
        .status-completion { background: #4caf50; color: white; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🏠 AyoKos</h1>
            <p>Sistem Manajemen Kos Terpadu</p>
        </div>

        <div class="content">
            <div class="greeting">
                <strong>Halo, {{ $userName }}!</strong>
            </div>

            @if(isset($isCompletion) && $isCompletion)
                <span class="status-badge status-completion">KONTRAK SELESAI</span>
            @else
                @if(isset($type) && $type === 'before')
                    <span class="status-badge status-before">PENGINGAT {{ $daysLeft ?? '' }} HARI</span>
                @elseif(isset($type) && $type === 'today')
                    <span class="status-badge status-today">BERAKHIR HARI INI</span>
                @elseif(isset($type) && $type === 'overdue')
                    <span class="status-badge status-overdue">LEWAT {{ $daysLeft ?? '' }} HARI</span>
                @endif
            @endif

            <div class="contract-info">
                <h3>📋 Informasi Kontrak</h3>

                <div class="contract-detail">
                    <div class="contract-label">Nama Kos:</div>
                    <div class="contract-value"><strong>{{ $kosName ?? '' }}</strong></div>
                </div>

                <div class="contract-detail">
                    <div class="contract-label">Nomor Kamar:</div>
                    <div class="contract-value">{{ $roomNumber ?? 'N/A' }}</div>
                </div>

                <div class="contract-detail">
                    <div class="contract-label">Tanggal Berakhir:</div>
                    <div class="contract-value">{{ isset($endDate) ? \Carbon\Carbon::parse($endDate)->format('d F Y') : '' }}</div>
                </div>

                @if(isset($daysLeft) && (!isset($isCompletion) || !$isCompletion))
                <div class="contract-detail">
                    <div class="contract-label">Sisa Waktu:</div>
                    <div class="contract-value">
                        @if($type === 'before')
                            <strong>{{ $daysLeft }} hari</strong>
                        @elseif($type === 'overdue')
                            <strong>Terlambat {{ $daysLeft }} hari</strong>
                        @endif
                    </div>
                </div>
                @endif
            </div>

            <div class="message-box">
                <p>{!! $emailMessage ?? '' !!}</p>
            </div>

            <div class="contact-info">
                <p><strong>📞 Kontak {{ isset($isPemilik) && $isPemilik ? 'Penghuni' : 'Pemilik' }}:</strong></p>
                <p>{{ $contactInfo ?? 'Silakan cek aplikasi untuk informasi kontak' }}</p>
            </div>

            <div class="action-buttons">
                @if(isset($isPemilik) && $isPemilik)
                    <a href="{{ url('/pemilik/kontrak') }}" class="button">📊 Dashboard Pemilik</a>
                    <a href="{{ url('/pemilik/kontrak/' . ($contractId ?? '')) }}" class="button">👁️ Lihat Kontrak</a>
                @else
                    <a href="{{ url('/penghuni/kontrak') }}" class="button">📋 Kontrak Saya</a>
                    <a href="{{ url('/penghuni/pembayaran') }}" class="button">💳 Pembayaran</a>
                @endif
            </div>

            <p style="text-align: center; color: #666; margin-top: 30px;">
                <em>Email ini dikirim secara otomatis oleh sistem AyoKos.</em><br>
                <small>Mohon tidak membalas email ini.</small>
            </p>
        </div>

        <div class="footer">
            <p>© {{ date('Y') }} AyoKos - All rights reserved</p>
            <p>Jl. Contoh No. 123, Kota Bandung | support@ayokos.com</p>
        </div>
    </div>
</body>
</html>