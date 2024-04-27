<?php


use App\Models\Product;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CartController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\LoteController;

use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\RecipeController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\Auth\AuthController;

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
Route::middleware('jwt.verify')->post('/get-products',  [ProductController::class, 'getProductsTable']);
Route::middleware('jwt.verify')->post('/get-lotes',     [ProductController::class, 'getlotesOfProductsTable']);
Route::middleware('jwt.verify')->post('/get-orders',    [OrderController::class, 'getOrdersTable']);
Route::middleware('jwt.verify')->post('/get-chefs',     [UserController::class, 'getUserTableByRol']);
Route::middleware('jwt.verify')->post('/get-users',     [UserController::class, 'getUserTableByRol']);
Route::middleware('jwt.verify')->post('/get-recipes',   [RecipeController::class, 'getRecipesTable']);


Route::middleware('jwt.verify')->post('/logout', [AuthController::class, 'logout']);
Route::middleware('jwt.verify')->post('/checkToken', [AuthController::class, 'checkToken']);


Route::middleware('jwt.verify')->prefix('user')->name('user.')->group(function () {
    Route::get('/', [AuthController::class, 'getUser'])->name('getUser');
    Route::post('/',[UserController::class, 'createUser']);
    Route::post('/{id}',[UserController::class, 'updateUser']);
    Route::post('/chef',[UserController::class, 'createUser']);
    Route::delete('/{id}',[UserController::class, 'deleteUser']);
    Route::get('/all-client', [UserController::class, 'getAllclient']);
    Route::get('/get-by-id/{id}',[UserController::class, 'getUserById']);


});
Route::middleware('jwt.verify')->prefix('order')->name('order.')->group(function () {
    Route::get('/', [OrderController::class, 'index'])->name('getOrders');
    Route::post('/', [OrderController::class, 'createOrder']);
    Route::get('/get-last', [OrderController::class, 'getLastByType']);
    Route::get('/get-by-id/{id}',[OrderController::class, 'getOrderById']);
    Route::post('/change-status/{id}',[OrderController::class, 'changeStatus']);
});
Route::middleware('jwt.verify')->prefix('out_order')->name('out_order.')->group(function () {
    Route::post('/', [OrderController::class, 'createOutOrder']);
    Route::get('/print/{id}', [OrderController::class, 'printOutOrder']);

});
Route::prefix('products')->name('product.')->group(function () {
    Route::get('/', [ProductController::class, 'index']);
    Route::get('/lotes', [ProductController::class, 'index']);
    Route::post('/',[ProductController::class, 'storeProduct']);
    Route::post('/{id}',[ProductController::class, 'updateProduct']);
    Route::delete('/{id}',[ProductController::class, 'deleteProduct']);
    Route::post('/add-stock/{id}',[ProductController::class, 'addStock']);
    Route::get('/get-critical-stock', [ProductController::class, 'getProductsCriticalStock']);
    Route::get('/get-by-id/{id}',[ProductController::class, 'getProductById']);
    Route::get('/get-by-search',[ProductController::class, 'getProductBySearch']);
    Route::get('/get-last-lote/{id}',[ProductController::class, 'getLastLoteFromProduct']);
    Route::get('/notify-out-stock/{id}',[ProductController::class, 'notifyOutStock']);

});
Route::middleware('jwt.verify')->prefix('lotes')->name('lotes.')->group(function () {
    Route::get('/get-by-id/{id}',[LoteController::class, 'getLoteById']);
    Route::delete('/{id}',[LoteController::class, 'deleteLote']);

});
Route::middleware('jwt.verify')->prefix('cart')->name('cart.')->group(function () {
    Route::get('/',[CartController::class, 'index']);
    Route::post('/',[CartController::class, 'store']);
    Route::post('/item/{id}',[CartController::class, 'deleteItem']);
    Route::post('/item/quantity/{id}',[CartController::class, 'editItemQuantity']);
    Route::delete('/{id}',[CartController::class, 'destroy']);
});
Route::prefix('recipes')->name('recipes.')->group(function () {
    Route::get('/get-by-id/{id}',[RecipeController::class, 'getRecipeById']);
});

Route::middleware('jwt.verify')->prefix('recipes')->name('recipes.')->group(function () {
    Route::post('/',[RecipeController::class, 'storeRecipe']);
    Route::get('/',[RecipeController::class, 'index']);
    Route::post('/{id}',[RecipeController::class, 'updateRecipe']);
    Route::delete('/{id}',[RecipeController::class, 'deleteRecipe']);
    Route::get('/get-by-search',[RecipeController::class, 'getRecipeBySearch']);
});

Route::middleware('jwt.verify')->prefix('support')->name('supports.')->group(function () {
    Route::post('/',[ChatController::class, 'storeChat']);
    Route::get('/',[ChatController::class, 'getChats']);
    Route::get('/{id}',[ChatController::class, 'getChatById']);
    Route::post('/{id}/newMessage',[ChatController::class, 'newMessage']);
    Route::post('/get-unread-messages', [ChatController::class, 'allUnReadMessages']);

});

Route::middleware('jwt.verify')->prefix('notification')->name('notification.')->group(function () {
    Route::get('/{id}',function($id){
        return getAllNotifiaction($id);
    });
    Route::get('/see-all/{user}/{type}',function($user,$type){
        return seeAllNotifiaction($user, $type);
    });
    Route::post('/',[Ordercontroller::class, 'newNotification']);
});
Route::middleware('jwt.verify')->prefix('message')->name('message.')->group(function () {
    Route::get('/{id}',function($id){
        return getAllMessage($id);
    });
    Route::get('/see-all/{user}',function($user){
        return seeAllMessage($user);
    });
});

Route::get('recipes/client/print/{id}', [RecipeController::class, 'printRecipeTag']);
Route::get('recipes/client/print/multiple', [RecipeController::class, 'printRecipeTag']);

