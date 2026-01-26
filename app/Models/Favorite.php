<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Favorite extends Model
{
    protected $table = 'favorites';
    protected $primaryKey = 'id';
    protected $fillable = ['id_penghuni', 'id_kos'];

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'id_penghuni', 'id_penghuni');
    }

    public function kos()
    {
        return $this->belongsTo(Kos::class, 'id_kos', 'id_kos');
    }
}
