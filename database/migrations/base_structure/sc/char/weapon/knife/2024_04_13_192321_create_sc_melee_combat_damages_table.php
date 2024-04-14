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
        Schema::create('sc_melee_combat_damages', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('combat_config_id');
            $table->string('name');

            $table->unsignedDouble('damage');

            $table->timestamps();

            $table->foreign('combat_config_id', 'fk_sc_m_c_con_combat_config_id')
                ->references('id')
                ->on('sc_melee_combat_configs')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_melee_combat_damages');
    }
};
