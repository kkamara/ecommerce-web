<?php

/*
|--------------------------------------------------------------------------
| Home Routes
|--------------------------------------------------------------------------
*/
Route::get('/', 'HomeController@index')->name('home');

/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/
Route::get('/products/{product}', 'ProductController@show')->name('productShow');
Route::get('/products/{product}/add', 'ProductController@create')->name('productAdd');

/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
*/
Route::get('/cart', 'CartController@show')->name('cartShow');
Route::put('/cart/update', 'CartController@update')->name('cartUpdate');

/*
|--------------------------------------------------------------------------
| Order History Routes
|--------------------------------------------------------------------------
*/
Route::get('/order/create', 'OrderHistoryController@create')->name('orderCreate');
