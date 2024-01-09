<?php

use App\Http\Controllers\Admin\PresenceCrudController;
use App\Http\Controllers\Admin\UserCrudController;
use Illuminate\Support\Facades\Route;

// --------------------------
// Custom Backpack Routes
// --------------------------
// This route file is loaded automatically by Backpack\Base.
// Routes you generate using Backpack\Generators will be placed here.

Route::group([
    'prefix' => config('backpack.base.route_prefix', 'admin'),
    'middleware' => array_merge(
        (array) config('backpack.base.web_middleware', 'web'),
        (array) config('backpack.base.middleware_key', 'admin')
    ),
    'namespace' => 'App\Http\Controllers\Admin',
], function () { // custom admin routes
    Route::crud('user', 'UserCrudController');
    Route::crud('schedule', 'ScheduleCrudController');
    Route::crud('salary', 'SalaryCrudController');
    Route::crud('loan', 'LoanCrudController');
    Route::crud('loan-payment', 'LoanPaymentCrudController');
    Route::crud('presence', 'PresenceCrudController');
    Route::crud('salary-recap', 'SalaryRecapCrudController');

    Route::group(['prefix'=>'user'],function (){
        Route::get("/{id}/print",[UserCrudController::class,'print'])->name('user.print');
    });
    Route::group(['prefix'=>'presence'],function (){
        Route::get("/scan",[PresenceCrudController::class,'scan'])->name('presence.scan');
    });


    Route::crud('day', 'DayCrudController');
    Route::crud('schedule-day-off', 'ScheduleDayOffCrudController');
}); // this should be the absolute last line of this file
