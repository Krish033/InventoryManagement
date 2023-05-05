<?php

use App\Http\Controllers\Payments\PaymentController;
use App\Http\Controllers\Payments\PaymentItemController;
use App\Http\Controllers\Sales\SaleController;
use App\Http\Controllers\Sales\SaleItemController;
use App\Http\Controllers\Transactions\PurchaseController;
use App\Http\Controllers\Transactions\PurchasedItemsController;
use Illuminate\Support\Facades\Route;

// Purchase controller
Route::controller(PurchaseController::class)->group(function () {
    // views
    Route::get('/transactions/purchase', 'index')->name('purchase.home');
    Route::get('/transactions/purchase/trash', 'trash')->name('purchase.trash');

    Route::get('/transactions/api/purchase', 'homeApi')->name('purchase.home.api');
    Route::get('/transactions/api/purchase/trash', 'trashApi')->name('purchase.trash.api');

    // create / update actions 
    Route::get('/transactions/purchase/create', 'create')->name('purchase.create');
    Route::post('/transactions/purchase/create', 'store');

    Route::get('/transactions/purchase/update/{puid}', 'edit')->name('purchase.update');
    Route::post('/transactions/purchase/update/{puid}', 'update');

    // delete and restore actions
    Route::post('/transactions/purchase/delete/{puid}', 'destroy')->name('purchase.delete');
    Route::post('/transactions/purchase/restore/{puid}', 'restore')->name('purchase.restore');

    // new Purchase Record
    Route::get('/transactions/api/purchase/requestCategory', 'requestCategories')->name('purchase.category'); // category record
    Route::get('/transactions/api/purchase/request-subCategory/{scId}', 'requestSubcategories')->name('purchase.subcategory'); // Sub category record

    Route::get('/transactions/api/purchase/request-products/{scId}', 'requestProducts')->name('purchase.products'); // Sub category record
    Route::get('/transactions/api/purchase/request-single-products/{pid}', 'requestSingleProducts')->name('purchase.single.products'); // Sub category record

    Route::get('/transactions/api/purchase/requestTax', 'requesttaxes')->name('purchase.tax'); // Tax Record
    Route::get('/transactions/api/purchase/requestSingleTax/{taxId}', 'requesttax')->name('purchase.single.tax'); // Single Tax record
    Route::get('/transactions/api/purchase/request-suppliers', 'requestSuppliers')->name('purchase.suppliers'); // category record

    Route::post('/transactions/api/purchase/create-record', 'store'); // category record
    Route::get('/transactions/api/purchase/request-created-products/{tranNo}', 'requestCreatedProducts'); // category record
    Route::post('//transactions/api/purchase/update-record/{tranNo}', 'update'); // category record
})->middleware(['auth']);

// Sales controller
Route::controller(SaleController::class)->group(function () {
    // views
    Route::get('/transactions/sales', 'index')->name('sales.home');
    Route::get('/transactions/sales/trash', 'trash')->name('sales.trash');

    Route::get('/transactions/api/sales', 'homeApi')->name('sales.home.api');
    Route::get('/transactions/api/sales/trash', 'trashApi')->name('sales.trash.api');

    // create / update actions 
    Route::get('/transactions/sales/create', 'create')->name('sales.create');
    Route::post('/transactions/sales/create', 'store');

    Route::get('/transactions/sales/update/{saId}', 'edit')->name('sales.update');
    Route::post('/transactions/sales/update/{saId}', 'update');

    // delete and restore actions
    Route::post('/transactions/sales/delete/{saId}', 'destroy')->name('sales.delete');
    Route::post('/transactions/sales/restore/{saId}', 'restore')->name('sales.restore');

    // ! these routes should be moved to its own invokable controllers >-> completed project
    Route::get('/transactions/api/sales/taxes', 'taxes')->name('sales.tax'); // Tax Record
    Route::get('/transactions/api/sales/tax/{taxId}', 'tax')->name('sales.single.tax'); // Single Tax record
    Route::get('/transactions/api/sales/customers', 'customers')->name('sales.customers'); // category record
    // getting a single record
    Route::get('/transactions/api/sales/created-products/{tranNo}', 'products'); // get records for a single item
    // Addons
    Route::get('/transactions/api/sales/categories', 'categories')->name('sales.category'); // category record
    Route::get('/transactions/api/sales/subCategories/{scId}', 'subCategories')->name('sales.subcategory'); // Sub category record
    Route::get('/transactions/api/sales/products/{scId}', 'products')->name('sales.products'); // Get all products
    Route::get('/transactions/api/sales/product/{pid}', 'product')->name('sales.single.products'); // Single product
});