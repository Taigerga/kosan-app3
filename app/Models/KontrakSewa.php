<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KontrakSewa extends Model
{
    use HasFactory;

    protected $table = 'kontrak_sewa';
    protected $primaryKey = 'id_kontrak';
    
    protected $fillable = [
        'id_penghuni', 'id_kos', 'id_kamar', 'foto_ktp',
        'tanggal_daftar', 'tanggal_mulai', 'tanggal_selesai', 'durasi_sewa',
        'harga_sewa', 'status_kontrak', 'alasan_ditolak'
    ];

    protected $casts = [
        'tanggal_daftar' => 'date',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    // Relationships
    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'id_penghuni');
    }

    public function kos()
    {
        return $this->belongsTo(Kos::class, 'id_kos');
    }

    public function kamar()
    {
        return $this->belongsTo(Kamar::class, 'id_kamar');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_kontrak');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_kontrak');
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status_kontrak', 'pending');
    }

    //public function scopeAktif($query)
    //{
        //return $query->where('status_kontrak', 'aktif');
    //}
    // Tambahkan method untuk analisis
    public function scopeForPenghuni($query, $penghuniId)
    {
        return $query->where('id_penghuni', $penghuniId);
    }

    // Scope untuk kontrak aktif
    public function scopeAktif($query)
    {
        return $query->where('status_kontrak', 'aktif')
                    ->where('tanggal_selesai', '>=', now());
    }

    public function scopeSelesai($query)
    {
        return $query->where('status_kontrak', 'selesai')
                    ->orWhere(function($q) {
                        $q->where('status_kontrak', 'aktif')
                        ->where('tanggal_selesai', '<', now());
                    });
    }    
    // Method untuk cek apakah kontrak aktif
    public function isAktif()
    {
        return $this->status_kontrak === 'aktif' && 
               $this->tanggal_selesai > now();
    }



    // Model event untuk otomatis update status kamar saat kontrak selesai ()
    protected static function booted()
    {
        static::updated(function ($kontrak) {
            // Jika status kontrak berubah menjadi 'selesai', update kamar terkait
            if ($kontrak->isDirty('status_kontrak') && $kontrak->status_kontrak === 'selesai') {
                try {
                    $kamar = $kontrak->kamar;
                    if ($kamar) {
                        $kamar->status_kamar = 'tersedia';
                        // Jika model Kamar memiliki kolom penghuni/owner, kosongkan relasi
                        if (isset($kamar->id_penghuni)) {
                            $kamar->id_penghuni = null;
                        }
                        $kamar->save();
                    }
                } catch (\Exception $e) {
                    // jangan melempar exception di model event; log jika perlu
                    \Log::error('Failed to update kamar after kontrak selesai: ' . $e->getMessage());
                }
            }
        });
    }
}