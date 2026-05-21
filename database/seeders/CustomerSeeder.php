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

        for ($i = 1; $i <= 10; $i++) {
            Customer::create([
                'kode_pelanggan' => 'CUST-00' . $i,
                'nama_pelanggan' => $faker->name,
                'nama_toko' => 'Toko ' . $faker->company,
                'alamat' => $faker->address,
                'kota' => $faker->city,
                'no_hp' => $faker->phoneNumber,
                'email' => $faker->email,
                'is_active' => true,
            ]);
        }
    }
}
