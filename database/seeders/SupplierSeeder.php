<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class SupplierSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $user = Supplier::create(
            [
                'count_id'  => 1,
                'code'      => 'SU-0001',
                'name'      => 'Aliyu Umar',
                'phone'     => '08031234567',
                'email'     => 'aliyu@paddyseller.com',
                'bank_name' => 'First Bank Plc',
                'bank_account'     => '3102323232',
                'advance'   =>  25000000,
                'note'     => 'Prime grower',
                'user_id'     => 1,
            ]);

        $user = Supplier::create(
            [
                'count_id'  => 2,
                'code'      => 'SU-0002',
                'name'      => 'Ishaya Barde',
                'phone'     => '08037654321',
                'email'     => 'ishbarde@gmail.com',
                'bank_name' => 'Guaranty Bank Plc',
                'bank_account'     => '0022232453',
                'advance'   =>  0,
                'note'     => 'Farmer',
                'user_id'     => 1,
            ]);


        $user = Supplier::create(
            [
                'count_id'  => 3,
                'code'      => 'SU-0003',
                'name'      => 'Mallam Maishinkafa',
                'phone'     => '080913245142',
                'email'     => '',
                'bank_name' => 'Polaris Bank',
                'bank_account'     => '1023432109',
                'advance'   =>  0,
                'note'     => 'Premium Grower',
                'user_id'     => 1,
            ]);
    }
}
