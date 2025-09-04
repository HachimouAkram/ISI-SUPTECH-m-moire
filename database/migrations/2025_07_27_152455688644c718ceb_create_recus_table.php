<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('recus', function (Blueprint $table) {
            $table->id();
            $table->string('fichier_pdf', 255); // limitation raisonnable du nom du fichier
            $table->date('date_emission'); // nom plus explicite
            $table->foreignId('paiement_id')->constrained()->onDelete('cascade'); // lien avec un paiement
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('recus');
    }
};
