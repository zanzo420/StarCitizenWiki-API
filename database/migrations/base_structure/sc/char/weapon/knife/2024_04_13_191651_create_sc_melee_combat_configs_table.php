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
        Schema::create('sc_melee_combat_configs', static function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('category');
            $table->double('stun_recovery_modifier');
            $table->double('block_stun_reduction_modifier');
            $table->double('block_stun_stamina_modifier');
            $table->double('attack_impulse');
            $table->boolean('ignore_body_part_impulse_scale');
            $table->boolean('fullbody_animation');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_melee_combat_configs');
    }
};
