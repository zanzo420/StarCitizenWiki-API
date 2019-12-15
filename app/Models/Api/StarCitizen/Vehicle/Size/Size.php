<?php declare(strict_types = 1);

namespace App\Models\Api\StarCitizen\Vehicle\Size;

use App\Models\System\Translation\AbstractHasTranslations as HasTranslations;
use App\Traits\HasVehicleRelationsTrait as VehicleRelations;
use Illuminate\Database\Eloquent\Builder;

/**
 * Vehicle Size Model
 */
class Size extends HasTranslations
{
    use VehicleRelations;

    /**
     * @var bool
     */
    public $timestamps = false;

    /**
     * @var array
     */
    protected $fillable = [
        'slug',
    ];

    /**
     * @var string
     */
    protected $table = 'vehicle_sizes';


    /**
     * @var array
     */
    protected $with = [
        'translations',
    ];

    /**
     * {@inheritdoc}
     */
    public function getRouteKeyName()
    {
        return 'slug';
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function translations()
    {
        return $this->hasMany(SizeTranslation::class);
    }

    /**
     * Ships
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeShip(Builder $query)
    {
        $query->where('slug', '!=', 'vehicle');
    }

    /**
     * Ground Vehicles
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     */
    public function scopeGroundVehicle(Builder $query)
    {
        $query->where('slug', 'vehicle');
    }
}
