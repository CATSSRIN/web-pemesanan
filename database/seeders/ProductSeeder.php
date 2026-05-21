<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;

class ProductSeeder extends Seeder
{
    public function run(): void
    {
        $products = [
            ['kode_produk' => 'MK-001', 'nama_produk' => 'Minyak Kemiri Original 100ml', 'kategori' => 'Minyak Kemiri', 'harga_default' => 50000, 'stok_awal' => 100],
            ['kode_produk' => 'MK-002', 'nama_produk' => 'Minyak Kemiri Bakar 100ml', 'kategori' => 'Minyak Kemiri', 'harga_default' => 55000, 'stok_awal' => 100],
            ['kode_produk' => 'PM-001', 'nama_produk' => 'Pomade Water Based (Strong Hold)', 'kategori' => 'Pomade', 'harga_default' => 85000, 'stok_awal' => 50],
            ['kode_produk' => 'PM-002', 'nama_produk' => 'Pomade Clay (Matte Finish)', 'kategori' => 'Pomade', 'harga_default' => 90000, 'stok_awal' => 50],
            ['kode_produk' => 'PM-003', 'nama_produk' => 'Pomade Oil Based (Medium Hold)', 'kategori' => 'Pomade', 'harga_default' => 75000, 'stok_awal' => 60],
        ];

        for ($i = 4; $i <= 13; $i++) {
            $products[] = [
                'kode_produk' => 'PRD-0' . str_pad($i, 2, '0', STR_PAD_LEFT),
                'nama_produk' => 'Produk Varian ' . $i,
                'kategori' => 'Lainnya',
                'harga_default' => 45000 + ($i * 1000),
                'stok_awal' => 50,
            ];
        }

        foreach ($products as $p) {
            Product::create($p);
        }
    }
}
