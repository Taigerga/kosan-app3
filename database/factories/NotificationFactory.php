<?php

namespace Database\Factories;

use App\Models\Notification;
use Illuminate\Database\Eloquent\Factories\Factory;

class NotificationFactory extends Factory
{
    protected $model = Notification::class;

    public function definition()
    {
        return [
            'id_user' => $this->faker->numberBetween(1, 100),
            'user_type' => $this->faker->randomElement(['penghuni', 'pemilik']),
            'judul' => $this->faker->sentence,
            'pesan' => $this->faker->paragraph,
            'tipe' => $this->faker->randomElement(['info', 'warning', 'success', 'danger']),
            'dibaca' => $this->faker->randomElement(['ya', 'tidak']),
            'link' => $this->faker->optional()->url,
        ];
    }

    public function unread()
    {
        return $this->state(function (array $attributes) {
            return [
                'dibaca' => 'tidak',
            ];
        });
    }

    public function read()
    {
        return $this->state(function (array $attributes) {
            return [
                'dibaca' => 'ya',
            ];
        });
    }

    public function forPenghuni()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'penghuni',
            ];
        });
    }

    public function forPemilik()
    {
        return $this->state(function (array $attributes) {
            return [
                'user_type' => 'pemilik',
            ];
        });
    }
}