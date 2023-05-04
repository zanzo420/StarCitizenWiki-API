<?php

declare(strict_types=1);

namespace App\Models\SC\Item;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ItemContainer extends Model
{
    use HasFactory;

    protected $table = 'sc_item_containers';

    protected $fillable = [
        'item_uuid',
        'width',
        'height',
        'length',
        'scu',
        'unit',
    ];

    protected $casts = [
        'width' => 'double',
        'height' => 'double',
        'length' => 'double',
        'scu' => 'double',
        'unit' => 'int',
    ];

    public function getOriginalConvertedSCUAttribute(): float {
        return $this->scu * (10 ** $this->unit);
    }
}
