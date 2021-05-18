<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductReviewController;
use App\Http\Controllers\FlaggedProductReviewController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\OrderHistoryController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\UserPaymentConfigController;
use App\Http\Controllers\UsersAddressController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\CompanyProductController;
use App\Http\Controllers\VendorApplicationController;
use App\Http\Controllers\ModeratorsHubController;

/*
|--------------------------------------------------------------------------
| Home Routes
|--------------------------------------------------------------------------
*/
Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Product Routes
|--------------------------------------------------------------------------
*/
Route::get('/products', [ProductController::class, 'index'])->name('productHome');
Route::get('/products/{product}', [ProductController::class, 'show'])->name('productShow');
Route::get('/products/{product}/add', [ProductController::class, 'create'])->name('productAdd');


/*
|--------------------------------------------------------------------------
| Product Review Routes
|--------------------------------------------------------------------------
*/
Route::post('/products/{product}/review', [ProductReviewController::class, 'store'])->name('reviewCreate');

/*
|--------------------------------------------------------------------------
| Flagged Product Reviews Routes
|--------------------------------------------------------------------------
*/
Route::get('/review/{productReview}/report', [FlaggedProductReviewController::class, 'store'])->name('flaggedReviewStore');

/*
|--------------------------------------------------------------------------
| Cart Routes
|--------------------------------------------------------------------------
*/
Route::get('/cart', [CartController::class, 'show'])->name('cartShow');
Route::put('/cart/update', [CartController::class, 'update'])->name('cartUpdate');

/*
|--------------------------------------------------------------------------
| Order History Routes
|--------------------------------------------------------------------------
*/
Route::get('/order/create', [OrderHistoryController::class, 'create'])->name('orderCreate');
Route::post('/order/store', [OrderHistoryController::class, 'store'])->name('orderStore');
Route::get('/order/invoice/{refNum}', [OrderHistoryController::class, 'show'])->name('orderShow');
Route::get('/order/invoice', [OrderHistoryController::class, 'index'])->name('orderHome');

/*
|--------------------------------------------------------------------------
| User Routes
|--------------------------------------------------------------------------
*/
Route::get('/login', [LoginController::class, 'create'])->name('login');
Route::put('/login/create', [LoginController::class, 'store'])->name('loginCreate');
Route::get('/register', [RegisterController::class, 'createUser'])->name('registerHome');
Route::post('/register/create', [RegisterController::class, 'storeUser'])->name('registerCreate');
Route::get('/logout', [LoginController::class, 'delete'])->name('logout');

/*
|--------------------------------------------------------------------------
| Billing Card Routes
|--------------------------------------------------------------------------
*/
Route::get('/billing', [UserPaymentConfigController::class, 'index'])->name('billingHome');
Route::get('/billing/{userPaymentConfig}/edit', [UserPaymentConfigController::class, 'edit'])->name('billingEdit');
Route::put('/billing/{userPaymentConfig}/update', [UserPaymentConfigController::class, 'update'])->name('billingUpdate');
Route::get('/billing/{userPaymentConfig}/delete', [UserPaymentConfigController::class, 'delete'])->name('billingDelete');
Route::delete('/billing/{userPaymentConfig}/destroy', [UserPaymentConfigController::class, 'destroy'])->name('billingDestroy');
Route::get('/billing/create', [UserPaymentConfigController::class, 'create'])->name('billingCreate');
Route::post('/billing/store', [UserPaymentConfigController::class, 'store'])->name('billingStore');

/*
|--------------------------------------------------------------------------
| Users Address Routes
|--------------------------------------------------------------------------
*/
Route::get('/address', [UsersAddressController::class, 'index'])->name('addressHome');
Route::get('/address/{usersAddress}/edit', [UsersAddressController::class, 'edit'])->name('addressEdit');
Route::put('/address/{usersAddress}/update', [UsersAddressController::class, 'update'])->name('addressUpdate');
Route::get('/address/{usersAddress}/delete', [UsersAddressController::class, 'delete'])->name('addressDelete');
Route::delete('/address/{usersAddress}/destroy', [UsersAddressController::class, 'destroy'])->name('addressDestroy');
Route::get('/address/create', [UsersAddressController::class, 'create'])->name('addressCreate');
Route::post('/address/store', [UsersAddressController::class, 'store'])->name('addressStore');

/*
|--------------------------------------------------------------------------
| Users Settings Routes
|--------------------------------------------------------------------------
*/
Route::get('user/{slug}', [UserController::class, 'edit'])->name('userEdit');
Route::put('user/{slug}', [UserController::class, 'update'])->name('userUpdate');

/*
|--------------------------------------------------------------------------
| Company Routes
|--------------------------------------------------------------------------
*/
// Route::get();


/*

|--------------------------------------------------------------------------
| Company Products Routes
|--------------------------------------------------------------------------
*/
Route::get('/vendor/{slug}/products', [CompanyProductController::class, 'index'])->name('companyProductHome');
Route::get('/vendor/{slug}/products/create', [CompanyProductController::class, 'create'])->name('companyProductCreate');
Route::post('/vendor/{slug}/products/store', [CompanyProductController::class, 'store'])->name('companyProductStore');
Route::get('/vendor/{slug}/products/{product}/delete', [CompanyProductController::class, 'delete'])->name('companyProductDelete');
Route::delete('/vendor/{slug}/products/{product}/destroy', [CompanyProductController::class, 'destroy'])->name('companyProductDestroy');
Route::get('/vendor/{slug}/products/{product}/edit', [CompanyProductController::class, 'edit'])->name('companyProductEdit');
Route::put('/vendor/{slug}/products/{product}/update', [CompanyProductController::class, 'update'])->name('companyProductUpdate');

/*
|--------------------------------------------------------------------------
| Vendor Application Routes
|--------------------------------------------------------------------------
*/
Route::get('/becomeavendor', [VendorApplicationController::class, 'create'])->name('vendorCreate');
Route::post('/becomeavendor/apply', [VendorApplicationController::class, 'store'])->name('vendorStore');
Route::get('/becomeavendor/applied', [VendorApplicationController::class, 'show'])->name('vendorShow');

/*
|--------------------------------------------------------------------------
| Moderator's Hub Routes
|--------------------------------------------------------------------------
*/
Route::get('/modhub', [ModeratorsHubController::class, 'index'])->name('modHubHome');
Route::post('/modhub/reviews/flagged/{productReview}/decision', [ModeratorsHubController::class, 'storeFlaggedReviewDecision'])->name('flaggedReviewDecisionStore');
Route::post('/modhub/vendor/applicants/{vendorApplication}/decision', [ModeratorsHubController::class, 'storeVendorApplicantDecision'])->name('vendorApplicationDecisionStore');
