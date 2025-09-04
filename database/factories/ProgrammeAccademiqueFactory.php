<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\ProgrammeAccademique>
 */
class ProgrammeAccademiqueFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'date_ouverture_inscription' => now()->subMonths(2),
            'date_fermeture_inscription' => now()->addMonths(1),
            'annee_accademique' => '2025-2026',
            'etat' => true,
        ];
    }

}
