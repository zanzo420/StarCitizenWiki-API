<?php

declare(strict_types=1);

namespace App\Http\Resources\SC\Shop;

use App\Http\Resources\AbstractBaseResource;
use Illuminate\Http\Request;

class ShopItemResource extends AbstractBaseResource
{
    /**
     * Transform the resource collection into an array.
     *
     * @param Request $request
     * @return array
     */
    public function toArray($request): array
    {
        $data = [
            'uuid' => $this->uuid,
            'name' => $this->name,
            'type' => $this->type,
            'sub_type' => $this->sub_type,
            'base_price' => $this->shop_data->base_price,
            'price_calculated' => $this->shop_data->offsetted_price,
            'price_range' => $this->shop_data->price_range,
            'base_price_offset' => $this->shop_data->base_price_offset,
            'max_discount' => $this->shop_data->max_discount,
            'max_premium' => $this->shop_data->max_premium,
            'inventory' => $this->shop_data->inventory,
            'optimal_inventory' => $this->shop_data->optimal_inventory,
            'max_inventory' => $this->shop_data->max_inventory,
            'auto_restock' => $this->shop_data->auto_restock,
            'auto_consume' => $this->shop_data->auto_consume,
            'refresh_rate' => $this->shop_data->refresh_rate,
            'buyable' => $this->shop_data->buyable,
            'sellable' => $this->shop_data->sellable,
            'rentable' => $this->shop_data->rentable,
            'version' => $this->shop_data->version,
        ];

        if (isset($this->shop_data->rental)) {
            $data['rental_price_days'] = [
                'duration_1' => $this->shop_data->price1,
                'duration_3' => $this->shop_data->price3,
                'duration_7' => $this->shop_data->price7,
                'duration_30' => $this->shop_data->price30,
            ];
        }

        return $data;
    }
}
