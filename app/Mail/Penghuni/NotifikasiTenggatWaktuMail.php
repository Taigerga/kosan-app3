<?php

namespace App\Mail\Penghuni;

use App\Models\KontrakSewa;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class NotifikasiTenggatWaktuMail extends Mailable
{
    use Queueable, SerializesModels;

    public $kontrak;
    public $penghuni;
    public $hariTersisa;
    public $tipeNotifikasi; // '7_hari', '3_hari', '1_hari', 'tenggat', 'terlambat'

    public function __construct(KontrakSewa $kontrak, $hariTersisa, $tipeNotifikasi)
    {
        $this->kontrak = $kontrak;
        $this->penghuni = $kontrak->penghuni;
        $this->hariTersisa = $hariTersisa;
        $this->tipeNotifikasi = $tipeNotifikasi;
    }

    public function envelope(): Envelope
    {
        $subjects = [
            '7_hari' => 'Pengingat: Kontrak Sewa Akan Berakhir dalam 7 Hari',
            '3_hari' => 'Pengingat: Kontrak Sewa Akan Berakhir dalam 3 Hari',
            '1_hari' => 'Pengingat: Kontrak Sewa Akan Berakhir Besok',
            'tenggat' => 'Penting: Kontrak Sewa Berakhir Hari Ini',
            'terlambat' => 'Peringatan: Kontrak Sewa Telah Melewati Tenggat Waktu',
        ];

        return new Envelope(
            subject: $subjects[$this->tipeNotifikasi] . ' - ' . $this->kontrak->kos->nama_kos,
        );
    }

    public function content(): Content
    {
        return new Content(
            view: 'emails.penghuni.tenggat_waktu',
            with: [
                'kontrak' => $this->kontrak,
                'penghuni' => $this->penghuni,
                'hariTersisa' => $this->hariTersisa,
                'tipeNotifikasi' => $this->tipeNotifikasi,
            ],
        );
    }
}