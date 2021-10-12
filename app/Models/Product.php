<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'brand_id',
        'name',
        'slug',
        'content',
        'image',
        'price',
        'new',
        'hit',
        'sale',
    ];

    // Связь «товар принадлежит» таблицы `products` с таблицей `categories`
   public function category() {
       return $this->belongsTo(Category::class);
   }

    // Связь «товар принадлежит» таблицы `products` с таблицей `brands`
   public function brand() {
       return $this->belongsTo(Brand::class);
   }

   // Связь «многие ко многим» таблицы `products` с таблицей `baskets`

    public function baskets() {
        return $this->belongsToMany(Basket::class)->withPivot('quantity');
    }
}
