<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\CustomerStatusController;


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

Route::post('login', [ApiController::class, 'authenticate']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function() {
    Route::get('logout', [ApiController::class, 'logout']);
    Route::get('get_user', [ApiController::class, 'get_user']);
    Route::get('customer/status', [CustomerStatusController::class, 'index']);
    Route::get('customer/{id}', [CustomerStatusController::class, 'show']);
    Route::post('customer/create', [CustomerStatusController::class, 'store']);
    Route::put('update/{customerStatus}',  [CustomerStatusController::class, 'update']);
    Route::delete('delete/{customerStatus}',  [CustomerStatusController::class, 'destroy']);
});
