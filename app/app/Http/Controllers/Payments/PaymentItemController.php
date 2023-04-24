<?php

namespace App\Http\Controllers\Payments;

use App\Http\Controllers\Controller;

use App\Http\Controllers\logController;
use App\Models\DocNum;
use App\Models\general;
use App\Models\ServerSideProcess;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * -----------------------------------------------------
 * Krish033
 * -----------------------------------------------------
 * Resource controller matched with defaults
 */

class PaymentItemController extends Controller {

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
    protected const table = "tbl_manual_payment_items";

    /**
     * Primary id for the Table
     */
    protected const primaryId = "id";

    /**
     * Use this document number
     */
    protected const docName = "";

    /**
     * Set views folder
     */
    protected const views = "transactions.payments";

    /**
     * Initialise default args
     */
    public function __construct() {

        $this->activeMenu = "";

        $this->pageTitle = "";

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
        ]);
    }

    /**
     * Fetch table resource
     */
    public function homeApi(Request $request, string $pyid) {
        if (!$this->can('view')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $columns = [
            ['db' => 'pyid', 'dt' => '0'],
            ['db' => 'name', 'dt' => '1'],
            ['db' => 'amount', 'dt' => '2'],
            ['db' => 'quantity', 'dt' => '3'],
            ['db' => self::primaryId, 'dt' => '4', "formatter" => function ($d) {
                return "
                <button 
                    type=\"button\" 
                    data-id=" . $d . " 
                    class=\"btn btn-outline-danger btn-sm deletePaymentButton\" 
                    data-original-title=\"Delete\"
                >
                    <i class=\"fa fa-trash\" aria-hidden=\"true\"></i>
                </button>
                ";
            }]
        ];

        return (new ServerSideProcess())->SSP([
            'POSTDATA' => $request,
            'TABLE' => self::table,
            'PRIMARYKEY' => self::primaryId,
            'COLUMNS' => $columns,
            'COLUMNS1' => $columns,
            'GROUPBY' => null,
            'WHERERESULT' => " pyid = '$pyid' ",
            'WHEREALL' => null,
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, string $pyid) {
        if (!$this->can('add')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }
        // calculate quantity price
        $qauntifiedAmount = intval($request['amount']) * intval($request['quantity']);

        DB::table(self::table)->insert([
            ...$request->except(['amount']),
            'amount' => $qauntifiedAmount,
            'pyid' => $pyid,
            'created_at' => now()
        ]);

        return response()->json(['message' => "Item created successfully"], 201);
    }


    /**
     * Summary of builder
     * @param mixed $id
     * @param mixed $column
     * @return \Illuminate\Database\Query\Builder
     */
    protected function builder($id, $column = self::primaryId) {
        return DB::table(self::table)
            ->where($column, $id);
    }


    /**
     * get tax record Api
     * @return array
     */
    public function taxes() {
        return DB::table('tbl_tax')
            ->where('DFlag', 0)
            ->get()
            ->toArray();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        if (!$this->can('delete')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $this->builder($id)->delete(); // deleting
        return response()->json(['message' => "Item Deleted successfully"], 202);
    }


    /**
     * Get the Statistics
     * @param string $pyid
     * @return \Illuminate\Support\Collection
     */
    public static function getItemsStats(string $pyid) {
        $sales = (array) DB::table('tbl_payments')
            ->where('pyid', $pyid)
            ->get()
            ->toArray()[0];

        $salesItemsBuilder = DB::table('tbl_manual_payment_items')
            ->where('pyid', $pyid);

        // dd($salesItemsBuilder->get());

        $salesCount = $salesItemsBuilder->count();

        $salesItemsPrice = self::getSaleItemPrice($salesItemsBuilder, 'amount'); // totalled amount



        return collect([
            'sales' => $sales,
            'calculatedPriceTotal' => $salesItemsPrice,
            'purchaseCount' => $salesCount,
        ]);
    }

    /**
     * To return stats to api view
     * @param string $pyid
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(string $pyid) {
        $itemStats = self::getItemsStats($pyid);
        // dd($itemStats);
        return response()->json($itemStats->toArray());
    }

    /**
     * Summary of getSaleItem
     * @param string $siId
     * @return \Illuminate\Database\Query\Builder
     */
    protected function getSaleItem(string $siId) {
        return DB::table('tbl_sold_products')
            ->where('siId', $siId);
    }

    /**
     * Summary of getSaleItemPrice
     * @param Builder $data
     * @return mixed
     */
    protected static function getSaleItemPrice(Builder $data, string $column = 'salesRate') {
        $array = $data->pluck($column)->toArray();
        return array_sum($array);
    }

}