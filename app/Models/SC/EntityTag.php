<?php

namespace App\Models\SC;

use App\Models\SC\Item\Item;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class EntityTag extends Model
{
    use HasFactory;

    protected $table = 'sc_entity_tags';

    protected $fillable = [
        'tag',
    ];

    public function items(): BelongsToMany
    {
        return $this->belongsToMany(
            Item::class,
            'sc_item_entity_tag',
            'item_id',
            'entity_tag_id'
        );
    }
}
