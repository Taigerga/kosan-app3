<?php

namespace App\Mail\Penghuni;

use App\Models\KontrakSewa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class KontrakDitolakMail extends Mailable
{
    use Queueable, SerializesModels;

    public $kontrak;
    public $penghuni;

    public function __construct(KontrakSewa $kontrak)
    {
        $this->kontrak = $kontrak;
        $this->penghuni = $kontrak->penghuni;
    }

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Kontrak Sewa Ditolak - ' . $this->kontrak->kos->nama_kos,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.penghuni.kontrak_ditolak',
            with: [
                'kontrak' => $this->kontrak,
                'penghuni' => $this->penghuni,
            ],
        );
    }
}