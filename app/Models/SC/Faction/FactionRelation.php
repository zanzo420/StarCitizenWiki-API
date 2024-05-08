<?php

namespace App\Models\SC\Faction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class FactionRelation extends Model
{
    use HasFactory;

    protected $table = 'sc_faction_relations';

    protected $fillable = [
        'faction_id',
        'other_faction_uuid',
        'relation',
    ];

    public function faction(): BelongsTo
    {
        return $this->belongsTo(
            Faction::class,
            'other_faction_uuid',
            'uuid'
        );
    }
}
