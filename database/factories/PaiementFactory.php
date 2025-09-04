<?php

namespace Database\Factories;

use App\Models\Paiement;
use App\Models\User;
use App\Models\Inscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class PaiementFactory extends Factory
{
    protected $model = Paiement::class;

    public function definition(): array
    {
        return [
            'montant' => $this->faker->randomFloat(2, 1000, 50000),
            'date' => $this->faker->date('Y-m-d'),
            'mode_paiement' => $this->faker->randomElement(['Espèces', 'Carte', 'Virement', 'Mobile']),
            'type_paiement' => $this->faker->randomElement(['Inscription', 'Mensualité']),
            'user_id' => User::factory(),           // crée un utilisateur si besoin
            'inscription_id' => Inscription::factory(), // crée une inscription associée
        ];
    }
}
