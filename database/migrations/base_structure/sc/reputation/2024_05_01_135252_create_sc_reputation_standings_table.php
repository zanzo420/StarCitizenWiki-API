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
        Schema::create('sc_reputation_standings', static function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name');
            $table->string('description')->nullable();
            $table->string('display_name')->nullable();
            $table->string('perk_description')->nullable();
            $table->double('min_reputation');
            $table->double('drift_reputation');
            $table->double('drift_time_hours');
            $table->boolean('gated');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_reputation_standings');
    }
};
