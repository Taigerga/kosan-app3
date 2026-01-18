<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Fasilitas extends Model
{
    use HasFactory;

    protected $table = 'fasilitas'; 
    protected $primaryKey = 'id_fasilitas';
    
    protected $fillable = [
        'nama_fasilitas', 'kategori', 'icon'
    ];

    // Relationships
    public function kos()
    {
        return $this->belongsToMany(Kos::class, 'kos_fasilitas', 'id_fasilitas', 'id_kos');
    }
}