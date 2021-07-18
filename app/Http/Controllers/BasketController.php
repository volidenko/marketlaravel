<?php

namespace App\Http\Controllers;
use App\Models\Basket;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cookie;

class BasketController extends Controller
{
    private $basket;

    public function __construct() {
        $this->getBasket();
    }

    public function index() {
        $products = $this->basket->products;
        return view('basket.index', compact('products'));
    }

    // Форма оформления заказа
    public function checkout() {
        return view('basket.checkout');
    }

    //Добавляет товар с идентификатором $id в корзину
    public function add(Request $request, $id) {
        $quantity = $request->input('quantity') ?? 1;
        $this->basket->increase($id, $quantity);
        return back();  // выполняем редирект обратно на ту страницу, где была нажата кнопка «В корзину»
    }

    // Увеличивает кол-во товара в корзине на единицу
    public function plus($id) {
        $this->basket->increase($id);
        return redirect()->route('basket.index'); // выполняем редирект обратно на страницу корзины
    }

    // Уменьшает кол-во товара в корзине на единицу
    public function minus($id) {
        $this->basket->decrease($id);
        return redirect()->route('basket.index');
    }

    // Возвращает объект корзины; если не найден — создает новый
    private function getBasket() {
        $basket_id = request()->cookie('basket_id');
        if (!empty($basket_id)) {
            try {
                $this->basket = Basket::findOrFail($basket_id);
            } catch (ModelNotFoundException $e) {
                $this->basket = Basket::create();
            }
        } else {
            $this->basket = Basket::create();
        }
        Cookie::queue('basket_id', $this->basket->id, 525600);
    }

    // Удаляет товар с идентификаторм $id из корзины
    public function remove($id) {
        $this->basket->remove($id);
        return redirect()->route('basket.index'); // выполняем редирект обратно на страницу корзины
    }

    // Полностью очищает содержимое корзины покупателя
    public function clear() {
        $this->basket->delete();
        // выполняем редирект обратно на страницу корзины
        return redirect()->route('basket.index');
    }
}
