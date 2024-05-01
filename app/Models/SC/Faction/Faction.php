<?php

namespace App\Models\SC\Faction;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Faction extends Model
{
    use HasFactory;

    protected $table = 'sc_factions';

    protected $fillable = [
        'uuid',
        'name',
        'class_name',
        'description',
        'game_token',
        'default_reaction',
    ];

    public function relations(): HasMany
    {
        return $this->hasMany(FactionRelation::class);
    }
}
