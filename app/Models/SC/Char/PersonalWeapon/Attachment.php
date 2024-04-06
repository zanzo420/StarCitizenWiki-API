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
                $builder->where('type', 'WeaponAttachment')
                    ->where('name', '<>', '<= PLACEHOLDER =>')
                    ->where('class_name', 'NOT LIKE', '%test%')
                    ->where('class_name', 'NOT LIKE', 'weaponmount_%')
                    ->where('class_name', 'NOT LIKE', '%ea_elim')
                    ->where('class_name', '<>', 'grin_tool_01_mag');
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
