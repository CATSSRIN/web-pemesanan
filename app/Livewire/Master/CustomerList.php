<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Customer;

class CustomerList extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    public $isPriceModalOpen = false;
    public $customerId;
    public $kode_pelanggan, $nama_pelanggan, $nama_toko, $alamat, $kota, $no_hp, $email, $catatan;
    public $is_active = true;

    public $pricingCustomerId;
    public $customerNameForPricing;
    public $customerPrices = []; // [product_id => custom_price]

    public function updatingSearch()
    {
        $this->resetPage();
    }

    public function openModal()
    {
        $this->resetValidation();
        $this->isOpen = true;
    }

    public function closeModal()
    {
        $this->isOpen = false;
        $this->reset(['customerId', 'kode_pelanggan', 'nama_pelanggan', 'nama_toko', 'alamat', 'kota', 'no_hp', 'email', 'catatan', 'is_active']);
    }

    public function create()
    {
        $this->reset(['customerId', 'kode_pelanggan', 'nama_pelanggan', 'nama_toko', 'alamat', 'kota', 'no_hp', 'email', 'catatan', 'is_active']);
        $this->openModal();
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail($id);
        $this->customerId = $id;
        $this->kode_pelanggan = $customer->kode_pelanggan;
        $this->nama_pelanggan = $customer->nama_pelanggan;
        $this->nama_toko = $customer->nama_toko;
        $this->alamat = $customer->alamat;
        $this->kota = $customer->kota;
        $this->no_hp = $customer->no_hp;
        $this->email = $customer->email;
        $this->is_active = $customer->is_active;
        $this->catatan = $customer->catatan;
        $this->openModal();
    }

    public function store()
    {
        $this->validate([
            'kode_pelanggan' => 'required|unique:customers,kode_pelanggan,' . $this->customerId,
            'nama_pelanggan' => 'required',
            'alamat' => 'required',
            'kota' => 'required',
            'no_hp' => 'required',
        ]);

        Customer::updateOrCreate(['id' => $this->customerId], [
            'kode_pelanggan' => $this->kode_pelanggan,
            'nama_pelanggan' => $this->nama_pelanggan,
            'nama_toko' => $this->nama_toko,
            'alamat' => $this->alamat,
            'kota' => $this->kota,
            'no_hp' => $this->no_hp,
            'email' => $this->email,
            'is_active' => $this->is_active,
            'catatan' => $this->catatan,
        ]);

        session()->flash('message', $this->customerId ? 'Data Pelanggan diperbarui.' : 'Data Pelanggan ditambahkan.');
        $this->closeModal();
    }

    public function openPriceModal($customerId)
    {
        $customer = Customer::findOrFail($customerId);
        $this->pricingCustomerId = $customer->id;
        $this->customerNameForPricing = $customer->nama_pelanggan;
        
        $prices = \App\Models\CustomerProductPrice::where('customer_id', $customerId)->get()->keyBy('product_id');
        $products = \App\Models\Product::where('is_active', true)->get();
        
        $this->customerPrices = [];
        foreach ($products as $p) {
            $this->customerPrices[$p->id] = $prices->has($p->id) ? $prices[$p->id]->custom_price : $p->harga_default;
        }
        
        $this->isPriceModalOpen = true;
    }

    public function closePriceModal()
    {
        $this->isPriceModalOpen = false;
        $this->reset(['pricingCustomerId', 'customerNameForPricing', 'customerPrices']);
    }

    public function savePrices()
    {
        foreach ($this->customerPrices as $productId => $price) {
            \App\Models\CustomerProductPrice::updateOrCreate(
                ['customer_id' => $this->pricingCustomerId, 'product_id' => $productId],
                ['custom_price' => $price, 'created_by' => auth()->id() ?? 1]
            );
        }
        session()->flash('message', 'Harga khusus pelanggan berhasil disimpan.');
        $this->closePriceModal();
    }

    public function render()
    {
        $customers = Customer::where('nama_pelanggan', 'like', '%' . $this->search . '%')
            ->orWhere('kode_pelanggan', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.master.customer-list', ['customers' => $customers])->layout('layouts.app', ['header' => 'Data Pelanggan']);
    }
}
