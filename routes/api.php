<?php

use App\Http\Controllers\Api\QuotationApiController;
use App\Http\Controllers\Api\QuotationPropsalApiController;
use App\Http\Controllers\Api\SupplierApiController;
use App\Http\Controllers\Api\SupplierCategoryApiController;
use App\Http\Controllers\Api\SupplierPreRequiredDataApiController;
use App\Http\Controllers\Api\TenderApiController;
use App\Http\Controllers\Api\TenderPropsalApiController;
use App\Http\Controllers\Api\UserAuthApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


// Route::post('register', [UserAuthApiController::class, 'register']);
Route::get('document-types', [SupplierPreRequiredDataApiController::class, 'getDocumentTypeList']);
Route::get('supplier-business-types', [SupplierPreRequiredDataApiController::class, 'getSupplierBusinessTypeList']);
Route::get('supplier-countries', [SupplierPreRequiredDataApiController::class, 'getCountryList']);
Route::get('supplier-industries', [SupplierPreRequiredDataApiController::class, 'getSupplierIndustryList']);
Route::get('supplier-categories', [SupplierCategoryApiController::class, 'getSupplierCategoryList']);
Route::get('supplier-sub-categories/{parent_id}', [SupplierCategoryApiController::class, 'getSupplierSubCategoryList']);
Route::post('suppliers/individual', [SupplierApiController::class, 'createIndividualRegister']);
Route::post('suppliers/corporate', [SupplierApiController::class, 'createCorporateRegister']);
Route::post('login', [UserAuthApiController::class, 'login']);

Route::group(['as' => 'api.', 'middleware' => ['auth:sanctum']], function () {
    Route::post('logout', [UserAuthApiController::class, 'logout']);

    // Tenders
    Route::get('tenders', [TenderApiController::class, 'getTenders']);
    Route::get('tenders/{id}', [TenderApiController::class, 'getTenderDetail']);
    Route::post('tenders/{id}/accept-nda', [TenderApiController::class, 'acceptNDAToParticipate']);
    Route::get('tender-questions/{tender_id}', [TenderApiController::class, 'getTenderQuestions']);
    Route::post('tender-questions/{tender_id}', [TenderApiController::class, 'createTenderQuestion']);
    Route::post('tender-proposals/{tender_id}', [TenderPropsalApiController::class, 'createTenderProposal']);
    Route::put('tender-proposals/{id}/cancel', [TenderPropsalApiController::class, 'cancelTenderProposal']);
    Route::post('tenders/{tender_id}/documents', [TenderApiController::class, 'addTenderDocument']);

    // Quotations
    Route::get('quotations/{id}', [QuotationApiController::class, 'getQuotationDetail']);
    Route::post('quotations/{id}/accept-nda', [QuotationApiController::class, 'acceptNDAToParticipate']);
    Route::get('quotation-questions/{quotation_id}', [QuotationApiController::class, 'getQuotationQuestions']);
    Route::post('quotation-questions/{quotation_id}', [QuotationApiController::class, 'createQuotationQuestion']);
    Route::post('quotations/{quotation_id}/documents', [QuotationApiController::class, 'addQuotationDocument']);
    Route::post('quotation-proposals/{quotation_id}', [QuotationPropsalApiController::class, 'createQuotationProposal']);
    Route::put('quotation-proposals/{id}/cancel', [QuotationPropsalApiController::class, 'cancelQuotationProposal']);

    Route::get('tenders-by-supplier', [TenderApiController::class, 'getTenderListBySupplier']);
    Route::get('tenders-by-supplier/count', [TenderApiController::class, 'getTenderCountBySupplier']);
});
