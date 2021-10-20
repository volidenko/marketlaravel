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

    /**
     * Форма оформления заказа
     *
     * @param Request $request
     * @return \Illuminate\Http\Response
     */
    public function checkout(Request $request) {
        $profile = null;
        $profiles = null;
        if (auth()->check()) {
            $user = auth()->user();
            $profiles = $user->profiles;
            $prof_id = (int)$request->input('profile_id');
            if ($prof_id) {
                $profile = $user->profiles()->whereIdAndUserId($prof_id, $user->id)->first();
            }
        }
        return view('basket.checkout', compact('profiles', 'profile'));
    }

     /**
     * Возвращает профиль пользователя в формате JSON
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function profile(Request $request)
    {
        if ( ! $request->ajax()) {
            abort(404);
        }
        if ( ! auth()->check()) {
            return response()->json(['error' => 'Нужна авторизация!'], 404);
        }
        $user = auth()->user();
        $profile_id = (int)$request->input('profile_id');
        if ($profile_id) {
            $profile = $user->profiles()->whereIdAndUserId($profile_id, $user->id)->first();
            if ($profile) {
                return response()->json(['profile' => $profile]);
            }
        }
        return response()->json(['error' => 'Профиль не найден!'], 404);
    }

    //Добавляет товар с идентификатором $id в корзину
    public function add(Request $request, $id) {
        $quantity = $request->input('quantity') ?? 1;
        $this->basket->increase($id, $quantity);
        if ( ! $request->ajax()) {
            return back();  // выполняем редирект обратно на ту страницу, где была нажата кнопка «В корзину»
        }
        // в случае ajax-запроса возвращаем html-код корзины в правом верхнем углу, чтобы заменить исходный html-код, потому что
        // теперь количество позиций будет другим
        $positions = $this->basket->products->count();
        return view('basket.part.basket', compact('positions'));
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

        $basket->delete(); // очищаем корзину
        return redirect()
            ->route('basket.success')
            ->with('order_id', $order->id);
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
