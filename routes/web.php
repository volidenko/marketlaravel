<?php

use Illuminate\Support\Facades\Route;

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

//Route::get('/', 'IndexController')->name('index');
Route::resource('index','IndexController');

// Route::get('/catalog/index', 'CatalogController@index')->name('catalog.index');
// Route::get('/catalog/category/{slug}', 'CatalogController@category')->name('catalog.category');
// Route::get('/catalog/brand/{slug}', 'CatalogController@brand')->name('catalog.brand');
// Route::get('/catalog/product/{slug}', 'CatalogController@product')->name('catalog.product');
Route::resource('catalog','CatalogController');
//Route::resource('catalog', CatalogController::class);

// Route::get('/basket/index', 'BasketController@index')->name('basket.index');
// Route::get('/basket/checkout', 'BasketController@checkout')->name('basket.checkout');

Route::resource('basket','BasketController');

Route::post('/basket/add/{id}', 'BasketController@add')
    ->where('id', '[0-9]+')
    ->name('basket.add');

Route::post('/basket/plus/{id}', 'BasketController@plus')
    ->where('id', '[0-9]+')
    ->name('basket.plus');
Route::post('/basket/minus/{id}', 'BasketController@minus')
    ->where('id', '[0-9]+')
    ->name('basket.minus');

Route::post('/basket/remove/{id}', 'BasketController@remove')
    ->where('id', '[0-9]+')
    ->name('basket.remove');
Route::post('/basket/clear', 'BasketController@clear')->name('basket.clear');
