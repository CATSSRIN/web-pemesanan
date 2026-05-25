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

    public function getPriceForProduct($productId, $defaultPrice = 0)
    {
        // 1. Personal Pricing
        $customPrice = $this->customPrices()->where('product_id', $productId)->first();
        if ($customPrice) {
            return $customPrice->custom_price;
        }

        // 2. Category Pricing
        if ($this->kategori_harga) {
            $product = Product::find($productId);
            if ($product) {
                if ($this->kategori_harga === 'high' && $product->harga_high > 0) return $product->harga_high;
                if ($this->kategori_harga === 'middle' && $product->harga_middle > 0) return $product->harga_middle;
                if ($this->kategori_harga === 'low' && $product->harga_low > 0) return $product->harga_low;
            }
        }

        // 3. Default Price
        return $defaultPrice;
    }
}
