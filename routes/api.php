<?php


use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\ProductController;

use Illuminate\Support\Facades\Route;

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
// Route::group([

//     'middleware' => 'api',
//     'prefix' => 'auth'

// ], function ($router) {

//     Route::post('login', 'AuthController@login');
//     Route::post('logout', 'AuthController@logout');
//     Route::post('refresh', 'AuthController@refresh');
//     Route::post('me', 'AuthController@me');

// });

Route::post('login', [AuthController::class, 'login']);
Route::middleware('jwt.verify')->post('/get-products', [ProductController::class, 'getProductsTable']);
Route::middleware('jwt.verify')->post('/get-orders', [OrderController::class, 'getOrdersTable']);
Route::post('/get-recipes', [ProductController::class, 'getProductsTable']);



// Route::put('/validate_email/{id}', [UserController::class, 'validateEmail']);


// Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('jwt.verify')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('jwt.verify')->prefix('user')->name('user.')->group(function () {
    Route::get('/', [AuthController::class, 'getUser'])->name('getUser');
});
Route::middleware('jwt.verify')->prefix('order')->name('order.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('getOrders');
    Route::get('/get-last', [OrderController::class, 'getLastByType']);
    Route::get('/get-by-id/{id}',[OrderController::class, 'getOrderById']);
    
});
Route::middleware('jwt.verify')->prefix('products')->name('product.')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::post('/',[ProductController::class, 'storeProduct']);
    Route::post('/{id}',[ProductController::class, 'updateProduct']);
    Route::post('/add-stock/{id}',[ProductController::class, 'addStock']);
    Route::get('/get-critical-stock', [ProductController::class, 'getProductsCriticalStock']);
    Route::get('/get-by-id/{id}',[ProductController::class, 'getProductById']);
    Route::get('/get-by-search',[ProductController::class, 'getProductBySearch']);
    Route::get('/get-by-searchs',[ProductController::class, 'getProductBySearchs']);




});

