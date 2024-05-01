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
        Schema::create('sc_mission_deadlines', static function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('mission_id');
            $table->double('mission_completion_time');
            $table->boolean('mission_auto_end');
            $table->string('mission_result_after_timer_end');
            $table->string('mission_end_reason');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_mission_deadlines');
    }
};
