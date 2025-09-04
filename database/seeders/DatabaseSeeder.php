<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Formation;
use App\Models\Classe;
use App\Models\User;
use App\Models\Inscription;
use App\Models\Paiement;
use App\Models\Recu;
use App\Models\Rappel;
use App\Models\Document;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Formations
        $formations = Formation::factory()->count(5)->create();

        // 2. Classes liées aux formations
        $classes = Classe::factory()->count(10)->create([
            'formation_id' => $formations->random()->id,
        ]);

        // 3. Utilisateurs
        $users = User::factory()->count(10)->create();

        // 4. Inscriptions
        $inscriptions = Inscription::factory()->count(15)->create()->each(function ($inscription) use ($users, $classes) {
            $inscription->user_id = $users->random()->id;
            $inscription->classe_id = $classes->random()->id;
            $inscription->save();
        });

        // 5. Paiements
        $paiements = Paiement::factory()->count(20)->create()->each(function ($paiement) use ($inscriptions) {
            $inscription = $inscriptions->random();
            $paiement->inscription_id = $inscription->id;
            $paiement->user_id = $inscription->user_id;
            $paiement->save();
        });

        // 6. Reçus
        Recu::factory()->count(20)->create()->each(function ($recu) use ($paiements) {
            $recu->paiement_id = $paiements->random()->id;
            $recu->save();
        });

        // 7. Rappels
        Rappel::factory()->count(15)->create([
            'user_id' => $users->random()->id,
        ]);

        // 8. Documents
        Document::factory()->count(20)->create([
            'inscription_id' => $inscriptions->random()->id,
        ]);
    }
}
