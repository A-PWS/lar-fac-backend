<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

use App\Models\addShift;
use App\Models\Shifts;
use App\Models\Hourlydata;
use App\Models\RejectReason;
use App\Models\newProductionHour;
use App\Models\NShift;
use App\Models\Rejects;

use App\Http\Controllers\ShiftController;
use App\Http\Controllers\ShiftsController;
use App\Http\Controllers\HourlyDataController;
use App\Http\Controllers\RejectReasonController;
use App\Http\Controllers\ProductionController;
use App\Http\Controllers\ApiController;
use App\Http\Controllers\NShiftController;
use App\Http\Controllers\HourController;
use App\Http\Controllers\RejectController;

use App\Http\Resources\ShiftResource;
use App\Http\Resources\ShiftsResource;
use App\Http\Resources\HourlyDataResource;
use App\Http\Resources\RejectReasonResource;
use App\Http\Resources\ProductionResource;


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


// ====================== Shifts =====================================

// Route::get('/shift/{shiftId}', function($shiftId){
//     return new ShiftsResource(Shifts::findOrFail($shiftId));
// });

// Route::get('/shifts', function(){
//     return ShiftsResource::collection(Shifts::all());
// });

// Route::put('/shift/{shiftId}',[ShiftsController::class,'update']);

// Route::delete('/shift/{shiftId}',[ShiftsController::class,'destroy']);

// Route::post('/shifts',[ShiftsController::class,'store']);

// 





// ==================================================================================


//=================== Hourly Data =========================================

// Route::get('/hourlydata/{shiftId}', function($shiftId){
//     return new HourlyDataResource(Hourlydata::findOrFail($shiftId));
// });

// Route::get('/hourlydatas', function(){
//     return HourlyDataResource::collection(Hourlydata::all());
// });

// Route::put('/hourlydata/{shiftId}',[HourlyDataController::class,'update']);

// Route::delete('/hourlydata/{shiftId}',[HourlyDataController::class,'destroy']);

// Route::post('/hourlydatas',[HourlyDataController::class,'store']);



//====================================================================================


//======================== Reject Reason ==============================================

// Route::get('/rejectreason/{id}', function($id){
//     return new HourlyDataResource(Hourlydata::findOrFail($id));
// });

// Route::get('/rejectreasons', function(){
//     return HourlyDataResource::collection(Hourlydata::all());
// });

// Route::put('/rejectreason/{id}',[HourlyDataController::class,'update']);

// Route::delete('/rejectreason/{id}',[HourlyDataController::class,'destroy']);

// Route::post('/rejectreasons',[HourlyDataController::class,'store']);

// Route::get('/',[RejectController::class,'index']);


//======================================================================================

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


Route::get('testlar', [ApiController::class, 'testlar']);


Route::post('login', [ApiController::class, 'login']);
Route::post('register', [ApiController::class, 'register']);

Route::group(['middleware' => ['jwt.verify']], function(){
    Route::get('logout/{token}', [ApiController::class, 'logout']);
    
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
        // Route::post('rejects', [HourController::class, 'addRejectData']);
        Route::get('reject', [HourController::class, 'getReject']);

        Route::get('getOneReject/{id}', [NShiftController::class, 'getOneReject']);

       Route::get('getShiftReport',[NShiftController::class, 'getShiftReport']);
       Route::get('getShiftReportReject',[NShiftController::class, 'getShiftReportReject']);

       Route::get('getShiftTimeDropDown',[HourController::class, 'getShiftTimeDropDown']);

       Route::get('getHourlyProductionTable',[NShiftController::class, 'getHourlyProductionTable']);

       
    //    ---***---------------------------------------------------------------------------------------
    Route::get('dashboardTotalProductionUpToDate', [NShiftController::class, 'dashboardTotalProductionUpToDate']);

    Route::get('dashboardTotalRejectionUpToDate', [NShiftController::class, 'dashboardTotalRejectionUpToDate']);

    Route::get('getLowReasonDropDown',[HourController::class, 'getLowReasonDropDown']);

    Route::get('getDailyReject',[NShiftController::class, 'getDailyReject']);

    Route::get('dashboardAVG',[NShiftController::class, 'dashboardAVG']);

    Route::get('hourBreakDown',[NShiftController::class, 'hourBreakDown']);

    Route::get('hourBreakDownQuntity',[NShiftController::class, 'hourBreakDownQuntity']);

    // ----------------------------------------------------------------------------------------------------

    });

        Route::post('addRejectData', [RejectController::class, 'addRejectData']);
        Route::get('getRejectData', [RejectController::class, 'getRejectData']);
       


