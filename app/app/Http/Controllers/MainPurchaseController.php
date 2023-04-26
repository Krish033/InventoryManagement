<?php

namespace App\Http\Controllers;


use App\Http\Controllers\logController;
use App\Models\DocNum;
use App\Models\ServerSideProcess;
use App\Models\general;

use Illuminate\Http\Request;


/**
 * -----------------------------------------------------
 * Krish033
 * -----------------------------------------------------
 * Resource controller matched with defaults
 */

class MainPurchaseController extends Controller {

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
    protected const views = "";

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
    public function homeApi() {
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
            'WHEREALL' => " dflag = 0 ",
        ]);
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
            return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id) {
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
            'EditData' => []
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id) {
        if (!$this->can('edit')) {
            return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }
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
    public function destroy(string $id) {
        if (!$this->can('delete')) {
            return response()->json(['status' => false, 'message' => "Un Authorized"], 401);
        }
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