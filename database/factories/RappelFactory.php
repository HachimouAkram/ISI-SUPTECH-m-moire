<?php

namespace Database\Factories;

use App\Models\Rappel;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class RappelFactory extends Factory
{
    protected $model = Rappel::class;

    public function definition(): array
    {
        return [
            'date_rappel' => $this->faker->dateTimeBetween('+1 days', '+1 month'),
            'message' => $this->faker->sentence(10),
            'user_id' => User::factory(), // Crée un utilisateur associé automatiquement
        ];
    }
}
