<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        //php artisan db:seed  
        $this->call([
            ProvinceSeeder::class,
            CitySeeder::class,
            SubDistrictSeeder::class
        ]);
    }
}
