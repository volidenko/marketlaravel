<?php

namespace Database\Seeders;

use App\Models\Brand;
use Illuminate\Database\Seeder;
// use App\Brand;
class BrandTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // создать 5 брендов
        //factory(Brand::class, 5)->create();
        Brand::factory()->count(5)->create();
    }
}
