<?php

namespace App\Models;

use Exception;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ValidUniqueModel extends Model {
	use HasFactory;

	public function Validate_Unique($data) {
		$return = false;
		if (is_array($data)) {
			if (array_key_exists("TABLE", $data)) {
				try {
					$sql = "SELECT COUNT(*) as RCOUNT FROM " . $data['TABLE'] . " WHERE 1=1";
					if (array_key_exists("WHERE", $data)) {
						$sql .= " AND " . $data['WHERE'];
					}
					$result = DB::select($sql);
					if (count($result) > 0) {
						if ($result[0]->RCOUNT == 0) {
							$return = true;
						}
					}
				} catch (Exception $e) {
				}
			}
		}
		return $return;
	}
	public function check_data($data) {
		$return = false;
		if (is_array($data)) {
			if (array_key_exists("TABLE", $data)) {
				try {
					$sql = "SELECT COUNT(*) as RCOUNT FROM " . $data['TABLE'] . " WHERE 1=1";
					if (array_key_exists("WHERE", $data)) {
						foreach ($data['WHERE'] as $KeyName => $KeyValue) {
							$sql .= " AND " . $KeyValue['COLUMN'] . $KeyValue['CONDITION'] . "'" . $KeyValue['VALUE'] . "'";
						}
					}
					if (array_key_exists("WHERECUSTOM", $data)) {
						$sql .= $data['WHERECUSTOM'];
					}
					$result = DB::select($sql); //echo $sql;
					if (count($result) > 0) {
						if ($result[0]->RCOUNT > 0) {
							$return = true;
						}
					}
				} catch (Exception $e) {
				}
			}
		}
		return $return;
	}
}