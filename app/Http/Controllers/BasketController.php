<?php

namespace App\Http\Controllers;
use App\Models\Basket;
use App\Models\Order;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Facades\Cookie;

class BasketController extends Controller
{
    private $basket;

    // public function __construct() {
    //     $this->getBasket();
    // }

    public function __construct() {
        $this->basket = Basket::getBasket();
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

    // Удаляет товар с идентификаторм $id из корзины
    public function remove($id) {
        $this->basket->remove($id);
        return redirect()->route('basket.index');
    }

    // Полностью очищает содержимое корзины покупателя
    public function clear() {
        $this->basket->delete();
        return redirect()->route('basket.index');
    }

    // Сохранение заказа в БД
    public function saveOrder(Request $request) {
        // проверяем данные формы оформления
        $this->validate($request, [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255',
            'phone' => 'required|max:255',
            'address' => 'required|max:255',
        ]);

        // валидация пройдена, сохраняем заказ
        $basket = Basket::getBasket();
        $user_id = auth()->check() ? auth()->user()->id : null;
        $order = Order::create(
            $request->all() + ['amount' => $basket->getAmount(), 'user_id' => $user_id]
        );

        foreach ($basket->products as $product) {
            $order->items()->create([
                'product_id' => $product->id,
                'name' => $product->name,
                'price' => $product->price,
                'quantity' => $product->pivot->quantity,
                'cost' => $product->price * $product->pivot->quantity,
            ]);
        }
        // уничтожаем корзину
        $basket->delete();

        return redirect()
            ->route('basket.success')
            ->with('order_id', $order->id);
            // ->with('success', 'Ваш заказ успешно размещен');
    }

    // Сообщение об успешном оформлении заказа
    public function success(Request $request) {
        if ($request->session()->exists('order_id')) {
            // сюда покупатель попадает сразу после успешного оформления заказа
            $order_id = $request->session()->pull('order_id');
            $order = Order::findOrFail($order_id);
            return view('basket.success', compact('order'));
        } else {
            // если покупатель попал сюда случайно, не после оформления заказа — отправляем на страницу корзины
            return redirect()->route('basket.index');
        }
    }
}
