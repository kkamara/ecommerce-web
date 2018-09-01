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
Route::get('/products', 'ProductController@index')->name('productHome');
Route::get('/products/{product}', 'ProductController@show')->name('productShow');
Route::get('/products/{product}/add', 'ProductController@create')->name('productAdd');


/*
|--------------------------------------------------------------------------
| Product Review Routes
|--------------------------------------------------------------------------
*/
Route::post('/products/{product}/review', 'ProductReviewController@store')->name('reviewCreate');

/*
|--------------------------------------------------------------------------
| Flagged Product Reviews Routes
|--------------------------------------------------------------------------
*/
Route::get('/review/{productReview}/report', 'FlaggedProductReviewController@store')->name('flaggedReviewStore');

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
Route::get('/billing/create', 'UserPaymentConfigController@create')->name('billingCreate');
Route::post('/billing/store', 'UserPaymentConfigController@store')->name('billingStore');

/*
|--------------------------------------------------------------------------
| Users Address Routes
|--------------------------------------------------------------------------
*/
Route::get('/address', 'UsersAddressController@index')->name('addressHome');
Route::get('/address/{usersAddress}/edit', 'UsersAddressController@edit')->name('addressEdit');
Route::put('/address/{usersAddress}/update', 'UsersAddressController@update')->name('addressUpdate');
Route::get('/address/{usersAddress}/delete', 'UsersAddressController@delete')->name('addressDelete');
Route::delete('/address/{usersAddress}/destroy', 'UsersAddressController@destroy')->name('addressDestroy');
Route::get('/address/create', 'UsersAddressController@create')->name('addressCreate');
Route::post('/address/store', 'UsersAddressController@store')->name('addressStore');

/*
|--------------------------------------------------------------------------
| Users Settings Routes
|--------------------------------------------------------------------------
*/
Route::get('user/{slug}', 'UserController@edit')->name('userEdit');
Route::put('user/{slug}', 'UserController@update')->name('userUpdate');

/*
|--------------------------------------------------------------------------
| Company Routes
|--------------------------------------------------------------------------
*/
//

/*
|--------------------------------------------------------------------------
| Vendor Routes
|--------------------------------------------------------------------------
*/
Route::get('/becomeavendor', 'VendorApplicationController@create')->name('vendorCreate');
Route::get('/becomeavendor/apply', 'VendorApplicationController@store')->name('vendorStore');
Route::get('/becomeavendor/applied', 'VendorApplicationController@show')->name('vendorShow');

/*
|--------------------------------------------------------------------------
| Moderator's Hub Routes
|--------------------------------------------------------------------------
*/
Route::get('/modhub', 'ModeratorsHubController@index')->name('modHubHome');
Route::post('/modhub/reviews/flagged/{productReview}/decision', 'ModeratorsHubController@storeFlaggedReviewDecision')->name('flaggedReviewDecisionStore');
Route::post('/modhub/vendor/applicants/{vendorApplication}/decision', 'ModeratorsHubController@storeVendorApplicantDecision')->name('vendorApplicationDecisionStore');
