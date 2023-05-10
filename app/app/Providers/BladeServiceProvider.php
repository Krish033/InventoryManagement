<?php

namespace App\Providers;

use App\Services\PageHeaderService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class BladeServiceProvider extends ServiceProvider {

    protected $navigationSplitsAt = "InventoryManagement";

    /**
     * Register services.
     */
    public function register(): void {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void {
        // sending the menu name to all blade pages
        $currentUrl = PageHeaderService::page($this->navigationSplitsAt);

        View::share('urls', $currentUrl); // navigation
        View::share('header', PageHeaderService::header()); // header
        View::share('home', PageHeaderService::reachedHome()); // home
    }
}