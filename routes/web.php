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
Route::post('/order/store', 'OrderHistoryController@store')->name('orderStore');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', 'Auth\LoginController@create')->name('loginHome');
Route::put('/login/create', 'Auth\LoginController@store')->name('loginCreate');
Route::get('/register', 'Auth\RegisterController@createUser')->name('registerHome');
Route::post('/register/create', 'Auth\RegisterController@storeUser')->name('registerCreate');
Route::get('/logout', 'Auth\LoginController@delete')->name('logout');
