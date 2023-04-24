<?php

use App\Http\Controllers\loginController;
use App\Http\Controllers\generalController;
use App\Http\Controllers\dashboardController;
use Illuminate\Support\Facades\Route;

require __DIR__ . '/auth.php';
require __DIR__ . '/master.php';
require __DIR__ . '/reports.php';
require __DIR__ . '/users.php';
require __DIR__ . '/transactions.php';

Route::controller(loginController::class)->group(function () {
    Route::post('/Clogin', 'login');
})->middleware(['guest']);

Route::controller(dashboardController::class)->group(function () {
    // Route::get('/', 'dashboard');
    Route::get('/dashboard', 'dashboard');
})->middleware(['auth']);

Route::controller(generalController::class)->group(function () {
    Route::post('/Set/Theme/Update', 'ThemeUpdate');
    Route::post('/get/getMenus', 'getMenus');
    Route::post('/get/getMenusData', 'getMenuData');
    Route::post('/Get/Country', 'GetCountry');
    Route::post('Get/States', 'GetState');
    Route::post('Get/Gender', 'GetGender');
    Route::post('/Get/City', 'GetCity');
    Route::post('Get/PostalCode', 'getPostalCode');
    Route::post('Get/Role', 'RoleData');
});