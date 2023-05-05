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

class subcategory extends Controller {
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
		$this->ActiveMenuName = "Sub-Category";
		$this->PageTitle = "SubCategory";
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
			return view('master.SubCategory.view', $FormData);
		} elseif ($this->general->isCrudAllow($this->CRUD, "add") == true) {
			return Redirect::to('/master/SubCategory/create');
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
			return view('master.SubCategory.trash', $FormData);
		} elseif ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			return Redirect::to('/master/SubCategory/');
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
			return view('master.SubCategory.SubCategory', $FormData);
		} elseif ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			return Redirect::to('/master/SubCategory/');
		} else {
			return view('errors.403');
		}
	}
	public function edit(Request $req, $SCID) {
		if ($this->general->isCrudAllow($this->CRUD, "edit") == true) {
			$FormData = $this->general->UserInfo;
			$FormData['menus'] = $this->Menus;
			$FormData['crud'] = $this->CRUD;
			$FormData['ActiveMenuName'] = $this->ActiveMenuName;
			$FormData['PageTitle'] = $this->PageTitle;
			$FormData['isEdit'] = true;
			$FormData['EditData'] = DB::Table('tbl_subcategory')->where('DFlag', 0)->Where('SCID', $SCID)->get();
			if (count($FormData['EditData']) > 0) {
				return view('master.SubCategory.SubCategory', $FormData);
			} else {
				return view('errors.403');
			}
		} elseif ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			return Redirect::to('/master/SubCategory/');
		} else {
			return view('errors.403');
		}
	}
	public function save(Request $req) {
		if ($this->general->isCrudAllow($this->CRUD, "add") == true) {
			$OldData = array();
			$NewData = array();
			$SCID = "";
			$rules = array(
				'SCName' => ['required', 'max:50', new ValidUnique(array("TABLE" => "tbl_subcategory", "WHERE" => " SCName='" . $req->SCName . "'  "), "This Sub Category Name is already taken.")],
				'CID' => 'required',
				'SCImage' => 'mimes:jpeg,jpg,png,gif,bmp'
			);
			$message = array(
				'SCName.required' => "Sub Category Name is required",
				'SCName.min' => "Sub Category Name must be greater than 2 characters",
				'SCName.max' => "Sub Category Name may not be greater than 100 characters",
				'CID' => 'Category  is required'
			);
			$validator = Validator::make($req->all(), $rules, $message);

			if ($validator->fails()) {
				return array('status' => false, 'message' => "Sub Category Create Failed", 'errors' => $validator->errors());
			}
			DB::beginTransaction();
			$status = false;
			try {
				$SCImage = "";
				if ($req->hasFile('SCImage')) {
					$dir = "uploads/master/SubCategory/";
					if (!file_exists($dir)) {
						mkdir($dir, 0777, true);
					}
					$file = $req->file('SCImage');
					$fileName = md5($file->getClientOriginalName() . time());
					$fileName1 = $fileName . "." . $file->getClientOriginalExtension();
					$file->move($dir, $fileName1);
					$SCImage = $dir . $fileName1;
				}
				$SCID = $this->DocNum->getDocNum("SUB-CATEGORY");
				$data = array(
					"SCID" => $SCID,
					"SCName" => $req->SCName,
					"CID" => $req->CID,
					'SCImage' => $SCImage,
					"ActiveStatus" => $req->ActiveStatus,
					"CreatedBy" => $this->UserID,
					"CreatedOn" => date("Y-m-d H:i:s")
				);
				$status = DB::Table('tbl_subcategory')->insert($data);
			} catch (Exception $e) {
				$status = false;
			}

			if ($status == true) {
				$this->DocNum->updateDocNum("SUB-CATEGORY");
				$NewData = (array) DB::table('tbl_subcategory')->where('SCID', $SCID)->get();
				$logData = array("Description" => "New Sub Category Created ", "ModuleName" => "SubCategory", "Action" => "Add", "ReferID" => $SCID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status' => true, 'message' => "Sub Category Created Successfully");
			} else {
				DB::rollback();
				return array('status' => false, 'message' => "Sub Category Create Failed");
			}
		} else {
			return array('status' => false, 'message' => 'Access denined');
		}
	}
	public function update(Request $req, $SCID) {
		if ($this->general->isCrudAllow($this->CRUD, "edit") == true) {
			$OldData = array();
			$NewData = array();
			$rules = array(
				'SCName' => ['required', 'max:50', new ValidUnique(array("TABLE" => "tbl_subcategory", "WHERE" => " SCName='" . $req->SCName . "' and SCID<>'" . $SCID . "'  "), "This Sub Category Name is already taken.")],
				'CID' => 'required',
				'SCImage' => 'mimes:jpeg,jpg,png,gif,bmp'
			);
			$message = array(
				'SCName.required' => "Sub Category Name is required",
				'SCName.min' => "Sub Category Name must be greater than 2 characters",
				'SCName.max' => "Sub Category Name may not be greater than 100 characters",
				'CID' => 'Category  is required'
			);
			$validator = Validator::make($req->all(), $rules, $message);

			if ($validator->fails()) {
				return array('status' => false, 'message' => "Sub Category Update Failed", 'errors' => $validator->errors());
			}
			DB::beginTransaction();
			$status = false;
			try {
				$OldData = (array) DB::table('tbl_subcategory')->where('SCID', $SCID)->get();
				$SCImage = "";
				if ($req->hasFile('SCImage')) {
					$dir = "uploads/master/SubCategory/";
					if (!file_exists($dir)) {
						mkdir($dir, 0777, true);
					}
					$file = $req->file('SubCImage');
					$fileName = md5($file->getClientOriginalName() . time());
					$fileName1 = $fileName . "." . $file->getClientOriginalExtension();
					$file->move($dir, $fileName1);
					$SubCImage = $dir . $fileName1;
				}
				$data = array(
					"SCName" => $req->SCName,
					"ActiveStatus" => $req->ActiveStatus,
					"CID" => $req->CID,
					"UpdatedBy" => $this->UserID,
					"UpdatedOn" => date("Y-m-d H:i:s")
				);
				if ($SCImage != "") {
					$data['SCImage'] = $SCImage;
				}
				$status = DB::Table('tbl_subcategory')->where('SCID', $SCID)->update($data);
			} catch (Exception $e) {
				$status = false;
			}

			if ($status == true) {
				$NewData = (array) DB::table('tbl_subcategory')->get();
				$logData = array("Description" => "SubCategory Updated ", "ModuleName" => "SubCategory", "Action" => "Update", "ReferID" => $SCID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status' => true, 'message' => "SubCategory Updated Successfully");
			} else {
				DB::rollback();
				return array('status' => false, 'message' => "SubCategory Update Failed");
			}
		} else {
			return array('status' => false, 'message' => 'Access denined');
		}
	}

	public function Delete(Request $req, $SCID) {
		$OldData = $NewData = array();
		if ($this->general->isCrudAllow($this->CRUD, "delete") == true) {
			DB::beginTransaction();
			$status = false;
			try {
				$OldData = DB::table('tbl_subcategory')->where('SCID', $SCID)->get();
				$status = DB::table('tbl_subcategory')->where('SCID', $SCID)->update(array("DFlag" => 1, "DeletedBy" => $this->UserID, "DeletedOn" => date("Y-m-d H:i:s")));
			} catch (Exception $e) {

			}
			if ($status == true) {
				DB::commit();
				$logData = array("Description" => "Sub Category has been Deleted ", "ModuleName" => "SubCategory", "Action" => "Delete", "ReferID" => $SCID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				return array('status' => true, 'message' => "Sub Category Deleted Successfully");
			} else {
				DB::rollback();
				return array('status' => false, 'message' => "Sub Category Delete Failed");
			}
		} else {
			return response(array('status' => false, 'message' => "Access Denied"), 403);
		}
	}
	public function Restore(Request $req, $SCID) {
		$OldData = $NewData = array();
		if ($this->general->isCrudAllow($this->CRUD, "restore") == true) {
			DB::beginTransaction();
			$status = false;
			try {
				$OldData = DB::table('tbl_subcategory')->where('SCID', $SCID)->get();
				$status = DB::table('tbl_subcategory')->where('SCID', $SCID)->update(array("DFlag" => 0, "UpdatedBy" => $this->UserID, "UpdatedOn" => date("Y-m-d H:i:s")));
			} catch (Exception $e) {

			}
			if ($status == true) {
				DB::commit();
				$NewData = DB::table('tbl_subcategory')->where('SCID', $SCID)->get();
				$logData = array("Description" => "Sub Category has been Restored ", "ModuleName" => "SubCategory", "Action" => "Restore", "ReferID" => $SCID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				return array('status' => true, 'message' => "Sub Category Restored Successfully");
			} else {
				DB::rollback();
				return array('status' => false, 'message' => "Sub Category Restore Failed");
			}
		} else {
			return response(array('status' => false, 'message' => "Access Denied"), 403);
		}
	}
	public function TableView(Request $request) {
		if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			$ServerSideProcess = new ServerSideProcess();
			$columns = array(
				array('db' => 'SC.SCID', 'dt' => '0'),
				array('db' => 'SC.SCName', 'dt' => '1'),
				array('db' => 'C.CName', 'dt' => '2'),
				array(
					'db' => 'SC.ActiveStatus',
					'dt' => '3',
					'formatter' => function ($d, $row) {
						if ($d == "1") {
							return "<span class='badge badge-success m-1'>Active</span>";
						} else {
							return "<span class='badge badge-danger m-1'>Inactive</span>";
						}
					}
				),
				array(
					'db' => 'SC.SCID',
					'dt' => '4',
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
			$columns1 = array(
				array('db' => 'SCID', 'dt' => '0'),
				array('db' => 'SCName', 'dt' => '1'),
				array('db' => 'CName', 'dt' => '2'),
				array(
					'db' => 'ActiveStatus',
					'dt' => '3',
					'formatter' => function ($d, $row) {
						if ($d == "1") {
							return "<span class='badge badge-success m-1'>Active</span>";
						} else {
							return "<span class='badge badge-danger m-1'>Inactive</span>";
						}
					}
				),
				array(
					'db' => 'SCID',
					'dt' => '4',
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
			$data['TABLE'] = 'tbl_subcategory as SC LEFT JOIN tbl_category as C ON C.CID=SC.CID';
			$data['PRIMARYKEY'] = 'SCID';
			$data['COLUMNS'] = $columns;
			$data['COLUMNS1'] = $columns1;
			$data['GROUPBY'] = null;
			$data['WHERERESULT'] = null;
			$data['WHEREALL'] = " SC.DFlag=0 ";
			return $ServerSideProcess->SSP($data);
		} else {
			return response(array('status' => false, 'message' => "Access Denied"), 403);
		}
	}
	public function TrashTableView(Request $request) {
		if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			$ServerSideProcess = new ServerSideProcess();
			$columns = array(
				array('db' => 'SC.SCID', 'dt' => '0'),
				array('db' => 'SC.SCName', 'dt' => '1'),
				array('db' => 'C.CName', 'dt' => '2'),
				array(
					'db' => 'SC.ActiveStatus',
					'dt' => '3',
					'formatter' => function ($d, $row) {
						if ($d == "1") {
							return "<span class='badge badge-success m-1'>Active</span>";
						} else {
							return "<span class='badge badge-danger m-1'>Inactive</span>";
						}
					}
				),
				array(
					'db' => 'SC.SCID',
					'dt' => '4',
					'formatter' => function ($d, $row) {
						$html = '<button type="button" data-id="' . $d . '" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
						return $html;
					}
				)
			);
			$columns1 = array(
				array('db' => 'SCID', 'dt' => '0'),
				array('db' => 'SCName', 'dt' => '1'),
				array('db' => 'CName', 'dt' => '2'),
				array(
					'db' => 'ActiveStatus',
					'dt' => '3',
					'formatter' => function ($d, $row) {
						if ($d == "1") {
							return "<span class='badge badge-success m-1'>Active</span>";
						} else {
							return "<span class='badge badge-danger m-1'>Inactive</span>";
						}
					}
				),
				array(
					'db' => 'SCID',
					'dt' => '4',
					'formatter' => function ($d, $row) {
						$html = '<button type="button" data-id="' . $d . '" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
						return $html;
					}
				)
			);
			$data = array();
			$data['POSTDATA'] = $request;
			$data['TABLE'] = 'tbl_subcategory as SC LEFT JOIN tbl_category as C ON C.CID=SC.CID';
			$data['PRIMARYKEY'] = 'SC.SCID';
			$data['COLUMNS'] = $columns;
			$data['COLUMNS1'] = $columns1;
			$data['GROUPBY'] = null;
			$data['WHERERESULT'] = null;
			$data['WHEREALL'] = " SC.DFlag=1 ";
			return $ServerSideProcess->SSP($data);
		} else {
			return response(array('status' => false, 'message' => "Access Denied"), 403);
		}
	}

	public function getCategory(Request $request) {

		$tbl_category = DB::table('tbl_category')->get();
		return $tbl_category;
	}
}