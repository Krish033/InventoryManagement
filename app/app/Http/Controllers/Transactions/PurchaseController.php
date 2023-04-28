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
    public function homeApi(Request $request) { // crate api
        if (!$this->can('view')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $columns = [
            ['db' => 'tranNo', 'dt' => '0'],
            ['db' => 'tranDate', 'dt' => '1'],
            ['db' => 'invoiceNo', 'dt' => '2'],
            ['db' => 'supplierId', 'dt' => '3'],
            ['db' => 'mop', 'dt' => '4'],
            ['db' => 'taxable', 'dt' => '5'],
            ['db' => 'taxAmount', 'dt' => '6'],
            ['db' => 'TotalAmount', 'dt' => '7'],
            ['db' => 'paidAmount', 'dt' => '8'],
            ['db' => 'balanceAmount', 'dt' => '9'],

            // buttons
            [
                'db' => 'tranNo',
                'dt' => '10',
                'formatter' => function ($d, $row) {

                    $html = '
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
            'POSTDATA' => $request,
            'TABLE' => 'tbl_purchase_head',
            'PRIMARYKEY' => 'tranNo',
            'COLUMNS' => $columns,
            'COLUMNS1' => $columns,
            'GROUPBY' => null,
            'WHERERESULT' => null,
            'WHEREALL' => " dflag = 0 ",
        ]);
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
        // create doucment number
        $documentNumber = $this->docNum->getDocNum(self::docName);
        // main data to be introduced
        $data = [
            ...$request->except(['products']),
            'tranNo' => $documentNumber,
            'createdBy' => auth()->user()->UserID,
            'created_at' => now(),
            'dflag' => false
        ];
        // start the transactions
        DB::transaction(function () use ($request, $documentNumber, $data) {
            // creating purchasedItems
            collect($request->products)->map(function ($item) use ($documentNumber) {
                // ! the collection does not return anything
                $itemNumber = $this->docNum->getDocNum('PurchasedItems');
                // udating data
                $purchaseData = [
                    ...$item, // single product
                    'detailId' => $itemNumber,
                    'tranNo' => $documentNumber,
                    'created_at' => now(),
                ]; // updating docnum
                $this->docNum->updateDocNum('PurchasedItems'); // updating documnet number
                LogForStoredEvent::dispatch($purchaseData, 'tbl_purchase_details');
            }); // making it a collection to use the map function

            // create logs, create Data
            LogForStoredEvent::dispatch($data, 'tbl_purchase_head');
            $this->docNum->updateDocNum(self::docName); // updating documnet number
            DB::commit(); // commiting the database trans -> doesnt required usually, the events contain commit itself
        });

        return response()->json(['status' => true, 'message' => 'Purchase created Successfully']);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
        if (!$this->can('edit')) {
            // abort(401, "Un Authorized");
        }

        return view(self::views . ".main", [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->menus,
            'crud' => $this->crud,
            'ActiveMenuName' => $this->activeMenu,
            'PageTitle' => $this->pageTitle,
            'isEdit' => true,

            'EditData' => DB::table('tbl_purchase_head')
                ->where('tranNo', '=', $id)
                ->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(MainPurchaseRequest $request, string $tranNo) {
        if (!$this->can('edit')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $data = [
            ...$request->except(['products']),
            'updatedBy' => auth()->user()->UserID,
            'dflag' => false
        ];
        // start the transactions
        DB::transaction(function () use ($tranNo, $request, $data) {
            // delete previously made products
            DB::table('tbl_purchase_details')
                ->where('tranNo', $tranNo)
                ->delete();

            // creating purchasedItems
            collect($request->products)->map(function ($item) use ($tranNo) {
                $itemNumber = $this->docNum->getDocNum('PurchasedItems');
                // udating data
                $purchaseData = [
                    ...$item, // single product
                    'detailId' => $itemNumber,
                    'tranNo' => $tranNo,
                    'created_at' => now(),
                ]; // updating docnum

                $this->docNum->updateDocNum('PurchasedItems'); // updating documnet number
                LogForStoredEvent::dispatch($purchaseData, 'tbl_purchase_details');
            }); // making it a collection to use the map function

            // create logs, create Data
            LogForUpdatedEvents::dispatch($data, 'tbl_purchase_head', $tranNo);
            // $this->docNum->updateDocNum(self::docName); // updating documnet number
            DB::commit(); // commiting the database trans -> doesnt required usually, the events contain commit itself
        });
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

        $databaseDeletionData = (array) DB::table('tbl_purchase_head')
            ->where('tranNo', '=', $id)
            ->get()->toArray()[0];

        if (!$this->hasLength($databaseDeletionData)) {
            return response()->json(['status' => false, 'message' => 'Database record not found']);
        }

        LogForDeletedEvents::dispatch($databaseDeletionData, 'tbl_purchase_head');
        return response()->json(['status' => true, 'message' => 'Purchase Moved to Trash']);
    }

    /**
     * Summary of hasLength
     * @param array $arr
     * @return bool
     */
    protected function hasLength(array $arr) {
        return count($arr) > 0;
    }

    /**
     * Summary of trashApi
     * @param Request $request
     * @return array
     */
    public function trashApi(Request $request) {
        if (!$this->can('view')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $columns = [
            ['db' => 'tranNo', 'dt' => '0'],
            ['db' => 'tranDate', 'dt' => '1'],
            ['db' => 'supplierId', 'dt' => '2'],
            ['db' => 'invoiceNo', 'dt' => '3'],
            ['db' => 'mop', 'dt' => '4'],
            ['db' => 'taxable', 'dt' => '5'],
            ['db' => 'taxAmount', 'dt' => '6'],
            ['db' => 'TotalAmount', 'dt' => '7'],
            ['db' => 'paidAmount', 'dt' => '8'],
            ['db' => 'balanceAmount', 'dt' => '9'],

            // buttons
            [
                'db' => 'tranNo',
                'dt' => '10',
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
            'POSTDATA' => $request,
            'TABLE' => 'tbl_purchase_head',
            'PRIMARYKEY' => 'tranNo',
            'COLUMNS' => $columns,
            'COLUMNS1' => $columns,
            'GROUPBY' => null,
            'WHERERESULT' => null,
            'WHEREALL' => " dflag = 1 ",
        ]);
    }

    /**
     * restore a resource
     * @param string $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function restore(string $id) {
        if (!$this->can('restore')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $dataToRestore = (array) DB::table('tbl_purchase_head')
            ->where('dflag', '=', 1)
            ->where('tranNo', '=', $id)
            ->get()->toArray()[0];

        LogForRestoredEvents::dispatch($dataToRestore, 'tbl_purchase_head');
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

    /**
     * Summary of requestProducts
     * @param string $scId
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestProducts(string $scId) {
        return response()->json([...$this->requestProductRecords($scId)]);
    }

    /**
     * Summary of requestSingleProducts
     * @param string $pid
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestSingleProducts(string $pid) {
        return response()->json($this->requestSingleProductRecords($pid));
    }

    /**
     * Summary of requestSuppliers
     * @return \Illuminate\Http\JsonResponse
     */
    public function requestSuppliers() {
        return response()->json($this->requestSupplierRecords());
    }

    /**
     * Summary of requestCreatedProducts
     * @param string $tranNo
     * @return array
     */
    public function requestCreatedProducts(string $tranNo) {
        return DB::table('tbl_purchase_details')
            ->where('tranNo', $tranNo)
            ->get()->toArray();
    }


}