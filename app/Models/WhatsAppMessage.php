<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WhatsAppMessage extends Model
{
    use HasFactory;

    protected $table = 'whatsapp_messages';

    protected $fillable = [
        'phone',
        'message',
        'status',
        'attempts',
        'whatsapp_message_id',
        'error',
        'processing_at',
        'sent_at',
        'failed_at',
        'type',
        'metadata'
    ];

    protected $casts = [
        'attempts' => 'integer',
        'metadata' => 'array',
        'processing_at' => 'datetime',
        'sent_at' => 'datetime',
        'failed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];

    /**
     * Scope untuk pesan pending
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope untuk pesan hari ini
     */
    public function scopeToday($query)
    {
        return $query->whereDate('created_at', today());
    }

    /**
     * Scope untuk pesan yang gagal
     */
    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }
}
