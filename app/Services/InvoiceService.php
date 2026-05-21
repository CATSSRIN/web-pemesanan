<?php

namespace App\Services;

use App\Models\DeliveryOrder;
use App\Models\Invoice;
use App\Models\InvoiceItem;
use Illuminate\Support\Facades\DB;

class InvoiceService
{
    public function generateFromDo(DeliveryOrder $do, $userId)
    {
        if ($do->status_do !== 'finalized') {
            throw new \Exception("DO belum difinalisasi.");
        }
        if ($do->invoice) {
            throw new \Exception("DO ini sudah memiliki Invoice.");
        }

        DB::beginTransaction();
        try {
            // Calculate total based on items since DO itself doesn't store grand total in schema
            $subtotal = $do->items()->sum('subtotal');

            // Generate Invoice number
            $lastInvoice = Invoice::latest('id')->first();
            $nextId = $lastInvoice ? $lastInvoice->id + 1 : 1;
            $invoiceNumber = 'INV-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);

            $invoice = Invoice::create([
                'nomor_invoice' => $invoiceNumber,
                'tanggal_invoice' => now(),
                'delivery_order_id' => $do->id,
                'customer_id' => $do->customer_id,
                'jatuh_tempo' => now()->addDays(14),
                'subtotal' => $subtotal,
                'total' => $subtotal,
                'status_invoice' => 'unpaid',
                'created_by' => $userId
            ]);

            foreach ($do->items as $item) {
                InvoiceItem::create([
                    'invoice_id' => $invoice->id,
                    'product_id' => $item->product_id,
                    'qty' => $item->qty,
                    'harga' => $item->harga,
                    'subtotal' => $item->subtotal
                ]);
            }

            $do->update(['status_do' => 'invoiced']);
            
            DB::commit();
            return $invoice;
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
