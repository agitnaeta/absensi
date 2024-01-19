<?php

use App\Http\Controllers\Admin\LoanCrudController;
use App\Http\Controllers\Admin\PresenceCrudController;
use App\Http\Controllers\Admin\SalaryRecapCrudController;
use App\Http\Controllers\Admin\ScheduleCrudController;
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
        Route::get("/print-all",[UserCrudController::class,'printAll'])->name('user.print.all');
    });
    Route::group(['prefix'=>'presence'],function (){
        Route::get("/scan",[PresenceCrudController::class,'scan'])->name('presence.scan');
        Route::post("/record",[PresenceCrudController::class,'record'])->name('presence.record');
        Route::get("/record",[PresenceCrudController::class,'record'])->name('presence.record.get');
    });

    Route::group(['prefix'=>'schedule'],function (){
        Route::get("/view-update",[ScheduleCrudController::class,'viewSchedule'])->name('schedule.view.update');
        Route::post("/mass-update",[ScheduleCrudController::class,'massUpdateSchedule'])->name('schedule.mass_update');
    });
    Route::group(['prefix'=>'loan'],function (){
        Route::get("/recap",[LoanCrudController::class,'loanRecap'])->name('loan.recap');
        Route::get("/download",[LoanCrudController::class,'download'])->name('loan.download');
        Route::get("/{id}/download-detail",[LoanCrudController::class,'downloadDetail'])->name('loan.download.detail');
        Route::get("/{id}/print-detail",[LoanCrudController::class,'print'])->name('loan.download.print');
        Route::get("/{id}/detail",[LoanCrudController::class,'detail'])->name('loan.detail');
    });
    Route::group(['prefix'=>'salary-recap'],function (){
        Route::get('export',[SalaryRecapCrudController::class,'export'])
            ->name('salary-recap.export');
        Route::get('print',[SalaryRecapCrudController::class,'print'])
            ->name('salary-recap.print');
    });


    Route::crud('day', 'DayCrudController');
    Route::crud('schedule-day-off', 'ScheduleDayOffCrudController');
    Route::crud('national-holiday', 'NationalHolidayCrudController');
    Route::crud('company-profile', 'CompanyProfileCrudController');
}); // this should be the absolute last line of this file


Route::group(['prefix'=>'presence'],function (){
    Route::get("/scan",[PresenceCrudController::class,'scan'])->name('presence.scan');
});
