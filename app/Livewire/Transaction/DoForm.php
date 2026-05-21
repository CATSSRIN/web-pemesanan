<?php

namespace App\Livewire\Transaction;

use Livewire\Component;
use App\Models\Customer;
use App\Models\Product;
use App\Models\DeliveryOrder;
use App\Models\DeliveryOrderItem;
use Illuminate\Support\Facades\DB;

class DoForm extends Component
{
    public $doId;
    public $nomor_do;
    public $tanggal_do;
    public $customer_id;
    public $alamat_kirim;
    public $keterangan;
    
    public $items = []; // Array of arrays: ['product_id', 'qty', 'harga', 'subtotal']
    
    public $availableProducts = [];
    public $selectedProductId = '';

    public function mount($id = null)
    {
        $this->availableProducts = Product::where('is_active', true)->get();
        $this->tanggal_do = date('Y-m-d');
        
        if ($id) {
            $do = DeliveryOrder::with('items')->findOrFail($id);
            if ($do->status_do !== 'draft') {
                return redirect()->route('do.index')->with('error', 'Hanya DO Draft yang bisa diedit.');
            }
            $this->doId = $do->id;
            $this->nomor_do = $do->nomor_do;
            $this->tanggal_do = $do->tanggal_do->format('Y-m-d');
            $this->customer_id = $do->customer_id;
            $this->alamat_kirim = $do->alamat_kirim;
            $this->keterangan = $do->keterangan;

            foreach ($do->items as $item) {
                $this->items[] = [
                    'product_id' => $item->product_id,
                    'nama_produk' => $item->product->nama_produk,
                    'qty' => $item->qty,
                    'harga' => $item->harga,
                    'subtotal' => $item->subtotal
                ];
            }
        }
    }

    public function updatedCustomerId($val)
    {
        if ($val) {
            $customer = Customer::find($val);
            if ($customer) {
                $this->alamat_kirim = $customer->alamat;
                
                // Recalculate prices if items exist
                foreach ($this->items as $index => $item) {
                    $product = Product::find($item['product_id']);
                    $price = $customer->getPriceForProduct($product->id, $product->harga_default);
                    $this->items[$index]['harga'] = $price;
                    $this->items[$index]['subtotal'] = $price * $item['qty'];
                }
            }
        } else {
            $this->alamat_kirim = '';
        }
    }

    public function addProduct()
    {
        if (!$this->selectedProductId || !$this->customer_id) {
            session()->flash('error_item', 'Pilih pelanggan dan produk terlebih dahulu.');
            return;
        }

        $product = Product::find($this->selectedProductId);
        $customer = Customer::find($this->customer_id);

        $price = $customer->getPriceForProduct($product->id, $product->harga_default);

        $this->items[] = [
            'product_id' => $product->id,
            'nama_produk' => $product->nama_produk,
            'qty' => 1,
            'harga' => $price,
            'subtotal' => $price * 1
        ];

        $this->selectedProductId = '';
    }

    public function updateQty($index, $qty)
    {
        $qty = (int) $qty;
        if ($qty > 0) {
            $this->items[$index]['qty'] = $qty;
            $this->items[$index]['subtotal'] = $this->items[$index]['harga'] * $qty;
        }
    }

    public function removeItem($index)
    {
        unset($this->items[$index]);
        $this->items = array_values($this->items);
    }

    public function getGrandTotalProperty()
    {
        return collect($this->items)->sum('subtotal');
    }

    public function store()
    {
        $this->validate([
            'customer_id' => 'required',
            'tanggal_do' => 'required|date',
            'alamat_kirim' => 'required',
            'items' => 'required|array|min:1',
        ]);

        DB::beginTransaction();
        try {
            if (!$this->doId) {
                // Generate nomor_do
                $lastDo = DeliveryOrder::latest('id')->first();
                $nextId = $lastDo ? $lastDo->id + 1 : 1;
                $this->nomor_do = 'DO-' . date('Ym') . '-' . str_pad($nextId, 4, '0', STR_PAD_LEFT);
            }

            $do = DeliveryOrder::updateOrCreate(['id' => $this->doId], [
                'nomor_do' => $this->nomor_do,
                'tanggal_do' => $this->tanggal_do,
                'customer_id' => $this->customer_id,
                'alamat_kirim' => $this->alamat_kirim,
                'keterangan' => $this->keterangan,
                'status_do' => 'draft',
                'created_by' => auth()->id() ?? 1,
            ]);

            // Sync items
            DeliveryOrderItem::where('delivery_order_id', $do->id)->delete();
            
            foreach ($this->items as $item) {
                DeliveryOrderItem::create([
                    'delivery_order_id' => $do->id,
                    'product_id' => $item['product_id'],
                    'qty' => $item['qty'],
                    'harga' => $item['harga'],
                    'subtotal' => $item['subtotal'],
                ]);
            }

            DB::commit();
            return redirect()->route('do.index')->with('message', 'Delivery Order berhasil disimpan (Draft).');
        } catch (\Exception $e) {
            DB::rollBack();
            session()->flash('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $customers = Customer::where('is_active', true)->get();
        return view('livewire.transaction.do-form', ['customers' => $customers])->layout('layouts.app', ['header' => $this->doId ? 'Edit Delivery Order' : 'Buat Delivery Order']);
    }
}
