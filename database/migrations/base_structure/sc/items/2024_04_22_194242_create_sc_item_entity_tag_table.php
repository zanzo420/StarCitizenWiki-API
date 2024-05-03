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
        Schema::create('sc_item_entity_tag', static function (Blueprint $table) {
            $table->unsignedBigInteger('item_id');
            $table->unsignedBigInteger('entity_tag_id');

            $table->foreign('item_id', 'sc_i_e_tag_item_id')
                ->references('id')
                ->on('sc_items')
                ->onDelete('cascade');

            $table->foreign('entity_tag_id', 'sc_i_e_tag_tag_id')
                ->references('id')
                ->on('sc_entity_tags')
                ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_item_entity_tag');
    }
};
