<?php

namespace Database\Factories;

use App\Models\Brand;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

use Faker\Generator as Faker;

// $factory->define(Brand::class, function (Faker $faker) {
//     $name = $faker->realText(rand(20, 30));
//     return [
//         'name' => $name,
//         'content' => $faker->realText(rand(150, 200)),
//     ];
// });

class BrandFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Brand::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        $name = $this->faker->realText(rand(20, 30));
        return [
            'name' =>$name,
            'content' => $this->faker->realText(rand(150, 200)),
            'slug' => Str::slug($name),
        ];
    }
}
