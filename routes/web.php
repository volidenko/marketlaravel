<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CatalogController;
use App\Http\Controllers\IndexController;
use App\Http\Controllers\BasketController;
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

Route::get('/', function () {
    return view('welcome');
});

Route::get('index', [IndexController::class], 'index');

Route::get('catalog/index', [CatalogController::class,'index']);

Route::get('/catalog/index', [CatalogController::class,'index'])->name('catalog.index');

Route::get('/catalog/category/{slug}', [CatalogController::class,'category'])->name('catalog.category');
Route::get('/catalog/brand/{slug}', [CatalogController::class,'brand'])->name('catalog.brand');
Route::get('/catalog/product/{slug}', [CatalogController::class,'product'])->name('catalog.product');


Route::get('/basket/index', [BasketController::class,'index'])->name('basket.index');
Route::get('/basket/checkout', [BasketController::class,'checkout'])->name('basket.checkout');

// Route::resource('basket','BasketController');

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
