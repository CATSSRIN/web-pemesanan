<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryOrder;
use App\Models\Invoice;
use Barryvdh\DomPDF\Facade\Pdf;

class PrintController extends Controller
{
    public function printDo($id)
    {
        $do = DeliveryOrder::with(['customer', 'items.product'])->findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.do', compact('do'));
        return $pdf->stream('DO_' . $do->nomor_do . '.pdf');
    }

    public function printInvoice($id)
    {
        $invoice = Invoice::with(['customer', 'deliveryOrder', 'items.product'])->findOrFail($id);
        
        $pdf = Pdf::loadView('pdf.invoice', compact('invoice'));
        return $pdf->stream('INV_' . $invoice->nomor_invoice . '.pdf');
    }
}
