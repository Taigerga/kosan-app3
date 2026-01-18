<?php

namespace App\Mail\Pemilik;

use App\Models\KontrakSewa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KontrakDisetujuiMail extends Mailable
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
            subject: 'Kontrak Disetujui - ' . $this->kontrak->penghuni->nama,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pemilik.kontrak_disetujui',
            with: [
                'kontrak' => $this->kontrak,
                'pemilik' => $this->pemilik,
            ],
        );
    }
}
