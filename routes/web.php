<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Auth\CustomerAuthController;
use App\Http\Controllers\Auth\WarehouseAuthController;
use App\Http\Controllers\Customer\CustomerDashboardController;
use App\Http\Controllers\Customer\OrderController;
use App\Http\Controllers\Customer\ProductController as CustomerProductController;
use App\Http\Controllers\Warehouse\ProductController;
use App\Http\Controllers\Warehouse\WarehouseController;
use App\Http\Controllers\Warehouse\WarehouseDashboardControlle;
use Illuminate\Support\Facades\Auth;

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

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

Route::prefix('customer')->name('customer.')->group(function () {
    Route::get('register', [App\Http\Controllers\Auth\CustomerAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [App\Http\Controllers\Auth\CustomerAuthController::class, 'register'])->name('register.submit');


    Route::get('login', [CustomerAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [CustomerAuthController::class, 'login'])->name('login.submit');
    Route::post('logout', [CustomerAuthController::class, 'logout'])->name('logout');

});

Route::prefix('warehouse')->name('warehouse.')->group(function () {
    Route::get('register', [App\Http\Controllers\Auth\WarehouseAuthController::class, 'showRegisterForm'])->name('register');
    Route::post('register', [App\Http\Controllers\Auth\WarehouseAuthController::class, 'register'])->name('register.submit');
    
    Route::get('login', [WarehouseAuthController::class, 'showLoginForm'])->name('login');
    Route::post('login', [WarehouseAuthController::class, 'login'])->name('login.submit');
    Route::post('logout', [WarehouseAuthController::class, 'logout'])->name('logout');
});

Route::prefix('customer')
    ->name('customer.')
    ->middleware('customer')
    ->group(function () {
        Route::get('dashboard', [CustomerDashboardController::class, 'index'])->name('dashboard');
        Route::get('/products', [CustomerProductController::class, 'index'])->name('products.index');
        Route::get('/products/{product}', [CustomerProductController::class, 'show'])->name('products.show');
        Route::post('/order', [OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/success/{order}', function ($orderId) {
            return view('customer.orders.success', ['order' => App\Models\Order::findOrFail($orderId)]);
        })->name('orders.success');
    

    });

Route::prefix('warehouse')
    ->name('warehouse.')
    ->middleware('warehouse')
    ->group(function () {
        Route::get('dashboard', [WarehouseDashboardControlle::class, 'index'])->name('dashboard');
        Route::resource('warehouses', WarehouseController::class);
        Route::get('products', [ProductController::class, 'index'])->name('products.index');
        Route::get('products/create', [ProductController::class, 'create'])->name('products.create');
        Route::post('products/store', [ProductController::class, 'store'])->name('products.store');

    });
