<?php

namespace Tests\Feature;

use App\Models\Notification;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_notifications()
    {
        $user = User::factory()->create(['role' => 'penghuni']);
        Notification::factory()->count(3)->create([
            'id_user' => $user->id,
            'user_type' => 'penghuni'
        ]);

        $response = $this->actingAs($user)->getJson('/api/notifications');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'success',
                'data' => [
                    'notifications',
                    'unread_count'
                ]
            ]);
    }

    public function test_user_can_mark_notification_as_read()
    {
        $user = User::factory()->create(['role' => 'pemilik']);
        $notification = Notification::factory()->create([
            'id_user' => $user->id,
            'user_type' => 'pemilik',
            'dibaca' => 'tidak'
        ]);

        $response = $this->actingAs($user)
            ->postJson("/api/notifications/{$notification->id_notifikasi}/mark-as-read");

        $response->assertStatus(200)
            ->assertJson(['success' => true]);

        $this->assertDatabaseHas('notifications', [
            'id_notifikasi' => $notification->id_notifikasi,
            'dibaca' => 'ya'
        ]);
    }
}