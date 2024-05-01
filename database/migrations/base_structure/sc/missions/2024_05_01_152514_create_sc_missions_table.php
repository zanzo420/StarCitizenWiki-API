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
        Schema::create('sc_missions', static function (Blueprint $table) {
            $table->id();
            $table->uuid();
            $table->boolean('not_for_release');
            $table->string('title');
            $table->string('title_hud');
            $table->string('mission_giver');
            $table->string('comms_channel_name');
            $table->unsignedInteger('type_id')->nullable();
            $table->string('locality_available');
            $table->string('location_mission_available');
            $table->boolean('initially_active');
            $table->boolean('notify_on_available');
            $table->boolean('show_as_offer');
            $table->double('mission_buy_in_amount');
            $table->double('refund_buy_in_on_withdraw');
            $table->boolean('has_complete_button');
            $table->boolean('handles_abandon_request');
            $table->integer('mission_module_per_player');
            $table->integer('max_instances');
            $table->integer('max_players_per_instance');
            $table->integer('max_instances_per_player');
            $table->boolean('can_be_shared');
            $table->boolean('once_only');
            $table->boolean('tutorial');
            $table->boolean('display_allied_markers');
            $table->boolean('available_in_prison');
            $table->boolean('fail_if_sent_to_prison');
            $table->boolean('fail_if_became_criminal');
            $table->boolean('fail_if_leave_prison');
            $table->boolean('request_only');
            $table->double('respawn_time');
            $table->double('respawn_time_variation');
            $table->boolean('instance_has_life_time');
            $table->boolean('show_life_time_in_mobi_glas');
            $table->double('instance_life_time');
            $table->double('instance_life_time_variation');
            $table->boolean('can_reaccept_after_abandoning');
            $table->double('abandoned_cooldown_time');
            $table->double('abandoned_cooldown_time_variation');
            $table->boolean('can_reaccept_after_failing');
            $table->boolean('has_personal_cooldown');
            $table->double('personal_cooldown_time');
            $table->double('personal_cooldown_time_variation');
            $table->boolean('module_handles_own_shutdown');
            $table->uuid('linked_mission');
            $table->boolean('lawful_mission');
            $table->unsignedBigInteger('giver_id')->nullable();
            $table->uuid('invitation_mission');
            $table->string('version');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('sc_missions');
    }
};
