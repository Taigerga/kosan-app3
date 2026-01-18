<?php

namespace App\Mail\Pemilik;

use App\Models\KontrakSewa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class PengajuanBaruMail extends Mailable
{
    use Queueable, SerializesModels;

    public $kontrak;
    public $pemilik;

    public function __construct(KontrakSewa $kontrak)
    {
        $this->kontrak = $kontrak;
        $this->pemilik = $kontrak->kos->pemilik;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Pengajuan Sewa Baru - ' . $this->kontrak->kos->nama_kos,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pemilik.pengajuan_baru',
            with: [
                'kontrak' => $this->kontrak,
                'pemilik' => $this->pemilik,
            ],
        );
    }
}