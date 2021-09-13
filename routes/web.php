<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\BasketController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AdminController;

use App\Http\Controllers\Auth\LoginController;
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

// Route::get('/', [IndexController::class])->name('index');

Route::get('/', function () {
    return view('index');
});


Route::get('catalog/index', [CatalogController::class,'index']);

Route::get('/catalog/index', [CatalogController::class,'index'])->name('catalog.index');

Route::get('/catalog/category/{slug}', [CatalogController::class,'category'])->name('catalog.category');
Route::get('/catalog/brand/{slug}', [CatalogController::class,'brand'])->name('catalog.brand');
Route::get('/catalog/product/{slug}', [CatalogController::class,'product'])->name('catalog.product');


Route::get('/basket/index', [BasketController::class,'index'])->name('basket.index');
Route::get('/basket/checkout', [BasketController::class,'checkout'])->name('basket.checkout');
Route::post('/basket/saveorder', [BasketController::class, 'saveOrder'])->name('basket.saveorder');

Route::get('/basket/success', [BasketController::class,'success'])
    ->name('basket.success');

Route::post('/basket/add/{id}', [BasketController::class,'add'])
    ->where('id', '[0-9]+')
    ->name('basket.add');

Route::post('/basket/plus/{id}', [BasketController::class,'plus'])
    ->where('id', '[0-9]+')
    ->name('basket.plus');
Route::post('/basket/minus/{id}', [BasketController::class,'minus'])
    ->where('id', '[0-9]+')
    ->name('basket.minus');

Route::post('/basket/remove/{id}', [BasketController::class,'remove'])
    ->where('id', '[0-9]+')
    ->name('basket.remove');
Route::post('/basket/clear', [BasketController::class,'clear'])->name('basket.clear');

//Auth::routes();

Route::name('user.')->prefix('user')->group(function () {
    Route::get('index', [UserController::class,'index'])->name('index');
    // Auth::routes();

    $this->get('login','Auth\LoginController@showLoginForm')->name('login');
    $this->post('login','Auth\LoginController@login');
    $this->post('logout','Auth\LoginController@logout')->name('logout');// Registration Routes...
    $this->get('register','Auth\RegisterController@showRegistrationForm')->name('register');
    $this->post('register','Auth\RegisterController@register');// Password Reset Routes...
    $this->get('password/reset','Auth\ForgotPasswordController@showLinkRequestForm')->name('password.request');
    $this->post('password/email','Auth\ForgotPasswordController@sendResetLinkEmail')->name('password.email');
    $this->get('password/reset/{​​​token}​​​','Auth\ResetPasswordController@showResetForm')->name('password.reset');
    $this->post('password/reset','Auth\ResetPasswordController@reset');


});

Route::namespace('Admin')->name('admin.')->prefix('admin')->middleware('auth','admin')->group(function () {
    Route::get('index', [AdminController::class,'index'])->name('index');
    Route::resource('category', CategoryController::class);
});


// Route::group([
//     'as' => 'admin.', // имя маршрута, например admin.index
//     'prefix' => 'admin', // префикс маршрута, например admin/index
//     'namespace' => 'Admin', // пространство имен контроллера
//     'middleware' => ['auth', 'admin'] // один или несколько посредников
// ], function () {
//     // главная страница панели управления
//     Route::get('index', [IndexController::class])->name('index');
//     Route::get('/admin/category/{slug}', [CategoryController::class,'category'])->name('admin.category');
//     // CRUD-операции над категориями каталога
//     // Route::resource('category', CategoryController::class);
// });
