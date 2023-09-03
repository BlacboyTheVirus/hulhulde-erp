<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Database\Seeders\CreateSuperUserSeeder;
use Database\Seeders\InputSeeder;
use Database\Seeders\SupplierSeeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
       $this->call([CreateSuperUserSeeder::class]);
       $this->call([InputSeeder::class]);
       $this->call([SupplierSeeder::class]);

    }
}
