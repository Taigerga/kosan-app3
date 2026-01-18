<?php

namespace Database\Seeders;

use App\Models\Notification;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    public function run()
    {
        // Notifikasi untuk penghuni
        Notification::factory()->count(10)->forPenghuni()->create();
        
        // Notifikasi untuk pemilik
        Notification::factory()->count(10)->forPemilik()->create();
        
        // Beberapa notifikasi belum dibaca
        Notification::factory()->count(5)->unread()->forPenghuni()->create();
        Notification::factory()->count(5)->unread()->forPemilik()->create();
    }
}