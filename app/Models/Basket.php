<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cookie;

class Basket extends Model
{
    //Связь «многие ко многим» таблицы `baskets` с таблицей `products`
    public function products() {
        return $this->belongsToMany(Product::class)->withPivot('quantity');
    }

    public function getAmount() {
        $amount = 0.0;
        foreach ($this->products as $product) {
            $amount = $amount + $product->price * $product->pivot->quantity;
        }
        return $amount;
    }

    //Увеличивает кол-во товара в корзине на величину $count
    public function increase($id, $count = 1) {
        $this->change($id, $count);
    }

    // Уменьшает кол-во товара в корзине на величину $count
    public function decrease($id, $count = 1) {
        $this->change($id, -1 * $count);
    }

    //  Изменяет количество товара в корзине на величину $count; если товара еще нет в корзине — добавляет этот товар; $count может быть как положительным, так и отрицательным числом
    private function change($id, $count = 0) {
        if ($count == 0) {
            return;
        }

        if ($this->products->contains($id)) {  // если товар есть в корзине — изменяем кол-во
            $pivotRow = $this->products()->where('product_id', $id)->first()->pivot; // получаем объект строки таблицы `basket_product`
            $quantity = $pivotRow->quantity + $count;
            if ($quantity > 0) {
                $pivotRow->update(['quantity' => $quantity]);// обновляем количество товара в корзине
            } else {
                $pivotRow->delete();// кол-во равно нулю — удаляем товар из корзины
            }
        } elseif ($count > 0) { // иначе — добавляем этот товар
            $this->products()->attach($id, ['quantity' => $count]);
        }
        $this->touch(); // обновляем поле `updated_at` таблицы `baskets`
    }

    // Удаляет товар с идентификатором из корзины покупателя
    public function remove($id) {
        $this->products()->detach($id); // удаляем товар из корзины
        $this->touch(); // обновляем поле `updated_at` таблицы `baskets`
    }

    // Возвращает объект корзины; если не найден — создает новый
    public static function getBasket() {
        $basket_id = request()->cookie('basket_id');
        if (!empty($basket_id)) {
            try {
                $basket = Basket::findOrFail($basket_id);
            } catch (ModelNotFoundException $e) {
                $basket = Basket::create();
            }
        } else {
            $basket = Basket::create();
        }
        Cookie::queue('basket_id', $basket->id, 525600);
        return $basket;
    }

    // Возвращает количество позиций в корзине
    public static function getCount() {
        $basket_id = request()->cookie('basket_id');
        if (empty($basket_id)) {
            return 0;
        }
        return self::getBasket()->products->count();
    }
}
