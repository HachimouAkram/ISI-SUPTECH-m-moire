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
        $this->call(PermissionSeeder::class);

    }
}
