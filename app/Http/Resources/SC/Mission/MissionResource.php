<?php

namespace App\Http\Resources\SC\Mission;

use App\Http\Resources\AbstractTranslationResource;
use Illuminate\Http\Request;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'mission_v2',
    title: 'Mission',
    properties: [
        new OA\Property(property: 'uuid', type: 'string', nullable: true),
        new OA\Property(property: 'title', type: 'string', nullable: true),
        new OA\Property(property: 'title_hud', type: 'string', nullable: true),
        new OA\Property(
            property: 'description',
            oneOf: [
                new OA\Schema(type: 'string'),
                new OA\Schema(
                    type: 'array',
                    items: new OA\Items(ref: '#/components/schemas/translation_v2'),
                ),
            ],
        ),
        new OA\Property(property: 'mission_giver', type: 'string', nullable: true),
        new OA\Property(property: 'mission_giver_record', ref: '#/components/schemas/mission_giver_v2', nullable: true),
        new OA\Property(property: 'comms_channel_name', type: 'string', nullable: true),
        new OA\Property(property: 'type', type: 'string', nullable: true),
        new OA\Property(property: 'locality_available', type: 'string', nullable: true),
        new OA\Property(property: 'location_mission_available', type: 'string', nullable: true),
        new OA\Property(property: 'not_for_release', type: 'boolean', nullable: true),
        new OA\Property(property: 'initially_active', type: 'boolean', nullable: true),
        new OA\Property(property: 'notify_on_available', type: 'boolean', nullable: true),
        new OA\Property(property: 'show_as_offer', type: 'boolean', nullable: true),
        new OA\Property(property: 'mission_buy_in_amount', type: 'double', nullable: true),
        new OA\Property(property: 'refund_buy_in_on_withdraw', type: 'double', nullable: true),
        new OA\Property(property: 'has_complete_button', type: 'boolean', nullable: true),
        new OA\Property(property: 'handles_abandon_request', type: 'boolean', nullable: true),
        new OA\Property(property: 'mission_module_per_player', type: 'integer', nullable: true),
        new OA\Property(property: 'max_instances', type: 'integer', nullable: true),
        new OA\Property(property: 'max_players_per_instance', type: 'integer', nullable: true),
        new OA\Property(property: 'max_instances_per_player', type: 'integer', nullable: true),
        new OA\Property(property: 'can_be_shared', type: 'boolean', nullable: true),
        new OA\Property(property: 'once_only', type: 'boolean', nullable: true),
        new OA\Property(property: 'tutorial', type: 'boolean', nullable: true),
        new OA\Property(property: 'display_allied_markers', type: 'boolean', nullable: true),
        new OA\Property(property: 'available_in_prison', type: 'boolean', nullable: true),
        new OA\Property(property: 'fail_if_sent_to_prison', type: 'boolean', nullable: true),
        new OA\Property(property: 'fail_if_became_criminal', type: 'boolean', nullable: true),
        new OA\Property(property: 'fail_if_leave_prison', type: 'boolean', nullable: true),
        new OA\Property(property: 'request_only', type: 'boolean', nullable: true),
        new OA\Property(property: 'respawn_time', type: 'double', nullable: true),
        new OA\Property(property: 'respawn_time_variation', type: 'double', nullable: true),
        new OA\Property(property: 'instance_has_life_time', type: 'boolean', nullable: true),
        new OA\Property(property: 'show_life_time_in_mobi_glas', type: 'boolean', nullable: true),
        new OA\Property(property: 'instance_life_time', type: 'double', nullable: true),
        new OA\Property(property: 'instance_life_time_variation', type: 'double', nullable: true),
        new OA\Property(property: 'can_reaccept_after_abandoning', type: 'boolean', nullable: true),
        new OA\Property(property: 'abandoned_cooldown_time', type: 'double', nullable: true),
        new OA\Property(property: 'abandoned_cooldown_time_variation', type: 'double', nullable: true),
        new OA\Property(property: 'can_reaccept_after_failing', type: 'boolean', nullable: true),
        new OA\Property(property: 'has_personal_cooldown', type: 'boolean', nullable: true),
        new OA\Property(property: 'personal_cooldown_time', type: 'double', nullable: true),
        new OA\Property(property: 'personal_cooldown_time_variation', type: 'double', nullable: true),
        new OA\Property(property: 'module_handles_own_shutdown', type: 'boolean', nullable: true),
        new OA\Property(property: 'linked_mission', ref: '#/components/schemas/mission_link_v2', nullable: true),
        new OA\Property(property: 'lawful_mission', type: 'boolean', nullable: true),
        new OA\Property(property: 'deadline', ref: '#/components/schemas/mission_deadline_v2', nullable: true),
        new OA\Property(property: 'reward', ref: '#/components/schemas/mission_reward_v2', nullable: true),
        new OA\Property(property: 'associated_missions', ref: '#/components/schemas/mission_link_v2', nullable: true),
        new OA\Property(property: 'required_missions', ref: '#/components/schemas/mission_link_v2', nullable: true),
        new OA\Property(property: 'invitation_mission', type: 'string', nullable: true),
        new OA\Property(property: 'version', type: 'string', nullable: true),
    ],
    type: 'object'
)]
class MissionResource extends AbstractTranslationResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'uuid' => $this->uuid,
            'not_for_release' => $this->not_for_release,
            'title' => $this->title,
            'title_hud' => $this->title_hud,
            'description' => $this->getTranslation($this, $request),
            'mission_giver' => $this->mission_giver,
            $this->mergeWhen($this->giver->uuid !== null, [
                'mission_giver_record' => new GiverResource($this->giver),
            ]),
            'comms_channel_name' => $this->comms_channel_name,
            'type' => $this->type->name,
            'locality_available' => $this->locality_available,
            'location_mission_available' => $this->location_mission_available,
            'initially_active' => $this->initially_active,
            'notify_on_available' => $this->notify_on_available,
            'show_as_offer' => $this->show_as_offer,
            'mission_buy_in_amount' => $this->mission_buy_in_amount,
            'refund_buy_in_on_withdraw' => $this->refund_buy_in_on_withdraw,
            'has_complete_button' => $this->has_complete_button,
            'handles_abandon_request' => $this->handles_abandon_request,
            'mission_module_per_player' => $this->mission_module_per_player,
            'max_instances' => $this->max_instances,
            'max_players_per_instance' => $this->max_players_per_instance,
            'max_instances_per_player' => $this->max_instances_per_player,
            'can_be_shared' => $this->can_be_shared,
            'once_only' => $this->once_only,
            'tutorial' => $this->tutorial,
            'display_allied_markers' => $this->display_allied_markers,
            'available_in_prison' => $this->available_in_prison,
            'fail_if_sent_to_prison' => $this->fail_if_sent_to_prison,
            'fail_if_became_criminal' => $this->fail_if_became_criminal,
            'fail_if_leave_prison' => $this->fail_if_leave_prison,
            'request_only' => $this->request_only,
            'respawn_time' => $this->respawn_time,
            'respawn_time_variation' => $this->respawn_time_variation,
            'instance_has_life_time' => $this->instance_has_life_time,
            'show_life_time_in_mobi_glas' => $this->show_life_time_in_mobi_glas,
            'instance_life_time' => $this->instance_life_time,
            'instance_life_time_variation' => $this->instance_life_time_variation,
            'can_reaccept_after_abandoning' => $this->can_reaccept_after_abandoning,
            'abandoned_cooldown_time' => $this->abandoned_cooldown_time,
            'abandoned_cooldown_time_variation' => $this->abandoned_cooldown_time_variation,
            'can_reaccept_after_failing' => $this->can_reaccept_after_failing,
            'has_personal_cooldown' => $this->has_personal_cooldown,
            'personal_cooldown_time' => $this->personal_cooldown_time,
            'personal_cooldown_time_variation' => $this->personal_cooldown_time_variation,
            'module_handles_own_shutdown' => $this->module_handles_own_shutdown,
            'linked_mission' => $this->makeApiUrl(self::MISSIONS_SHOW, $this->linked_mission),
            'lawful_mission' => $this->lawful_mission,
            'deadline' => DeadlineResource::make($this->deadline)->resolve($request),
            'reward' => RewardResource::make($this->reward)->resolve($request),
            'invitation_mission' => $this->invitation_mission,
            'associated_missions' => MissionLinkResource::collection($this->associatedMissions),
            'required_missions' => MissionLinkResource::collection($this->requiredMissions),
            'version' => $this->version,
        ];
    }
}
