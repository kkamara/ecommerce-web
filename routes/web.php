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
Route::get('/order/invoice/{refNum}', 'OrderHistoryController@show')->name('orderShow');
Route::get('/order/invoice', 'OrderHistoryController@index')->name('orderHome');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', 'Auth\LoginController@create')->name('login');
Route::put('/login/create', 'Auth\LoginController@store')->name('loginCreate');
Route::get('/register', 'Auth\RegisterController@createUser')->name('registerHome');
Route::post('/register/create', 'Auth\RegisterController@storeUser')->name('registerCreate');
Route::get('/logout', 'Auth\LoginController@delete')->name('logout');

/*
|--------------------------------------------------------------------------
| Billing Card Routes
|--------------------------------------------------------------------------
*/
Route::get('/billing', 'UserPaymentConfigController@index')->name('billingHome');
Route::get('/billing/{userPaymentConfig}/edit', 'UserPaymentConfigController@edit')->name('billingEdit');
Route::put('/billing/{userPaymentConfig}/update', 'UserPaymentConfigController@update')->name('billingUpdate');
Route::get('/billing/{userPaymentConfig}/delete', 'UserPaymentConfigController@delete')->name('billingDelete');
Route::delete('/billing/{userPaymentConfig}/destroy', 'UserPaymentConfigController@destroy')->name('billingDestroy');

/*
|--------------------------------------------------------------------------
| Users Address Routes
|--------------------------------------------------------------------------
*/
Route::get('/address', 'UsersAddressController@index')->name('addressHome');
Route::get('/address/{userPaymentConfig}/edit', 'UsersAddressController@edit')->name('addressEdit');
Route::put('/address/{userPaymentConfig}/update', 'UsersAddressController@update')->name('addressUpdate');
Route::get('/address/{userPaymentConfig}/delete', 'UsersAddressController@delete')->name('addressDelete');
Route::delete('/address/{userPaymentConfig}/destroy', 'UsersAddressController@destroy')->name('addressDestroy');
