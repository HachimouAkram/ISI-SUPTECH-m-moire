<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            'photo' => $this->faker->imageUrl(200, 200, 'people', true),
            'nom' => $this->faker->lastName,
            'prenom' => $this->faker->firstName,
            'email' => $this->faker->unique()->safeEmail,
            'role' => $this->faker->randomElement(['admin', 'etudiant']),
            'telephone' => $this->faker->phoneNumber,
            'sexe' => $this->faker->randomElement(['Homme', 'Femme', 'Autre']),
            'date_naissance' => $this->faker->date('Y-m-d', '-18 years'),
            'password' => Hash::make('password'),
            'remember_token' => Str::random(10),
        ];
    }
}
