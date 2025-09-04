<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProgrammeAccademiquesTable extends Migration
{
    public function up()
    {
        Schema::create('programme_accademiques', function (Blueprint $table) {
            $table->id();
            $table->date('date_ouverture_inscription');
            $table->date('date_fermeture_inscription');
            $table->string('annee_accademique');
            $table->boolean('etat')->default(true); // actif ou non
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('programme_accademiques');
    }
}
