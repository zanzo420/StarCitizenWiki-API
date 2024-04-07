<?php

declare(strict_types=1);

namespace App\Models\SC\Char\PersonalWeapon;

use App\Models\SC\Item\Item;
use Illuminate\Database\Eloquent\Builder;

class Attachment extends Item
{
    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(
            'type',
            static function (Builder $builder) {
                $builder->where('type', 'WeaponAttachment');
            }
        );
    }

    public function getAttachmentPointAttribute()
    {
        return $this->getDescriptionDatum('Attachment Point');
    }

    public function getAttachmentTypeAttribute()
    {
        return $this->getDescriptionDatum('Type');
    }

    public function getSizeAttribute()
    {
        return $this->getDescriptionDatum('Size');
    }
}
