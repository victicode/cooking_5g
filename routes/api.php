<?php


use App\Http\Controllers\Api\BankController;
use App\Http\Controllers\Api\BankAccountController;
use App\Http\Controllers\Api\BankAccountTypeController;
use App\Http\Controllers\Api\CoinTypeController;
use App\Http\Controllers\Api\ConfigurationController;
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\CouponController;
use App\Http\Controllers\Api\CompanyAccountController;
use App\Http\Controllers\Api\DepartmentController;
use App\Http\Controllers\Api\DistrictController;
use App\Http\Controllers\Api\DocumentTypeController;
use App\Http\Controllers\Api\ExchangeRateController;
use App\Http\Controllers\Api\FundOriginController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\OcupationController;
use App\Http\Controllers\Api\OperationController;
use App\Http\Controllers\Api\PersonalAccountController;
use App\Http\Controllers\Api\PeruConsultController;
use App\Http\Controllers\Api\ProvinceController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\Auth\ForgotPasswordController;
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

Route::post('/login', [AuthController::class, 'login']);
Route::get('/get-all-by-search', [UserController::class, 'getAllBySearch']);
Route::get('/getTodayRates_api', [ExchangeRateController::class, 'getTodayRatesApi']);
Route::post('/personal-accounts-data-table', [PersonalAccountController::class, 'getPersonalAccountsForDataTable']);
Route::post('/company-accounts-data-table', [CompanyAccountController::class, 'getCompanyAccountsForDataTable']);
Route::post('/operations-data-table', [OperationController::class, 'getOperationsForDataTable']);
Route::post('/sbs-data-table', [OperationController::class, 'getOperationsForSbsReportDataTable']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/password/reset', [ForgotPasswordController::class, 'reset']);
Route::post('/password/reset/email', [ForgotPasswordController::class, 'forgot']);
Route::post('/password/update', [ForgotPasswordController::class, 'forgot']);
Route::get('/auth/{provider}/redirect', [AuthController::class, 'SocialiteRedirect']);
Route::get('/auth/{provider}/callback', [AuthController::class, 'SocialiteCreated']);
Route::put('/validate_email/{id}', [UserController::class, 'validateEmail']);


Route::middleware('auth:api')->post('/logout', [AuthController::class, 'logout']);

Route::middleware('auth:api')->prefix('user')->name('user.')->group(function () {
    Route::get('', [UserController::class, 'getUser'])->name('getUser');
    Route::get('/validate_user_exist', [UserController::class, 'ConsultExistUser']);
    Route::post('/all', [UserController::class, 'getAllUser']);
    Route::put('/pass_update', [UserController::class, 'updatePassword']);
    Route::get('/verify', [UserController::class, 'getUser'])->name('verifyUser');
    Route::get('/get-label-active-account', [UserController::class, 'getLabelActiveAccount']);
    Route::get('/get-all', [UserController::class, 'getAll']);
    Route::get('/get-all-accounts', [UserController::class, 'getAllAccounts']);
    Route::post('/get-all-accounts-by-user', [UserController::class, 'getAllAccountsByUser']);
    Route::post('/active/change', [UserController::class, 'changeActiveAccount']);
    Route::get('/get-all-by-search-adm', [UserController::class, 'getAllBySearch']);
    Route::get('/pruebax/{id}', [OperationController::class, 'sendOperationMailsRecfact']);
    Route::post('', [UserController::class, 'create']);
});
Route::middleware('auth:api')->prefix('exchange-rates')->name('exchange-rates.')->group(function () {
    Route::get('', [ExchangeRateController::class, 'getAll']);
    Route::get('/last', [ExchangeRateController::class, 'getLast']);
    Route::post('', [ExchangeRateController::class, 'store']);
    Route::put('/{exchange_rate_id}', [ExchangeRateController::class, 'update']);
    Route::delete('/{exchange_rate_id}', [ExchangeRateController::class, 'delete']);
    Route::get('/{exchange_rate_id}/restore', [ExchangeRateController::class, 'restore']);
});

Route::middleware('auth:api')->prefix('banks')->name('banks.')->group(function () {
    Route::get('', [BankController::class, 'getAll']);
    Route::post('', [BankController::class, 'store']);
    Route::put('/{bank_id}', [BankController::class, 'update']);
    Route::post('/set', [BankController::class, 'getAllSelect']);
    Route::delete('/{bank_id}', [BankController::class, 'delete']);
    Route::get('/{bank_id}/restore', [BankController::class, 'restore']);
});

