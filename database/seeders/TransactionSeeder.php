<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use App\Models\Product;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderItem;
use App\Services\InvoiceService;

class TransactionSeeder extends Seeder
{
    public function run(): void
    {
        $customers = Customer::all();
        $products = Product::all();
        $invoiceService = new InvoiceService();

        for ($i = 1; $i <= 10; $i++) {
            $customer = $customers->random();
            $do = DeliveryOrder::create([
                'nomor_do' => 'DO-' . date('Ym') . '-' . str_pad($i, 4, '0', STR_PAD_LEFT),
                'tanggal_do' => now()->subDays(rand(1, 10)),
                'customer_id' => $customer->id,
                'alamat_kirim' => $customer->alamat,
                'status_do' => $i <= 5 ? 'finalized' : 'draft',
                'created_by' => 1,
            ]);

            for ($j = 0; $j < 2; $j++) {
                $product = $products->random();
                $qty = rand(1, 5);
                $harga = $customer->getPriceForProduct($product->id, $product->harga_default);
                DeliveryOrderItem::create([
                    'delivery_order_id' => $do->id,
                    'product_id' => $product->id,
                    'qty' => $qty,
                    'harga' => $harga,
                    'subtotal' => $harga * $qty,
                ]);
            }

            if ($i <= 3) {
                $invoiceService->generateFromDo($do, 1);
            }
        }
    }
}
