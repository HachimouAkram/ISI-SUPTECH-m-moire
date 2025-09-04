<?php

namespace Database\Factories;

use App\Models\Document;
use App\Models\Inscription;
use Illuminate\Database\Eloquent\Factories\Factory;

class DocumentFactory extends Factory
{
    protected $model = Document::class;

    public function definition(): array
    {
        return [
            'nom' => $this->faker->words(2, true),
            'description' => $this->faker->sentence(8),
            'chemin_fichier' => 'documents/' . $this->faker->uuid . '.pdf',
            'inscription_id' => Inscription::factory(), // Crée une inscription automatiquement si besoin
        ];
    }
}
