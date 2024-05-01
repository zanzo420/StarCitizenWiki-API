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
        Schema::create('sc_reputation_scopes', static function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->string('scope_name');
            $table->string('display_name');
            $table->string('description')->nullable();
            $table->string('class_name');
            $table->double('initial_reputation');
            $table->double('reputation_ceiling');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_reputation_scopes');
    }
};
