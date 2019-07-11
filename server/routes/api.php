<?php

use Illuminate\Http\Request;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::prefix('v1')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | User Routes
    |--------------------------------------------------------------------------
    */
    Route::post('/user/register', 'Auth\RegisterController@register');
    Route::post('/user/login', 'Auth\LoginController@create');

    Route::group(['middleware' => ['jwt.verify']], function() {

        /*
        |--------------------------------------------------------------------------
        | Users Settings Routes
        |--------------------------------------------------------------------------
        */
        Route::put('user/{slug}', 'UserController@update')->name('userUpdate');

        /*
        |--------------------------------------------------------------------------
        | User Routes
        |--------------------------------------------------------------------------
        */
        Route::get('/user/authenticate', 'Auth\UserController@authenticate');

        /*
        |--------------------------------------------------------------------------
        | Product Review Routes
        |--------------------------------------------------------------------------
        */
        Route::post('/products/{product}/review', 'ProductReviewController@store')->name('reviewCreate');

        /*
        |--------------------------------------------------------------------------
        | Order History Routes
        |--------------------------------------------------------------------------
        */
        Route::post('/order/store', 'OrderHistoryController@store')->name('orderStore');
        Route::get('/order/invoice/{refNum}', 'OrderHistoryController@show')->name('orderShow');
        Route::get('/order/invoice', 'OrderHistoryController@index')->name('orderHome');

        /*
        |--------------------------------------------------------------------------
        | Billing Card Routes
        |--------------------------------------------------------------------------
        */
        Route::get('/billing', 'UserPaymentConfigController@index')->name('billingHome');
        Route::put('/billing/{userPaymentConfig}/update', 'UserPaymentConfigController@update')->name('billingUpdate');
        Route::delete('/billing/{userPaymentConfig}/destroy', 'UserPaymentConfigController@destroy')->name('billingDestroy');
        Route::post('/billing/store', 'UserPaymentConfigController@store')->name('billingStore');

        /*
        |--------------------------------------------------------------------------
        | Users Address Routes
        |--------------------------------------------------------------------------
        */
        Route::get('/address', 'UsersAddressController@index')->name('addressHome');
        Route::put('/address/{usersAddress}/update', 'UsersAddressController@update')->name('addressUpdate');
        Route::delete('/address/{usersAddress}/destroy', 'UsersAddressController@destroy')->name('addressDestroy');
        Route::post('/address/store', 'UsersAddressController@store')->name('addressStore');

        /*
        |--------------------------------------------------------------------------
        | Company Products Routes
        |--------------------------------------------------------------------------
        */
        Route::get('/vendor/{slug}/products', 'CompanyProductController@index')->name('companyProductHome');
        Route::post('/vendor/{slug}/products/store', 'CompanyProductController@store')->name('companyProductStore');
        Route::delete('/vendor/{slug}/products/{product}/destroy', 'CompanyProductController@destroy')->name('companyProductDestroy');
        Route::put('/vendor/{slug}/products/{product}/update', 'CompanyProductController@update')->name('companyProductUpdate');

        /*
        |--------------------------------------------------------------------------
        | Vendor Application Routes
        |--------------------------------------------------------------------------
        */
        Route::post('/becomeavendor/apply', 'VendorApplicationController@store')->name('vendorStore');
        Route::get('/becomeavendor/applied', 'VendorApplicationController@show')->name('vendorShow');

        /*
        |--------------------------------------------------------------------------
        | Moderator's Hub Routes
        |--------------------------------------------------------------------------
        */
        Route::get('/modhub', 'ModeratorsHubController@index')->name('modHubHome');
        Route::post('/modhub/reviews/flagged/{productReview}/decision', 'ModeratorsHubController@storeFlaggedReviewDecision')->name('flaggedReviewDecisionStore');
        Route::post('/modhub/vendor/applicants/{vendorApplication}/decision', 'ModeratorsHubController@storeVendorApplicantDecision')->name('vendorApplicationDecisionStore');

    });

    /*
    |--------------------------------------------------------------------------
    | Product Routes
    |--------------------------------------------------------------------------
    */
    Route::get('/products', 'ProductController@index')->name('productHome');
    Route::get('/products/{product}', 'ProductController@show')->name('productShow');
    Route::post('/products/{product}/store', 'ProductController@store')->name('productAdd');

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

});



