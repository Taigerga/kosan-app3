<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    use HasFactory;

    protected $table = 'notifications';
    protected $primaryKey = 'id_notifikasi';
    
    protected $fillable = [
        'id_user',
        'user_type',
        'judul',
        'pesan',
        'tipe',
        'dibaca',
        'link'
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    // Scope untuk notifikasi yang belum dibaca
    public function scopeUnread($query)
    {
        return $query->where('dibaca', 'tidak');
    }

    // Scope untuk notifikasi berdasarkan user type
    public function scopeForUserType($query, $userType)
    {
        return $query->where('user_type', $userType);
    }

    // Scope untuk notifikasi berdasarkan user
    public function scopeForUser($query, $userId, $userType)
    {
        return $query->where('id_user', $userId)
                    ->where('user_type', $userType);
    }

    // Method untuk menandai sebagai sudah dibaca
    public function markAsRead()
    {
        $this->update(['dibaca' => 'ya']);
    }

    // Method untuk menandai sebagai belum dibaca
    public function markAsUnread()
    {
        $this->update(['dibaca' => 'tidak']);
    }

    // Accessor untuk check jika notifikasi sudah dibaca
    public function getIsReadAttribute()
    {
        return $this->dibaca === 'ya';
    }
}