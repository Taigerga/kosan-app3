<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Penghuni extends Authenticatable
{
    use HasFactory;

    protected $table = 'penghuni';
    protected $primaryKey = 'id_penghuni';
    
    protected $fillable = [
        'nama', 'nik', 'no_hp', 'email', 'jenis_kelamin', 'tanggal_lahir',
        'alamat', 'foto_profil', 'username', 'password',
        'status_penghuni', 'role', 'remember_token'
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    // Relationships
    public function kontrakSewa()
    {
        return $this->hasMany(KontrakSewa::class, 'id_penghuni');
    }

    public function pembayaran()
    {
        return $this->hasMany(Pembayaran::class, 'id_penghuni');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'id_penghuni');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'user', 'user_type', 'id_user');
    }
}