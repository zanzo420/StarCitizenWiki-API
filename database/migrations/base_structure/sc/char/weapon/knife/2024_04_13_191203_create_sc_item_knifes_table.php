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
        Schema::create('sc_item_knifes', static function (Blueprint $table) {
            $table->id();
            $table->uuid('item_uuid');
            $table->boolean('can_be_used_for_take_down');
            $table->boolean('can_block');
            $table->boolean('can_be_used_in_prone');
            $table->boolean('can_dodge');
            $table->uuid('melee_combat_config_uuid');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_item_knifes');
    }
};
