<?php

namespace App\Http\Controllers\Sales;

use App\Events\LogForDeletedEvents;
use App\Events\LogForRestoredEvents;
use App\Events\LogForStoredEvent;
use App\Events\LogForUpdatedEvents;
use App\Http\Controllers\Controller;

use App\Http\Controllers\logController;
use App\Http\Requests\MainSaleRequest;
use App\Models\DocNum;
use App\Models\general;
use App\Models\ServerSideProcess;
use Illuminate\Database\Query\Builder;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

/**
 * -----------------------------------------------------
 * Krish033
 * -----------------------------------------------------
 * Resource controller matched with defaults
 */

class SaleController extends Controller {

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
    protected const table = "tbl_sales_head";

    /**
     * Primary id for the Table
     */
    protected const primaryId = "tranNo";

    /**
     * Use this document number
     */
    protected const docName = "Sales";

    /**
     * Set views folder
     */
    protected const views = "transactions.sales";

    /**
     * Initialise default args
     */
    public function __construct() {

        $this->activeMenu = self::docName;

        $this->pageTitle = self::docName;

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
            abort(401, "Un Authorized");
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
    public function homeApi(Request $request) {
        if (!$this->can('view')) {
            return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $columns = [
            ['db' => self::primaryId, 'dt' => '0'],
            ['db' => 'tranDate', 'dt' => '1'],
            ['db' => 'customerId', 'dt' => '2'],
            ['db' => 'mop', 'dt' => '3'],
            ['db' => 'taxable', 'dt' => '4'],
            ['db' => 'taxAmount', 'dt' => '5'],
            ['db' => 'totalAmount', 'dt' => '6'],
            ['db' => 'paidAmount', 'dt' => '7'],
            ['db' => 'balanceAmount', 'dt' => '8'],

            ['db' => 'createdBy', 'dt' => '9'],
            ['db' => self::primaryId, 'dt' => '10', "formatter" => function ($d) {
                return "
                <a 
                    href=" . route('sales.update', $d) . "
                    type=\"button\" 
                    data-id=" . $d . " 
                    class=\"btn btn-outline-primary btn-sm editSale\" 
                    data-original-title=\"Edit\"
                >
                    <i class=\"fa fa-pencil\" aria-hidden=\"true\"></i>
                </a>
                <button 
                    type=\"button\" 
                    data-id=" . $d . " 
                    class=\"btn btn-outline-danger btn-sm deleteSale\" 
                    data-original-title=\"Delete\"
                >
                    <i class=\"fa fa-trash\" aria-hidden=\"true\"></i>
                </button>
                ";
            }],
        ];

        return (new ServerSideProcess())->SSP([
            'POSTDATA' => $request,
            'TABLE' => self::table,
            'PRIMARYKEY' => self::primaryId,
            'COLUMNS' => $columns,
            'COLUMNS1' => $columns,
            'GROUPBY' => null,
            'WHERERESULT' => null,
            'WHEREALL' => " dflag = 0 ",
        ]);
    }


    /**
     * Query builder for Tax records
     * @return Builder
     */
    protected function taxBuilder() {
        return DB::table('tbl_tax')
            ->where('DFlag', 0)
            ->where('ActiveStatus', 1);
    }

    /**
     *  GET all tax records
     * @return \Illuminate\Support\Collection
     */
    public function taxes() {
        return $this->taxBuilder()
            ->get(['TaxName', 'TaxPercentage', 'TaxID']);
    }

    /**
     * Get a Single Tax
     * @param mixed $taxId
     * @return void
     */
    public function tax(string $taxId) {
        $this->taxBuilder()
            ->when('TaxID', $taxId)
            ->first(['TaxName', 'TaxPercentage', 'TaxID']);
    }


    /**
     * Query builder for Tax records
     * @return Builder
     */
    protected function productsBuilder(string $productId) {
        return DB::table('tbl_products')
            ->where('dflag', 0)
            ->where('is_active', 1);
    }

    /**
     * GET All tax
     * @param mixed $scid
     * @return \Illuminate\Support\Collection
     */
    public function products(string $scid) {
        return $this->productsBuilder($scid)
            ->where('subCategoryId', $scid)
            ->get();
    }

    /**
     * Fetch a single Product
     * @param mixed $pid
     * @return \Illuminate\Database\Concerns\BuildsQueries|\Illuminate\Database\Eloquent\Model|object|null
     */
    public function product(string $pid) {
        return $this->productsBuilder($pid)
            ->where('pid', $pid)
            ->first();
    }

    /**
     * Categories
     * @return \Illuminate\Support\Collection
     */
    public function categories() {
        return DB::table('tbl_category')
            ->where('DFlag', 0)
            ->where('ActiveStatus', 1)
            ->get();
    }

    /**
     * SUb Categories
     * @return \Illuminate\Support\Collection
     */
    public function subCategories() {
        return DB::table('tbl_subcategory')
            ->where('DFlag', 0)
            ->where('ActiveStatus', 1)
            ->get();
    }

    public function customers() {
        return DB::table('tbl_customer')
            ->where('DFlag', 0)
            ->where('ActiveStatus', 1)
            ->get();
    }


    public function createdProducts(string $tranNo) {
        return DB::table('tbl_sales_details')
            ->where('tranNo', $tranNo)
            ->get();
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        if (!$this->can('add')) {
            abort(401, "Un Authorized");
        }

        return view(self::views . ".create", [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->menus,
            'crud' => $this->crud,
            'ActiveMenuName' => $this->activeMenu,
            'PageTitle' => $this->pageTitle,
            'isEdit' => false,
            'invoiceNumber' => (new DocNum)->getDocNum(self::docName)
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(MainSaleRequest $request) {

        if (!$this->can('add')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        // create doucment number
        $documentNumber = $this->docNum->getDocNum(self::docName);
        // main data to be introduced
        $data = [
            ...$request->except(['products']),
            'tranNo' => $documentNumber,
            'createdBy' => auth()->user()->UserID
        ];

        // start the transactions
        DB::transaction(function () use ($request, $documentNumber, $data) {
            // creating purchasedItems
            collect($request->products)->map(function ($item) use ($documentNumber, $request) {
                // ! the collection does not return anything
                $itemNumber = $this->docNum->getDocNum('SalesItem');
                // udating data
                $purchaseData = [
                    ...$item, // single product
                    'detailId' => $itemNumber,
                    'tranNo' => $documentNumber,
                    'createdAt' => now(),
                ]; // updating docnum
                $this->docNum->updateDocNum('SalesItem'); // updating document number
                LogForStoredEvent::dispatch($purchaseData, 'tbl_sales_details', $request->ip());
            }); // making it a collection to use the map function

            // create logs, create Data
            LogForStoredEvent::dispatch($data, self::table, $request->ip());
            $this->docNum->updateDocNum(self::docName); // updating documnet number
            DB::commit(); // commiting the database trans -> doesnt required usually, the events contain commit itself
        });

        return response()->json(['message' => "Sale created successfully"], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $saId) {
        if (!$this->can('edit')) {
            abort(401, "Un Authorized");
        }

        $mainData = DB::table(self::table)
            ->where(self::primaryId, $saId)
            ->get()
            ->first();

        return view(self::views . ".create", [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->menus,
            'crud' => $this->crud,
            'ActiveMenuName' => $this->activeMenu,
            'PageTitle' => $this->pageTitle,
            'isEdit' => true,
            'invoiceNumber' => $mainData->tranNo,
            'EditData' => $mainData
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $tranNo) {
        if (!$this->can('edit')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $data = [
            ...$request->except(['products']),
            'updatedBy' => auth()->user()->UserID,
            'updatedAt' => now()
        ];

        // start the transactions
        DB::transaction(function () use ($tranNo, $request, $data) {
            // delete previously made products
            DB::table('tbl_sales_details')
                ->where('tranNo', $tranNo)
                ->delete();

            // creating purchasedItems
            collect($request->products)->map(function ($item) use ($tranNo, $request) {
                $itemNumber = $this->docNum->getDocNum('SalesItem');
                // udating data
                $purchaseData = [
                    ...$item, // single product
                    'detailId' => $itemNumber,
                    'tranNo' => $tranNo,
                    'createdAt' => now(),
                ]; // updating docnum

                $this->docNum->updateDocNum('SalesItem'); // updating documnet number
                LogForStoredEvent::dispatch($purchaseData, 'tbl_sales_details', $request->ip());
            }); // making it a collection to use the map function

            // create logs, create Data
            LogForUpdatedEvents::dispatch($data, self::table, $tranNo);
            DB::commit(); // commiting the database trans -> doesnt required usually, the events contain commit itself
        });

        return response()->json(['message' => "Sale updated successfully"], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function trash() {

        if (!$this->can('delete')) {
            // abort(401, "Un Authorized");
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
     * Item has lenght
     * @param mixed $arr
     * @return bool
     */
    protected function hasLength(array $arr) {
        return count($arr) > 0;
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $saId) {
        if (!$this->can('delete')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $databaseDeletionData = (array) DB::table(self::table)
            ->where(self::primaryId, $saId)
            ->get()
            ->toArray()[0];

        if (!$this->hasLength($databaseDeletionData)) {
            return response()->json(['status' => false, 'message' => 'Database record not found']);
        }

        // dispatching delete
        LogForDeletedEvents::dispatch($databaseDeletionData, self::table);
        return response()->json(['message' => "Sale deleted Successfully"], 202);
    }

    /**
     * Fetch table resource for trash table
     */
    public function trashApi() {
        if (!$this->can('view')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $columns = [
            ['db' => self::primaryId, 'dt' => '0'],
            ['db' => 'tranDate', 'dt' => '1'],
            ['db' => 'customerId', 'dt' => '2'],
            ['db' => 'mop', 'dt' => '3'],
            ['db' => 'taxable', 'dt' => '4'],
            ['db' => 'taxAmount', 'dt' => '5'],
            ['db' => 'totalAmount', 'dt' => '6'],
            ['db' => 'paidAmount', 'dt' => '7'],
            ['db' => 'balanceAmount', 'dt' => '8'],

            ['db' => 'createdBy', 'dt' => '9'],
            ['db' => self::primaryId, 'dt' => '10', "formatter" => function ($d, $row) {
                return "
                <button 
                    type=\"button\" 
                    data-id=" . $d . " 
                    class=\"btn btn-outline-danger btn-sm restoreSale\" 
                    data-original-title=\"Delete\"
                >
                    <i class=\"fa fa-rotate\" aria-hidden=\"true\"></i>
                </button>
                ";
            }],
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
    public function restore(string $saId) {

        if (!$this->can('restore')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }
        // fetching item
        $dataToRestore = (array) DB::table(self::table)
            ->where('dFlag', 1)
            ->where(self::primaryId, $saId)
            ->get()
            ->toArray()[0];

        // not found
        if ($this->hasLength($dataToRestore)) {
            // return response()->json(['message' => "Database record does not exists"], 409);
        }

        // dispatching delete
        LogForRestoredEvents::dispatch($dataToRestore, self::table);
        return response()->json(['message' => "Sale restored Successfully"], 202);
    }
}