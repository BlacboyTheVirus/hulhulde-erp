<?php

namespace Database\Seeders;

use App\Models\Product;
use App\Models\Supplier;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run(): void
    {
        $user = Product::create(
            [
                'name'      => "Head Rice (50kg)" ,
                'bags'      => 10,
                'weight'    => 0.5,
                'bag_weight'=> 0.05,
                'price'=> 50000.00,
                'status'    => 1,
                'user_id'   => 1,
            ]);

        $user = Product::create(
            [
                'name'      => "Head Rice (25kg)" ,
                'bags'      => 10,
                'weight'    => 0.25,
                'bag_weight'=> 0.025,
                'price'=> 25000.00,
                'status'    => 1,
                'user_id'   => 1,
            ]);

        $user = Product::create(
            [
                'name'      => "Broken (50kg)" ,
                'bags'      => 10,
                'weight'    => 0.5,
                'bag_weight'=> 0.05,
                'price'=> 40000.00,
                'status'    => 1,
                'user_id'   => 1,
            ]);


        $user = Product::create(
            [
                'name'      => "Light Reject (50kg)" ,
                'bags'      => 10,
                'weight'    => 0.5,
                'bag_weight'=> 0.05,
                'price'=> 35000.00,
                'status'    => 1,
                'user_id'   => 1,
            ]);


        $user = Product::create(
            [
                'name'      => "Dark Reject (50kg)" ,
                'bags'      => 10,
                'weight'    => 0.5,
                'bag_weight'=> 0.05,
                'price'=> 32000.00,
                'status'    => 1,
                'user_id'   => 1,
            ]);

    }
}
