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
        Schema::create('sc_factions', static function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name');
            $table->string('class_name');
            $table->string('description')->nullable();
            $table->string('game_token');
            $table->string('default_reaction');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_factions');
    }
};
