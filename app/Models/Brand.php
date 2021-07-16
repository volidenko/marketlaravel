<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    // public function getProducts() {
    //     return Product::where('brand_id', $this->id)->get();
    // }

    //  Связь «один ко многим» таблицы `brands` с таблицей `products`

    public function products() {
        return $this->hasMany(Product::class);
    }
}
