<?php
namespace App\Http\Controllers\master;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\DocNum;
use App\Models\general;
use App\Models\ServerSideProcess;
use App\Rules\ValidUnique;
use App\Http\Controllers\logController;
use Exception;
use Illuminate\Support\Facades\DB;

class category extends Controller {
	private $general;
	private $DocNum;
	private $UserID;
	private $ActiveMenuName;
	private $PageTitle;
	private $CRUD;
	private $logs;
	private $Settings;
	private $Menus;
	public function __construct() {
		$this->ActiveMenuName = "Category";
		$this->PageTitle = "Category";
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
	public function view(Request $req) {
		if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			$FormData = $this->general->UserInfo;
			$FormData['menus'] = $this->Menus;
			$FormData['crud'] = $this->CRUD;
			$FormData['ActiveMenuName'] = $this->ActiveMenuName;
			$FormData['PageTitle'] = $this->PageTitle;
			return view('master.category.view', $FormData);
		} elseif ($this->general->isCrudAllow($this->CRUD, "add") == true) {
			return Redirect::to('/master/category/create');
		} else {
			return view('errors.403');
		}
	}
	public function TrashView(Request $req) {
		if ($this->general->isCrudAllow($this->CRUD, "restore") == true) {
			$FormData = $this->general->UserInfo;
			$FormData['menus'] = $this->Menus;
			$FormData['crud'] = $this->CRUD;
			$FormData['ActiveMenuName'] = $this->ActiveMenuName;
			$FormData['PageTitle'] = $this->PageTitle;
			session()->flash('success', 'Trash table fetched');
			return view('master.category.trash', $FormData);
		} elseif ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			return Redirect::to('/master/category/');
		} else {
			return view('errors.403');
		}
	}
	public function create(Request $req) {
		if ($this->general->isCrudAllow($this->CRUD, "add") == true) {
			$FormData = $this->general->UserInfo;
			$FormData['menus'] = $this->Menus;
			$FormData['crud'] = $this->CRUD;
			$FormData['ActiveMenuName'] = $this->ActiveMenuName;
			$FormData['PageTitle'] = $this->PageTitle;
			$FormData['isEdit'] = false;
			return view('master.category.category', $FormData);
		} elseif ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			return Redirect::to('/master/category/');
		} else {
			return view('errors.403');
		}
	}
	public function edit(Request $req, $CID) {
		if ($this->general->isCrudAllow($this->CRUD, "edit") == true) {
			$FormData = $this->general->UserInfo;
			$FormData['menus'] = $this->Menus;
			$FormData['crud'] = $this->CRUD;
			$FormData['ActiveMenuName'] = $this->ActiveMenuName;
			$FormData['PageTitle'] = $this->PageTitle;
			$FormData['isEdit'] = true;
			$FormData['EditData'] = DB::Table('tbl_category')->where('DFlag', 0)->Where('CID', $CID)->get();
			if (count($FormData['EditData']) > 0) {
				return view('master.category.category', $FormData);
			} else {
				return view('errors.403');
			}
		} elseif ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			return Redirect::to('/master/category/');
		} else {
			return view('errors.403');
		}
	}
	public function save(Request $req) {
		if ($this->general->isCrudAllow($this->CRUD, "add") == true) {
			$OldData = array();
			$NewData = array();
			$CID = "";
			$rules = array(
				'CName' => ['required', 'max:50', new ValidUnique(array("TABLE" => "tbl_category", "WHERE" => " CName='" . $req->CName . "'  "), "This Category Name is already taken.")],
				'CImage' => 'mimes:jpeg,jpg,png,gif,bmp'
			);
			$message = array(
				'CName.required' => "Category Name is required",
				'CName.min' => "Category Name must be greater than 2 characters",
				'CName.max' => "Category Name may not be greater than 100 characters"
			);
			$validator = Validator::make($req->all(), $rules, $message);

			if ($validator->fails()) {
				return array('status' => false, 'message' => "Category Create Failed", 'errors' => $validator->errors());
			}
			DB::beginTransaction();
			$status = false;
			try {
				$CImage = "";
				if ($req->hasFile('CImage')) {
					$dir = "uploads/master/category/";
					if (!file_exists($dir)) {
						mkdir($dir, 0777, true);
					}
					$file = $req->file('CImage');
					$fileName = md5($file->getClientOriginalName() . time());
					$fileName1 = $fileName . "." . $file->getClientOriginalExtension();
					$file->move($dir, $fileName1);
					$CImage = $dir . $fileName1;
				}
				$CID = $this->DocNum->getDocNum("CATEGORY");
				$data = array(
					"CID" => $CID,
					"CName" => $req->CName,
					'CImage' => $CImage,
					"ActiveStatus" => $req->ActiveStatus,
					"CreatedBy" => $this->UserID,
					"CreatedOn" => date("Y-m-d H:i:s")
				);
				$status = DB::Table('tbl_category')->insert($data);
			} catch (Exception $e) {
				$status = false;
			}

			if ($status == true) {
				$this->DocNum->updateDocNum("CATEGORY");
				$NewData = (array) DB::table('tbl_category')->where('CID', $CID)->get();
				$logData = array("Description" => "New Category Created ", "ModuleName" => "Category", "Action" => "Add", "ReferID" => $CID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				DB::commit();
				session()->flash('success', 'Category Created Successfully');
				return array('status' => true, 'message' => "Category Created Successfully");
			} else {
				DB::rollback();
				session()->flash('error', 'Category Cration failed');
				return array('status' => false, 'message' => "Category Create Failed");
			}
		} else {
			session()->flash('error', 'Access Denied for user');
			return array('status' => false, 'message' => 'Access denined');
		}
	}
	public function update(Request $req, $CID) {
		if ($this->general->isCrudAllow($this->CRUD, "edit") == true) {
			$OldData = array();
			$NewData = array();
			$rules = array(
				'CName' => ['required', 'max:50', new ValidUnique(array("TABLE" => "tbl_category", "WHERE" => " CName='" . $req->CName . "' and CID<>'" . $CID . "'  "), "This Category Name is already taken.")],
				'CImage' => 'mimes:jpeg,jpg,png,gif,bmp'
			);
			$message = array(
				'CName.required' => "Category Name is required",
				'CName.min' => "Category Name must be greater than 2 characters",
				'CName.max' => "Category Name may not be greater than 100 characters"
			);
			$validator = Validator::make($req->all(), $rules, $message);

			if ($validator->fails()) {
				return array('status' => false, 'message' => "Category Update Failed", 'errors' => $validator->errors());
			}
			DB::beginTransaction();
			$status = false;
			try {
				$OldData = (array) DB::table('tbl_category')->where('CID', $CID)->get();
				$CImage = "";
				if ($req->hasFile('CImage')) {
					$dir = "uploads/master/category/";
					if (!file_exists($dir)) {
						mkdir($dir, 0777, true);
					}
					$file = $req->file('CImage');
					$fileName = md5($file->getClientOriginalName() . time());
					$fileName1 = $fileName . "." . $file->getClientOriginalExtension();
					$file->move($dir, $fileName1);
					$CImage = $dir . $fileName1;
				}
				$data = array(
					"CName" => $req->CName,
					"ActiveStatus" => $req->ActiveStatus,
					"UpdatedBy" => $this->UserID,
					"UpdatedOn" => date("Y-m-d H:i:s")
				);
				if ($CImage != "") {
					$data['CImage'] = $CImage;
				}
				$status = DB::Table('tbl_category')->where('CID', $CID)->update($data);
			} catch (Exception $e) {
				$status = false;
			}

			if ($status == true) {
				$NewData = (array) DB::table('tbl_category')->get();
				$logData = array("Description" => "Category Updated ", "ModuleName" => "Category", "Action" => "Update", "ReferID" => $CID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				DB::commit();
				session()->flash('success', 'Category Updated Successfully');
				return array('status' => true, 'message' => "Category Updated Successfully");
			} else {
				DB::rollback();
				session()->flash('error', 'Category Update Failed');
				return array('status' => false, 'message' => "Category Update Failed");
			}
		} else {
			session()->flash('error', 'Access denied');
			return array('status' => false, 'message' => 'Access denied');
		}
	}

	public function Delete(Request $req, $CID) {
		$OldData = $NewData = array();
		if ($this->general->isCrudAllow($this->CRUD, "delete") == true) {
			DB::beginTransaction();
			$status = false;
			try {
				$OldData = DB::table('tbl_category')->where('CID', $CID)->get();
				$status = DB::table('tbl_category')->where('CID', $CID)->update(array("DFlag" => 1, "DeletedBy" => $this->UserID, "DeletedOn" => date("Y-m-d H:i:s")));
			} catch (Exception $e) {

			}
			if ($status == true) {
				DB::commit();
				$logData = array("Description" => "Category has been Deleted ", "ModuleName" => "Category", "Action" => "Delete", "ReferID" => $CID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				session()->flash('success', 'Category Deleted Successfully');
				return array('status' => true, 'message' => "Category Deleted Successfully");
			} else {
				DB::rollback();
				session()->flash('error', 'Category Deletion Failed');
				return array('status' => false, 'message' => "Category Delete Failed");
			}
		} else {
			session()->flash('error', 'Access denied');
			return response(array('status' => false, 'message' => "Access Denied"), 403);
		}
	}
	public function Restore(Request $req, $CID) {
		$OldData = $NewData = array();
		if ($this->general->isCrudAllow($this->CRUD, "restore") == true) {
			DB::beginTransaction();
			$status = false;
			try {
				$OldData = DB::table('tbl_category')->where('CID', $CID)->get();
				$status = DB::table('tbl_category')->where('CID', $CID)->update(array("DFlag" => 0, "UpdatedBy" => $this->UserID, "UpdatedOn" => date("Y-m-d H:i:s")));
			} catch (Exception $e) {

			}
			if ($status == true) {
				DB::commit();
				$NewData = DB::table('tbl_category')->where('CID', $CID)->get();
				$logData = array("Description" => "Category has been Restored ", "ModuleName" => "Category", "Action" => "Restore", "ReferID" => $CID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				session()->flash('success', 'Category Restored Successfully');
				return array('status' => true, 'message' => "Category Restored Successfully");
			} else {
				DB::rollback();
				session()->flash('error', 'Category Restore Failed');
				return array('status' => false, 'message' => "Category Restore Failed");
			}
		} else {
			session()->flash('error', 'Access denied');
			return response(array('status' => false, 'message' => "Access Denied"), 403);
		}
	}
	public function TableView(Request $request) {
		if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			$ServerSideProcess = new ServerSideProcess();
			$columns = array(
				array('db' => 'CID', 'dt' => '0'),
				array('db' => 'CName', 'dt' => '1'),
				array(
					'db' => 'ActiveStatus',
					'dt' => '2',
					'formatter' => function ($d, $row) {
						if ($d == "1") {
							return "<span class='badge badge-success m-1'>Active</span>";
						} else {
							return "<span class='badge badge-danger m-1'>Inactive</span>";
						}
					}
				),
				array(
					'db' => 'CID',
					'dt' => '3',
					'formatter' => function ($d, $row) {
						$html = '';
						if ($this->general->isCrudAllow($this->CRUD, "edit") == true) {
							$html .= '<button type="button" data-id="' . $d . '" class="btn  btn-outline-success btn-sm -success mr-10 btnEdit" data-original-title="Edit"><i class="fa fa-pencil"></i></button>';
						}
						if ($this->general->isCrudAllow($this->CRUD, "delete") == true) {
							$html .= '<button type="button" data-id="' . $d . '" class="btn  btn-outline-danger btn-sm -success btnDelete" data-original-title="Delete"><i class="fa fa-trash" aria-hidden="true"></i></button>';
						}
						return $html;
					}
				)
			);
			$data = array();
			$data['POSTDATA'] = $request;
			$data['TABLE'] = 'tbl_category';
			$data['PRIMARYKEY'] = 'CID';
			$data['COLUMNS'] = $columns;
			$data['COLUMNS1'] = $columns;
			$data['GROUPBY'] = null;
			$data['WHERERESULT'] = null;
			$data['WHEREALL'] = " DFlag=0 ";
			// session()->flash('success', 'Category Restored Successfully');
			return $ServerSideProcess->SSP($data);
		} else {
			session()->flash('error', 'Access denied');
			return response(array('status' => false, 'message' => "Access Denied"), 403);
		}
	}
	public function TrashTableView(Request $request) {
		if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			$ServerSideProcess = new ServerSideProcess();
			$columns = array(
				array('db' => 'CID', 'dt' => '0'),
				array('db' => 'CName', 'dt' => '1'),
				array(
					'db' => 'ActiveStatus',
					'dt' => '2',
					'formatter' => function ($d, $row) {
						if ($d == "1") {
							return "<span class='badge badge-success m-1'>Active</span>";
						} else {
							return "<span class='badge badge-danger m-1'>Inactive</span>";
						}
					}
				),
				array(
					'db' => 'CID',
					'dt' => '3',
					'formatter' => function ($d, $row) {
						$html = '<button type="button" data-id="' . $d . '" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
						return $html;
					}
				)
			);
			$data = array();
			$data['POSTDATA'] = $request;
			$data['TABLE'] = 'tbl_category';
			$data['PRIMARYKEY'] = 'CID';
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
}