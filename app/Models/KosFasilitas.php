<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class KosFasilitas extends Pivot
{
    use HasFactory;

    protected $table = 'kos_fasilitas';
    protected $primaryKey = 'id_kos_fasilitas';
    
    public $incrementing = true;
    public $timestamps = true;
}