<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    use HasFactory;

    protected $table = 'reviews';
    protected $primaryKey = 'id_review';
    
    // Hapus 'status_review' dari fillable
    protected $fillable = [
        'id_kos', 'id_penghuni', 'id_kontrak', 'rating', 'komentar', 'foto_review'
    ];

    // Hapus casts untuk status_review jika ada
    protected $casts = [
        'rating' => 'integer',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Validation untuk rating
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($review) {
            if ($review->rating < 1 || $review->rating > 5) {
                throw new \Exception('Rating harus antara 1 dan 5');
            }
        });
        
        static::updating(function ($review) {
            if ($review->rating < 1 || $review->rating > 5) {
                throw new \Exception('Rating harus antara 1 dan 5');
            }
        });
    }

    // Relationships
    public function kos()
    {
        return $this->belongsTo(Kos::class, 'id_kos');
    }

    public function penghuni()
    {
        return $this->belongsTo(Penghuni::class, 'id_penghuni');
    }

    public function kontrak()
    {
        return $this->belongsTo(KontrakSewa::class, 'id_kontrak');
    }

    // HAPUS semua scope yang berhubungan dengan status_review
    // public function scopeDisetujui($query) { ... } // HAPUS
    // public function scopePending($query) { ... } // HAPUS
    // public function scopeDitolak($query) { ... } // HAPUS

    public function scopeRatingTertinggi($query)
    {
        return $query->orderBy('rating', 'desc');
    }
    
    // Check if review belongs to user
    public function isOwnedBy($penghuniId)
    {
        return $this->id_penghuni == $penghuniId;
    }
    
    // HAPUS method getStatusColorAttribute dan getStatusTextAttribute
    public function getAverageRatingAttribute()
    {
        return $this->reviews()->avg('rating') ?? 0;
    }

    public function getTotalReviewsAttribute()
    {
        return $this->reviews()->count();
    }

    public function getRatingDistributionAttribute()
    {
        $distribution = [];
        for ($i = 1; $i <= 5; $i++) {
            $distribution[$i] = $this->reviews()->where('rating', $i)->count();
        }
        return $distribution;
    }

    // Method untuk rating bintang visual
    public function getStarRatingAttribute()
    {
        $rating = $this->average_rating;
        $stars = '';
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= floor($rating)) {
                $stars .= '★';
            } elseif ($i - 0.5 <= $rating) {
                $stars .= '⭐';
            } else {
                $stars .= '☆';
            }
        }
        
        return $stars;
    }
}