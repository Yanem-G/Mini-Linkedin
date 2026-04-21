<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('competence_profil', function (Blueprint $table) {
            $table->id();
            $table->foreignId("competence_id")->constrained()->onDelete("cascade");
            $table->foreignId("profil_id")->constrained()->onDelete("cascade");
            $table->enum("niveau",["Débutant","Intermédiaire","Expert"])->default("Débutant");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
