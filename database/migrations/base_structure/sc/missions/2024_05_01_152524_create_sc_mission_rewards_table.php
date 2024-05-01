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
        Schema::create('sc_mission_rewards', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mission_id');
            $table->double('amount');
            $table->double('max');
            $table->boolean('plus_bonuses');
            $table->string('currency');
            $table->uuid('reputation_bonus');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_mission_rewards');
    }
};
