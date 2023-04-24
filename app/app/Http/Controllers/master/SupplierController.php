<?php

namespace App\Http\Controllers\master;

use App\Events\LogForDeletedEvents;
use App\Events\LogForRestoredEvents;
use App\Events\LogForStoredEvent;
use App\Events\LogForUpdatedEvents;
use App\Http\Controllers\Controller;
use App\Http\Controllers\logController;
use App\Models\DocNum;
use App\Models\general;
use App\Models\ServerSideProcess;
use App\Rules\ValidUnique;
use App\Services\Server;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class SupplierController extends Controller {
    // import documents
    private $general;
    private $DocNum;
    private $UserID;
    private $ActiveMenuName;
    private $PageTitle;
    private $CRUD;
    private $logs;
    private $Settings;
    private $Menus;

    // table name
    protected const table = "tbl_suppliers";
    protected const primaryId = "sid";
    protected const docName = "Supplier";



    // constructing 
    public function __construct() {

        // Assigned items for this constructor
        // * $this->ActiveMenuName: active menu name
        // * $this->PageTitle: page title
        // * $this->UserID: User id from Auth

        // * $this->general: General modal;
        // * $this->Menus: Menus;
        // * $this->CRUD: array with user permissions;

        // * $this->logs: create log files;
        // * $this->Settings: settings, not have been used;

        $this->ActiveMenuName = "Suppliers"; // assigning active menu
        $this->PageTitle = "Suppliers"; // page title
        $this->middleware('auth'); // middleware can be assigned with the middleware function
        $this->DocNum = new DocNum(); // get document number
        // auth user can only be assinged in the middleware
        $this->middleware(function ($request, $next) {
            $this->UserID = auth()->user()->UserID; // user
            $this->general = new general($this->UserID, $this->ActiveMenuName); // active menu
            $this->Menus = $this->general->loadMenu(); // load the HTML menu 
            $this->CRUD = $this->general->getCrudOperations($this->ActiveMenuName); // get cruds array
            $this->logs = new logController(); // innitailizing logs
            $this->Settings = $this->general->getSettings(); // settings
            return $next($request);
        });
    }


    /**
     * Summary of can
     * @param string $permission
     * @return bool
     */
    protected function can(string $permission) {
        return $this->general->isCrudAllow($this->CRUD, $permission);
    }

    public function index() {

        // if user only has add permissions
        if (!$this->can('view') && boolVal($this->can('add')))
            return redirect()->route('supplier.create');

        // abort if not authorized
        if (!$this->can('view')) {
            abort(401);
        }
        // eturn view
        return view('master.supplier.view', [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->Menus,
            'crud' => $this->CRUD,
            'ActiveMenuName' => $this->ActiveMenuName,
            'PageTitle' => $this->PageTitle,
        ]);
    }

    public function tableView(Request $request) {


        // dd($request);

        //  if does not have view permissions
        if (!$this->can('view')) {
            return response()->json(['status' => false, 'message' => "Access Denied"], 403);
        } // original data

        $columns = [
            ['db' => 'img', 'dt' => '0', 'formatter' => function ($d, $row) {
                return "<img src=" . url('/') . '/' . $d . " class=\"rounded-circle table-img\" />";
            }],
            ['db' => 'name', 'dt' => '1'],
            ['db' => 'phone', 'dt' => '2'], ['db' => 'email', 'dt' => '3'],
            ['db' => 'address', 'dt' => '4'], ['db' => 'countryId', 'dt' => '5'],
            ['db' => 'is_active', 'dt' => '6',
                'formatter' => function ($d, $row) {
                    return $d == "1"
                        ? "<span class='badge badge-success m-1'>Active</span>"
                        : "<span class='badge badge-danger m-1'>Inactive</span>";
                }
            ],
            [
                'db' => 'sid',
                'dt' => '7',
                'formatter' => function ($d, $row) {
                    $html = '';
                    if (boolval($this->can('edit'))) {
                        $html .= '
                        <a type="button" 
                            href="' . route('supplier.edit', $d) . '" 
                            data-id="' . $d . '" 
                            class="btn btn-outline-success btn-sm -success mr-10 btnEdit" 
                            id="supplierViewEditBtn" 
                            data-original-title="Edit"
                        >
                            <i class="fa fa-pencil"></i>
                        </a>';
                    }
                    if (boolval($this->can('delete'))) {
                        $html .= '
                        <button 
                            type="button" 
                            data-id="' . $d . '" 
                            class="btn btn-outline-danger btn-sm supplierDeleteBtn" 
                            data-original-title="Delete"
                        >
                            <i class="fa fa-trash" aria-hidden="true"></i>
                        </button>';
                    }
                    return $html;
                }
            ]
        ];

        // data to be send to the server side 
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


    // Trash view
    public function trashView() {

        // if user only has add permissions
        if (!$this->can('restore') && $this->can('view'))
            return redirect('/master/supplier/');

        // abort if not authorized
        if (!$this->can('restore')) {
            abort(401);
        }

        // eturn view
        return view('master.supplier.trash', [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->Menus,
            'crud' => $this->CRUD,
            'ActiveMenuName' => $this->ActiveMenuName,
            'PageTitle' => $this->PageTitle,
        ]);
    }

    // Trash page view Api
    public function trashTableView(Request $request) {
        // Un authorized
        if (!$this->can('view')) {
            return response()->json(['status' => false, 'message' => "Access Denied"], 401);
        } // table data
        $columns = [
            ['db' => 'img', 'dt' => '0', 'formatter' => function ($d, $row) {
                return "<img src=" . url('/') . '/' . $d . " class=\"rounded-circle table-img\" />";
            }], ['db' => 'name', 'dt' => '1'],
            ['db' => 'phone', 'dt' => '2'], ['db' => 'email', 'dt' => '3'],
            ['db' => 'address', 'dt' => '4'], ['db' => 'countryId', 'dt' => '5'],
            ['db' => 'is_active', 'dt' => '6',
                'formatter' => function ($d, $row) {
                    return $d == '1'
                        ? "<span class='badge badge-success m-1'>Active</span>"
                        : "<span class='badge badge-danger m-1'>Inactive</span>";
                }
            ],
            ['db' => 'sid', 'dt' => '7',
                'formatter' => function ($d, $row) {
                    $html = '<button type="button" data-id="' . $d . '" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
                    return $html;
                }
            ]
        ]; // data to be send to the server side 
        return (new ServerSideProcess())->SSP([
            'POSTDATA' => $request,
            'TABLE' => self::table,
            'PRIMARYKEY' => self::primaryId,
            'COLUMNS' => $columns,
            'COLUMNS1' => $columns,
            'GROUPBY' => null,
            'WHERERESULT' => null,
            'WHEREALL' => " dflag = 1 ",
        ]);
    }

    // view
    public function create(Request $req) {

        if (!$this->can('add') && boolval($this->can('view'))) {
            return redirect('/master/suppliers/');
        }

        if (!$this->can('add')) {
            abort(403);
        }


        return view('master.supplier.create', [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->Menus,
            'crud' => $this->CRUD,
            'ActiveMenuName' => $this->ActiveMenuName,
            'PageTitle' => $this->PageTitle,
            'isEdit' => false,
        ]);
    }

    public function save(Request $req) {
        $OldData = $NewData = [];
        $RoleID = "";

        if (!$this->can('add')) {
            session()->flash('error', 'Un Authorized');
            return;
        }
        // rules
        $rules = array(
            "img" => 'nullable',
            "name" => 'required',
            "email" => 'required|email',
            "address" => 'required|min:10',
            "countryId" => 'required',
            "stateId" => 'required',
            "cityId" => 'required',
            "phone" => ['required', 'max:10', new ValidUnique(array("TABLE" => "tbl_user_info", "WHERE" => " MobileNumber='" . $req->MobileNumber . "' "), "This Mobile Number is already taken.")],
            "is_active" => 'required',
        );
        // rules message
        $message = array(
            // 'CImg.required' => 'Image is required',
            'name.required' => 'FirstName is required',
            'name.min' => 'FirstName must be at least 3 characters',
            'name.max' => 'FirstName may not be greater than 100 characters',
            'name.unique' => 'The FirstName has already been taken.',
            'address.required' => 'Address is required',
            'address.min' => 'Address must be at least 3 characters',
            'address.max' => 'Address may not be greater than 100 characters',
        );
        //  validate rules 
        $validator = Validator::make($req->all(), $rules, $message);
        // error message
        if ($validator->fails()) {
            session()->flash('error', 'Cannot create supplier with invalid data');
            return response()->json(['status' => false, 'message' => "Suppplier Creation Failed", 'errors' => $validator->errors()]);
        }
        // start db transaction
        DB::beginTransaction();
        $status = false;
        $ProfileImage = "";

        try {
            $RoleID = $this->DocNum->getDocNum(self::docName);

            // $UserRights = json_decode($req->CRUD, true);
            $ProfileImage = "";

            if ($req->hasFile('img')) {
                $dir = "uploads/master/supplier/";

                if (!file_exists($dir)) {
                    mkdir($dir, 0777, true);
                }

                $file = $req->file('img');
                $fileName = md5($file->getClientOriginalName() . time());
                $fileName1 = $fileName . "." . $file->getClientOriginalExtension();
                $file->move($dir, $fileName1);
                $ProfileImage = $dir . $fileName1;
            }

            $data = array(
                "sid" => $RoleID,
                "img" => $ProfileImage,
                "name" => $req->name,
                "email" => $req->email,
                "address" => $req->address,
                "countryId" => $req->countryId,
                "stateId" => $req->stateId,
                "cityId" => $req->cityId,
                "phone" => $req->phone,
                "is_active" => $req->is_active,
                "dflag" => 0,
                "created_by" => $this->UserID,
            );

            // LogForStoredEvent::dispatch($data, self::table);

            $status = DB::table(self::table)->insert($data);

            if ($status == true) {
                if ($ProfileImage != "") {
                    $data['img'] = $ProfileImage;
                    $status = DB::table(self::table)->where(self::primaryId, $RoleID)->update($data);
                }
            }

        } catch (Exception $e) {
            dd($e);
            $status = false;
        }
        if ($status == true) {
            DB::commit();

            $this->DocNum->updateDocNum(self::docName);
            $NewData = DB::table(self::table)->where(self::primaryId, $RoleID)->get();
            $logData = array("Description" => "New Supplier Created ", "ModuleName" => "Supplier", "Action" => "Add", "ReferID" => $RoleID, "OldData" => "", "NewData" => serialize($NewData), "UserID" => $this->UserID, "IP" => $req->ip());
            $this->logs->Store($logData);
            return response(['status' => true, 'message' => "Supplier Create Successfully"]);
        } else {
            DB::rollback();
            return response(['status' => false, 'message' => "Supplier Create Failed"]);
        }
    }

    public function edit(string $sid) {

        if (!$this->can('edit') && $this->can('view')) {
            return redirect('/master/supplier/');
        }

        if (!$this->can('edit')) {
            abort(403);
        }
        // return view 
        return view('master.supplier.create', [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->Menus,
            'crud' => $this->CRUD,
            'ActiveMenuName' => $this->ActiveMenuName,
            'PageTitle' => $this->PageTitle,
            'isEdit' => true,
            'EditData' => DB::table(self::table)->where(self::primaryId, '=', $sid)->first()
        ]);
    }

    public function update(Request $req, $UserID) {

        if ($this->general->isCrudAllow($this->CRUD, "edit") == true) {

            $rules = array(
                "img" => 'nullable',
                "name" => 'required',
                "email" => 'required|email',
                "address" => 'required|min:10',
                "cityId" => 'required',
                "stateId" => 'required',
                "countryId" => 'required',
                "phone" => ['required', 'max:10', new ValidUnique(array("TABLE" => "tbl_user_info", "WHERE" => " MobileNumber='" . $req->MobileNumber . "' "), "This Mobile Number is already taken.")],
                "is_active" => 'required',
            );

            $message = array(
                // 'CImg.required' => 'Image is required',
                'name.required' => 'FirstName is required',
                'name.min' => 'FirstName must be at least 3 characters',
                'name.max' => 'FirstName may not be greater than 100 characters',
                'name.unique' => 'The FirstName has already been taken.',
                'address.required' => 'Address is required',
                'address.min' => 'Address must be at least 3 characters',
                'address.max' => 'Address may not be greater than 100 characters',
            );

            $validator = Validator::make($req->all(), $rules, $message);

            if ($validator->fails()) {
                return array('status' => false, 'message' => "Cannot Update invalid supplier data", 'errors' => $validator->errors());
            }

            $status = false;

            try {

                $ProfileImage = "";
                //  Profile Image
                // $ProfileImage = DB::table(self::table)
                //     ->where('sid', $UserID)
                //     ->pluck('img')
                //     ->first();
                // Old Data
                $OldData = DB::table(self::table)
                    ->where('sid', $UserID)
                    ->get();
                // $UserRights = json_decode($req->CRUD, true);
                if ($req->hasFile('img')) {
                    $dir = "uploads/master/suppliers/";
                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }
                    $file = $req->file('img');
                    $fileName = md5($file->getClientOriginalName() . time());
                    $fileName1 = $fileName . "." . $file->getClientOriginalExtension();
                    $file->move($dir, $fileName1);
                    $ProfileImage = $dir . $fileName1;
                }

                // LogForUpdatedEvents::dispatch($data);

                $data = array(
                    "sid" => $UserID,
                    "name" => $req->name,
                    "email" => $req->email,
                    "address" => $req->address,
                    "cityId" => $req->cityId,
                    "stateId" => $req->stateId,
                    "countryId" => $req->countryId,
                    "phone" => $req->phone,
                    "is_active" => $req->is_active,
                    "dflag" => 0,
                    "created_by" => $this->UserID,
                    "created_at" => date("Y-m-d H:i:s"),
                );


                $status = DB::table(self::table)->where('sid', $UserID)->update($data);
                if ($status == true) {
                    if ($ProfileImage != "") {
                        $data['img'] = $ProfileImage;
                        $status = DB::Table(self::table)->where('sid', $UserID)->update($data);
                    }
                    $NewData = (array) DB::table(self::table)->get();
                    $logData = array("Description" => "Supplier Updated ", "ModuleName" => "Supplier", "Action" => "Update", "ReferID" => $UserID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
                    $this->logs->Store($logData);
                }

            } catch (Exception $e) {
                $status = false;
                dd($e);
            }
            if ($status == true) {
                DB::commit();
                return array('status' => true, 'message' => "Supplier Update Successfully");
            } else {
                DB::rollback();
                return array('status' => false, 'message' => "Supplier Update Failed");
            }
        }
    }

    public function delete(Request $req, $sid) {

        $OldData = $NewData = array();
        if ($this->general->isCrudAllow($this->CRUD, "delete") == true) {
            DB::beginTransaction();
            $status = false;
            try {
                $OldData = DB::table(self::table)->where('sid', $sid)->get();
                $status = DB::table(self::table)->where('sid', $sid)->update(array("dflag" => 1, "deleted_by" => $this->UserID, "deleted_at" => date("Y-m-d H:i:s")));
            } catch (Exception $e) {
                dd($e);
            }
            if ($status == true) {
                DB::commit();
                $logData = array("Description" => "customer has been Deleted ", "ModuleName" => "customer", "Action" => "Delete", "ReferID" => $sid, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
                $this->logs->Store($logData);
                return array('status' => true, 'message' => "Supplier deleted Successfully");
            } else {
                DB::rollback();
                return array('status' => false, 'message' => "Supplier deletion Failed");
            }
        } else {
            return response(array('status' => false, 'message' => "Access Denied"), 403);
        }
    }


    public function restore(Request $req, $sid) {
        $OldData = $NewData = array();
        if ($this->general->isCrudAllow($this->CRUD, "restore") == true) {
            DB::beginTransaction();
            $status = false;
            try {
                $OldData = DB::table(self::table)->where(self::primaryId, $sid)->get();
                $status = DB::table(self::table)->where(self::primaryId, $sid)->update(array("dflag" => 0, "updated_by" => $this->UserID, "updated_at" => date("Y-m-d H:i:s")));
            } catch (Exception $e) {

                dd($e);
            }
            if ($status == true) {
                DB::commit();
                $NewData = DB::table(self::table)->where(self::primaryId, $sid)->get();
                $logData = array("Description" => "Supplier has been Restored ", "ModuleName" => "Supplier", "Action" => "Restore", "ReferID" => $sid, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
                $this->logs->Store($logData);
                return array('status' => true, 'message' => "Supplier Restored Successfully");
            } else {
                DB::rollback();
                return array('status' => false, 'message' => "Supplier Restore Failed");
            }
        } else {
            return response(array('status' => false, 'message' => "Access Denied"), 403);
        }
    }




}