<?php

declare(strict_types=1);

namespace App\Models\SC\Item;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class ItemPort extends Model
{
    use HasFactory;

    protected $table = 'sc_item_ports';

    protected $fillable = [
        'name',
        'display_name',
        'equipped_item_uuid',
        'min_size',
        'max_size',
    ];

    protected $casts = [
        'min_size' => 'int',
        'max_size' => 'int',
    ];

    public function item(): BelongsTo
    {
        return $this->belongsTo(
            Item::class,
            'equipped_item_uuid',
            'uuid'
        );
    }
}
