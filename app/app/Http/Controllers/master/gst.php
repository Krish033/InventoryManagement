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

class gst extends Controller {
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
		$this->ActiveMenuName = "Tax";
		$this->PageTitle = "Tax";
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
			return view('master.gst.view', $FormData);
		} elseif ($this->general->isCrudAllow($this->CRUD, "add") == true) {
			return Redirect::to('/master/gst/create');
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
			return view('master.gst.trash', $FormData);
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
			return view('master.gst.gst', $FormData);
		} elseif ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			return Redirect::to('/master/gst/');
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
			$FormData['EditData'] = DB::Table('tbl_tax')->where('DFlag', 0)->Where('TaxID', $CID)->get();
			if (count($FormData['EditData']) > 0) {
				return view('master.GST.gst', $FormData);
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
				'GstName' => ['required', 'max:50', new ValidUnique(array("TABLE" => "tbl_tax", "WHERE" => " TaxName='" . $req->GstName . "'  "), "This GstName Name is already taken.")],
				'Percentage' => 'required',

			);
			$message = array(
				'GstName.required' => "GST Name is required",
				'GstName.max' => "GST Name may not be greater than 100 characters"
			);
			$validator = Validator::make($req->all(), $rules, $message);

			if ($validator->fails()) {
				return array('status' => false, 'message' => "TaxName Create Failed", 'errors' => $validator->errors());
			}
			DB::beginTransaction();
			$status = false;
			try {

				$TaxID = $this->DocNum->getDocNum("TAX");
				$data = array(
					"TaxID" => $TaxID,
					"TaxName" => $req->GstName,
					"TaxPercentage" => $req->Percentage,
					"ActiveStatus" => $req->ActiveStatus,
					"CreatedBy" => $this->UserID,
					"CreatedOn" => date("Y-m-d H:i:s")
				);
				$status = DB::Table('tbl_tax')->insert($data);
			} catch (Exception $e) {
				$status = false;
			}

			if ($status == true) {
				$this->DocNum->updateDocNum("TAX");
				$NewData = (array) DB::table('tbl_tax')->where('TaxID', $TaxID)->get();
				$logData = array("Description" => "New GST Created ", "ModuleName" => "tbl_tax", "Action" => "Add", "ReferID" => $CID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status' => true, 'message' => "GST Created Successfully");
			} else {
				DB::rollback();
				return array('status' => false, 'message' => "GST Create Failed");
			}
		} else {
			return array('status' => false, 'message' => 'Access denined');
		}
	}
	public function update(Request $req, $CID) {
		if ($this->general->isCrudAllow($this->CRUD, "edit") == true) {
			$OldData = array();
			$NewData = array();
			$rules = array(
				'GstName' => ['required', 'max:50', new ValidUnique(array("TABLE" => "tbl_tax", "WHERE" => " TaxName='" . $req->GstName . "' and TaxID<>'" . $CID . "'  "), "This Tax Name is already Exit.")],
			);
			$message = array(
				'GstName.required' => "Tax Name Name is required",
				'GstName.min' => "Tax Name Name must be greater than 2 characters",
				'GstName.max' => "Tax Name Name may not be greater than 100 characters"
			);
			$validator = Validator::make($req->all(), $rules, $message);

			if ($validator->fails()) {
				return array('status' => false, 'message' => "Gst Update Failed", 'errors' => $validator->errors());
			}
			DB::beginTransaction();
			$status = false;
			try {
				$OldData = (array) DB::table('tbl_tax')->where('TaxID', $CID)->get();

				$data = array(
					"TaxID" => $CID,
					"TaxName" => $req->GstName,
					"TaxPercentage" => $req->Percentage,
					"ActiveStatus" => $req->ActiveStatus,
					"UpdatedBy" => $this->UserID,
					"UpdatedOn" => date("Y-m-d H:i:s")
				);

				$status = DB::Table('tbl_tax')->where('TaxID', $CID)->update($data);
			} catch (Exception $e) {
				$status = false;
			}

			if ($status == true) {
				$NewData = (array) DB::table('tbl_tax')->get();
				$logData = array("Description" => "Category Updated ", "ModuleName" => "tbl_tax", "Action" => "Update", "ReferID" => $CID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				DB::commit();
				return array('status' => true, 'message' => "TAx Updated Successfully");
			} else {
				DB::rollback();
				return array('status' => false, 'message' => "TAx Update Failed");
			}
		} else {
			return array('status' => false, 'message' => 'Access denined');
		}
	}

	public function Delete(Request $req, $CID) {

		$OldData = $NewData = array();
		if ($this->general->isCrudAllow($this->CRUD, "delete") == true) {
			DB::beginTransaction();
			$status = false;
			try {
				$OldData = DB::table('tbl_tax')->where('TaxID', $CID)->get();
				$status = DB::table('tbl_tax')->where('TaxID', $CID)->update(array("DFlag" => 1, "DeletedBy" => $this->UserID, "DeletedOn" => date("Y-m-d H:i:s")));
			} catch (Exception $e) {

			}
			if ($status == true) {
				DB::commit();
				$logData = array("Description" => "Tax has been Deleted ", "ModuleName" => "Tax", "Action" => "Delete", "ReferID" => $CID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				return array('status' => true, 'message' => "Tax Deleted Successfully");
			} else {
				DB::rollback();
				return array('status' => false, 'message' => "Tax Delete Failed");
			}
		} else {
			return response(array('status' => false, 'message' => "Access Denied"), 403);
		}
	}
	public function Restore(Request $req, $CID) {

		$OldData = $NewData = array();
		if ($this->general->isCrudAllow($this->CRUD, "restore") == true) {
			DB::beginTransaction();
			$status = false;
			try {
				$OldData = DB::table('tbl_tax')->where('TaxID', $CID)->get();
				$status = DB::table('tbl_tax')->where('TaxID', $CID)->update(array("DFlag" => 0, "UpdatedBy" => $this->UserID, "UpdatedOn" => date("Y-m-d H:i:s")));
			} catch (Exception $e) {

			}
			if ($status == true) {
				DB::commit();
				$NewData = DB::table('tbl_tax')->where('TaxID', $CID)->get();
				$logData = array("Description" => "Tax has been Restored ", "ModuleName" => "Tax", "Action" => "Restore", "ReferID" => $CID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				return array('status' => true, 'message' => "Tax Restored Successfully");
			} else {
				DB::rollback();
				return array('status' => false, 'message' => "Tax Restore Failed");
			}
		} else {
			return response(array('status' => false, 'message' => "Access Denied"), 403);
		}
	}
	public function TableView(Request $request) {
		if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			$ServerSideProcess = new ServerSideProcess();
			$columns = array(
				array('db' => 'TaxID', 'dt' => '0'),
				array('db' => 'TaxName', 'dt' => '1'),
				array('db' => 'TaxPercentage', 'dt' => '2'),
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
					'db' => 'TaxID',
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
			$data['TABLE'] = 'tbl_tax';
			$data['PRIMARYKEY'] = 'TaxID';
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

	public function TrashTableView(Request $request) {
		if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			$ServerSideProcess = new ServerSideProcess();
			$columns = array(
				array('db' => 'TaxID', 'dt' => '0'),
				array('db' => 'TaxName', 'dt' => '1'),
				array('db' => 'TaxPercentage', 'dt' => '2'),
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
					'db' => 'TaxID',
					'dt' => '4',
					'formatter' => function ($d, $row) {
						$html = '<button type="button" data-id="' . $d . '" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
						return $html;
					}
				)
			);
			$data = array();
			$data['POSTDATA'] = $request;
			$data['TABLE'] = 'tbl_tax';
			$data['PRIMARYKEY'] = 'TaxID';
			$data['COLUMNS'] = $columns;
			$data['COLUMNS1'] = $columns;
			$data['GROUPBY'] = null;
			$data['WHERERESULT'] = null;
			$data['WHEREALL'] = " DFlag=1";
			return $ServerSideProcess->SSP($data);
		} else {
			return response(array('status' => false, 'message' => "Access Denied"), 403);
		}
	}
}