<?php

namespace App\Providers;

use App\Http\Controllers\logController;
use App\Models\DocNum;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\ServiceProvider;

class MacroServiceProvider extends ServiceProvider {
    /**
     * Register services.
     */
    public function register(): void {
        //
    }


    public function boot(): void {

        /**
         *  Creat a recird with Logs included
         *  @param array $data
         * 
         */

        Builder::macro('make', function (array $data) {

        });
    }
}