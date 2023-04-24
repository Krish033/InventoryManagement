<?php

namespace App\Http\Controllers\Sales;

use App\Events\LogForDeletedEvents;
use App\Events\LogForStoredEvent;
use App\Events\LogForUpdatedEvents;
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

class SaleItemController extends Controller {

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
    protected const table = "tbl_sales_items";

    /**
     * Primary id for the Table
     */
    protected const primaryId = "siId";

    /**
     * Use this document number
     */
    protected const docName = "SaleItem";

    /**
     * Set views folder
     */
    protected const views = "transactions.sales";

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
    public function index(string $saId) {
        if (!$this->can('view')) {
            // abort(401, "Un Authorized");
        }

        /**
         * Data to be send to views
         */
        return view(self::views . ".single", [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->menus,
            'crud' => $this->crud,
            'ActiveMenuName' => $this->activeMenu,
            'PageTitle' => $this->pageTitle,

            'sales' => DB::table('tbl_sales')
                ->where('saId', $saId)
                ->first()
        ]);
    }

    /**
     * Fetch table resource
     */
    public function homeApi(Request $request) {
        if (!$this->can('view')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $columns = [
            ['db' => 'name', 'dt' => '0'],
            ['db' => 'salesRate', 'dt' => '1'],
            ['db' => 'pid', 'dt' => '2', "formatter" => function ($d) {
                return "<button class=\"btn btn-sm btn-outline-success addProducts\" data-id=" . $d . " >
                    <i class=\"fa fa-plus\"></i>
                </button>";
            }],
        ];

        return (new ServerSideProcess())->SSP([
            'POSTDATA' => $request,
            'TABLE' => 'tbl_products',
            'PRIMARYKEY' => 'pid',
            'COLUMNS' => $columns,
            'COLUMNS1' => $columns,
            'GROUPBY' => null,
            'WHERERESULT' => null,
            'WHEREALL' => "dflag = 0 ",
        ]);
    }

    /**
     * Fetch table resource
     */
    public function mainApi(Request $request, string $saId) {
        if (!$this->can('view')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $columns = [
            ['db' => 'siId', 'dt' => '0'],
            ['db' => 'saId', 'dt' => '1'],
            ['db' => 'amount', 'dt' => '2'],
            [
                'db' => 'siId',
                'dt' => '3',
                'formatter' => function ($d, $row) {
                    $html = '';
                    // if (boolval($this->can('edit'))) {
                    $html .= '
                        <a type="button" 
                            href="' . route('sales.single.update', $d) . '" 
                            data-id="' . $d . '" 
                            class="btn btn-outline-success btn-sm -success me-1 editSalesItem" 
                            id="supplierViewEditBtn" 
                            data-original-title="Edit"
                        >
                            <i class="fa fa-pencil"></i>
                        </a>';

                    // }
                    // if (boolval($this->can('delete'))) {
                    $html .= '
                        <button 
                            type="button" 
                            data-id="' . $d . '" 
                            class="btn btn-outline-warning btn-sm deleteSalesItem" 
                            data-original-title="Delete"
                        >
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>';
                    // }
                    return $html;
                }
            ],
        ];

        return (new ServerSideProcess())->SSP([
            'POSTDATA' => $request,
            'TABLE' => 'tbl_sales_items',
            'PRIMARYKEY' => 'siId',
            'COLUMNS' => $columns,
            'COLUMNS1' => $columns,
            'GROUPBY' => null,
            'WHERERESULT' => " saId = '$saId' ",
            'WHEREALL' => "dflag = 0 ",
        ]);
    }


    /**
     * Summary of stats
     * @param string $puid
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(string $puid) {
        $itemStats = $this->getItemsStats($puid);
        // dd($itemStats);
        return response()->json($itemStats->toArray());
    }

    protected function getTaxRecord(string $taxId) {
        return DB::table('tbl_tax')
            ->where('TaxID', $taxId)
            ->first();
    }

    public function getItemsStats(string $saId) {

        $sales = (array) DB::table('tbl_sales')
            ->where('saId', $saId)
            ->get()
            ->toArray()[0];

        $salesItemsBuilder = DB::table('tbl_sales_items')
            ->where('saId', $saId) // array
            ->where('dflag', 0);

        // dd($salesItemsBuilder->get());

        $salesCount = $salesItemsBuilder->count();

        $salesItemsPrice = $this->getSaleItemPrice($salesItemsBuilder, 'amount'); // totalled amount
        // tax record
        $taxInPercentage = $this->getTaxRecord($sales['tax_id']);
        // calculating gst formula = (totalAmount * gst percent) / 100 
        $taxable = (intval($salesItemsPrice) * intval($taxInPercentage->TaxPercentage)) / 100; // get taxable 

        return collect([
            'tax' => $taxInPercentage->TaxPercentage,
            'sales' => $sales,
            'calculatedPriceTotal' => $salesItemsPrice,
            'calculatedPriceTotalIncludingGST' => intval($taxable + $salesItemsPrice),
            'salesCount' => $salesCount,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(string $saId) {
        if (!$this->can('add')) {
            // abort(401, "Un Authorized");
        }

        $sales = DB::table('tbl_sales')
            ->where('saId', $saId)
            ->first();

        if (is_null($sales->tax_id)) {
            return redirect()->back()->with(['error' => 'TaxID is required']);
        }

        return view(self::views . ".single-create", [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->menus,
            'crud' => $this->crud,
            'ActiveMenuName' => $this->activeMenu,
            'PageTitle' => $this->pageTitle,
            'isEdit' => false,
            'sales' => $sales
        ]);
    }

    /**
     * Summary of product
     * @param mixed $pid
     * @return \Illuminate\Database\Concerns\BuildsQueries|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function product($pid) {
        return DB::table('tbl_products')
            ->where('pid', $pid)
            ->get(['pid', 'name', 'salesRate'])
            ->first();
    }

    public function customers() {
        return DB::table('tbl_customer')
            ->get(['CName', 'CID']);
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
    protected function getSaleItemPrice(Builder $data, string $column = 'salesRate') {
        $array = $data->pluck($column)->toArray();
        return array_sum($array);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request, $saId) {
        if (!$this->can('add')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $siId = (new DocNum())->getDocNum(self::docName);
        (new DocNum())->updateDocNum(self::docName); // updating document number

        collect($request['data'])->map(function ($item) use ($siId) {
            return DB::table('tbl_sold_products')
                ->insert([
                    ...$item,
                    'siId' => $siId,
                    'created_at' => now(),
                ]); // looping and creating records
        });
        // getting item price
        $amount = $this->getSaleItemPrice($this->getSaleItem($siId));

        $data = [
            'date' => '2019-02-11',
            'customer_id' => $request['customer_id'] == "defaultCustomer" ? null : $request['customer_id'],
            'siId' => $siId,
            'saId' => $saId,
            'amount' => $amount,
            'created_at' => now()
        ];
        // creating item
        LogForStoredEvent::dispatch($data, self::table);
        return response()->json(['message' => 'Sale added successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $siId) {
        if (!$this->can('edit')) {
            // abort(401, "Un Authorized");
        }

        $single = DB::table('tbl_sales_items')
            ->where('siId', $siId)
            ->first();

        $sales = DB::table('tbl_sales_items')
            ->where('saId', $single->saId)
            ->first();

        return view(self::views . ".single-create", [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->menus,
            'crud' => $this->crud,
            'ActiveMenuName' => $this->activeMenu,
            'PageTitle' => $this->pageTitle,
            'isEdit' => true,
            'EditData' => [],
            'sales' => $sales,
            'single' => $single
        ]);
    }


    public function getSingleProducts(string $siId) {
        $products = DB::table('tbl_sold_products')
            ->where('siId', $siId)
            ->get();

        return [
            ...$products->map(function ($item) {
                return [
                    // ...$item,
                    "name" => DB::table('tbl_products')
                        ->where('pid', $item->pid)
                        ->pluck('name')
                        ->first(),
                    'pid' => $item->pid,
                    'salesRate' => $item->salesRate,
                    'quantity' => $item->quantity,
                ];
            })
        ];
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $siId) {
        if (!$this->can('edit')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $main = DB::table(self::table)
            ->where(self::primaryId, $siId)
            ->first();

        // get products
        $products = DB::table('tbl_sold_products')
            ->where('siId', $siId)
            ->get();

        $products->map(function ($item) {
            return DB::table('tbl_sold_products')
                ->where('id', $item->id)
                ->delete();
        });

        $data = [
            'date' => '2019-02-11',
            'customer_id' => $request['customer_id'] == "defaultCustomer" ? null : $request['customer_id'],
            'siId' => $siId,
            'saId' => $main->saId,
            'amount' => $main->amount,
            'updated_at' => now()
        ];

        // deleting old data
        $old = DB::table('tbl_sold_products')
            ->where('siId', $siId)
            ->get();

        // bulk delete
        $old->map(function ($item) {
            return DB::table('tbl_sold_products')
                ->when('id', $item->id)
                ->delete();
        });

        // sold items
        $soldItems = collect($request['data']);

        $soldItems->map(function ($item) use ($data) {
            return DB::table('tbl_sold_products')
                ->insert([
                    ...$item,
                    'siId' => $data['siId'],
                    'created_at' => now(),
                ]);
        });

        LogForUpdatedEvents::dispatch($data, self::table, $siId);
        return response()->json(['message' => 'Sale updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function trash(string $id) {
        if (!$this->can('delete')) {
            abort(401, "Un Authorized");
        }

        return view(self::views . ".trash", [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->menus,
            'crud' => $this->crud,
            'ActiveMenuName' => $this->activeMenu,
            'PageTitle' => $this->pageTitle,
        ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $siId) {
        if (!$this->can('delete')) {
            return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $itemArray = (array) DB::table(self::table)
            ->where(self::primaryId, $siId)
            ->get()
            ->toArray()[0];

        LogForDeletedEvents::dispatch([
            ...$itemArray,
            'updated_at' => now()
        ], self::table);

        return response()->json(['message' => 'Sale item deleted successfully']);
    }

    /**
     * Fetch table resource for trash table
     */
    public function trashApi() {
        if (!$this->can('view')) {
            return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $columns = [
            // ['db' => '', 'dt' => '', formatter => function () {}]
        ];

        return (new ServerSideProcess())->SSP([
            'POSTDATA' => [],
            'TABLE' => self::table,
            'PRIMARYKEY' => self::primaryId,
            'COLUMNS' => $columns,
            'COLUMNS1' => $columns,
            'GROUPBY' => null,
            'WHERERESULT' => null,
            'WHEREALL' => " dflag = 1 ",
        ]);
    }


    /**
     * Restore specific resource
     */
    public function restore(string $id) {
        if (!$this->can('restore')) {
            return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }
    }
}