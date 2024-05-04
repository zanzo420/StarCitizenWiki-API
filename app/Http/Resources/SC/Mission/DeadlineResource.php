<?php

namespace App\Http\Resources\SC\Mission;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: 'mission_deadline_v2',
    title: 'Mission Deadline',
    properties: [
        new OA\Property(property: 'mission_completion_time', type: 'double', nullable: true),
        new OA\Property(property: 'mission_auto_end', type: 'boolean', nullable: true),
        new OA\Property(property: 'mission_result_after_timer_end', type: 'string'),
        new OA\Property(property: 'mission_end_reason', type: 'string'),
    ],
    type: 'object'
)]
class DeadlineResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'mission_completion_time' => $this->mission_completion_time,
            'mission_auto_end' => $this->mission_auto_end,
            'mission_result_after_timer_end' => $this->mission_result_after_timer_end,
            'mission_end_reason' => $this->mission_end_reason,
        ];
    }
}
