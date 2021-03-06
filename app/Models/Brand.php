<?php

namespace App\Models;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'content',
        'image',
    ];

    //  Связь «один ко многим» таблицы `brands` с таблицей `products`
    public function products() {
        return $this->hasMany(Product::class);
    }
    //список популярных брендов
    public static function popular() {
        return self::withCount('products')->orderByDesc('products_count')->limit(5)->get();
    }
}
