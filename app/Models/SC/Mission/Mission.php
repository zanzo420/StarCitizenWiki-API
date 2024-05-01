<?php

namespace App\Models\SC\Mission;

use App\Models\System\Translation\AbstractHasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Mission extends AbstractHasTranslations
{
    use HasFactory;

    protected $table = 'sc_missions';

    protected $fillable = [
        'uuid',
        'not_for_release',
        'title',
        'title_hud',
        'mission_giver',
        'comms_channel_name',
        'type',
        'locality_available',
        'location_mission_available',
        'initially_active',
        'notify_on_available',
        'show_as_offer',
        'mission_buy_in_amount',
        'refund_buy_in_on_withdraw',
        'has_complete_button',
        'handles_abandon_request',
        'mission_module_per_player',
        'max_instances',
        'max_players_per_instance',
        'max_instances_per_player',
        'can_be_shared',
        'once_only',
        'tutorial',
        'display_allied_markers',
        'available_in_prison',
        'fail_if_sent_to_prison',
        'fail_if_became_criminal',
        'fail_if_leave_prison',
        'request_only',
        'respawn_time',
        'respawn_time_variation',
        'instance_has_life_time',
        'show_life_time_in_mobi_glas',
        'instance_life_time',
        'instance_life_time_variation',
        'can_reaccept_after_abandoning',
        'abandoned_cooldown_time',
        'abandoned_cooldown_time_variation',
        'can_reaccept_after_failing',
        'has_personal_cooldown',
        'personal_cooldown_time',
        'personal_cooldown_time_variation',
        'module_handles_own_shutdown',
        'linked_mission',
        'lawful_mission',
        'invitation_mission',
        'version',
        'type_id',
        'giver_id',
    ];

    protected $casts = [
        'not_for_release' => 'boolean',
        'initially_active' => 'boolean',
        'notify_on_available' => 'boolean',
        'show_as_offer' => 'boolean',
        'mission_buy_in_amount' => 'double',
        'refund_buy_in_on_withdraw' => 'double',
        'has_complete_button' => 'boolean',
        'handles_abandon_request' => 'boolean',
        'mission_module_per_player' => 'integer',
        'max_instances' => 'integer',
        'max_players_per_instance' => 'integer',
        'max_instances_per_player' => 'integer',
        'can_be_shared' => 'boolean',
        'once_only' => 'boolean',
        'tutorial' => 'boolean',
        'display_allied_markers' => 'boolean',
        'available_in_prison' => 'boolean',
        'fail_if_sent_to_prison' => 'boolean',
        'fail_if_became_criminal' => 'boolean',
        'fail_if_leave_prison' => 'boolean',
        'request_only' => 'boolean',
        'respawn_time' => 'double',
        'respawn_time_variation' => 'double',
        'instance_has_life_time' => 'boolean',
        'show_life_time_in_mobi_glas' => 'boolean',
        'instance_life_time' => 'double',
        'instance_life_time_variation' => 'double',
        'can_reaccept_after_abandoning' => 'boolean',
        'abandoned_cooldown_time' => 'double',
        'abandoned_cooldown_time_variation' => 'double',
        'can_reaccept_after_failing' => 'boolean',
        'has_personal_cooldown' => 'boolean',
        'personal_cooldown_time' => 'double',
        'personal_cooldown_time_variation' => 'double',
        'module_handles_own_shutdown' => 'boolean',
        'lawful_mission' => 'boolean',
    ];

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function deadline(): HasOne
    {
        return $this->hasOne(Deadline::class)->withDefault();
    }

    public function reward(): HasOne
    {
        return $this->hasOne(Reward::class)->withDefault();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(Type::class)->withDefault();
    }

    public function giver(): BelongsTo
    {
        return $this->belongsTo(Giver::class)->withDefault();
    }

    public function requiredMissions(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'sc_mission_required_missions',
            'mission_id',
            'required_mission_id',
        );
    }

    public function associatedMissions(): BelongsToMany
    {
        return $this->belongsToMany(
            self::class,
            'sc_mission_associated_missions',
            'mission_id',
            'associated_mission_id',
        );
    }

    public function translations()
    {
        return $this->hasMany(
            Translation::class,
            'mission_uuid',
            'uuid',
        );
    }
}
