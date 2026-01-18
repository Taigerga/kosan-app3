<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kos extends Model
{
    use HasFactory;

    protected $table = 'kos';
    protected $primaryKey = 'id_kos';
    
    protected $fillable = [
        'id_pemilik', 'nama_kos', 'alamat', 'kecamatan', 'kota', 'provinsi',
        'kode_pos', 'latitude', 'longitude', 'deskripsi', 'peraturan',
        'jenis_kos', 'tipe_sewa', 'foto_utama', 'status_kos'
    ];

    // Relationships
    public function pemilik()
    {
        return $this->belongsTo(Pemilik::class, 'id_pemilik');
    }

    public function kamar()
    {
        return $this->hasMany(Kamar::class, 'id_kos');
    }

    public function fasilitas()
    {
        return $this->belongsToMany(Fasilitas::class, 'kos_fasilitas', 'id_kos', 'id_fasilitas');
    }

    public function kontrakSewa()
    {
        return $this->hasMany(KontrakSewa::class, 'id_kos');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_kos');
    }

    public function pengaturan()
    {
        return $this->hasOne(PengaturanKos::class, 'id_kos');
    }

    // Scope untuk kos aktif
    public function scopeAktif($query)
    {
        return $query->where('status_kos', 'aktif');
    }

    // Scope untuk kamar tersedia
    public function scopeDenganKamarTersedia($query)
    {
        return $query->whereHas('kamar', function($q) {
            $q->where('status_kamar', 'tersedia');
        });
    }
}