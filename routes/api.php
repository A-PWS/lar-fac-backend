<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\NShiftController;
use App\Http\Controllers\HourController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| These routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. 
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('/', [ApiController::class, 'index']);
Route::post('register',  [AuthController::class, 'register']);
Route::post('login',  [AuthController::class, 'login']);
Route::get('logout/{token}', [AuthController::class, 'logout']);


Route::delete('deleteTestUser/{userEmail}', [AuthController::class, 'deleteTestUser']);


Route::group(['middleware' => ['jwt.verify']], function(){
    Route::get('protectedRoute', [ApiController::class, 'index']);

    Route::delete('delete/{userId}', [AuthController::class, 'delete']);

    Route::get('n_shifts', [NShiftController::class, 'getData']);
    Route::post('addn_shifts', [NShiftController::class, 'addData']);
    Route::delete('deleteShift/{id}', [NShiftController::class, 'deleteData']);
    
    Route::get('getOneShift/{id}', [NShiftController::class, 'getOneData']);

    Route::patch('updateShift/{id}', [NShiftController::class, 'updateData']);

    Route::get('hour', [HourController::class, 'getData']);
    Route::post('addhour', [HourController::class, 'addData']);
    Route::delete('deleteHour/{id}', [HourController::class, 'deleteData']);

    Route::get('getOneHour/{id}', [HourController::class, 'getOneData']);

    Route::patch('updateHour/{id}', [HourController::class, 'updateData']);


    Route::post('reject_reasons', [HourController::class, 'create']);
    Route::get('reject', [HourController::class, 'getReject']);
    Route::get('getOneReject/{id}', [NShiftController::class, 'getOneReject']);

    Route::get('getShiftReport',[NShiftController::class, 'getShiftReport']);
    Route::get('getShiftReportReject',[NShiftController::class, 'getShiftReportReject']);
    Route::get('getShiftTimeDropDown',[HourController::class, 'getShiftTimeDropDown']);
    Route::get('getHourlyProductionTable',[NShiftController::class, 'getHourlyProductionTable']);

       
    Route::get('dashboardTotalProductionUpToDate', [NShiftController::class, 'dashboardTotalProductionUpToDate']);
    Route::get('dashboardTotalRejectionUpToDate', [NShiftController::class, 'dashboardTotalRejectionUpToDate']);

    Route::get('getLowReasonDropDown',[HourController::class, 'getLowReasonDropDown']);
    Route::get('getDailyReject',[NShiftController::class, 'getDailyReject']);
    Route::get('dashboardAVG',[NShiftController::class, 'dashboardAVG']);
    Route::get('hourBreakDown',[NShiftController::class, 'hourBreakDown']);

    Route::get('hourBreakDownQuntity',[NShiftController::class, 'hourBreakDownQuntity']);

});
