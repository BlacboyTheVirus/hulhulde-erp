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
                'wallet'        => 0.00,
                'user_id'       => 1,
            ]
        );

        $user = Customer::create(
            [
                'id'            => 2,
                'count_id'      => 2 ,
                'code'          => 'CU-0002',
                'name'          => 'Yusuf Maikudi',
                'phone'         => '08034536746',
                'address'       => 'Sabongari, Zaria',
                'invoice_due'   => 0.00,
                'wallet'        => 200000,
                'user_id'       => 1,
            ]
        );

        $user = Customer::create(
            [
                'id'            => 3,
                'count_id'      => 3 ,
                'code'          => 'CU-0003',
                'name'          => 'John Musa',
                'phone'         => '08059835243',
                'address'       => 'Maitama, Abuja',
                'invoice_due'   => 0.00,
                'wallet'        => 0.00,
                'user_id'       => 1,
            ]
        );


    }
}
