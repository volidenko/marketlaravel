<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(CategoryTableSeeder::class);
        $this->command->info('Таблица категорий: данные загружены!');

        $this->call(BrandTableSeeder::class);
        $this->command->info('Таблица брендов: данные загружены!');

        $this->call(ProductTableSeeder::class);
        $this->command->info('Таблица товаров: данные загружены!');
    }
}
