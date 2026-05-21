<?php

namespace App\Livewire\Transaction;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Invoice;

class InvoiceList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function markAsPaid($id)
    {
        $invoice = Invoice::findOrFail($id);
        if ($invoice->status_invoice === 'unpaid') {
            $invoice->update(['status_invoice' => 'paid']);
            session()->flash('message', 'Invoice ' . $invoice->nomor_invoice . ' berhasil ditandai Lunas.');
        }
    }

    public function render()
    {
        $invoices = Invoice::with('customer', 'deliveryOrder')
            ->where('nomor_invoice', 'like', '%' . $this->search . '%')
            ->orWhereHas('customer', function($q) {
                $q->where('nama_pelanggan', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.transaction.invoice-list', ['invoices' => $invoices])->layout('layouts.app', ['header' => 'Faktur (Invoice)']);
    }
}
