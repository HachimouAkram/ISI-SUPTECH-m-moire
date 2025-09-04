<?php

namespace Database\Factories;

use App\Models\Inscription;
use App\Models\Classe;
use App\Models\ProgrammeAccademique;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class InscriptionFactory extends Factory
{
    protected $model = Inscription::class;

    public function definition(): array
    {
        return [
            'date' => $this->faker->date('Y-m-d'),
            'statut' => $this->faker->randomElement(['Encours', 'Valider', 'Terminer']),
            'classe_id' => Classe::factory(), // Associe une nouvelle classe générée
            'user_id' => User::factory(),     // Associe un nouvel utilisateur généré
            'programme_accademique_id' => ProgrammeAccademique::factory(),
        ];
    }
}
