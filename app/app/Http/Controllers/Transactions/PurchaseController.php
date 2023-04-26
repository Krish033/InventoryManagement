<?php

namespace App\Http\Controllers\Transactions;

use App\Events\LogForDeletedEvents;
use App\Events\LogForRestoredEvents;
use App\Events\LogForStoredEvent;
use App\Events\LogForUpdatedEvents;
use App\Http\Controllers\Controller;

use App\Http\Controllers\logController;
use App\Http\Requests\Purchase\MainPurchaseRequest;
use App\Http\Requests\Transactions\PurchaseCreateRequest;
use App\Http\Requests\Transactions\PurchaseUpdateRequest;
use App\Models\DocNum;
use App\Models\general;
use App\Models\ServerSideProcess;
use App\Services\Purchase;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;

use function PHPSTORM_META\map;

/**
 * -----------------------------------------------------
 *# Krishna
 * -----------------------------------------------------
 * Resource controller matched with defaults
 */

class PurchaseController extends Purchase {

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
    protected const table = "tbl_purchases";

    /**
     * Primary id for the Table
     */
    protected const primaryId = "puid";

    /**
     * Use this document number
     */
    protected const docName = "Purchases";

    /**
     * Set views folder
     */
    protected const views = "transactions.purchase";

    /**
     * Initialise default args
     */
    public function __construct() {

        $this->activeMenu = "Purchase";

        $this->pageTitle = "Purchase";

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
    public function index() { // conpleted
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
    public function homeApi() { // crate api
        if (!$this->can('view')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $columns = [
            ['db' => 'puid', 'dt' => '0'],
            ['db' => 'date', 'dt' => '1'],
            ['db' => 'name', 'dt' => '2'],
            // is active column
            ['db' => 'is_active', 'dt' => '3', "formatter" => function ($d, $row) {
                return $d == '1'
                    ? "<span class='badge badge-success m-1'>Active</span>"
                    : "<span class='badge badge-danger m-1'>Inactive</span>";
            }],
            // created by
            ['db' => 'created_by', 'dt' => '4'],
            // buttons
            [
                'db' => 'puid',
                'dt' => '5',
                'formatter' => function ($d, $row) {
                    $html = '';
                    // if (boolval($this->can('edit'))) {
                    $html .= '
                        <a type="button" 
                            href="' . route('purchase.update', $d) . '" 
                            data-id="' . $d . '" 
                            class="btn btn-outline-success btn-sm -success me-1 editPurchase" 
                            id="supplierViewEditBtn" 
                            data-original-title="Edit"
                        >
                            <i class="fa fa-pencil"></i>
                        </a>';

                    $html .= '
                        <a type="button" 
                            href="' . route('purchase.item.home', $d) . '" 
                            data-id="' . $d . '" 
                            class="btn btn-outline-primary btn-sm -success me-1 viewPurchase" 
                            id="supplierViewEditBtn" 
                            data-original-title="Edit"
                        >
                            <i class="fa fa-eye"></i>
                        </a>
                    ';
                    // }
                    // if (boolval($this->can('delete'))) {
                    $html .= '
                        <button 
                            type="button" 
                            data-id="' . $d . '" 
                            class="btn btn-outline-danger btn-sm deletePurchase" 
                            data-original-title="Delete"
                        >
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>';
                    // }
                    return $html;
                }
            ],
        ];

        return (new ServerSideProcess())->SSP([
            'POSTDATA' => [],
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
     * @param string $puid
     * @return \Illuminate\Support\Collection
     */
    protected function getItemsStats(string $puid) {
        // getting data
        $purchase = (array) DB::table('tbl_purchases')
            ->where('puid', '=', $puid)
            ->get()
            ->toArray()[0];

        // get purchase count
        $purchaseCount = DB::table('tbl_purchased_items')
            ->where('purchase_id', '=', $puid)
            ->where('dflag', 0)
            ->count();

        // get price value
        $purchasePriceTotalArray = DB::table('tbl_purchased_items')
            ->where('purchase_id', '=', $puid)
            ->where('dflag', 0)
            ->pluck('amount')
            ->toArray();

        $calculatedPriceTotal = array_sum($purchasePriceTotalArray);

        // calculating gst
        $taxId = $purchase['tax_id'];
        $taxValueInPercent = DB::table('tbl_tax')
            ->where('TaxID', $taxId)
            ->pluck('TaxPercentage')
            ->first();

        $tax = ($calculatedPriceTotal * intval($taxValueInPercent)) / 100;
        $calculatedPriceTotalIncludingGST = $calculatedPriceTotal + $tax;

        return collect([
            'purchase' => $purchase,
            'calculatedPriceTotal' => $calculatedPriceTotal,
            'purchaseCount' => $purchaseCount,
            'calculatedPriceTotalIncludingGST' => $calculatedPriceTotalIncludingGST,
            'tax' => $taxValueInPercent
        ]);
    }

    /**
     * To return stats to api view
     * @param string $puid
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(string $puid) {
        $itemStats = $this->getItemsStats($puid);
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
     * @param string $puid
     * @return \Illuminate\Http\JsonResponse
     */
    public function recordTax(Request $request, string $puid) {
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
            ->where('puid', $puid)
            ->get()
            ->toArray()[0];

        // spreading the data to assign tax id
        $data = [
            ...$purchase, // get the purchase
            'tax_id' => $request->tax_id,
        ];

        LogForUpdatedEvents::dispatch($data, self::table, $puid);
        return response()->json(['message' => 'Tax assigned successfully'], 200);
    }

    /**
     * Update auto update payments
     * @param Request $request
     * @param string $puid
     * @return \Illuminate\Http\JsonResponse
     */
    public function autoUpdatePayments(Request $request, string $puid) {
        $integerValue = $request->auto_update_payment == "true"
            ? 1 // true
            : 0; // if set to true the paymnet can be updated by a single button

        DB::table(self::table)
            ->where('puid', '=', $puid)
            ->update(['auto_update_payment' => $integerValue]);

        return response()->json(['message' => 'Updated auto update payments']);
    }

    public function createPaymentRecords(string $puid) {

        $purchaseStats = $this->getItemsStats($puid);
        // getting tax record
        if (!$purchaseStats['purchase']['tax_id']) {
            return response()->json(['message' => "Select a tax record!"], 500);
        }

        // find payments 
        $paymentRecordExists = (array) DB::table('tbl_payments')
            ->where('reference_id', '=', $puid)
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
            "date" => $purchaseStats['purchase']['date'],
            "description" => "",
            "category" => "purchase",
            "amount" => $purchaseStats['calculatedPriceTotal'],
            "payment_type" => "expense",
            "deleted_by" => 'admin',
            "dflag" => 0,
            'tax_amount' => $purchaseStats['calculatedPriceTotalIncludingGST'],
            "created_at" => now(),
            "updated_at" => now(),
            "reference_id" => $puid
        ];

        if ($itemIsEditable == true) { // create that item
            DB::table('tbl_payments')
                ->insert($updatedPaymentRecord);
            return response()->json(['message' => "Payment records created"], 201);
        }

        // update that item
        DB::table('tbl_payments')
            ->where('reference_id', '=', $puid)
            ->update($updatedPaymentRecord);
        return response()->json(['message' => "Payment records updated"], 202);
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create() {
        if (!$this->can('add')) {
            // abort(401, "Un Authorized");
        }

        return view(self::views . ".main", [
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
    public function store(MainPurchaseRequest $request) {
        if (!$this->can('add')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $documentNumber = $this->docNum->getDocNum(self::docName);

        $data = [
            ...$request->except(['products']),
            'tranNo' => $documentNumber,
            'createdBy' => auth()->user()->UserID,
            'created_at' => now(),
            'dflag' => false
        ];

        collect($request->products)->map(function ($item) use ($documentNumber) {
            $itemNumber = $this->docNum->getDocNum('PurchasedItems');
            $purchaseData = [
                ...$item,
                'detailId' => $itemNumber,
                'tranNo' => $documentNumber,
                'created_at' => now(),
            ];

            $this->docNum->updateDocNum('PurchasedItems'); // updating documnet number
            LogForStoredEvent::dispatch($purchaseData, 'tbl_purchase_details');
        });

        // create logs, create Data
        LogForStoredEvent::dispatch($data, 'tbl_purchase_head');

        $this->docNum->updateDocNum(self::docName); // updating documnet number
        return response()->json(['status' => true, 'message' => 'Purchase created Successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        if (!$this->can('edit')) {
            // abort(401, "Un Authorized");
        }

        return view(self::views . ".create", [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->menus,
            'crud' => $this->crud,
            'ActiveMenuName' => $this->activeMenu,
            'PageTitle' => $this->pageTitle,
            'isEdit' => true,

            'EditData' => DB::table(self::table)
                ->where(self::primaryId, '=', $id)
                ->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PurchaseUpdateRequest $request, string $id) {
        if (!$this->can('edit')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $data = [
            ...$request->validated(),
            'updated_by' => auth()->user()->UserID,
        ];

        // create logs, create Data
        LogForUpdatedEvents::dispatch($data, self::table, $id);
        return response()->json(['status' => true, 'message' => 'Purchase Updated Successfully']);
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
     * Remove the specified resource from storage.
     */
    public function destroy(string $id) {
        if (!$this->can('delete')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $databaseDeletionData = (array) DB::table(self::table)
            ->where(self::primaryId, '=', $id)
            ->get()->toArray()[0];

        if (!$this->hasLength($databaseDeletionData)) {
            return response()->json(['status' => false, 'message' => 'Database record not found']);
        }

        LogForDeletedEvents::dispatch($databaseDeletionData, self::table);
        return response()->json(['status' => true, 'message' => 'Purchase Moved to Trash']);
    }

    protected function hasLength(array $arr) {
        return count($arr) > 0;
    }

    /**
     * Fetch table resource for trash table
     */
    public function trashApi() {
        if (!$this->can('view')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $columns = [
            ['db' => 'puid', 'dt' => '0'],
            ['db' => 'date', 'dt' => '1'],
            ['db' => 'name', 'dt' => '2'],
            // is active column
            ['db' => 'is_active', 'dt' => '3', "formatter" => function ($d, $row) {
                return $d == '1'
                    ? "<span class='badge badge-success m-1'>Active</span>"
                    : "<span class='badge badge-danger m-1'>Inactive</span>";
            }],
            // created by
            ['db' => 'created_by', 'dt' => '4'],
            // buttons
            [
                'db' => 'puid',
                'dt' => '5',
                'formatter' => function ($d, $row) {
                    $html = '
                    <button 
                        type="button" 
                        data-id="' . $d . '" 
                        class="btn btn-outline-success btn-sm  m-2 restorePurchase"
                    >
                        <i class="fa fa-rotate" aria-hidden="true"></i>
                    </button>';
                    // }
                    return $html;
                }
            ],
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
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $dataToRestore = (array) DB::table(self::table)
            ->where('dflag', '=', 1)
            ->where(self::primaryId, '=', $id)
            ->get()->toArray()[0];

        LogForRestoredEvents::dispatch($dataToRestore, self::table);
        return response()->json(['status' => true, 'message' => 'Purchase Restored Successfully']);
    }

    /**
     * Summary of requesttaxes
     * @return \Illuminate\Http\JsonResponse
     */
    public function requesttaxes() {
        return response()->json([...$this->requestTaxRecords()]);
    }

    /**
     * Summary of requesttax
     * @param string $taxId
     * @return \Illuminate\Http\JsonResponse
     */
    public function requesttax(string $taxId) {
        return response()->json($this->requestSingleTaxRecords($taxId));
    }

    /**
     * Summary of requestCategories
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestCategories() {
        return response()->json([...$this->requestCategoryRecords()], 200);
    }

    /**
     * Summary of requestSubcategories
     * @param string $cid
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestSubcategories(string $cid) {
        return response()->json([...$this->requestSubCategoryRecords($cid)]);
    }

    public function requestProducts(string $scId) {
        return response()->json([...$this->requestProductRecords($scId)]);
    }

    public function requestSingleProducts(string $pid) {
        return response()->json($this->requestSingleProductRecords($pid));
    }
    public function requestSuppliers() {
        return response()->json($this->requestSupplierRecords());
    }

    public function recordPerchase(MainPurchaseRequest $mainPurchaseRequest) {
        dd($mainPurchaseRequest);
        return response()->json(['message' => 'Ppurchase created successfully'], 201);
    }
}