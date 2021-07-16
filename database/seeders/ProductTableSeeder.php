<?php

namespace Database\Seeders;
use App\Models\Product;

use Illuminate\Database\Seeder;

class ProductTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // создать 10 товаров
        //factory(App\Product::class, 10)->create();
        Product::factory()->count(10)->create();
    }
}
