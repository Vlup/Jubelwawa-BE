<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = ['Residence', 'Place of Business', 'Building', 'Kavling'];
        $subCategories = [
            ['House', 'Boarding House', 'Villa', 'Condominium', 'Townhouse'],
            ['Shophouse', 'Factory', 'Office', 'Business Space', 'Retail Store', 'Restaurant', 'Hotel'],
            ['Warehouse', 'Apartment'],
            ['Residential Land', 'Commercial Land', 'Industrial Land', 'Agricultural Land', 'Recreational Land', 'Vacant Land']
        ];

        for ($i=0; $i<4; $i++) {
            $category = Category::create(['name' => $categories[$i]]);
            foreach ($subCategories[$i] as $subCategory) {
                SubCategory::create(['name' => $subCategory, 'category_id' => $category->id]);
            }
        }
    }
}
