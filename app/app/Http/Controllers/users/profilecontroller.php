<?php

namespace App\Http\Controllers\users;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\DocNum;
use App\Models\general;
use App\Rules\ValidDB;

use App\Http\Controllers\logController;
use Exception;
use Illuminate\Support\Facades\DB;

class profilecontroller extends Controller {
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
		$this->ActiveMenuName = "USERPRFILE";
		$this->PageTitle = "USERPROFILE";
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
	public function Profile() {

		$FormData = $this->general->UserInfo;
		$FormData['ActiveMenuName'] = $this->ActiveMenuName;
		$FormData['PageTitle'] = $this->PageTitle;
		$FormData['menus'] = $this->Menus;
		$FormData['UserData'] = $this->Get_User_info(auth()->user()->UserID)[0];
		$FormData['crud'] = $this->CRUD;
		return view('Users.profile.profile', $FormData);

	}
	public function Get_User_info($UserID) {
		$return = array();
		$sql = "Select U.ID,U.UserID,U.RoleID,UR.RoleName,U.Name,U.EMail as UserName,UI.EMail,UI.FirstName,UI.LastName,UI.DOB,UI.GenderID,G.Gender,UI.Address,UI.CityID,CI.CityName,UI.StateID,S.StateName,UI.CountryID,CO.CountryName,CO.PhoneCode,UI.PostalCodeID,PC.PostalCode,UI.EMail,UI.MobileNumber,UI.ProfileImage,U.ActiveStatus,U.DFlag From users AS U LEFT JOIN tbl_user_info AS UI ON UI.UserID=U.UserID left join tbl_cities AS CI On CI.CityID=UI.CityID Left Join tbl_countries AS CO ON CO.CountryID=UI.CountryID LEFT JOIN tbl_states as S On S.StateID=UI.StateID  Left Join tbl_postalcodes as PC On PC.PID=UI.PostalCodeID Left Join tbl_genders as G On G.GID=UI.GenderID Left join tbl_user_roles as UR ON UR.RoleID=U.RoleID Where U.UserID='" . $UserID . "'";

		$return = DB::select($sql);
		return $return;
	}

	public function UpdateProfile(Request $req) {
		$sql = "Select UI.UserID,U.EMAIL as UserName,UI.FirstName,UI.LastName,UI.DOB,UI.GenderID,UI.Address,UI.CityID,UI.StateID,UI.CountryID,UI.PostalCodeID,UI.EMail,UI.MobileNumber,U.RoleID,UI.ProfileImage,UI.ActiveStatus From tbl_user_info as UI LEFT JOIN users AS U ON U.UserID=UI.UserID";
		$sql .= " where UI.DFlag=0 and UI.UserID='" . $this->UserID . "'";

		$FormData = $this->general->UserInfo;
		$FormData['ActiveMenuName'] = $this->ActiveMenuName;
		$FormData['PageTitle'] = "Profile";
		$FormData['menus'] = $this->Menus;
		$FormData['CRUD'] = $this->CRUD;
		$FormData['isEdit'] = true;
		$FormData['COUNTRY'] = DB::table('tbl_countries')->get();
		$FormData['Genders'] = DB::table('tbl_genders')->where('ActiveStatus', 1)->Where("DFlag", 0)->get();
		$FormData['UserRoles'] = DB::table("tbl_user_roles")->where('ActiveStatus', 1)->Where("DFlag", 0)->Where("isShow", 1)->get();
		$FormData['EditData'] = DB::select($sql);
		// gettting user data

		//return $FormData['EditData'];

		return view('Users.profile.updateprofile', $FormData);
	}
	public function ProfileUpdate(Request $req) {

		$OldData = $NewData = array();
		if ($req->PostalCodeID == $req->PostalCode) {
			// $req->PostalCodeID = $this->general->Check_and_Create_PostalCode($req->PostalCode, $req->CountryID, $req->StateID, $this->DocNum);
		}

		$ValidDB = array();
		$ValidDB['City']['TABLE'] = "tbl_cities";
		$ValidDB['City']['ErrMsg'] = "City name does  not exist";
		$ValidDB['City']['WHERE'][] = array("COLUMN" => "CityID", "CONDITION" => "=", "VALUE" => $req->CityID);
		$ValidDB['City']['WHERE'][] = array("COLUMN" => "StateID", "CONDITION" => "=", "VALUE" => $req->StateID);

		$ValidDB['State']['TABLE'] = "tbl_states";
		$ValidDB['State']['ErrMsg'] = "State name does  not exist";
		$ValidDB['State']['WHERE'][] = array("COLUMN" => "StateID", "CONDITION" => "=", "VALUE" => $req->StateID);
		$ValidDB['State']['WHERE'][] = array("COLUMN" => "CountryID", "CONDITION" => "=", "VALUE" => $req->CountryID);

		$ValidDB['Country']['TABLE'] = "tbl_countries";
		$ValidDB['Country']['ErrMsg'] = "Country name  does not exist";
		$ValidDB['Country']['WHERE'][] = array("COLUMN" => "CountryID", "CONDITION" => "=", "VALUE" => $req->CountryID);

		$ValidDB['Gender']['TABLE'] = "tbl_genders";
		$ValidDB['Gender']['ErrMsg'] = "Gender  does not exist";
		$ValidDB['Gender']['WHERE'][] = array("COLUMN" => "GID", "CONDITION" => "=", "VALUE" => $req->GenderID);

		$ValidDB['PostalCode']['TABLE'] = "tbl_postalcodes";
		$ValidDB['PostalCode']['ErrMsg'] = "Postal Code  does not exist";
		$ValidDB['PostalCode']['WHERE'][] = array("COLUMN" => "PID", "CONDITION" => "=", "VALUE" => $req->PostalCodeID);

		$rules = array(
			'FirstName' => 'required|min:3|max:100',
			'LastName' => 'max:100',
			'GenderID' => ['required', new ValidDB($ValidDB['Gender'])],
			//'Address' =>'min:5',
			'CityID' => ['required', new ValidDB($ValidDB['City'])],
			'StateID' => ['required', new ValidDB($ValidDB['State'])],
			'CountryID' => ['required', new ValidDB($ValidDB['Country'])],
			'PostalCode' => ['required', new ValidDB($ValidDB['PostalCode'])],
			'ProfileImage' => 'mimes:jpeg,jpg,png,gif,bmp'
		);
		$message = array(
			'DOB.required' => 'Date of Birth is required',
			'DOB.date' => 'Date of Birth is not valid a date',
			'DOB.before' => 'The Date of Birth must be a date before ' . date("01-01-Y", strtotime(" - 18 year")),
			'DOJ.required' => 'Date of Joining  is required',
			'DOJ.date' => 'Date of Joining  is not valid a date',
			'EMail.required' => 'E-Mail Address is required',
			'EMail.email' => 'E-Mail Address is not valid',
			'EMail.unique' => 'E-Mail Address is already taken',
			'GenderID.required' => 'Gender is required',
			'CityID.required' => 'City is required',
			'StateID.required' => 'State is required',
			'CountryID.required' => 'Country is required',
			'PostalCodeID.required' => 'PostalCode is required'
		);
		if ($req->EMail != "") {
			$rules["EMail"] = 'email';
		}
		if ($req->DOB != "") {
			$rules["DOB"] = 'required|date|before:' . date("Y-01-01", strtotime(" - 18 year"));
		}
		$validator = Validator::make($req->all(), $rules, $message);

		if ($validator->fails()) {
			return array('success' => false, 'message' => "Profile Update Failed", 'errors' => $validator->errors());
		}
		DB::beginTransaction();

		$status = false;
		try {
			$OldData = DB::table('tbl_user_info')->where('UserID', $this->UserID)->get();

			$ProfileImage = "";
			if ($req->hasFile('ProfileImage')) {

				$dir = "Uploads/users-and-permissions/users/";

				if (!file_exists($dir)) {
					mkdir($dir, 0777, true);
				}

				$file = request()->file('ProfileImage');

				$fileName = md5($file->getClientOriginalName() . time());

				$fileName1 = $fileName . "." . $file->getClientOriginalExtension();
				$file->move($dir, $fileName1);
				$ProfileImage = $dir . $fileName1;
			}
			$Name = trim($req->FirstName . " " . $req->LastName);
			$data = array(
				"Name" => $Name,
				"FirstName" => $req->FirstName,
				"LastName" => $req->LastName,
				"DOB" => $req->DOB,
				"GenderID" => $req->GenderID,
				"Address" => $req->Address,
				"CityID" => $req->CityID,
				"StateID" => $req->StateID,
				"CountryID" => $req->CountryID,
				"PostalCodeID" => $req->PostalCodeID,
				"EMail" => $req->EMail,
				"UpdatedBy" => $this->UserID,
				"UpdatedOn" => date("Y-m-d H:i:s")
			);

			$data['ProfileImage'] = $ProfileImage;

			$status = DB::table('tbl_user_info')->where('UserID', $this->UserID)->update($data);
			if ($status == true) {
				$UserData = array("name" => $Name, "UpdatedBy" => $this->UserID, "updated_at" => date("Y-m-d H:i:s"));
				$status = DB::table("users")->where('UserID', $this->UserID)->update($UserData);
			}
		} catch (Exception $e) {
			$status = false;
		}
		if ($status == true) {
			$NewData = DB::table('tbl_user_info')->where('UserID', $this->UserID)->get();
			$logData = array("Description" => "User Info Updated", "ModuleName" => "User", "Action" => "Update", "ReferID" => $this->UserID, "OldData" => $OldData, "NewData" => $NewData, "UserID" => $this->UserID, "IP" => $req->ip());
			// $this->logs->Logs_Store($logData);
			DB::commit();
			return array('status' => true, 'message' => "User Updated Successfully");
		} else {
			DB::rollback();
			return array('status' => false, 'message' => "User Update Failed");
		}
	}
}