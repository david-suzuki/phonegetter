<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\PhoneController;
use App\Http\Controllers\TagController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\PickingController;

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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('login', [AuthController::class, 'login']);

Route::group(['middleware' => ['auth:sanctum']], function () {
    // Route::get('verify', function(Request $request) {
    //     return auth()->user();
    // });

    Route::get('verify', [AuthController::class, 'verify']);

    Route::post('update_user', [AccountController::class, 'setAccount']);
    Route::post('update_password', [AccountController::class, 'setPassword']);
    Route::get('dashboard', [AccountController::class, 'getDashboardData']);
    Route::get('dashboard/chart', [AccountController::class, 'getChartData']);

    Route::get('admins/list', [AdminController::class, 'list']);
    Route::post('admins/create', [AdminController::class, 'create']);
    Route::get('admins/fetch/{id}', [AdminController::class, 'fetch']);
    Route::post('admins/update', [AdminController::class, 'update']);
    Route::get('admins/delete/{id}', [AdminController::class, 'delete']);
    Route::get('admins/histories/{id}', [AdminController::class, 'histories']);
    Route::get('admins/delete_history/{id}', [AdminController::class, 'deleteHistory']);

    Route::get('users/list', [UserController::class, 'list']);
    Route::post('users/create', [UserController::class, 'create']);
    Route::get('users/fetch/{id}', [UserController::class, 'fetch']);
    Route::post('users/update', [UserController::class, 'update']);
    Route::get('users/delete/{id}', [UserController::class, 'delete']);
    Route::get('users/histories/{id}', [UserController::class, 'histories']);
    Route::get('users/delete_history/{id}', [UserController::class, 'deleteHistory']);

    Route::post('phones/import', [PhoneController::class, 'import']);
    Route::get('phones/list', [PhoneController::class, 'list']);
    Route::get('phones/fetch/{id}', [PhoneController::class, 'fetch']);
    Route::post('phones/update', [PhoneController::class, 'update']);
    Route::get('phones/delete/{id}', [PhoneController::class, 'delete']);

    Route::get('picking/list/{amount}', [PickingController::class, 'list']);
    Route::get('picking/current', [PickingController::class, 'pickinglist']);
    Route::post('picking/mark', [PickingController::class, 'mark']);

    Route::get('tags/list', [TagController::class, 'list']);
    Route::post('tags/create', [TagController::class, 'create']);
    Route::get('tags/fetch/{id}', [TagController::class, 'fetch']);
    Route::post('tags/update', [TagController::class, 'update']);
    Route::get('tags/delete/{id}', [TagController::class, 'delete']);

    Route::get('report/list', [ReportController::class, 'list']);
    Route::post('report/search', [ReportController::class, 'search']);
    Route::post('report/excel', [ReportController::class, 'excel']);
    
    Route::post('/auth/logout', [AuthController::class, 'logout']);
});
