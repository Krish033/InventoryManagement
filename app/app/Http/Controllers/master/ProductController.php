<?php

namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use App\Http\Controllers\logController;
use App\Models\DocNum;
use App\Models\general;
use App\Models\ServerSideProcess;
use App\Rules\ValidUnique;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller {
    private $general;
    private $DocNum;
    private $UserID;
    private $ActiveMenuName;
    private $PageTitle;
    private $CRUD;
    private $logs;
    private $Settings;
    private $Menus;

    protected const table = "tbl_products";
    protected const primaryKey = "pid";

    public function __construct() {
        $this->ActiveMenuName = "Products";
        $this->PageTitle = "Products";
        $this->middleware('auth');
        $this->DocNum = new DocNum();

        $this->middleware(function ($request, $next) {
            $this->UserID = auth()->user()->UserID;
            $this->general = new general($this->UserID, $this->ActiveMenuName);
            $this->Menus = $this->general->loadMenu();
            $this->CRUD = $this->general->getCrudOperations($this->ActiveMenuName);
            $this->logs = new logController();
            $this->Settings = $this->general->getSettings();
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

    public function index(Request $req) {
        if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
            $FormData = $this->general->UserInfo;
            $FormData['menus'] = $this->Menus;
            $FormData['crud'] = $this->CRUD;
            $FormData['ActiveMenuName'] = $this->ActiveMenuName;
            $FormData['PageTitle'] = $this->PageTitle;
            $FormData['SETTINGS'] = $this->Settings;
            return view('master.product.view', $FormData);
        } elseif ($this->general->isCrudAllow($this->CRUD, "add") == true) {
            return Redirect::to('/master/products');
        } else {
            abort(403);
        }
    }

    // Table view
    public function tableView(Request $request) {

        if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
            $ServerSideProcess = new ServerSideProcess();
            $columns = array(
                array('db' => 'img', 'dt' => '0', "formatter" => function ($d, $row) {
                    return "<img class=\"rounded-circle table-img\" src=" . url('/') . '/' . $d . " />";
                }),
                array('db' => 'name', 'dt' => '1'),
                array('db' => 'categoryId', 'dt' => '2'),
                array('db' => 'subCategoryId', 'dt' => '3'),
                array('db' => 'taxId', 'dt' => '4'),
                array('db' => 'hsn_sac_code', 'dt' => '5'),
                array('db' => 'maxQuantity', 'dt' => '6'),
                array('db' => 'minQuantity', 'dt' => '7'),
                array('db' => 'salesRate', 'dt' => '8'),
                array('db' => 'purchaseRate', 'dt' => '9'),

                array(
                    'db' => 'is_active',
                    'dt' => '10',
                    'formatter' => function ($d, $row) {
                        if ($d == "1") {
                            return "<span class='badge badge-success m-1'>Active</span>";
                        } else {
                            return "<span class='badge badge-danger m-1'>Inactive</span>";
                        }
                    }
                ),
                array(
                    'db' => self::primaryKey,
                    'dt' => '11',
                    'formatter' => function ($d, $row) {
                        $html = '';
                        if ($this->general->isCrudAllow($this->CRUD, "edit") == true) {

                            $html .= '
                            <a type="button" 
                                href="' . route('product.edit', $d) . '" 
                                data-id="' . $d . '" 
                                class="btn btn-outline-success btn-sm -success mr-10 btnEdit" 
                                id="supplierViewEditBtn" 
                                data-original-title="Edit"
                            >
                                <i class="fa fa-pencil"></i>
                            </a>';
                        }
                        if ($this->general->isCrudAllow($this->CRUD, "delete") == true) {
                            $html .= '<button type="button" data-id="' . $d . '" class="btn btn-outline-danger btn-sm -success btnDelete" data-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
                        }
                        return $html;
                    }
                )
            );

            $data = array();
            $data['POSTDATA'] = $request;
            $data['TABLE'] = self::table;
            $data['PRIMARYKEY'] = self::primaryKey;
            $data['COLUMNS'] = $columns;
            $data['COLUMNS1'] = $columns;
            $data['GROUPBY'] = null;
            $data['WHERERESULT'] = null;
            $data['WHEREALL'] = " DFlag=0 ";
            return $ServerSideProcess->SSP($data);
        } else {
            return response(array('status' => false, 'message' => "Access Denied"), 403);
        }
    }

    // Trash table view API
    public function trashView(Request $req) {
        if ($this->general->isCrudAllow($this->CRUD, "restore") == true) {
            $FormData = $this->general->UserInfo;
            $FormData['menus'] = $this->Menus;
            $FormData['crud'] = $this->CRUD;
            $FormData['ActiveMenuName'] = $this->ActiveMenuName;
            $FormData['PageTitle'] = $this->PageTitle;
            return view('master.product.trash', $FormData);
        } elseif ($this->general->isCrudAllow($this->CRUD, "view") == true) {
            return Redirect::to('/master/products/');
        } else {
            return abort(403);
        }
    }

    // Trash page view Api
    public function TrashTableView(Request $request) {
        if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
            $ServerSideProcess = new ServerSideProcess();
            $columns = array(
                array('db' => 'img', 'dt' => '0', "formatter" => function ($d, $row) {
                    return "<img class=\"rounded-circle table-img\" src=" . url('/') . '/' . $d . " />";
                }),
                array('db' => 'name', 'dt' => '1'),
                array('db' => 'categoryId', 'dt' => '2'),
                array('db' => 'subCategoryId', 'dt' => '3'),
                array('db' => 'taxId', 'dt' => '4'),
                array('db' => 'hsn_sac_code', 'dt' => '5'),
                array('db' => 'maxQuantity', 'dt' => '6'),
                array('db' => 'minQuantity', 'dt' => '7'),
                array('db' => 'salesRate', 'dt' => '8'),
                array('db' => 'purchaseRate', 'dt' => '9'),
                array(
                    'db' => 'is_active',
                    'dt' => '10',
                    'formatter' => function ($d, $row) {
                        if ($d == "1") {
                            return "<span class='badge badge-success m-1'>Active</span>";
                        } else {
                            return "<span class='badge badge-danger m-1'>Inactive</span>";
                        }
                    }
                ),
                array(
                    'db' => self::primaryKey,
                    'dt' => '11',
                    'formatter' => function ($d, $row) {
                        $html = '<button type="button" data-id="' . $d . '" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
                        return $html;
                    }
                )
            );

            $data = array();
            $data['POSTDATA'] = $request;
            $data['TABLE'] = self::table;
            $data['PRIMARYKEY'] = self::primaryKey;
            $data['COLUMNS'] = $columns;
            $data['COLUMNS1'] = $columns;
            $data['GROUPBY'] = null;
            $data['WHERERESULT'] = null;
            $data['WHEREALL'] = " DFlag=1 ";
            return $ServerSideProcess->SSP($data);
        } else {
            return response(array('status' => false, 'message' => "Access Denied"), 403);
        }
    }

    // view
    public function create(Request $req) {
        if ($this->general->isCrudAllow($this->CRUD, "add") == true) {
            $FormData = $this->general->UserInfo;
            $FormData['menus'] = $this->Menus;
            $FormData['crud'] = $this->CRUD;
            $FormData['ActiveMenuName'] = $this->ActiveMenuName;
            $FormData['PageTitle'] = $this->PageTitle;
            $FormData['SETTINGS'] = $this->Settings;
            $FormData['isEdit'] = false;


            return view('master.product.create', $FormData);
        } elseif ($this->general->isCrudAllow($this->CRUD, "view") == true) {
            return Redirect::to('/master/product/');
        } else {
            return abort(403);
        }
    }

    public function save(Request $req) {

        $OldData = $NewData = array();
        $RoleID = "";
        if ($this->general->isCrudAllow($this->CRUD, "add") == true) {
            $rules = array(
                "img" => 'nullable',
                "name" => 'required',
                "categoryId" => 'required',
                "subCategoryId" => 'required|min:10',
                "taxId" => 'required',
                "maxQuantity" => 'required',
                "minQuantity" => 'required',
                "purchaseRate" => 'required',
                "salesRate" => 'required',
            );
            $message = array(
                // 'CImg.required' => 'Image is required',
                'name.required' => 'FirstName is required',
                'name.min' => 'FirstName must be at least 3 characters',
                'name.max' => 'FirstName may not be greater than 100 characters',
                'name.unique' => 'The FirstName has already been taken.',
            );

            $validator = Validator::make($req->all(), $rules, $message);
            if ($validator->fails()) {
                session()->flash('error', 'Cannot store invalid product data');
                return array('status' => false, 'message' => "Cannot store invalid product data", 'errors' => $validator->errors());
            }

            DB::beginTransaction();
            $status = false;
            $ProfileImage = "";
            try {
                $RoleID = $this->DocNum->getDocNum("Product");
                $UserRights = json_decode($req->CRUD, true);

                if ($req->hasFile('img')) {
                    $dir = "uploads/master/products/";
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
                    'pid' => $RoleID,
                    "name" => $req->name,
                    "categoryId" => $req->categoryId,
                    "subCategoryId" => $req->subCategoryId,
                    "taxId" => $req->taxId,
                    "maxQuantity" => $req->maxQuantity,
                    "minQuantity" => $req->minQuantity,
                    "purchaseRate" => $req->purchaseRate,
                    "hsn_sac_code" => $req->hsn_sac_code,
                    "salesRate" => $req->salesRate,
                    "is_active" => $req->is_active,
                    "dflag" => 0,
                    "created_by" => $this->UserID,
                );

                $status = DB::table(self::table)->insert($data);

                if ($status == true) {
                    if ($ProfileImage != "") {
                        $data['img'] = $ProfileImage;
                        $status = DB::table(self::table)->where(self::primaryKey, $RoleID)->update($data);
                    }
                }

            } catch (Exception $e) {
                dd($e);
                $status = false;
            }
            if ($status == true) {
                DB::commit();

                $this->DocNum->updateDocNum("Product");
                $NewData = DB::Table(self::table)->where(self::primaryKey, $RoleID)->get();
                $logData = array("Description" => "New Product Created ", "ModuleName" => "Product", "Action" => "Add", "ReferID" => $RoleID, "OldData" => "", "NewData" => serialize($NewData), "UserID" => $this->UserID, "IP" => $req->ip());
                $this->logs->Store($logData);
                return array('status' => true, 'message' => "Product Created");
            } else {
                DB::rollback();
                return array('status' => false, 'message' => "Product Creation Failed");
            }
        }
    }


    public function edit(string $pid) {

        if (!$this->can('edit') && $this->can('view')) {
            return redirect('/master/supplier/');
        }

        if (!$this->can('edit')) {
            abort(403);
        }
        // return view 
        return view('master.product.create', [
            'UInfo' => $this->general->UserInfo['UInfo'],
            'menus' => $this->Menus,
            'crud' => $this->CRUD,
            'ActiveMenuName' => $this->ActiveMenuName,
            'PageTitle' => $this->PageTitle,
            'isEdit' => true,
            'EditData' => DB::table(self::table)->where(self::primaryKey, '=', $pid)->first()
        ]);
    }

    // Edit data
    public function update(Request $req, string $UserID) {

        if ($this->general->isCrudAllow($this->CRUD, "edit") == true) {

            $rules = array(
                "img" => 'nullable',
                "name" => 'required',
                "categoryId" => 'required',
                "subCategoryId" => 'required|min:10',
                "taxId" => 'required',
                "maxQuantity" => 'required',
                "minQuantity" => 'required',
                "purchaseRate" => 'required',
                "salesRate" => 'required',
            );
            $message = array(
                // 'CImg.required' => 'Image is required',
                'name.required' => 'FirstName is required',
                'name.min' => 'FirstName must be at least 3 characters',
                'name.max' => 'FirstName may not be greater than 100 characters',
                'name.unique' => 'The FirstName has already been taken.',
            );



            $validator = Validator::make($req->all(), $rules, $message);

            if ($validator->fails()) {
                return array('status' => false, 'message' => "Cannot Update invalid Product data", 'errors' => $validator->errors());
            }

            $status = false;

            try {
                $OldData = (array) DB::table(self::table)->where(self::primaryKey, $UserID)->get();
                // $UserRights = json_decode($req->CRUD, true);
                if ($req->hasFile('img')) {

                    $dir = "uploads/master/products/";

                    if (!file_exists($dir)) {
                        mkdir($dir, 0777, true);
                    }

                    $file = $req->file('img');

                    $fileName = md5($file->getClientOriginalName() . time());

                    $fileName1 = $fileName . "." . $file->getClientOriginalExtension();

                    $file->move($dir, $fileName1);

                    $ProfileImage = $dir . $fileName1;

                } else {
                    $ProfileImage = null;
                }

                $data = array(
                    'pid' => $UserID,
                    "name" => $req->name,
                    "categoryId" => $req->categoryId,
                    "subCategoryId" => $req->subCategoryId,
                    "taxId" => $req->taxId,
                    "maxQuantity" => $req->maxQuantity,
                    "minQuantity" => $req->minQuantity,
                    "purchaseRate" => $req->purchaseRate,
                    "salesRate" => $req->salesRate,
                    "is_active" => $req->is_active,
                    "dflag" => 0,
                    "created_by" => $this->UserID,
                );


                $status = DB::table(self::table)->where(self::primaryKey, $UserID)->update($data);
                if ($status == true) {
                    if ($ProfileImage != "") {
                        $data['img'] = $ProfileImage;
                        $status = DB::Table(self::table)->where(self::primaryKey, $UserID)->update($data);
                    }
                    $NewData = (array) DB::table(self::table)->get();
                    $logData = array("Description" => "Product Updated ", "ModuleName" => "Products", "Action" => "Update", "ReferID" => $UserID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
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


    public function delete(Request $req, $pid) {
        $OldData = $NewData = array();
        if ($this->general->isCrudAllow($this->CRUD, "delete") == true) {
            DB::beginTransaction();
            $status = false;
            try {
                $OldData = DB::table(self::table)->where(self::primaryKey, $pid)->get();
                $status = DB::table(self::table)->where(self::primaryKey, $pid)->update(array("dflag" => 1, "deleted_by" => $this->UserID, "deleted_at" => date("Y-m-d H:i:s")));
            } catch (Exception $e) {
                dd($e);
            }
            if ($status == true) {
                DB::commit();
                $logData = array("Description" => "Product has been Deleted ", "ModuleName" => "Product", "Action" => "Delete", "ReferID" => $pid, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
                $this->logs->Store($logData);
                return array('status' => true, 'message' => "Product deleted Successfully");
            } else {
                DB::rollback();
                return array('status' => false, 'message' => "Product deletion Failed");
            }
        } else {
            return response(array('status' => false, 'message' => "Access Denied"), 403);
        }
    }


    public function restore(Request $req, $pid) {
        $OldData = $NewData = array();
        if ($this->general->isCrudAllow($this->CRUD, "restore") == true) {
            DB::beginTransaction();
            $status = false;
            try {
                $OldData = DB::table(self::table)->where(self::primaryKey, $pid)->get();
                $status = DB::table(self::table)->where(self::primaryKey, $pid)->update(array("dflag" => 0, "updated_by" => $this->UserID, "updated_at" => date("Y-m-d H:i:s")));
            } catch (Exception $e) {
                dd($e);
            }
            if ($status == true) {
                DB::commit();
                $NewData = DB::table(self::table)->where(self::primaryKey, $pid)->get();
                $logData = array("Description" => "Product has been Restored ", "ModuleName" => "Supplier", "Action" => "Restore", "ReferID" => $pid, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
                $this->logs->Store($logData);
                return array('status' => true, 'message' => "Product Restored Successfully");
            } else {
                DB::rollback();
                return array('status' => false, 'message' => "Product Restore Failed");
            }
        } else {
            return response(array('status' => false, 'message' => "Access Denied"), 403);
        }
    }


    // routes for product creation
    // categories
    public function categories() {
        return DB::table('tbl_category')
            ->get(['CID', 'CName']);
    }

    // subcategorues
    public function subCategories(Request $request) {
        return DB::table('tbl_subcategory')
            ->where('CID', '=', $request->category)
            ->get(['SCID', 'SCName']);
    }

    // taxes
    public function taxes() {
        return DB::table('tbl_tax')
            ->get(['TaxID', 'TaxName']);
    }
}