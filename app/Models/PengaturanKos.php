<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengaturanKos extends Model
{
    use HasFactory;

    protected $table = 'pengaturan_kos';
    protected $primaryKey = 'id_pengaturan';
    
    protected $fillable = [
        'id_kos', 'notifikasi_pembayaran_h_min', 'denda_keterlambatan',
        'toleransi_keterlambatan'
    ];

    // Relationships
    public function kos()
    {
        return $this->belongsTo(Kos::class, 'id_kos');
    }
}