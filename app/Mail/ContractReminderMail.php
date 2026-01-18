<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\KontrakSewa;
use App\Models\Penghuni;
use App\Models\Pemilik;

class ContractReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $subject;
    public $message;
    public $user;
    public $contract;
    public $isPemilik;

    public function __construct($subject, $message, $user, KontrakSewa $contract, $isPemilik = false)
    {
        $this->subject = $subject;
        $this->message = $message;
        $this->user = $user;
        $this->contract = $contract;
        $this->isPemilik = $isPemilik;
    }

    public function build()
    {
        $kos = $this->contract->kos;
        $kamar = $this->contract->kamar;

        return $this->subject($this->subject)
            ->view('emails.contract_reminder')
            ->with([
                'userName' => $this->user->nama,
                'messageContent' => $this->message,
                'kosName' => $kos->nama_kos,
                'roomNumber' => $kamar->nomor_kamar,
                'endDate' => $this->contract->tanggal_selesai,
                'isPemilik' => $this->isPemilik,
                'contactInfo' => $this->isPemilik 
                    ? ($this->contract->penghuni->no_hp ?? 'Tidak tersedia')
                    : ($kos->pemilik->no_hp ?? 'Tidak tersedia')
            ]);
    }
}