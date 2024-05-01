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
        Schema::create('sc_reputation_rewards', static function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('class_name');
            $table->string('editor_name');
            $table->double('reputation_amount');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_reputation_rewards');
    }
};
