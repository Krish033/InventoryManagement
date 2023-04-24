<?php

namespace App\Http\Controllers\Transactions;

use App\Events\LogForDeletedEvents;
use App\Events\LogForStoredEvent;
use App\Http\Controllers\Controller;

use App\Http\Controllers\logController;
use App\Http\Requests\Transactions\CreatePurchaseItem;
use App\Listeners\LodDeletedListener;
use App\Models\DocNum;
use App\Models\general;
use App\Models\ServerSideProcess;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use stdClass;

use function PHPSTORM_META\map;

/**
 * -----------------------------------------------------
 * Krish033
 * -----------------------------------------------------
 * Resource controller matched with defaults
 */

class PurchasedItemsController extends Controller {

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
    protected const table = "tbl_purchased_items";

    /**
     * Primary id for the Table
     */
    protected const primaryId = "piid";

    /**
     * Use this document number
     */
    protected const docName = "PurchasedItems";

    /**
     * Set views folder
     */
    protected const views = "transactions.purchase";

    /**
     * Initialise default args
     */
    public function __construct() {

        $this->activeMenu = "Purchase";

        $this->pageTitle = "Purchased Items";

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
    public function index(string $puid) {
        if (!$this->can('view')) {
            // abort(401, "Un Authorized");
        }


        // getting data
        $purchase = DB::table('tbl_purchases')
            ->where('puid', '=', $puid)
            ->first();

        /**
         * Data to be send to views
         */
        return view(self::views . ".single", [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->menus,
            'crud' => $this->crud,
            'ActiveMenuName' => $this->activeMenu,
            'PageTitle' => $this->pageTitle,
            'purchase' => $purchase,
            'calculatedPriceTotal' => '',
            'purchaseCount' => '',
        ]);
    }




    /**
     * Fetch table resource
     */
    public function homeApi(string $puid) {
        if (!$this->can('view')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $columns = [
            ['db' => 'piid', 'dt' => '0'],
            ['db' => 'name', 'dt' => '1'],
            ['db' => 'description', 'dt' => '2'],
            ['db' => 'amount', 'dt' => '3'],
            [
                'db' => 'piid',
                'dt' => '4',
                'formatter' => function ($d, $row) {
                    return '
                        <button 
                            type="button" 
                            data-id="' . $d . '" 
                            class="btn btn-outline-danger btn-sm deletePurchaseItem" 
                            data-original-title="Delete"
                        >
                            <i class="fa fa-close" aria-hidden="true"></i>
                        </button>';
                }
            ],
        ];

        return (new ServerSideProcess())->SSP([
            'POSTDATA' => [],
            'TABLE' => "tbl_purchased_items",
            'PRIMARYKEY' => 'piid',
            'COLUMNS' => $columns,
            'COLUMNS1' => $columns,
            'GROUPBY' => null,
            'WHERERESULT' => "purchase_id = '$puid'",
            'WHEREALL' => "dflag = 0 ",
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CreatePurchaseItem $request, string $puid) {
        if (!$this->can('add')) {
            return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $purchase = DB::table('tbl_purchases')
            ->where('puid', $puid)
            ->first()->puid;

        if (is_null($purchase)) {
            return response()->json(['status' => false, 'message' => "Not Found"], 404);
        }

        $documentNumber = (new DocNum())->getDocNum(self::docName);
        (new DocNum())->updateDocNum(self::docName);

        // Logs are not stored for item
        LogForStoredEvent::dispatch([
            ...$request->except(['is_active']),
            "is_active" => $request->is_active == 'On',
            "piid" => $documentNumber,
            "purchase_id" => $purchase,
            "created_by" => auth()->user()->UserID,
            "created_at" => now(),
            "dflag" => 0
        ], self::table);

        return response()->json(['status' => true, 'message' => "Item created successfully"], 201);
    }

    /**
     * get the supplier
     * @param string $id
     * @return Collection
     */
    public function suppliers() {
        return DB::table('tbl_suppliers')
            ->get();
    }


    /**
     * Summary of destroy
     * @param mixed $puid
     * @param mixed $piid
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(string $piid) {

        // finding the data in the database
        $item = (array) DB::table(self::table)
            ->where('piid', '=', $piid)
            ->get()
            ->toArray()[0];

        LogForDeletedEvents::dispatch($item, self::table);
        return response()->json(['message' => 'Deleted purchased item successfully'], 202);
    }


}