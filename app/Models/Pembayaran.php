<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    use HasFactory;

    protected $table = 'pembayaran';
    protected $primaryKey = 'id_pembayaran';

    protected $fillable = [
        'id_kontrak',
        'id_penghuni',
        'bulan_tahun',
        'tanggal_jatuh_tempo',
        'tanggal_bayar',
        'jumlah',
        'bukti_pembayaran',
        'metode_pembayaran',
        'status_pembayaran',
        'keterangan',
        'tanggal_mulai_sewa',
        'tanggal_akhir_sewa',
        'jenis_pembayaran',
    ];

    protected $casts = [
        'tanggal_jatuh_tempo' => 'date',
        'tanggal_bayar' => 'date',
        'tanggal_mulai_sewa' => 'date',
        'tanggal_akhir_sewa' => 'date',
        'jumlah' => 'decimal:2'
    ];

    // Relasi ke kontrak sewa
    public function kontrak()
    {
        return $this->belongsTo(KontrakSewa::class, 'id_kontrak', 'id_kontrak');
    }

    // Relasi ke penghuni
    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'id_penghuni', 'id_penghuni');
    }

    // Scope untuk pembayaran pending
    public function scopePending($query)
    {
        return $query->where('status_pembayaran', 'pending');
    }

    // Scope untuk pembayaran belum bayar
    public function scopeBelumBayar($query)
    {
        return $query->where('status_pembayaran', 'belum');
    }

    // Scope untuk pembayaran lunas
    public function scopeLunas($query)
    {
        return $query->where('status_pembayaran', 'lunas');
    }

    // Method untuk menandai sebagai lunas
    public function markAsPaid($tanggalBayar = null)
    {
        $this->update([
            'status_pembayaran' => 'lunas',
            'tanggal_bayar' => $tanggalBayar ?? now()
        ]);
    }

    // Method untuk menandai sebagai pending
    public function markAsPending()
    {
        $this->update(['status_pembayaran' => 'pending']);
    }

    // Method untuk menandai sebagai terlambat
    public function markAsLate()
    {
        $this->update(['status_pembayaran' => 'terlambat']);
    }

    // Check jika pembayaran sudah jatuh tempo
    public function isOverdue()
    {
        return $this->tanggal_jatuh_tempo < now()->format('Y-m-d')
            && $this->status_pembayaran === 'belum';
    }
}