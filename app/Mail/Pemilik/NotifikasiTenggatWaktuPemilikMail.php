<?php

namespace App\Mail\Pemilik;

use App\Models\KontrakSewa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiTenggatWaktuPemilikMail extends Mailable
{
    use Queueable, SerializesModels;

    public $kontrak;
    public $pemilik;
    public $penghuni;
    public $hariTersisa;
    public $tipeNotifikasi;

    public function __construct(KontrakSewa $kontrak, $hariTersisa, $tipeNotifikasi)
    {
        $this->kontrak = $kontrak;
        $this->pemilik = $kontrak->kos->pemilik;
        $this->penghuni = $kontrak->penghuni;
        $this->hariTersisa = $hariTersisa;
        $this->tipeNotifikasi = $tipeNotifikasi;
    }

    public function envelope(): Envelope
    {
        $subjects = [
            '7_hari' => 'INFO: Kontrak Penghuni Akan Berakhir dalam 7 Hari',
            '3_hari' => 'INFO: Kontrak Penghuni Akan Berakhir dalam 3 Hari',
            '1_hari' => 'PERHATIAN: Kontrak Penghuni Akan Berakhir Besok',
            'tenggat' => 'PENTING: Kontrak Penghuni Berakhir Hari Ini',
            'terlambat' => 'PERINGATAN: Kontrak Penghuni Telah Melewati Tenggat Waktu',
        ];

        return new Envelope(
            subject: $subjects[$this->tipeNotifikasi] . ' - ' . $this->kontrak->kos->nama_kos,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.pemilik.tenggat_waktu',
            with: [
                'kontrak' => $this->kontrak,
                'pemilik' => $this->pemilik,
                'penghuni' => $this->penghuni,
                'hariTersisa' => $this->hariTersisa,
                'tipeNotifikasi' => $this->tipeNotifikasi,
            ],
        );
    }
}