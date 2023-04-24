<?php

namespace App\Models;


use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class DocNum extends Model {
	use HasFactory;
	public function getDocNum($DocType) {
		$result = DB::Select("SELECT SLNO,DocType,Prefix,Length,CurrNum,IFNULL(Suffix,'') as Suffix,IFNULL(Year,'') as Year FROM tbl_docnum Where DocType='" . $DocType . "'");
		if (count($result) > 0) {
			$DocNum = $result[0];
			if ($DocNum->Year != "") {
				if (intval($DocNum->Year) != intval(date("Y"))) {
					DB::table('tbl_docnum')->where('DocType', $DocType)->update(array("Year" => date("Y"), "CurrNum" => 1));
					return $this->getDocNum($DocType);
				}
			}
			$return = $DocNum->Prefix . date("Y") . "-" . str_pad($DocNum->CurrNum, $DocNum->Length, '0', STR_PAD_LEFT);
			return $return;
		}
		return '';
	}
	public function updateDocNum($DocType) {
		$sql = "Update tbl_docnum SET CurrNum=CurrNum+1 WHERE DocType='" . $DocType . "'";
		$result = DB::statement($sql);
		return $result;
	}
}