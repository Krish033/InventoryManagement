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
use Helper;
use Illuminate\Support\Facades\DB;

class customercontroller extends Controller {
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
		$this->ActiveMenuName = "Customer";
		$this->PageTitle = "Customer";
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
			$FormData['SETTINGS'] = $this->Settings;
			return view('master.customer.view', $FormData);
		} elseif ($this->general->isCrudAllow($this->CRUD, "add") == true) {
			return Redirect::to('/master/customer/customer/');
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
			return view('master.customer.trash', $FormData);
		} elseif ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			return Redirect::to('/master/customer/');
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
			$FormData['SETTINGS'] = $this->Settings;
			$FormData['isEdit'] = false;
			return view('master.customer.customer', $FormData);
		} elseif ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			return Redirect::to('/master/customer/');
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
			$FormData['SETTINGS'] = $this->Settings;

			$sql = "SELECT C.*, CCD.* FROM tbl_customer as C LEFT JOIN tbl_customer_contact_details as CCD On CCD.CID=C.CID Where  C.CID='" . $CID . "'";

			$Contactperson = DB::select($sql);

			$FormData['Contactperson'] = $Contactperson;
			$FormData['EditData'] = DB::table('tbl_customer')
				->leftJoin('tbl_shippingaddress', 'tbl_customer.CID', '=', 'tbl_shippingaddress.CustID')
				->select('tbl_customer.*', 'tbl_shippingaddress.*')
				->where('DFlag', 0)
				->Where('CID', $CID)
				->get();

			if (count($FormData['EditData']) > 0) {

				// dd($FormData);
				return view('master.customer.customer', $FormData);
			} else {
				return view('errors.403');
			}
		} elseif ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			return Redirect::to('/master/customer/edit');
		} else {
			return view('errors.403');
		}
	}
	public function Save(Request $req) {

		if ($req->PostalCodeID == $req->PostalCode) {
			$req->PostalCodeID = $this->general->Check_and_Create_PostalCode($req->PostalCode, $req->Country, $req->State, $this->DocNum);
		}
		$OldData = $NewData = array();
		$RoleID = "";
		if ($this->general->isCrudAllow($this->CRUD, "add") == true) {
			$rules = array(
				"CImg" => 'nullable',
				"CName" => 'required',
				"Email" => 'required|email',
				"Address" => 'required|min:10',
				"CityID" => 'required',
				"StateID" => 'required',
				"CountryID" => 'required',
				"MobileNumber" => ['required', 'max:10', new ValidUnique(array("TABLE" => "tbl_user_info", "WHERE" => " MobileNumber='" . $req->MobileNumber . "' "), "This Mobile Number is already taken.")],
				"ActiveStatus" => 'required',
			);
			$message = array(
				// 'CImg.required' => 'Image is required',
				'CName.required' => 'FirstName is required',
				'CName.min' => 'FirstName must be at least 3 characters',
				'CName.max' => 'FirstName may not be greater than 100 characters',
				'CName.unique' => 'The FirstName has already been taken.',
				'Address.required' => 'Address is required',
				'Address.min' => 'Address must be at least 3 characters',
				'Address.max' => 'Address may not be greater than 100 characters',
			);

			$validator = Validator::make($req->all(), $rules, $message);

			if ($validator->fails()) {
				session()->flash('error', 'User Role Create Failed');
				return array('status' => false, 'message' => "User Role Create Failed", 'errors' => $validator->errors());
			}
			DB::beginTransaction();
			$status = false;
			$ProfileImage = "";
			try {

				$RoleID = $this->DocNum->getDocNum("Customer");
				$UserRights = json_decode($req->CRUD, true);

				if ($req->hasFile('CImg')) {
					$dir = "uploads/master/customer/customer/";
					if (!file_exists($dir)) {
						mkdir($dir, 0777, true);
					}
					$file = $req->file('CImg');
					$fileName = md5($file->getClientOriginalName() . time());
					$fileName1 = $fileName . "." . $file->getClientOriginalExtension();
					$file->move($dir, $fileName1);
					$ProfileImage = $dir . $fileName1;
				}

				$data = array(
					"CID" => $RoleID,
					"CName" => $req->CName,
					"CImg" => $ProfileImage,
					"Email" => $req->Email,
					"Address" => $req->Address,
					"CityID" => $req->City,
					"StateID" => $req->CityID,
					"CountryID" => $req->CountryID,
					"MobileNumber" => $req->MobileNumber,
					"ActiveStatus" => $req->ActiveStatus,
					"DFlag" => 0,
					"CreatedBy" => $this->UserID,
					"CreatedOn" => date("Y-m-d H:i:s"),
				);

				$status = DB::table('tbl_customer')->insert($data);

				if ($status == true) {
					if ($ProfileImage != "") {
						$data['CImg'] = $ProfileImage;
						$status = DB::Table('tbl_customer')->where('CID', $RoleID)->update($data);
					}
				}

			} catch (Exception $e) {
				$status = false;
			}
			if ($status == true) {
				DB::commit();

				$this->DocNum->updateDocNum("Customer");
				// $this->DocNum->updateDocNum("Shipping-Address");
				$NewData = DB::Table('tbl_customer')->where('CID', $RoleID)->get();
				$logData = array("Description" => "New User Created ", "ModuleName" => "User-Role", "Action" => "Add", "ReferID" => $RoleID, "OldData" => "", "NewData" => serialize($NewData), "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				return array('status' => true, 'message' => "Customer Create Successfully");
			} else {
				DB::rollback();
				return array('status' => false, 'message' => "Customer Create Failed");
			}
		}
	}

	public function Update(Request $req, $UserID) {

		if ($req->PostalCodeID == $req->PostalCode) {

			$req->PostalCodeID = $this->general->Check_and_Create_PostalCode($req->PostalCode, $req->Country, $req->State, $this->DocNum);
		}

		if ($this->general->isCrudAllow($this->CRUD, "edit") == true) {



			$rules = array(
				"CImg" => 'nullable',
				"CName" => 'required',
				"Email" => 'required|email',
				"Address" => 'required|min:10',
				"CityID" => 'required',
				"StateID" => 'required',
				"CountryID" => 'required',
				"MobileNumber" => ['required', 'max:10', new ValidUnique(array("TABLE" => "tbl_user_info", "WHERE" => " MobileNumber='" . $req->MobileNumber . "' "), "This Mobile Number is already taken.")],
				"ActiveStatus" => 'required',
			);
			$message = array(
				// 'CImg.required' => 'Image is required',
				'CName.required' => 'FirstName is required',
				'CName.min' => 'FirstName must be at least 3 characters',
				'CName.max' => 'FirstName may not be greater than 100 characters',
				'CName.unique' => 'The FirstName has already been taken.',
				'Address.required' => 'Address is required',
				'Address.min' => 'Address must be at least 3 characters',
				'Address.max' => 'Address may not be greater than 100 characters',
			);
			$validator = Validator::make($req->all(), $rules, $message);

			if ($validator->fails()) {
				return array('status' => false, 'message' => "Customer Update Failed", 'errors' => $validator->errors());
			}
			$status = false;
			try {
				$OldData = (array) DB::table('tbl_customer')->where('CID', $UserID)->get();

				$UserRights = json_decode($req->CRUD, true);

				if ($req->hasFile('CImg')) {
					$dir = "uploads/master/customer/customer/";
					if (!file_exists($dir)) {
						mkdir($dir, 0777, true);
					}
					$file = $req->file('CImg');
					$fileName = md5($file->getClientOriginalName() . time());
					$fileName1 = $fileName . "." . $file->getClientOriginalExtension();
					$file->move($dir, $fileName1);
					$ProfileImage = $dir . $fileName1;
				} else {
					$ProfileImage = null;
				}

				$data = array(
					"CID" => $UserID,
					"CName" => $req->CName,
					"CImg" => $ProfileImage,
					"Email" => $req->Email,
					"Address" => $req->Address,
					"CityID" => $req->City,
					"StateID" => $req->CityID,
					"CountryID" => $req->CountryID,
					"MobileNumber" => $req->MobileNumber,
					"ActiveStatus" => $req->ActiveStatus,
					"DFlag" => 0,
					"CreatedBy" => $this->UserID,
					"CreatedOn" => date("Y-m-d H:i:s"),
				);

				$status = DB::table('tbl_customer')->where('CID', $UserID)->Update($data);

				if ($status == true) {
					if ($ProfileImage != "") {
						$data['CImage'] = $ProfileImage;
						$status = DB::Table('tbl_customer')->where('CID', $UserID)->update($data);
					}

					$NewData = (array) DB::table('tbl_customer')->get();
					$logData = array("Description" => "customer Updated ", "ModuleName" => "Customer", "Action" => "Update", "ReferID" => $UserID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
					$this->logs->Store($logData);

				}


			} catch (Exception $e) {
				$status = false;
			}
			if ($status == true) {
				DB::commit();
				return array('status' => true, 'message' => "Customer Update Successfully");
			} else {
				DB::rollback();
				return array('status' => false, 'message' => "Customer Update Failed");
			}

		}
	}

	public function Delete(Request $req, $CID) {
		$OldData = $NewData = array();
		if ($this->general->isCrudAllow($this->CRUD, "delete") == true) {
			DB::beginTransaction();
			$status = false;
			try {
				$OldData = DB::table('tbl_customer')->where('CID', $CID)->get();
				$status = DB::table('tbl_customer')->where('CID', $CID)->update(array("DFlag" => 1, "DeletedBy" => $this->UserID, "DeletedOn" => date("Y-m-d H:i:s")));
			} catch (Exception $e) {

			}
			if ($status == true) {
				DB::commit();
				$logData = array("Description" => "customer has been Deleted ", "ModuleName" => "customer", "Action" => "Delete", "ReferID" => $CID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				return array('status' => true, 'message' => "customer Deleted Successfully");
			} else {
				DB::rollback();
				return array('status' => false, 'message' => "customer Delete Failed");
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
				$OldData = DB::table('tbl_customer')->where('CID', $CID)->get();
				$status = DB::table('tbl_customer')->where('CID', $CID)->update(array("DFlag" => 0, "UpdatedBy" => $this->UserID, "UpdatedOn" => date("Y-m-d H:i:s")));
			} catch (Exception $e) {

			}
			if ($status == true) {
				DB::commit();
				$NewData = DB::table('tbl_customer')->where('CID', $CID)->get();
				$logData = array("Description" => "Customer has been Restored ", "ModuleName" => "Customer", "Action" => "Restore", "ReferID" => $CID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
				$this->logs->Store($logData);
				return array('status' => true, 'message' => "Customer Restored Successfully");
			} else {
				DB::rollback();
				return array('status' => false, 'message' => "Customer Restore Failed");
			}
		} else {
			return response(array('status' => false, 'message' => "Access Denied"), 403);
		}
	}
	public function TableView(Request $request) {

		if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
			$ServerSideProcess = new ServerSideProcess();
			$columns = array(
				array('db' => 'CImg', 'dt' => '0'),
				array('db' => 'CName', 'dt' => '1'),
				array('db' => 'MobileNumber', 'dt' => '2'),
				array('db' => 'Email', 'dt' => '3'),
				array('db' => 'Address', 'dt' => '4'),
				array('db' => 'Gender', 'dt' => '5'),
				array('db' => 'CountryID', 'dt' => '6'),
				array(
					'db' => 'ActiveStatus',
					'dt' => '7',
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
					'dt' => '8',
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
			$data['TABLE'] = 'tbl_customer';
			$data['PRIMARYKEY'] = 'CID';
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
				array('db' => 'CImg', 'dt' => '0'),
				array('db' => 'CName', 'dt' => '1'),
				array('db' => 'MobileNumber', 'dt' => '2'),
				array('db' => 'Email', 'dt' => '3'),
				array('db' => 'Address', 'dt' => '4'),
				array('db' => 'Gender', 'dt' => '5'),
				array('db' => 'CountryID', 'dt' => '6'),
				array(
					'db' => 'ActiveStatus',
					'dt' => '7',
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
					'dt' => '8',
					'formatter' => function ($d, $row) {
						$html = '<button type="button" data-id="' . $d . '" class="btn btn-outline-success btn-sm  m-2 btnRestore"> <i class="fa fa-repeat" aria-hidden="true"></i> </button>';
						return $html;
					}
				)
			);

			$data = array();
			$data['POSTDATA'] = $request;
			$data['TABLE'] = 'tbl_customer';
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