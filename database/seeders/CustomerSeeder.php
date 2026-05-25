<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Customer;
use Faker\Factory as Faker;

class CustomerSeeder extends Seeder
{
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        $categories = ['high', 'middle', 'low'];

        for ($i = 1; $i <= 10; $i++) {
            Customer::create([
                'kode_pelanggan' => 'CUST-' . str_pad($i, 3, '0', STR_PAD_LEFT),
                'nama_pelanggan' => 'Pelanggan ' . $i,
                'nama_toko' => 'Toko ' . $i,
                'alamat' => 'Jalan Pelanggan No ' . $i,
                'kota' => 'Kota ' . $i,
                'no_hp' => '0812' . rand(10000000, 99999999),
                'email' => 'customer' . $i . '@email.com',
                'kategori_harga' => $categories[array_rand($categories)],
                'is_active' => true,
            ]);
        }
    }
}
