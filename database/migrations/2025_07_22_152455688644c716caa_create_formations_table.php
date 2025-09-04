<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('formations', function (Blueprint $table) {
            $table->id();
            $table->string('nom', 150);
            $table->text('description')->nullable();
            $table->enum('duree',['3 ans', '2 ans']);
            $table->enum('type_formation', ['Master', 'Licence', 'BTS']);
            $table->enum('domaine', ['informatique', 'Gestion']);
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('formations');
    }
};
