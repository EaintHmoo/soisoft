<?php

use App\Http\Controllers\SupplierApiController;
use App\Http\Controllers\TenderApiController;
use App\Http\Controllers\UserAuthApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('register', [UserAuthApiController::class, 'register']);
Route::post('login', [UserAuthApiController::class, 'login']);
Route::post('logout', [UserAuthApiController::class, 'logout']);

Route::group(['as' => 'api.', 'middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [UserAuthApiController::class, 'logout']);
    Route::post('suppliers/individual',[SupplierApiController::class,'createIndividualRegister']);
    Route::post('suppliers/corporate',[SupplierApiController::class,'createCorporateRegister']);

    Route::get('tenders',[TenderApiController::class,'getTenders']);
    Route::get('tenders/{id}',[TenderApiController::class,'getTenderDetail']);
});

Route::get('supplier-industries',[SupplierApiController::class,'getSupplierIndustryList']);
Route::get('supplier-categories',[SupplierApiController::class,'getSupplierCategoryList']);
