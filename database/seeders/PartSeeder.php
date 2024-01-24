<?php

namespace Database\Seeders;

use App\Models\Parts\Parts;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class PartSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $user = Parts::create(
            [
                'count_id'  => 1 ,
                'code'      => 'PX-0001',
                'name'      => 'Line Belts',
                'description'=> 'Belts for rice conveyor  machine',
                'unit'=> 'pieces',
                'quantity'=> 0.00,
                'restock_level'=> 5,
                'status'    => 1,
                'user_id'   => 1,
            ]);

        $user = Parts::create(
            [
                'count_id'  => 2 ,
                'code'      => 'PX-0002',
                'name'      => 'Sack Needles',
                'description'=> 'Needle for sack sewer',
                'unit'=> 'pieces',
                'quantity'=> 0.00,
                'restock_level'=> 20,
                'status'    => 1,
                'user_id'   => 1,
            ]);

    }
}
