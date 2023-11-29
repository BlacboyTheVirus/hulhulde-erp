<?php

namespace Database\Seeders;

use App\Models\Customer;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class CustomerSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $user = Customer::create(
            [
                'id'            => 1,
                'count_id'      => 1 ,
                'code'          => 'CU-0001',
                'name'          => 'Walk-In Customer',
                'phone'         => null,
                'address'       => null,
                'invoice_due'   => 0.00,
                'user_id'       => 1,
            ]
        );


    }
}
