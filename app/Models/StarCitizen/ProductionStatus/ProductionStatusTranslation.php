<?php declare(strict_types = 1);

namespace App\Models\StarCitizen\ProductionStatus;

use App\Models\AbstractTranslation as Translation;

/**
 * Production Status Translations
 */
class ProductionStatusTranslation extends Translation
{
    protected $fillable = [
        'language_id',
        'production_status_id',
        'translation',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function productionStatus()
    {
        return $this->belongsTo('App\Models\StarCitizen\ProductionStatus\ProductionStatus');
    }
}
