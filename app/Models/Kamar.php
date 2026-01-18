<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class Kamar extends Model
{
    use HasFactory;

    protected $table = 'kamar';
    protected $primaryKey = 'id_kamar';
    
    protected $fillable = [
        'id_kos', 'nomor_kamar', 'tipe_kamar', 'harga', 'luas_kamar',
        'kapasitas', 'fasilitas_kamar', 'foto_kamar', 'status_kamar'
    ];

    protected $casts = [
        'fasilitas_kamar' => 'array'
    ];

    // TAMBAHKAN APPENDS
    protected $appends = ['foto_kamar_url'];

    // Relationships
    public function kos()
    {
        return $this->belongsTo(Kos::class, 'id_kos');
    }

    public function kontrakSewa()
    {
        return $this->hasMany(KontrakSewa::class, 'id_kamar');
    }

    // Accessor untuk URL foto kamar
    public function getFotoKamarUrlAttribute()
    {
        if (!$this->foto_kamar) {
            return null;
        }
        
        // Normalize path untuk konsistensi
        $normalizedPath = str_replace('\\', '/', $this->foto_kamar);
        
        $storagePath = storage_path('app/public/' . $normalizedPath);
        
        if (!file_exists($storagePath)) {
            // Coba dengan berbagai format path
            $altPath1 = storage_path('app\public\\' . str_replace('/', '\\', $normalizedPath));
            $altPath2 = storage_path('app/public/' . $this->foto_kamar);
            
            \Log::warning('File kamar tidak ditemukan', [
                'kamar_id' => $this->id_kamar,
                'original_path' => $this->foto_kamar,
                'normalized_path' => $normalizedPath,
                'storage_path' => $storagePath,
                'exists_1' => file_exists($storagePath),
                'exists_2' => file_exists($altPath1),
                'exists_3' => file_exists($altPath2),
                'files_in_dir' => glob(storage_path('app/public/kamar/*'))
            ]);
            
            return null;
        }
        
        return url('storage/' . $normalizedPath);
    }

    // Atau buat accessor untuk path yang sudah dinormalisasi
    public function getFotoKamarNormalizedAttribute()
    {
        if (!$this->foto_kamar) {
            return null;
        }
        return str_replace('\\', '/', $this->foto_kamar);
    }

    // Mutator untuk fasilitas_kamar
    public function setFasilitasKamarAttribute($value)
    {
        $this->attributes['fasilitas_kamar'] = json_encode($value);
    }
}