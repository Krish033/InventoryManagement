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
    Route::get('/transactions/api/purchase/tax-records', 'taxes'); // get Tax records
    Route::post('/transactions/purchase/assign-tax/{puid}', 'recordTax'); // attach tax request to item
    Route::post('/transactions/purchase/auto-update-payments/{puid}', 'autoUpdatePayments'); // attach tax request to item
    // create / update actions 
    Route::get('/transactions/purchase/create', 'create')->name('purchase.create');
    Route::post('/transactions/purchase/create', 'store');
    Route::get('/transactions/purchase/update/{puid}', 'edit')->name('purchase.update');
    Route::post('/transactions/purchase/update/{puid}', 'update');

    // delete and restore actions
    Route::post('/transactions/purchase/delete/{puid}', 'destroy')->name('purchase.delete');
    Route::post('/transactions/purchase/restore/{puid}', 'restore')->name('purchase.restore');

    Route::get('/transactions/api/purchased-item/stats/{puid}', 'stats'); // get total amount and number of items
    // ? update purchase records
    Route::post('/transactions/api/purchased-item/payment-record/{puid}', 'createPaymentRecords'); // update payment records
});

// single action controller -- purchase items
Route::controller(PurchasedItemsController::class)->group(function () {
    // single purchase
    Route::post('/transactions/api/purchased/list/{puid}', 'homeApi')->name('purchase.item.api');
    Route::get('/transactions/purchased-items/{puid}', 'index')->name('purchase.item.home');
    // set supplier 
    Route::get('/transactions/api/purchased-items/suppliers', 'suppliers')->name('purchase.item.suppliers');

    // creatw purchased item
    Route::post('/transactions/api/purchased-item/create/{puid}', 'store')->name('purchase.item.create');
    Route::post('/transactions/api/purchased-item/delete/{piid}', 'destroy')->name('purchase.item.destroy');
});

// Sales controller
Route::controller(SaleController::class)->group(function () {
    // views
    Route::get('/transactions/sales', 'index')->name('sales.home');
    Route::get('/transactions/sales/trash', 'trash')->name('sales.trash');

    Route::get('/transactions/api/sales', 'homeApi')->name('sales.home.api');
    Route::get('/transactions/api/sales/trash', 'trashApi')->name('sales.trash.api');
    Route::get('/transactions/api/sales/tax-records', 'taxes'); // get Tax records
    Route::post('/transactions/sales/assign-tax/{saId}', 'recordTax'); // attach tax request to item
    Route::post('/transactions/sales/auto-update-payments/{saId}', 'autoUpdatePayments'); // attach tax request to item
    // create / update actions 
    Route::get('/transactions/sales/create', 'create')->name('sales.create');
    Route::post('/transactions/sales/create', 'store');
    Route::get('/transactions/sales/update/{saId}', 'edit')->name('sales.update');
    Route::post('/transactions/sales/update/{saId}', 'update');

    // delete and restore actions
    Route::post('/transactions/sales/delete/{saId}', 'destroy')->name('sales.delete');
    Route::post('/transactions/sales/restore/{saId}', 'restore')->name('sales.restore');
    // ? update sales records
    Route::post('/transactions/api/sales/payment-record/{saId}', 'createPaymentRecords'); // update payment records
    // main page actions
    Route::post('/transactions/api/sales/mark-completed/{saId}', 'setCompleted'); // set as completed
    Route::post('/transactions/api/sales/start-sale/{saId}', 'startSale'); // start sale
    Route::post('/transactions/api/sales/end-sale/{saId}', 'endSale'); // end sale
    Route::get('/transactions/api/sales/single/stats/{saId}', 'stats'); // end sale
});


// single action controller -- purchase items
Route::controller(SaleItemController::class)->group(function () {
    Route::get('/transactions/sales/single/{saId}', 'index')->name('sales.single.home'); // end sale
    Route::post('/transactions/api/sales/single/home/{saId}', 'mainApi')->name('sales.single.api'); // end sale

    Route::get('/transactions/sales/single/create/{saId}', 'create')->name('sales.single.create'); // end sale
    Route::post('/transactions/sales/single/create/{saId}', 'store'); // end sale

    Route::get('/transactions/sales/single/update/{siId}', 'edit')->name('sales.single.update'); // end sale
    Route::post('/transactions/sales/single/update/{siId}', 'update'); // end sale
    Route::post('/transactions/sales/single/get-products/{siId}', 'getSingleProducts'); // end sale

    Route::get('/transactions/api/sales/stats/{saId}', 'stats');
    Route::post('/transactions/sales/single/products', 'homeApi')->name('sales.single.products'); // end sale
    Route::get('/transactions/sales/single/product/{pid}', 'product')->name('sales.single.product'); // end sale
    Route::post('/transactions/sales/single/customers/', 'customers')->name('sales.single.customers'); // end sale

    Route::post('/transactions/sales/single/destroy/{siId}', 'destroy')->name('sales.single.delete'); // delete
});


// transactions/payments
// Sales controller
Route::controller(PaymentController::class)->group(function () {
    Route::get('/transactions/payments', 'index')->name('payment.home');
    Route::post('/transactions/api/payments/home', 'homeApi')->name('payment.home.api');
    Route::get('/transactions/payments/trash', 'trash')->name('payment.trash');
    Route::post('/transactions/api/payments/trash', 'trashApi')->name('payment.trash.api');

    Route::get('/transactions/payment/{pyid}', 'payment')->name('payment.single');

    Route::get('/transactions/payments/create', 'create')->name('payment.create');
    Route::post('/transactions/payments/create', 'store');

    Route::get('/transactions/payments/update/{pyid}', 'edit')->name('payment.update'); // edit
    Route::post('/transactions/payments/update/{pyid}', 'update');

    Route::post('/transactions/payments/delete/{pyid}', 'destroy')->name('payment.delete');
    Route::get('/transactions/payments/restore/{pyid}', 'restore')->name('payment.restore'); // edit

    Route::get('/transactions/payments/update-payments/{pyid}', 'updatePayments')->name('payment.update.payments'); // edit
});


Route::controller(PaymentItemController::class)->group(function () {
    Route::post('/transactions/payments/item/create/{pyid}', 'store')->name('payment.item.create');
    Route::post('/transactions/payments/api/item/list/{pyid}', 'homeApi')->name('payment.item.home');


    Route::post('/transactions/payments/item/delete/{pyid}', 'destroy')->name('payment.item.delete');
    Route::get('/transactions/payments/item/stats/{pyid}', 'stats')->name('payment.item.stats');
});