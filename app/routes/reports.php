<?php

use App\Http\Controllers\Reports\PurchaseReportController;
use App\Http\Controllers\Reports\SalesReportController;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;


Route::controller(SalesReportController::class)->group(function () {
    ROute::get('/transactions/reports/sales', 'index')->name('report.sales.home');
});

// purchase controller
Route::controller(PurchaseReportController::class)->group(function () {
    ROute::get('/transactions/reports/purchase', 'index')->name('report.purchase.home');
    ROute::get('/transactions/reports/purchase/pdf', function () {

        $pdf = Pdf::loadView('reports.purchase.invoice');
        return $pdf->download('invoice.pdf');
    })->name('report.purchasewkdm');
});