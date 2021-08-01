<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    // public function getProducts() {
    //     return Product::where('category_id', $this->id)->get();
    // }

    // Связь «один ко многим» таб. `categories` с таб. `products`
    public function products() {
        return $this->hasMany(Product::class);
    }

    //Связь «один ко многим» таб. `categories` с таб. `categories`
   public function children() {
       return $this->hasMany(Category::class, 'parent_id');
   }

    // список корневых категорий
    public static function roots() {
        return self::where('parent_id', 0)->with('children')->get();
    }
}
