<?php

use App\Http\Controllers\Procurement\PaymentController;
use App\Http\Controllers\Procurement\ProcurementController;
use App\Http\Controllers\Procurement\ApprovalController;
use App\Http\Controllers\Procurement\QualityController;
use App\Http\Controllers\Procurement\SecurityController;
use App\Http\Controllers\Procurement\WarehouseController;
use App\Http\Controllers\Procurement\WeighbridgeController;
use App\Http\Controllers\Production\ProductionController;
use App\Http\Controllers\SupplierController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DashboardController;

use App\Http\Controllers\Users\RolesController;
use App\Http\Controllers\Users\UsersController;
use App\Http\Controllers\Users\PermissionsController;


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



Auth::routes();

Route::group(['middleware' => ['auth', 'permission']], function(){

    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');

    Route::group(['prefix' => 'users', 'as' => 'users.' ], function(){
        Route::resource('permissions', PermissionsController::class);
        Route::resource('roles', RolesController::class);
    });

    Route::group(['prefix' => 'procurement', 'as' => 'procurement.' ], function(){
        Route::resource('security', SecurityController::class);
        Route::resource('weighbridge', WeighbridgeController::class);
        Route::resource('quality', QualityController::class);
        Route::resource('warehouse', WarehouseController::class);

        Route::resource('payment', PaymentController::class);

        Route::get('approval/{approval}/edit', [ApprovalController::class, 'edit'])->name('approval.edit');
        Route::put('approval/update', [ApprovalController::class, 'update'])->name('approval.update');
    });

    Route::group(['prefix' => 'production', 'as' => 'production.' ], function(){

    });

    Route::resource('procurement', ProcurementController::class);
    Route::resource('production', ProductionController::class);


    Route::resource('users', UsersController::class);

    Route::post('suppliers/getlist', [SupplierController::class, 'getlist'])->name('suppliers.getlist');
    Route::resource('suppliers', SupplierController::class);



});

Route::get('/', function () {
    //return view('welcome');
    return redirect('dashboard');
});
