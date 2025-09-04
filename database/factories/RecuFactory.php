<?php

namespace Database\Factories;

use App\Models\Recu;
use App\Models\Paiement;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecuFactory extends Factory
{
    protected $model = Recu::class;

    public function definition(): array
    {
        return [
            'fichier_pdf' => 'recu_' . $this->faker->uuid . '.pdf',
            'date_emission' => $this->faker->date('Y-m-d'),
            'paiement_id' => Paiement::factory(), // Crée un paiement associé automatiquement
        ];
    }
}
