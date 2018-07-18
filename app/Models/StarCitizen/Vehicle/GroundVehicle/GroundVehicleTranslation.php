<?php declare(strict_types = 1);

namespace App\Models\StarCitizen\Vehicle\GroundVehicle;

use App\Models\AbstractTranslation as Translation;

/**
 * Ground Vehicle Translation Model
 */
class GroundVehicleTranslation extends Translation
{
    protected $fillable = [
        'language_id',
        'ground_vehicle_id',
        'translation',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function groundVehicle()
    {
        return $this->belongsTo('App\Models\StarCitizen\Vehicle\GroundVehicle\GroundVehicle');
    }
}
