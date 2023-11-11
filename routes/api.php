<?php



use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Auth\AuthController;
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

// Route::put('/validate_email/{id}', [UserController::class, 'validateEmail']);


// Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('jwt.verify')->prefix('user')->name('user.')->group(function () {
    Route::get('/{id}', [UserController::class, 'getUser'])->name('getUser');
});

