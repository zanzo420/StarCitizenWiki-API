<?php

namespace App\Models\SC\Mission;

use App\Models\System\Translation\AbstractHasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Giver extends AbstractHasTranslations
{
    use HasFactory;

    protected $table = 'sc_mission_givers';

    protected $fillable = [
        'uuid',
        'name',
        'headquarters',
        'invitation_timeout',
        'visit_timeout',
        'short_cooldown',
        'medium_cooldown',
        'long_cooldown',
    ];

    protected $casts = [
        'invitation_timeout' => 'double',
        'visit_timeout' => 'double',
        'short_cooldown' => 'double',
        'medium_cooldown' => 'double',
        'long_cooldown' => 'double',
    ];

    public function translations(): HasMany
    {
        return $this->hasMany(
            GiverTranslation::class,
            'giver_uuid',
            'uuid',
        );
    }

    public function missions(): HasMany
    {
        return $this->hasMany(Mission::class, 'giver_id', 'id');
    }
}
