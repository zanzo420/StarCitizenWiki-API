<?php

declare(strict_types=1);

namespace App\Models\SC;

use App\Models\SC\Item\Item;
use App\Models\SC\Item\ItemDescriptionData;
use App\Models\SC\Shop\Shop;
use App\Models\SC\Shop\ShopItem;
use App\Models\System\Translation\AbstractHasTranslations as HasTranslations;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;

abstract class CommodityItem extends HasTranslations
{
    use HasFactory;

    protected $with = [
        'item',
    ];

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(
            'version',
            static function (Builder $builder) {
                $builder->whereRelation('item', 'version', config('api.sc_data_version'));
            }
        );

        static::addGlobalScope(
            'only_usable',
            static function (Builder $builder) {
                $builder->has('item');
            }
        );
    }

    public function item(): BelongsTo
    {
        return $this->belongsTo(Item::class, 'item_uuid', 'uuid')->withoutGlobalScopes();
    }

    public function getNameAttribute()
    {
        return $this->item?->name;
    }

    public function getVersionAttribute()
    {
        return $this->item?->version;
    }

    public function translations(): HasMany
    {
        return $this->item->translations();
    }

    public function shops(): HasManyThrough
    {
        return $this->hasManyThrough(
            Shop::class,
            ShopItem::class,
            'item_uuid',
            'uuid',
            'uuid',
            'shop_uuid'
        );
    }

    public function descriptionData(): HasManyThrough
    {
        return $this->hasManyThrough(
            ItemDescriptionData::class,
            Item::class,
            'uuid',
            'item_uuid',
            'item_uuid',
            'uuid'
        );
    }
}
