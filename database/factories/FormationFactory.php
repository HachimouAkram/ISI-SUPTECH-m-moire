<?php

namespace Database\Factories;

use App\Models\Formation;
use Illuminate\Database\Eloquent\Factories\Factory;

class FormationFactory extends Factory
{
    protected $model = Formation::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->sentence(3),
            'description' => $this->faker->paragraph(),
            'duree' => $this->faker->randomElement(['3 ans', '2 ans']),
            'type_formation' => $this->faker->randomElement(['Master', 'Licence', 'BTS']),
            'domaine' => $this->faker->randomElement(['Genie informatique', 'Réseau informatique', 'Gestion']),
        ];
    }
}
