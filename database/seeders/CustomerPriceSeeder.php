<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Product;
use App\Models\CustomerProductPrice;

class CustomerPriceSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::take(3)->get();
        $products = Product::take(3)->get();

        foreach ($customers as $index => $customer) {
            foreach ($products as $product) {
                CustomerProductPrice::create([
                    'customer_id' => $customer->id,
                    'product_id' => $product->id,
                    'custom_price' => $product->harga_default - 5000 - ($index * 1000),
                    'created_by' => 1,
                ]);
            }
        }
    }
}
