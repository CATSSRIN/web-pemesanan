<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $guarded = [];

    public function customPrices()
    {
        return $this->hasMany(CustomerProductPrice::class);
    }

    public function deliveryOrders()
    {
        return $this->hasMany(DeliveryOrder::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function getPriceForProduct($productId, $defaultPrice)
    {
        $custom = $this->customPrices()->where('product_id', $productId)->first();
        return $custom ? $custom->custom_price : $defaultPrice;
    }
}
