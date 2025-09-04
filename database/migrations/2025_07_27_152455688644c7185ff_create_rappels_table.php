<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('rappels', function (Blueprint $table) {
            $table->id();
            $table->dateTime('date_rappel'); // meilleure précision, nom explicite
            $table->text('message'); // permet d’avoir plus qu’une simple chaîne
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); // optionnel : rattacher à un utilisateur
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('rappels');
    }
};
