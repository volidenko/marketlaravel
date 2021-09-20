<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
//use App\Http\Controllers\IndexController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\UserController;
// use App\Http\Controllers\Admin\CategoryController;
// use App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


//use App\Http\Controllers\Auth;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', 'IndexController')->name('index');
Route::get('/', IndexController::class)->name('index');

Route::group([
    'as' => 'catalog.', // имя маршрута, например catalog.index
    'prefix' => 'catalog', // префикс маршрута, например catalog/index
], function () {
    Route::get('index', [CatalogController::class, 'index'])->name('index');
    Route::get('category/{slug}', [CatalogController::class, 'category'])->name('category');
    Route::get('brand/{slug}', [CatalogController::class, 'brand'])->name('brand');
    Route::get('product/{slug}', [CatalogController::class, 'product'])->name('product');
});

Route::group([
    'as' => 'basket.', // имя маршрута, например basket.index
    'prefix' => 'basket', // префикс маршрута, например bsaket/index
], function () {
    Route::get('index', [BasketController::class, 'index'])->name('index'); // список всех товаров в корзине
    Route::get('checkout', [BasketController::class, 'checkout'])->name('checkout'); // страница с формой оформления заказа
    Route::post('saveorder', [BasketController::class, 'saveOrder'])->name('saveorder'); // отправка данных формы для сохранения заказа в БД
    Route::get('success', [BasketController::class, 'success'])->name('success'); // страница после успешного сохранения заказа в БД
    Route::post('add/{id}', [BasketController::class, 'add'])->where('id', '[0-9]+')->name('add'); // добавления товара в корзину
    Route::post('plus/{id}', [BasketController::class, 'plus'])->where('id', '[0-9]+')->name('plus'); // изменения кол-ва отдельного товара в корзине
    Route::post('minus/{id}', [BasketController::class, 'minus'])->where('id', '[0-9]+')->name('minus'); // изменения кол-ва отдельного товара в корзине
    Route::post('remove/{id}', [BasketController::class, 'remove'])->where('id', '[0-9]+')->name('remove'); // удаления отдельного товара из корзины
    Route::post('clear', [BasketController::class, 'clear'])->name('clear'); // удаления всех товаров из корзины
});

// Регистрация, вход в ЛК, восстановление пароля
Route::name('user.')->prefix('user')->group(function () {
    Auth::routes();
});

// кабинет зарегистрированного пользователя
Route::group([
    'as' => 'user.', // имя маршрута, например user.index
    'prefix' => 'user', // префикс маршрута, например user/index
    'middleware' => ['auth'] // один или несколько посредников
], function () {
    Route::get('index', [UserController::class, 'index'])->name('index'); // главная страница личного кабинета пользователя
    // Route::resource('profile', 'ProfileController'); // CRUD-операции над профилями пользователя
    // Route::get('order', [OrderController::class, 'index'])->name('order.index'); // просмотр списка заказов в личном кабинете
    // Route::get('order/{order}', [OrderController::class, 'show'])->name('order.show'); // просмотр отдельного заказа в личном кабинете
});

// Панель управления магазином для администратора сайта
Route::group([
    'as' => 'admin.', // имя маршрута, например admin.index
    'prefix' => 'admin', // префикс маршрута, например admin/index
    'namespace' => 'Admin', // пространство имен контроллера
    'middleware' => ['auth', 'admin'] // один или несколько посредников
], function () {
    Route::get('index', 'AdminController')->name('index'); // главная страница панели управления
    Route::resource('category', 'CategoryController'); // CRUD-операции над категориями каталога
    // Route::resource('brand', 'BrandController'); // CRUD-операции над брендами каталога
    // Route::resource('product', 'ProductController'); // CRUD-операции над товарами каталога
    // Route::get('product/category/{category}', 'ProductController@category')->name('product.category'); // доп.маршрут для показа товаров категории
    // Route::resource('order', 'OrderController', ['except' => ['create', 'store', 'destroy']]); // просмотр и редактирование заказов
    // Route::resource('user', 'UserController', ['except' => ['create', 'store', 'show', 'destroy' ]]); // просмотр и редактирование пользователей
    // Route::resource('page', 'PageController');   // CRUD-операции над страницами сайта
    // Route::post('page/upload/image', 'PageController@uploadImage')->name('page.upload.image'); // загрузка изображения из wysiwyg-редактора
    // Route::delete('page/remove/image', 'PageController@removeImage')->name('page.remove.image'); // удаление изображения в wysiwyg-редакторе
});


// Route::namespace('App\Http\Controllers\Admin')->name('admin.')->prefix('admin')->middleware('auth','admin')->group(function () {
//     // Route::get('index', [AdminController::class])->name('index');
//     //Route::get('index', [AdminController::class,'index'])->name('index');
//     Route::get('index', [AdminController::class,'__invoke'])->name('index');
//     //Route::get('category', [CategoryController::class,'index'])->name('index');
//     Route::resource('category', CategoryController::class);
//     // Route::resource('category', CategoryController::class);
// });

// Route::group([
//     'as' => 'admin.', // имя маршрута, например admin.index
//     'prefix' => 'admin', // префикс маршрута, например admin/index
//     'namespace' => 'Admin', // пространство имен контроллера
//     'middleware' => ['auth', 'admin'] // один или несколько посредников
// ], function () {
//     // главная страница панели управления
//     Route::get('index', [AdminController::class,'index'])->name('index');
//     // Route::get('/admin/category/{slug}', [CategoryController::class,'category'])->name('admin.category');
//     // CRUD-операции над категориями каталога
//     // Route::resource('category', CategoryController::class);
// });



//Route::resource('admin/category', CategoryController::class);





// --------------------------------------------------------------------------------------------------------------------


// Route::group([
//     'as' => 'catalog.', // имя маршрута, например catalog.index
//     'prefix' => 'catalog', // префикс маршрута, например catalog/index
// ], function () {
//     Route::get('index', 'CatalogController@index');
//     Route::get('index', 'CatalogController@index')->name('index');
//     Route::get('category/{slug}', 'CatalogController@category')->name('category');
//     Route::get('brand/{slug}', 'CatalogController@brand')->name('brand');
//     Route::get('product/{slug}', 'CatalogController@product')->name('product');
// });

// Route::get('catalog/index', [CatalogController::class,'index']);
// Route::get('catalog/index', [CatalogController::class,'index'])->name('catalog.index');
// Route::get('/catalog/category/{slug}', [CatalogController::class,'category'])->name('catalog.category');
// Route::get('/catalog/brand/{slug}', [CatalogController::class,'brand'])->name('catalog.brand');
// Route::get('/catalog/product/{slug}', [CatalogController::class,'product'])->name('catalog.product');

// Route::get('/basket/index', [BasketController::class, 'index'])->name('basket.index');
// Route::get('/basket/checkout', [BasketController::class, 'checkout'])->name('basket.checkout');
// Route::post('/basket/saveorder', [BasketController::class, 'saveOrder'])->name('basket.saveorder');
// Route::get('/basket/success', [BasketController::class, 'success'])->name('basket.success');
// Route::post('/basket/add/{id}', [BasketController::class, 'add'])->where('id', '[0-9]+')->name('basket.add');
// Route::post('/basket/plus/{id}', [BasketController::class, 'plus'])->where('id', '[0-9]+')->name('basket.plus');
// Route::post('/basket/minus/{id}', [BasketController::class, 'minus'])->where('id', '[0-9]+')->name('basket.minus');
// Route::post('/basket/remove/{id}', [BasketController::class, 'remove'])->where('id', '[0-9]+')->name('basket.remove');
// Route::post('/basket/clear', [BasketController::class, 'clear'])->name('basket.clear');




// Route::get('login', [LoginController::class, 'showLoginForm'])->name('login');
// Route::post('login', [LoginController::class, 'login']);
// Route::post('logout', [LoginController::class, 'logout'])->name('logout'); // Registration Routes...
// Route::get('register', [RegisterController::class, 'showRegistrationForm'])->name('register');
// Route::post('register', [RegisterController::class, 'register']); // Password Reset Routes...
// Route::get('password/reset', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
// Route::post('password/email', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
// Route::get('password/reset/{​​​token}​​​', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
// Route::post('password/reset', [ResetPasswordController::class, 'reset']);
