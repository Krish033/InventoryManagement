<?php

use App\Http\Controllers\master\category;
use App\Http\Controllers\master\subcategory;
use App\Http\Controllers\master\customercontroller;
use App\Http\Controllers\master\gst;
use App\Http\Controllers\master\Product;
use App\Http\Controllers\master\ProductController;
use App\Http\Controllers\master\SupplierController;
use Illuminate\Support\Facades\Route;

// Categiry controller
Route::controller(category::class)->group(function () {
    Route::get('/master/category', 'view');
    Route::post('/master/category/data', 'TableView');
    Route::get('/master/category/create', 'create');
    Route::get('/master/category/edit/{CID}', 'edit');
    Route::post('/master/category/create', 'save');
    Route::post('/master/category/edit/{CID}', 'update');
    Route::post('/master/category/delete/{CID}', 'delete');
    Route::get('/master/category/trash-view/', 'TrashView');
    Route::post('/master/category/trash-data', 'TrashTableView');
    Route::post('/master/category/restore/{CID}', 'Restore');

});

// SUb category controller
Route::controller(subcategory::class)->group(function () {
    Route::get('/master/SubCategory', 'view');
    Route::post('/master/SubCategory/data', 'TableView');
    Route::get('/master/SubCategory/create', 'create');
    Route::get('/master/SubCategory/edit/{CID}', 'edit');
    Route::post('/master/SubCategory/create', 'save');
    Route::post('/master/SubCategory/edit/{CID}', 'update');
    Route::post('/master/SubCategory/delete/{CID}', 'delete');
    Route::get('/master/SubCategory/trash-view/', 'TrashView');
    Route::post('/master/SubCategory/trash-data', 'TrashTableView');
    Route::post('/master/SubCategory/restore/{CID}', 'Restore');
    Route::post('/master/SubCategory/getCategory', 'getCategory');
});

// CUstomer controller
Route::controller(customercontroller::class)->group(function () {
    Route::get('/master/Customer', 'view');
    Route::post('master/Customer/data', 'TableView');
    Route::get('/master/customer/create', 'create')->name('customer.create');
    Route::get('/master/customer/edit/{CID}', 'edit');
    Route::post('/master/Customer/create', 'save');
    Route::post('master/customer/edit/{CID}', 'update');
    Route::post('/master/customer/delete/{CID}', 'delete');
    Route::get('/master/customer/trash-view/', 'TrashView');
    Route::post('/master/customer/trash-data', 'TrashTableView');
    Route::post('/master/customer/restore/{CID}', 'Restore');
});

// Product controller
Route::controller(Product::class)->group(function () {
    Route::get('/master/Product', 'view');
    Route::post('/master/Product/data', 'TableView');
    Route::get('/master/Product/create', 'create');
    Route::get('/master/Product/edit/{CID}', 'edit');
    Route::post('/master/Product/create', 'save');
    Route::post('/master/Product/edit/{CID}', 'update');
    Route::post('/master/Product/delete/{CID}', 'delete');
    Route::get('/master/Product/trash-view/', 'TrashView');
    Route::post('/master/Product/trash-data', 'TrashTableView');
    Route::post('/master/Product/restore/{CID}', 'Restore');
    Route::post('/master/Product/CategorySelect', 'CategorySelect');
});

// tac / gst controller
Route::controller(gst::class)->group(function () {
    Route::get('/master/tax', 'view');
    Route::get('/master/gst', 'view');
    Route::post('master/gst/data', 'TableView');
    Route::get('master/gst/create', 'create');
    Route::get('/master/gst/edit/{CID}', 'edit');
    Route::post('master/gst/create', 'save');
    Route::post('master/gst/edit/{CID}', 'update');
    Route::post('master/gst/delete/{CID}', 'delete');
    Route::get('master/gst/trash-view/', 'TrashView');
    Route::post('master/gst/trash-data', 'TrashTableView');
    Route::post('master/gst/restore/{CID}', 'Restore');
});

// Supplier completed
Route::controller(SupplierController::class)->group(function () {
    Route::get('/master/suppliers', 'index');
    Route::post('master/suppliers/data', 'tableView')->name('api');
    // trash view 
    Route::get('master/suppliers/trash/', 'trashView')->name('supplier.trash');
    Route::post('master/suppliers/trash-data', 'trashTableView')->name('supplier.trash.api');
    // create and save
    Route::get('master/suppliers/create', 'create')->name('supplier.create');
    Route::post('master/suppliers/create', 'save');
    // edit and update
    Route::get('/master/suppliers/edit/{sid}', 'edit')->name('supplier.edit');
    Route::post('/master/suppliers/edit/{sid}', 'update');
    // delete
    Route::post('master/suppliers/destory/{id}', 'delete')->name('supplier.delete');
    Route::post('master/suppliers/restore/{sid?}', 'restore')->name('supplier.restore');
});

// product
Route::controller(ProductController::class)->group(function () {
    // views
    Route::get('/master/products', 'index');
    Route::post('master/products/data', 'tableView')->name('product.api');
    Route::post('master/products/trash-data', 'trashTableView')->name('product.trash.api');
    Route::get('master/products/trash/', 'trashView')->name('product.trash'); // done

    // Fetch addons
    Route::post('/addons/fetch/tax', 'taxes')->name('product.taxes');
    Route::post('/addons/fetch/categories', 'categories')->name('product.categories');
    Route::post('/addons/fetch/subcategories', 'subCategories')->name('product.subCategories');

    // create  Edit products
    Route::get('master/products/create', 'create')->name('product.create');
    Route::post('master/products/create', 'save');

    Route::get('master/products/edit/{pid}', 'edit')->name('product.edit');
    Route::post('master/products/edit/{pid}', 'update');
    // delete
    Route::post('master/products/destory/{pid}', 'delete')->name('product.delete');
    Route::post('master/products/restore/{pid?}', 'restore')->name('product.restore');
});