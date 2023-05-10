<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use App\Models\DocNum;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;

class general extends Model {
	use HasFactory;
	public $UserInfo;
	private $DocNum;
	private $UserID;
	private $ActiveMenuName;
	public function __construct($UserID, $ActiveMenuName) {
		$this->UserID = $UserID;
		$this->DocNum = new DocNum();
		$this->ActiveMenuName = $ActiveMenuName;
		$this->UserInfo = array("UInfo" => array());
		$result = $this->getUserInfo($this->UserID);
		if (count($result) > 0) {
			if (!file_exists(__DIR__ . $result[0]->ProfileImage)) {
				$result[0]->ProfileImage = "";
			}
			if (($result[0]->ProfileImage == "") || ($result[0]->ProfileImage == null)) {
				if (strtolower($result[0]->Gender) == "female") {
					$result[0]->ProfileImage = "assets/images/female-icon.png";
				} else {
					$result[0]->ProfileImage = "assets/images/male-icon.png";
				}
			}
			$this->UserInfo['UInfo'] = $result[0];
			$this->UserInfo['Theme'] = $this->getThemesOption($this->UserID);
			$this->UserInfo['CRUD'] = $this->getUserRights($result[0]->RoleID);
			$this->UserInfo['Settings'] = $this->getSettings();
		}
	}
	public function getUserInfo($UserID) {
		$return = array();

		$sql = "SELECT U.ID, U.UserID, U.RoleID, UR.RoleName, U.Name, U.EMail AS UserName, UI.EMail, UI.FirstName, UI.LastName, UI.DOB, UI.GenderID, G.Gender, UI.Address, UI.CityID, CI.CityName, UI.StateID, S.StateName, UI.CountryID, CO.CountryName, CO.PhoneCode, UI.PostalCodeID, PC.PostalCode, UI.EMail, UI.MobileNumber, UI.ProfileImage, U.ActiveStatus, U.DFlag FROM `users` AS U LEFT JOIN `tbl_user_info` AS UI ON UI.UserID = U.UserID LEFT JOIN `tbl_cities` AS CI On CI.CityID = UI.CityID LEFT JOIN `tbl_countries` AS CO ON CO.CountryID = UI.CountryID LEFT JOIN `tbl_states` AS S ON S.StateID = UI.StateID LEFT JOIN `tbl_postalcodes` AS PC ON PC.PID = UI.PostalCodeID LEFT JOIN `tbl_genders` AS G ON G.GID = UI.GenderID LEFT JOIN `tbl_user_roles` AS UR ON UR.RoleID = U.RoleID WHERE U.UserID = ?";

		$return = DB::select($sql, [$UserID]);
		return $return;
	}
	public function getThemesOption($UserID) {
		$return = array();
		$result = DB::Table('tbl_user_theme')->where('UserID', $UserID)->get();
		if (count($result) > 0) {
			for ($i = 0; $i < count($result); $i++) {
				$return[$result[$i]->Theme_option] = $result[$i]->Theme_Value;
			}
		}
		return $return;
	}

	public function getSettings() {
		$settings = array(
			"DATE-FORMAT" => "d-M-Y",
			"TIME-FORMAT" => "h:i:s A",
			"WEIGHT-DECIMAL-LENGTH" => 3,
			"PRICE-DECIMAL-LENGTH" => 2,
			"QTY-DECIMAL-LENGTH" => 0,
			"PERCENTAGE-DECIMAL-LENGTH" => 2,
			"DISTANCE-RANGE" => 2,
		);

		$result = DB::Table('tbl_settings')->get();

		for ($i = 0; $i < count($result); $i++) {
			if (strtolower($result[$i]->SType) == "serialize") {
				$settings[$result[$i]->KeyName] = unserialize($result[$i]->KeyValue);
			} elseif (strtolower($result[$i]->SType) == "json") {
				$settings[$result[$i]->KeyName] = json_decode($result[$i]->KeyValue, true);
			} else {
				$settings[$result[$i]->KeyName] = $result[$i]->KeyValue;
			}
		}
		return $settings;
	}
	public function getUserRights($RoleID) {
		$return = null;
		$result = DB::Table('tbl_user_roles')->where('RoleID', $RoleID)->get();
		if (count($result) > 0) {
			$return = unserialize($result[0]->CRUD);
		}
		return $return;
	}

	public function EncryptDecrypt($action, $string) {
		$output = false;
		$action = strtoupper($action);
		$encrypt_method = "AES-256-CBC";
		$secret_key = 'hSEjc5LcDzxLSoP';
		$secret_iv = 'n2dg7g4MerIxrnEPu3xLEeZOBZOUJ6b2UkHpbKLCxZSabegSVB';
		$key = hash('sha256', $secret_key);
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
		if ($action == 'ENCRYPT') {
			$output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
			$output = strrev(base64_encode($output));
		} elseif ($action == 'DECRYPT') {
			$output = openssl_decrypt(base64_decode(strrev($string)), $encrypt_method, $key, 0, $iv);
			;
		}
		return $output;
	}

	public function loadMenu() {
		$Menus = $this->getMenus(array("Level" => "L001"));
		return $this->loadHtmlMenu($Menus);
	}
	public function loadHtmlMenu($Menus) {

		$html = '';
		for ($i = 0; $i < count($Menus); $i++) {
			if ($Menus[$i]['hasSubMenu'] == 1) {
				$SubMenus = $this->loadHtmlMenu($Menus[$i]['SubMenu']);

				if ($SubMenus != "") {
					$html .= "
					<li class=\"nav-item w-100 " . $Menus[$i]['Level'] . "\">
						<a data-bs-toggle=\"collapse\" data-bs-target=\"#" . $Menus[$i]['Slug'] . "\" class=\"nav-link " . $Menus[$i]['Slug'] . "\">
							<i class=\"me-2\">" . $Menus[$i]['Icon'] . "</i>
							<span class=\"w-100\">" . $Menus[$i]['MenuName'] . "</span>
							<i class=\"fa fa-chevron-down small\"></i>
						</a>
					";
					$html .= "<ul class=\"nav-content collapse mt-2 \" data-bs-parent=\"#sidebar-nav\" id=\"" . $Menus[$i]['Slug'] . "\">" . $SubMenus . "</ul></li>";
				}
			} else {
				$defaultIcon = "<i class=\"bi bi-circle\"></i>";
				$originalIcon = "<i class=\"me-2 svgmenus\" >" . $Menus[$i]['Icon'] . "</i>";
				$menuIcon = is_null($Menus[$i]['Icon']) ? $defaultIcon : $originalIcon;

				$isAllow = true;
				// $isAllow = $this->isAllow($Menus[$i]['MID']);
				// $isAllow = true;	
				if ($isAllow == true) {
					if ($Menus[$i]['Level'] == "L001") {
						if ($Menus[$i]['MID'] == "M2021-0000008") {
							$activeElement = $Menus[$i]['ActiveName'] === $this->ActiveMenuName ? "active" : "";
							$html .= "
							<li class=\"nav-item " . $Menus[$i]['Level'] . "\">
								<a href='" . url('/') . '/' . $Menus[$i]['PageUrl'] . "' class=\"nav-link " . $activeElement . " \">
									" . $menuIcon . "
									<span>" . $Menus[$i]['MenuName'] . "</span>
								</a>
							</li>";
						} else {
							$activeElement = $Menus[$i]['ActiveName'] === $this->ActiveMenuName ? "active" : "";
							$html .= "
							<li class=\"nav-item " . $Menus[$i]['Level'] . "\">
								<a href='" . URL::to('/') . '/' . $Menus[$i]['PageUrl'] . "' class=\"nav-link " . $activeElement . " \">
									" . $menuIcon . "
									<span>" . $Menus[$i]['MenuName'] . "</span>
								</a>
							</li>";
						}
					} else {
						$activeElement = $Menus[$i]['ActiveName'] === $this->ActiveMenuName ? "active" : "";
						$html .= "
						<li class=\"nav-item\">
							<a href='" . url('/') . '/' . $Menus[$i]['PageUrl'] . "' class=\"nav-link " . $activeElement . " \">
								" . $menuIcon . "
								<span>" . $Menus[$i]['MenuName'] . "</span>
							</a>
						</li>";
					}
				}
			}
		}
		return $html;
	}
	private function isAllow($MenuID) {
		$allow = false;
		if (($MenuID == "M2022-0000001") || ($MenuID == "M2021-0000047")) {
			$allow = true;
		} else {
			if (is_array($this->UserInfo['CRUD'])) {
				if (array_key_exists($MenuID, $this->UserInfo['CRUD'])) {
					$CRUD = $this->UserInfo['CRUD'][$MenuID];
					if (($CRUD['add'] == 0) && ($CRUD['view'] == 0) && ($CRUD['edit'] == 0) && ($CRUD['delete'] == 0) && ($CRUD['copy'] == 0) && ($CRUD['excel'] == 0) && ($CRUD['csv'] == 0) && ($CRUD['print'] == 0)) {
						$allow = false;
					} else {
						$allow = true;
					}
				}
			}
		}
		return true;
	}
	public function getMenus($data = null) {
		$return = array();
		$sql = "Select MID,Slug,MenuName,ActiveName,Icon,PageUrl,ParentID,Level,hasSubMenu,Ordering,DFlag  From tbl_menus Where DFlag=0 and ActiveStatus=1 ";
		if (is_array($data)) {
			if (array_key_exists("MID", $data)) {
				$sql .= " and MID='" . $data['MID'] . "'";
			}
			if (array_key_exists("Slug", $data)) {
				$sql .= " and Slug='" . $data['Slug'] . "'";
			}
			if (array_key_exists("ParentID", $data)) {
				$sql .= " and ParentID='" . $data['ParentID'] . "'";
			}
			if (array_key_exists("Level", $data)) {
				$sql .= " and Level='" . $data['Level'] . "'";
			}
			if (array_key_exists("ActiveName", $data)) {
				$sql .= " and ActiveName='" . $data['ActiveName'] . "'";
			}

		}
		$sql .= " Order By Ordering"; //echo $sql;
		$result = DB::select($sql);
		for ($i = 0; $i < count($result); $i++) {
			$r = array();
			$isAllow = true;
			$SubMenu = $this->getMenus(array("ParentID" => $result[$i]->MID));

			$r['MID'] = $result[$i]->MID;
			$r['Slug'] = $result[$i]->Slug;
			$r['MenuName'] = $result[$i]->MenuName;
			$r['ActiveName'] = $result[$i]->ActiveName;
			$r['Icon'] = $result[$i]->Icon;
			$r['PageUrl'] = $result[$i]->PageUrl;
			$r['ParentID'] = $result[$i]->ParentID;
			$r['Level'] = $result[$i]->Level;
			$r['SubMenu'] = $SubMenu;
			$r['Crud'] = $this->getCrud($result[$i]->MID);
			if (count($SubMenu) > 0) {
				$r['hasSubMenu'] = 1;
			} else {
				$r['hasSubMenu'] = 0;
			}
			if ($result[$i]->hasSubMenu == 1) {
				if (count($SubMenu) <= 0) {
					$isAllow = false;
				}
			}
			if ($isAllow == true) {
				$return[] = $r;
			}
		}
		return $return;
	}
	public function getCrud($MenuID) {
		$return = array("Add" => 0, "View" => 0, "Edit" => 0, "Delete" => 0, "Copy" => 0, "Excel" => 0, "CSV" => 0, "Print" => 0, "PDF" => 0, "Restore" => 0, "Approval" => 0);
		$main = array("Add" => 1, "View" => 1, "Edit" => 1, "Delete" => 1, "Copy" => 1, "Excel" => 1, "CSV" => 1, "Print" => 1, "PDF" => 1, "Restore" => 1, "Approval" => 1);
		$result = DB::Table('tbl_cruds')->where('MID', $MenuID)->get();
		if (count($result) > 0) {
			$return["Add"] = $result[0]->add;
			$return["View"] = $result[0]->view;
			$return["Edit"] = $result[0]->edit;
			$return["Delete"] = $result[0]->delete;
			$return["Copy"] = $result[0]->copy;
			$return["Excel"] = $result[0]->excel;
			$return["CSV"] = $result[0]->csv;
			$return["Print"] = $result[0]->print;
			$return["PDF"] = $result[0]->pdf;
			$return["Restore"] = $result[0]->restore;
			$return["Approval"] = $result[0]->approval;
		}
		return $main;
	}
	public function Check_and_Create_PostalCode($PostalCode, $CountryID, $StateID, $DocNumModel) {
		$PostalCodeID = "";

		$result = DB::Table('tbl_postalcodes')->where('PostalCode', $PostalCode)->get();
		if (count($result) <= 0) {
			$PostalCodeID = $this->DocNum->getDocNum("POSTAL-CODE");
			$data = array(
				"PID" => $PostalCodeID,
				"PostalCode" => $PostalCode,
				"CountryID" => $CountryID,
				'StateID' => $StateID,
				"CreatedBy" => $this->UserID,
				"CreatedOn" => date("Y-m-d H:i:s")
			);
			$result = DB::Table('tbl_postalcodes')->insert($data);

			if ($result == true) {
				$DocNumModel = $this->DocNum->updateDocNum("POSTAL-CODE");
				$result1 = DB::Table('tbl_postalcodes')->where('PostalCode', $PostalCode)->get();
				if (count($result1) > 0) {
					$PostalCodeID = $result1[0]->PID;
				}
			}
		} else {
			$PostalCodeID = $result[0]->PID;
		}
		return $PostalCodeID;
	}
	public function isCrudAllow($CRUD, $Action) {
		$allow = false;
		$Action = strtolower($Action);
		if (array_key_exists($Action, $CRUD)) {
			if ($CRUD[$Action] == 1) {
				$allow = true;
			}
		}
		return $allow;
	}
	public function getCrudOperations($ActiveName) {
		$MID = "";
		$result = $this->getMenus(array("ActiveName" => $ActiveName));
		if (count($result) > 0) {
			$MID = $result[0]['MID'];
		}
		$return = array("add" => 0, "view" => 0, "edit" => 0, "felete" => 0, "copy" => 0, "excel" => 0, "csv" => 0, "print" => 0, "pdf" => 0, "restore" => 0, "approval" => 0);
		if (is_array($this->UserInfo['CRUD'])) {
			if (array_key_exists($MID, $this->UserInfo['CRUD'])) {
				$return = $this->UserInfo['CRUD'][$MID];
			}
		}
		$return = array("add" => 1, "view" => 1, "edit" => 1, "felete" => 1, "copy" => 1, "excel" => 1, "csv" => 1, "print" => 1, "pdf" => 1, "restore" => 1, "approval" => 1);
		return $return;
	}
	public function RandomString($len) {
		$validCharacters = "AaBbCcDdEeFfGgHhIiJjKkLlMmNnPpQqRrSsTtUuXxYyVvWwZz1234567890";
		$validCharNumber = strlen($validCharacters);
		$result = "";
		for ($i = 0; $i < $len; $i++) {
			$index = mt_rand(0, $validCharNumber - 1);
			$result .= $validCharacters[$index];
		}
		return $result;
	}
	public function OTPGenerator($len) {
		$validCharacters = "1234567890";
		$validCharNumber = strlen($validCharacters);
		$result = "";
		for ($i = 0; $i < $len; $i++) {
			$index = mt_rand(0, $validCharNumber - 1);
			$result .= $validCharacters[$index];
		}
		return $result;
	}
}