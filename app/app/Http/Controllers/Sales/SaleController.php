<?php

namespace App\Http\Controllers\Sales;

use App\Events\LogForDeletedEvents;
use App\Events\LogForRestoredEvents;
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
    protected const table = "tbl_sales";

    /**
     * Primary id for the Table
     */
    protected const primaryId = "saId";

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


    protected function getSalesRunningStatus($d) {

        $isSalesRunning = DB::table(self::table)
            ->where(self::primaryId, $d)
            ->first();

        $running = 'Not started';

        if (!is_null($isSalesRunning->start)) {
            $running = 'Started';
        }

        if (!is_null($isSalesRunning->end)) {
            $running = "Ended";
        }

        return $running;
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
            ['db' => 'date', 'dt' => '1'],
            ['db' => self::primaryId, 'dt' => '2', "formatter" => function ($d, $row) {
                $item = DB::table(self::table)
                    ->where(self::primaryId, '=', $d)
                    ->pluck('completed')
                    ->first();

                $disable = boolval(intval($item));
                return !$disable
                    ? "<button class=\"btn btn-sm btn-outline-success markSaleAsCompleted\" data-id=" . $d . ">
                            <i class=\"fa fa-check\"></i> Complete
                        </button>"
                    : "
                    <button class=\"btn btn-sm btn-outline-primary markSaleAsCompleted\" data-id=" . $d . ">
                            <i class=\"fa fa-exclamation\"></i> Pending
                    </button>";
            }],

            ['db' => self::primaryId, 'dt' => '3', "formatter" => function ($d) {

                $item = DB::table(self::table)
                    ->where(self::primaryId, $d)
                    ->first();

                $element = is_null($item->start)
                    ? "<button class=\"btn btn-sm btn-outline-dark startSale\" data-id=" . $item->saId . ">
                            <i class=\"fa fa-check\"></i> Start
                        </button>"
                    : "
                    <button disabled class=\"btn btn-sm btn-outline-dark startSale\" data-id=" . $item->saId . ">
                            <i class=\"fa fa-check\"></i> Start
                    </button>";

                $element .= is_null($item->end) && !is_null($item->start)
                    ? "<button class=\"btn btn-sm btn-outline-danger ms-2 endSale\" data-id=" . $item->saId . ">
                            <i class=\"fa fa-close\"></i> End
                        </button>"
                    : "
                    <button disabled class=\"btn btn-sm btn-outline-danger ms-2 endSale\" data-id=" . $item->saId . ">
                            <i class=\"fa fa-close\"></i> End
                    </button>";

                return $element;
            }],

            ['db' => 'created_by', 'dt' => '4'],
            ['db' => self::primaryId, 'dt' => '5', "formatter" => function ($d) {
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

                <a
                    href=" . route('sales.single.home', $d) . "
                    type=\"button\" 
                    data-id=" . $d . "
                    class=\"btn btn-outline-success btn-sm editSale\" 
                    data-original-title=\"Edit\"
                >
                    <i class=\"fa fa-plus\" aria-hidden=\"true\"></i>
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
     * Get the Statistics
     * @param string $saId
     * @return \Illuminate\Support\Collection
     */
    protected function getItemsStats(string $saId) {
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
     * To return stats to api view
     * @param string $saId
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(string $saId) {
        $itemStats = $this->getItemsStats($saId);
        // dd($itemStats);
        return response()->json($itemStats->toArray());
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
     * Assign tax to Elements
     * @param Request $request
     * @param string $saId
     * @return \Illuminate\Http\JsonResponse
     */
    public function recordTax(Request $request, string $saId) {
        if (!$this->can('edit')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        // checking if tax record exists
        $tax = DB::table('tbl_tax')
            ->where('TaxID', '=', $request->tax_id)
            ->count() > 0;

        if (!$tax) {
            return response()->json(['message' => 'Tax not found'], 404);
        }
        // assign tax record
        $purchase = (array) DB::table(self::table)
            ->where(self::primaryId, $saId)
            ->get()
            ->toArray()[0];

        // spreading the data to assign tax id
        $data = [
            ...$purchase, // get the purchase
            'tax_id' => $request->tax_id,
        ];

        LogForUpdatedEvents::dispatch($data, self::table, $saId);
        return response()->json(['message' => 'Tax assigned successfully'], 200);
    }

    /**
     * Update auto update payments
     * @param Request $request
     * @param string $saId
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoUpdatePayments(Request $request, string $saId) {
        $integerValue = $request->auto_update_payment == "true"
            ? 1 // true
            : 0; // if set to true the paymnet can be updated by a single button

        DB::table(self::table)
            ->where(self::primaryId, '=', $saId)
            ->update(['auto_update_payment' => $integerValue]);

        return response()->json(['message' => 'Updated auto update payments']);
    }

    protected function getTaxRecord(string $taxId) {
        return DB::table('tbl_tax')
            ->where('TaxID', $taxId)
            ->first();
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

    public function createPaymentRecords(string $saId) {

        $salesStats = $this->getItemsStats($saId);
        // getting tax record
        if (!$salesStats['sales']['tax_id']) {
            return response()->json(['message' => "Select a tax record!"], 500);
        }

        // find payments 
        $paymentRecordExists = (array) DB::table('tbl_payments')
            ->where('reference_id', '=', $saId)
            ->first();

        // can be edited
        $itemIsEditable = empty($paymentRecordExists);

        // not found
        if ($itemIsEditable == true) {
            $paymentRecordExists = [
                "pyid" => (new DocNum())->getDocNum('Payments'),
            ];
            // updating docnum
            (new DocNum())->updateDocNum('Payments');
        }

        // record need to be uploaded
        $updatedPaymentRecord = [
            ...$paymentRecordExists,
            "date" => $salesStats['sales']['date'],
            "description" => "sold items",
            "category" => "sales",
            "amount" => $salesStats['calculatedPriceTotal'],
            "payment_type" => "income",
            "deleted_by" => 'admin',
            "dflag" => 0,
            'tax_amount' => $salesStats['calculatedPriceTotalIncludingGST'],
            "created_at" => now(),
            "updated_at" => now(),
            "reference_id" => $saId
        ];

        if ($itemIsEditable == true) { // create that item
            DB::table('tbl_payments')
                ->insert($updatedPaymentRecord);
            return response()->json(['message' => "Payment records created"], 201);
        }

        // update that item
        DB::table('tbl_payments')
            ->where('reference_id', '=', $saId)
            ->update($updatedPaymentRecord);
        return response()->json(['message' => "Payment records updated"], 202);
    }

    /**
     * Set item to completed
     * @param string $saId
     * @return \Illuminate\Http\JsonResponse
     */
    public function setCompleted(string $saId) {

        $completed = DB::table(self::table)
            ->where(self::primaryId, $saId)
            ->pluck('completed')
            ->first();

        $completed = !boolval($completed);

        //item
        $item = DB::table(self::table)
            ->where(self::primaryId, '=', $saId)
            ->update(['completed' => $completed]);
        // error
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 500);
        }
        // main
        return response()->json(['message' => 'Action updated successfully'], 202);
    }

    /**
     * Start sale
     * @param string $saId
     * @return \Illuminate\Http\JsonResponse
     */

    public function startSale(string $saId) {

        //item
        $item = DB::table(self::table)
            ->where(self::primaryId, $saId)
            ->update(['start' => now()]);
        // error
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 500);
        }
        // main
        return response()->json(['message' => 'Sale Started'], 202);
    }

    /**
     * End sale
     * @param string $saId
     * @return \Illuminate\Http\JsonResponse
     */
    public function endSale(string $saId) {
        //item
        $item = DB::table(self::table)
            ->where(self::primaryId, $saId)
            ->update(['end' => now()]);
        // error
        if (!$item) {
            return response()->json(['message' => 'Item not found'], 500);
        }
        // main
        return response()->json(['message' => 'Sale Ended'], 202);
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
        ]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) {

        if (!$this->can('add')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        // validating request since nly less number of fields
        $formFields = $request->validate([
            'date' => 'required',
            'start' => 'nullable',
            'end' => 'nullable'
        ]);

        LogForStoredEvent::dispatch([
            ...$formFields,
            'saId' => (new DocNum())->getDocNum('Sales'),
            'created_by' => auth()->user()->UserID
        ], self::table);

        (new DocNum())->updateDocNum('Sales');

        return response()->json(['message' => "Sale created successfully"], 201);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $saId) {
        if (!$this->can('edit')) {
            abort(401, "Un Authorized");
        }

        return view(self::views . ".create", [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->menus,
            'crud' => $this->crud,
            'ActiveMenuName' => $this->activeMenu,
            'PageTitle' => $this->pageTitle,
            'isEdit' => true,
            'EditData' => DB::table(self::table)
                ->where(self::primaryId, $saId)
                ->get()
                ->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $saId) {
        if (!$this->can('edit')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        // validating request since nly less number of fields
        $formFields = $request->validate([
            'date' => 'nullable',
            'start' => 'nullable',
            'end' => 'nullable'
        ]);

        LogForUpdatedEvents::dispatch([
            ...$formFields,
            'updated_by' => auth()->user()->UserID
        ], self::table, $saId);


        return response()->json(['message' => "Sale updated successfully"], 201);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function trash() {
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
    public function destroy(string $saId) {
        if (!$this->can('delete')) {
            return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $item = (array) DB::table(self::table)
            ->where(self::primaryId, $saId)
            ->get()
            ->toArray()[0];

        if (is_null($item)) {
            return response()->json(['message' => "Item not found"], 500);
        }
        // dispatching delete
        LogForDeletedEvents::dispatch($item, self::table);
        return response()->json(['message' => "Sale deleted Successfully"], 202);
    }

    /**
     * Fetch table resource for trash table
     */
    public function trashApi() {
        if (!$this->can('view')) {
            return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $columns = [
            ['db' => self::primaryId, 'dt' => '0'],
            ['db' => 'date', 'dt' => '1'],
            ['db' => 'completed', 'dt' => '2', "formatter" => function ($d, $row) {
                $disable = boolval(intval($d));
                return !$disable
                    ? "Completed"
                    : "Incomplete";
            }],

            ['db' => 'deleted_by', 'dt' => '3'],
            ['db' => self::primaryId, 'dt' => '4', "formatter" => function ($d, $row) {
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
            return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }
        // fetching item
        $item = (array) DB::table(self::table)
            ->where('dflag', 1)
            ->where(self::primaryId, $saId)
            ->get()
            ->toArray()[0];

        // not found
        if (is_null($item)) {
            return response()->json(['message' => "Item not found"], 500);
        }
        // dispatching delete
        LogForRestoredEvents::dispatch($item, self::table);
        return response()->json(['message' => "Sale restored Successfully"], 201);
    }
}