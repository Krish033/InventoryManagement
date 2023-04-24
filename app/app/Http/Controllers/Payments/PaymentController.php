<?php

namespace App\Http\Controllers\Payments;

use App\Events\LogForStoredEvent;
use App\Events\LogForUpdatedEvents;
use App\Http\Controllers\Controller;

use App\Http\Controllers\logController;
use App\Http\Requests\Payments\CreatePaymentRequest;
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

class PaymentController extends Controller {

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
     * Show view icons for properties
     * @var array
     */
    protected $showEyeButtosFor = ['sales', 'purchase', 'manual'];

    /**
     * Tabel name to use
     */
    protected const table = "tbl_payments";

    /**
     * Primary id for the Table
     */
    protected const primaryId = "pyid";

    /**
     * Use this document number
     */
    protected const docName = "Payments";

    /**
     * Set views folder
     */
    protected const views = "transactions.payments";

    /**
     * Initialise default args
     */
    public function __construct() {

        $this->activeMenu = "Payments";

        $this->pageTitle = "Payments";

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
    public function homeApi(Request $request) {

        //  pyid date amount 	payment_type 	completed 	
        if (!$this->can('view')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $columns = [
            ['db' => 'pyid', 'dt' => '0'],
            ['db' => 'date', 'dt' => '1'],
            ['db' => 'amount', 'dt' => '2', "formatter" => function ($d) {
                return "
                    <span>
                        <span class=\"h6\">" . $d . "</span>
                        <i class=\"fa fa-rupee-sign small\"></i>
                    </span>
                ";
            }],
            ['db' => 'payment_type', 'dt' => '3'],
            ['db' => 'completed', 'dt' => '4', "formatter" => function ($d) {
                return boolval($d) ? "Completed" : "Incomplete";
            }],
            ['db' => self::primaryId, 'dt' => '5', "formatter" => function ($d) {

                $builder = $this->builder($d); // default query
                // route to category
                $category = $builder->get(['category', 'reference_id'])->first();

                $routeUrl = $category->category == "sales"
                    ? route('sales.single.home', $category->reference_id)
                    : url('/') . '/transactions/purchased-items/' . $category->reference_id; // anvigate to this route
    
                $routeUrl = $category->category == "manual"
                    ? route('payment.single', $d)
                    : $routeUrl;

                return "
                <a 
                    href=" . route('payment.update', $d) . "
                    type=\"button\" 
                    data-id=" . $d . " 
                    class=\"btn btn-outline-primary btn-sm editSale\" 
                    data-original-title=\"Edit\"
                >
                    <i class=\"fa fa-pencil\" aria-hidden=\"true\"></i>
                </a>

                <a
                    href=" . $routeUrl . "
                    type=\"button\" 
                    data-id=" . $d . "
                    class=\"btn btn-outline-success btn-sm\" 
                    data-original-title=\"Edit\"
                >
                    <i class=\"fa fa-eye\" aria-hidden=\"true\"></i>
                </a>
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
     * Show the form for creating a new resource.
     */
    public function create() {
        if (!$this->can('add')) {
            // abort(401, "Un Authorized");
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
    public function store(CreatePaymentRequest $request) {
        if (!$this->can('add')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $paymentId = (new DocNum())->getDocNum(self::docName);
        (new DocNum())->updateDocNum(self::docName);

        LogForStoredEvent::dispatch([
            ...$request->validated(),
            'pyid' => $paymentId,
            'category' => 'manual',
        ], self::table);

        return response()->json(['message' => "Payment created successfully", 'pyid' => $paymentId], 201);
    }


    public function payment(string $pyid) {

        if (!$this->can('view')) {
            // abort(401, "Un Authorized");
        }

        $payment = $this->builder($pyid)
            ->first();

        return view(self::views . ".single", [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->menus,
            'crud' => $this->crud,
            'ActiveMenuName' => $this->activeMenu,
            'PageTitle' => $this->pageTitle,
            'isEdit' => false,
            'EditData' => [],
            'payment' => $payment
        ]);
    }


    /**
     * Summary of builder
     * @param mixed $id
     * @param mixed $column
     * @return \Illuminate\Database\Query\Builder
     */
    protected function builder($id, $column = self::primaryId) {
        return DB::table(self::table)
            ->where($column, $id)
            ->where('dflag', 0);
    }



    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $pyid) {
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
            'EditData' => $this->builder($pyid)
                ->first()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CreatePaymentRequest $request, string $pyid) {
        if (!$this->can('edit')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        LogForUpdatedEvents::dispatch([
            ...$request->validated(),
            'pyid' => $pyid,
            'category' => 'manual',
        ], self::table, $pyid);
        return response()->json(['message' => 'Payment updated successfully', 'pyid' => $pyid]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function trash(string $id) {
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
    }

    /**
     * Fetch table resource for trash table
     */
    public function trashApi() {
        if (!$this->can('view')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
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
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }
    }


    // updatePayments
    public function updatePayments(string $pyid) {
        if (!$this->can('restore')) {
            // return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }

        $item = (array) $this->builder($pyid)->get()->toArray()[0];
        $stats = PaymentItemController::getItemsStats($pyid);


        LogForUpdatedEvents::dispatch([
            ...$item,
            'amount' => $stats['calculatedPriceTotal']
        ], self::table, $pyid);

        return response()->json(['message' => 'Payment updated successfully', 'pyid' => $pyid], 202);
    }
}