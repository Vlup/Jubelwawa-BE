<?php

namespace Database\Seeders;

use App\Models\Province;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProvinceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $provinces = [
            ['name' => 'Nanggroe Aceh Darussalam'], ['name' => 'Bali'], ['name' => 'Bangka Belitung'],
            ['name' => 'Banten'], ['name' => 'Bengkulu'], ['name' => 'D.I. Yogyakarta'],
            ['name' => 'Gorontalo'], ['name' => 'DKI Jakarta'], ['name' => 'Jambi'],
            ['name' => 'Jawa Barat'], ['name' => 'Jawa Tengah'], ['name' => 'Jawa Timur'],
            ['name' => 'Kalimantan Barat'], ['name' => 'Kalimantan Selatan'], ['name' => 'Kalimantan Tengah'],
            ['name' => 'Kalimantan Timur'], ['name' => 'Kalimantan Utara'], ['name' => 'Kepulauan Riau'],
            ['name' => 'Lampung'], ['name' => 'Maluku Utara'], ['name' => 'Maluku'],
            ['name' => 'Nusa Tenggara Barat'], ['name' => 'Nusa Tenggara Timur'],
            ['name' => 'Papua'], ['name' => 'Riau'], ['name' => 'Sulawesi Selatan'],
            ['name' => 'Sulawesi Tengah'], ['name' => 'Sulawesi Tenggara'], ['name' => 'Sulawesi Utara'],
            ['name' => 'Sumatera Barat'], ['name' => 'Sumatera Selatan'], ['name' => 'Sumatera Utara'],
            ['name' => 'Sulawesi Barat'], ['name' => 'Papua Barat']
        ];

        foreach($provinces as $province) {
            Province::create($province);
        }
    }
}
