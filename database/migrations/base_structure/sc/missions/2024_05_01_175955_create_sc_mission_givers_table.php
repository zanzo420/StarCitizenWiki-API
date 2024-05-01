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
        Schema::create('sc_mission_givers', static function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('name')->nullable();
            $table->string('headquarters')->nullable();
            $table->double('invitation_timeout');
            $table->double('visit_timeout');
            $table->double('short_cooldown');
            $table->double('medium_cooldown');
            $table->double('long_cooldown');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_mission_givers');
    }
};
