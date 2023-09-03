<?php

namespace Database\Seeders;

use App\Models\Input;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;


class InputSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = Input::create([
            'name' => 'Paddy',
            'quantity' => 0.00,
            'status' => 1,
            'user_id' => 1,
        ]);

    }
}
