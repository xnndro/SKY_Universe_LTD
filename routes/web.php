<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return view('auth.login');
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');
Route::get('/sales', [App\Http\Controllers\HomeController::class, 'sales'])->name('sales');
Route::get('/userList', [App\Http\Controllers\HomeController::class, 'userList'])->name('userList');
Route::get('/banned/{id}', [App\Http\Controllers\HomeController::class, 'banned'])->name('banned');
Route::get('/activate/{id}', [App\Http\Controllers\HomeController::class, 'activate'])->name('activate');
Route::get('/places', [App\Http\Controllers\PlacesController::class, 'index'])->name('places');


// routes/web.php

Route::get('/get-places-by-location', [App\Http\Controllers\PlacesController::class, 'getPlacesByLocation'])->name('get-places-by-location');
// checkout
Route::get('/checkout/{id}', [App\Http\Controllers\OrdersController::class, 'checkout'])->name('checkout');
Route::get('/orders', [App\Http\Controllers\OrdersController::class, 'index'])->name('index.orders');
Route::post('/session', [App\Http\Controllers\OrdersController::class, 'session'])->name('session');
Route::get('/success/{id}', [App\Http\Controllers\OrdersController::class, 'success'])->name('success');
Route::delete('/delete', [App\Http\Controllers\OrdersController::class, 'delete'])->name('delete');
