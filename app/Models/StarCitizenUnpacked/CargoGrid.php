<?php

declare(strict_types=1);

namespace App\Models\StarCitizenUnpacked;

use App\Models\StarCitizenUnpacked\ShipItem\AbstractShipItemSpecification;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class CargoGrid extends AbstractShipItemSpecification
{
    use HasFactory;

    protected $table = 'star_citizen_unpacked_vehicle_cargo_grids';

    protected $fillable = [
        'ship_item_id',
        'uuid',
        'x',
        'y',
        'z',
    ];

    protected $casts = [
        'x' => 'double',
        'y' => 'double',
        'z' => 'double',
    ];

    public function getDimensionAttribute(): float
    {
        return $this->x * $this->y * $this->z;
    }

    public function getScuAttribute(): float
    {
        return $this->dimension / 1.953125;
    }
}
