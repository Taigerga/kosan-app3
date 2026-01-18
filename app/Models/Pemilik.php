<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Pemilik extends Authenticatable
{
    use HasFactory;

    protected $table = 'pemilik';
    protected $primaryKey = 'id_pemilik';

    protected $fillable = [
        'nama',
        'no_hp',
        'email',
        'foto_profil',
        'username',
        'password',
        'alamat',
        'status_pemilik',
        'role',
        'remember_token',
        'jenis_kelamin',
        'tanggal_lahir',
        'nik',
        'nama_bank',
        'nomor_rekening'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'tanggal_lahir' => 'date',
    ];

    // Relationships
    public function kos()
    {
        return $this->hasMany(Kos::class, 'id_pemilik');
    }

    public function notifications()
    {
        return $this->morphMany(Notification::class, 'user', 'user_type', 'id_user');
    }
}