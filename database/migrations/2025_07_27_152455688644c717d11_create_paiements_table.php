<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('paiements', function (Blueprint $table) {
            $table->id();
            $table->decimal('montant', 10, 2);
            $table->date('date');
            $table->enum('mode_paiement', ['Espèces', 'Carte', 'Virement', 'Mobile']);
            $table->enum('type_paiement', ['Inscription', 'Mensualité']);
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('inscription_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('paiements');
    }
};

