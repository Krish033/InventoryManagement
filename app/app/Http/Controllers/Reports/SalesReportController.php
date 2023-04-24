<?php

namespace App\Http\Controllers\Reports;

use App\Http\Controllers\Controller;

use App\Http\Controllers\logController;
use App\Models\DocNum;
use App\Models\general;
use App\Models\ServerSideProcess;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * -----------------------------------------------------
 * Krish033
 * -----------------------------------------------------
 * Resource controller matched with defaults
 */

class SalesReportController extends Controller {

    private $general;
    private $docNum;
    private $userId;
    private $activeMenu;
    private $pageTitle;
    private $crud;
    private $logs;
    private $settings;
    private $menus;

    /**
     * Tabel name to use
     */
    protected const table = "";

    /**
     * Primary id for the Table
     */
    protected const primaryId = "";

    /**
     * Use this document number
     */
    protected const docName = "";

    /**
     * Set views folder
     */
    protected const views = "reports.sales";

    /**
     * Initialise default args
     */
    public function __construct() {

        $this->activeMenu = "Sales";

        $this->pageTitle = "Sales";

        $this->docNum = new DocNum();

        /**
         * middleware to bring auth in
         */
        $this->middleware(['auth', function ($request, $next) {

            $this->userId = auth()->user()->UserID;
            $this->general = new general($this->userId, $this->activeMenu);

            $this->menus = $this->general->loadMenu();
            $this->crud = $this->general->getCrudOperations($this->activeMenu);

            $this->logs = new logController();
            $this->settings = $this->general->getSettings();

            return $next($request);
        }]);
    }

    protected function can(string $permission): bool {
        return $this->general->isCrudAllow($this->crud, $permission);
    }

    /**
     * Display a listing of the resource.
     */
    public function index() {
        if (!$this->can('view')) {
            // abort(401, "Un Authorized");
        }

        /**
         * Data to be send to views
         */
        return view(self::views . ".index", [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->menus,
            'crud' => $this->crud,
            'ActiveMenuName' => $this->activeMenu,
            'PageTitle' => $this->pageTitle,

            'purchase' => $this->getTotalSales()
        ]);
    }

    protected function getTotalSales() {
        // total amount
        $purchaseBuilder = DB::table('tbl_sales_items')->where('dflag', 0);

        $thisYearPurchase = $purchaseBuilder->whereBetween('created_at', [
            now()->startOfYear(),
            now()->endOfYear()
        ]);

        return [
            'purchasePerMonth' => array_sum($purchaseBuilder->pluck('amount')->toArray()),
            'purchaseQty' => $purchaseBuilder->count(),
            'thisYearPurchase' => array_sum($thisYearPurchase->pluck('amount')->toArray()),
            'itemsSoldPerYear' => $thisYearPurchase->count(),
        ];
    }

    protected function builder(string $table = 'tbl_sales') {
        return DB::table($table)
            ->where('dflag', 0);
    }

}