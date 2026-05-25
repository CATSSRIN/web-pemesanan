<?php

namespace App\Livewire\Master;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\DeliveryOrderItem;
use App\Models\InvoiceItem;
use App\Models\Product;

class ProductList extends Component
{
    use WithPagination;

    public $search = '';
    public $isOpen = false;
    public $productId;
    public $kode_produk, $nama_produk, $kategori, $satuan, $harga_default, $harga_high, $harga_middle, $harga_low, $stok_awal, $deskripsi;
    public $is_active = true;

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
        $this->reset(['productId', 'kode_produk', 'nama_produk', 'kategori', 'satuan', 'harga_default', 'harga_high', 'harga_middle', 'harga_low', 'stok_awal', 'deskripsi', 'is_active']);
    }

    public function create()
    {
        $this->reset(['productId', 'kode_produk', 'nama_produk', 'kategori', 'satuan', 'harga_default', 'harga_high', 'harga_middle', 'harga_low', 'stok_awal', 'deskripsi', 'is_active']);
        $this->satuan = 'pcs';
        $this->openModal();
    }

    public function updatedNamaProduk($value)
    {
        if ($this->productId || !empty($this->kode_produk) || empty($value)) {
            return;
        }

        $prefix = strtoupper(substr(preg_replace('/[^A-Za-z]/', '', $value), 0, 3));
        $prefix = $prefix ?: 'PRD';
        $nextId = (Product::max('id') ?? 0) + 1;
        $this->kode_produk = $prefix . '-' . str_pad((string) $nextId, 3, '0', STR_PAD_LEFT);
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);
        $this->productId = $id;
        $this->kode_produk = $product->kode_produk;
        $this->nama_produk = $product->nama_produk;
        $this->kategori = $product->kategori;
        $this->satuan = $product->satuan;
        $this->harga_default = $product->harga_default;
        $this->harga_high = $product->harga_high;
        $this->harga_middle = $product->harga_middle;
        $this->harga_low = $product->harga_low;
        $this->stok_awal = $product->stok_awal;
        $this->deskripsi = $product->deskripsi;
        $this->is_active = $product->is_active;
        $this->openModal();
    }

    public function store()
    {
        $this->validate([
            'kode_produk' => 'required|unique:products,kode_produk,' . $this->productId,
            'nama_produk' => 'required',
            'harga_default' => 'required|numeric|min:0',
        ]);

        Product::updateOrCreate(['id' => $this->productId], [
            'kode_produk' => $this->kode_produk,
            'nama_produk' => $this->nama_produk,
            'kategori' => $this->kategori,
            'satuan' => $this->satuan ?? 'pcs',
            'harga_default' => $this->harga_default,
            'harga_high' => $this->harga_high ?? 0,
            'harga_middle' => $this->harga_middle ?? 0,
            'harga_low' => $this->harga_low ?? 0,
            'stok_awal' => $this->stok_awal ?? 0,
            'deskripsi' => $this->deskripsi,
            'is_active' => $this->is_active,
        ]);

        session()->flash('message', $this->productId ? 'Data Produk diperbarui.' : 'Data Produk ditambahkan.');
        $this->closeModal();
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);

        if (DeliveryOrderItem::where('product_id', $id)->exists() || InvoiceItem::where('product_id', $id)->exists()) {
            session()->flash('message', 'Produk tidak bisa dihapus karena sudah digunakan pada transaksi.');
            return;
        }

        $product->delete();
        session()->flash('message', 'Produk berhasil dihapus.');
    }

    public function render()
    {
        $products = Product::where('nama_produk', 'like', '%' . $this->search . '%')
            ->orWhere('kode_produk', 'like', '%' . $this->search . '%')
            ->latest()
            ->paginate(10);

        return view('livewire.master.product-list', ['products' => $products])->layout('layouts.app', ['header' => 'Data Produk']);
    }
}
