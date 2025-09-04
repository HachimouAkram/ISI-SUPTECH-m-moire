<?php

namespace Database\Factories;

use App\Models\Classe;
use App\Models\Formation;
use Illuminate\Database\Eloquent\Factories\Factory;

class ClasseFactory extends Factory
{
    protected $model = Classe::class;

    public function definition(): array
    {
        return [
            'prix_inscription' => $this->faker->randomFloat(2, 10000, 50000),
            'prix_mensuel' => $this->faker->randomFloat(2, 5000, 30000),
            'duree' => $this->faker->numberBetween(9, 10), // durée en mois ?
            'niveau' => $this->faker->numberBetween(1, 5),
            'mois_rentree' => $this->faker->randomElement(['Octobre', 'Novembre']),
            'etat' => true,
            'formation_id' => Formation::factory(), // Créera une formation associée automatiquement
        ];
    }
}
