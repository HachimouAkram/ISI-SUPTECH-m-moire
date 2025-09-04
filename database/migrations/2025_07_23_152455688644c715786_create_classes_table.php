<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('classes', function (Blueprint $table) {
            $table->id();
            $table->decimal('prix_inscription', 10, 2);
            $table->decimal('prix_mensuel', 10, 2);
            $table->string('mois_rentree');
            $table->integer('duree');
            $table->integer('niveau');
            $table->boolean('etat')->default(true);
            $table->foreignId('formation_id')->constrained()->onDelete('cascade');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('classes');    }
};
