<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FotoKos extends Model
{
    use HasFactory;

    protected $table = 'foto_kos';
    protected $primaryKey = 'id_foto';
    
    protected $fillable = [
        'id_kos', 'nama_file', 'urutan'
    ];

    // Relationships
    public function kos()
    {
        return $this->belongsTo(Kos::class, 'id_kos');
    }

    // Accessor untuk URL foto
    public function getUrlAttribute()
    {
        return asset('storage/kos/' . $this->nama_file);
    }
}