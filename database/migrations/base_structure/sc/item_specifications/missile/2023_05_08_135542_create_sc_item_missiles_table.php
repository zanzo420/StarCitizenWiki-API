<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        Schema::create('sc_item_missiles', static function (Blueprint $table) {
            $table->id();
            $table->uuid('item_uuid')->unique();
            $table->string('signal_type');
            $table->unsignedDouble('lock_time');

            $table->timestamps();

            $table->foreign('item_uuid', 'fk_sc_i_mis_item_uuid')
                ->references('uuid')
                ->on('sc_items')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_item_missiles');
    }
};
