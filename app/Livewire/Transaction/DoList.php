<?php

namespace App\Livewire\Transaction;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DeliveryOrder;
use App\Services\InvoiceService;

class DoList extends Component
{
    use WithPagination;

    public $search = '';

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function finalizeDo($id)
    {
        $do = DeliveryOrder::findOrFail($id);
        if ($do->status_do === 'draft') {
            $do->update(['status_do' => 'finalized']);
            session()->flash('message', 'DO ' . $do->nomor_do . ' berhasil difinalisasi.');
        }
    }

    public function createInvoice($id, InvoiceService $service)
    {
        $do = DeliveryOrder::findOrFail($id);
        try {
            $service->generateFromDo($do, auth()->id());
            session()->flash('message', 'Invoice berhasil dibuat untuk DO ' . $do->nomor_do);
        } catch (\Exception $e) {
            session()->flash('error', $e->getMessage());
        }
    }

    public function render()
    {
        $dos = DeliveryOrder::with('customer', 'invoice')
            ->where('nomor_do', 'like', '%' . $this->search . '%')
            ->orWhereHas('customer', function($q) {
                $q->where('nama_pelanggan', 'like', '%' . $this->search . '%');
            })
            ->latest()
            ->paginate(10);

        return view('livewire.transaction.do-list', ['dos' => $dos])->layout('layouts.app', ['header' => 'Delivery Order']);
    }
}
