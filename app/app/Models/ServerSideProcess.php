<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ServerSideProcess extends Model {
	use HasFactory;
	private function data_output($columns, $data) {
		$out = array();
		for ($i = 0, $ien = count($data); $i < $ien; $i++) {
			$row = array();
			for ($j = 0, $jen = count($columns); $j < $jen; $j++) {
				$column = $columns[$j];
				// Is there a formatter?
				if (isset($column['formatter'])) {
					$row[$column['dt']] = $column['formatter']($data[$i][$column['db']], $data[$i]);
				} else {
					$row[$column['dt']] = $data[$i][$columns[$j]['db']];
				}
			}
			$out[] = $row;
		}
		return $out;
	}
	private function limit($request, $columns) {
		$limit = '';
		if (isset($request['start']) && $request['length'] != -1) {
			$limit = "LIMIT " . intval($request['start']) . ", " . intval($request['length']);
		}
		return $limit;
	}
	private function order($request, $columns) {
		$order = '';
		if (isset($request['order']) && count($request['order'])) {
			$orderBy = array();
			$dtColumns = self::pluck($columns, 'dt');
			for ($i = 0, $ien = count($request['order']); $i < $ien; $i++) {
				$columnIdx = intval($request['order'][$i]['column']);
				$requestColumn = $request['columns'][$columnIdx];
				$columnIdx = array_search($requestColumn['data'], $dtColumns);
				$column = $columns[$columnIdx];
				if ($requestColumn['orderable'] == 'true') {
					$dir = $request['order'][$i]['dir'] === 'asc' ? 'ASC' : 'DESC';
					$orderBy[] = '' . $column['db'] . ' ' . $dir;
				}
			}
			if (count($orderBy)) {
				$order = 'ORDER BY ' . implode(', ', $orderBy);
			}
		}
		return $order;
	}
	private function filter($request, $columns, &$bindings) {
		$globalSearch = array();
		$columnSearch = array();
		$dtColumns = self::pluck($columns, 'dt');
		if (isset($request['search']) && $request['search']['value'] != '') {
			$str = $request['search']['value'];
			for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search($requestColumn['data'], $dtColumns);
				$column = $columns[$columnIdx];
				if ($requestColumn['searchable'] == 'true') {
					$globalSearch[] = "" . $column['db'] . " LIKE '%" . $str . "%'";
				}
			}
		}
		if (isset($request['columns'])) {
			for ($i = 0, $ien = count($request['columns']); $i < $ien; $i++) {
				$requestColumn = $request['columns'][$i];
				$columnIdx = array_search($requestColumn['data'], $dtColumns);
				$column = $columns[$columnIdx];
				$str = $requestColumn['search']['value'];
				if ($requestColumn['searchable'] == 'true' &&
					$str != '') {
					$columnSearch[] = "" . $column['db'] . " LIKE '%" . $str . "%'";
				}
			}
		}
		$where = '';
		if (count($globalSearch)) {
			$where = '(' . implode(' OR ', $globalSearch) . ')';
		}
		if (count($columnSearch)) {
			$where = $where === '' ?
				implode(' AND ', $columnSearch) :
				$where . ' AND ' . implode(' AND ', $columnSearch);
		}
		if ($where !== '') {
			$where = 'WHERE ' . $where;
		}
		return $where;
	}
	public function SSP($data) {
		$bindings = array();
		$localWhereResult = array();
		$localWhereAll = array();
		$whereAllSql = '';
		$limit = self::limit($data['POSTDATA'], $data['COLUMNS']);
		$order = self::order($data['POSTDATA'], $data['COLUMNS']);
		$where = self::filter($data['POSTDATA'], $data['COLUMNS'], $bindings);
		if ($data['WHERERESULT']) {
			if (($data['WHERERESULT'] != NULL) && ($data['WHERERESULT'] != "")) {
				$where = $where ? $where . ' AND ' . $data['WHERERESULT'] : 'WHERE ' . $data['WHERERESULT'];
			}
		}
		if ($data['WHEREALL']) {
			if (($data['WHEREALL'] != NULL) && ($data['WHEREALL'] != "")) {
				$where = $where ? $where . ' AND ' . $data['WHEREALL'] : 'WHERE ' . $data['WHEREALL'];
				$whereAllSql = 'WHERE ' . $data['WHEREALL'];
			}
		}
		// Main query to actually get the data
		$sql = "SELECT " . implode(",", self::pluck($data['COLUMNS'], 'db')) . " FROM " . $data['TABLE'];
		if (($where != "") && ($where != NULL)) {
			$sql .= " " . $where;
		}
		if (array_key_exists("GROUPBY", $data)) {
			if (($data['GROUPBY'] != "") && ($data['GROUPBY'] != NULL)) {
				$sql .= " Group By " . $data['GROUPBY'];
			}
		}
		if (($order != "") && ($order != NULL)) {
			$sql .= " " . $order;
		}
		if (($limit != "") && ($limit != NULL)) {
			$sql .= " " . $limit;
		}
		$sdata = self::sql_exec($sql);
		//echo $sql;
		$sql = "SELECT COUNT(" . $data['PRIMARYKEY'] . ") AS RCOUNT FROM " . $data['TABLE'];
		if (($where != "") && ($where != NULL)) {
			$sql .= " " . $where;
		}
		if (array_key_exists("GROUPBY", $data)) {
			if (($data['GROUPBY'] != "") && ($data['GROUPBY'] != NULL)) {
				$sql .= " Group By " . $data['GROUPBY'];
			}
		}
		// Data set length after filtering
		$resFilterLength = self::sql_exec($sql);
		$recordsFiltered = $resFilterLength[0]['RCOUNT'];

		// Total data set length
		$resTotalLength = self::sql_exec("SELECT COUNT(" . $data['PRIMARYKEY'] . ") AS RCOUNT FROM   " . $data['TABLE'] . " " . $whereAllSql);
		$recordsTotal = $resTotalLength[0]['RCOUNT'];
		/*
		 * Output
		 */
		return array(
			"draw" => isset($data['POSTDATA']['draw']) ?
			intval($data['POSTDATA']['draw']) :
			0,
			"recordsTotal" => intval($recordsTotal),
			"recordsFiltered" => intval($recordsFiltered),
			"data" => self::data_output($data['COLUMNS1'], $sdata)
		);
	}
	private function sql_exec($sql) {
		$result = DB::select($sql);
		$return = array();
		if (count($result) > 0) {
			for ($i = 0; $i < count($result); $i++) {
				$return[] = (array) $result[$i];
			}
		}
		return $return;
	}
	private function fatal($msg) {
		echo json_encode(array("error" => $msg));
		exit(0);
	}
	private function bind(&$a, $val, $type) {
		$key = ':binding_' . count($a);
		$a[] = array(
			'key' => $key,
			'val' => $val,
			'type' => $type
		);
		return $key;
	}
	private function pluck($a, $prop) {
		$out = array();
		for ($i = 0, $len = count($a); $i < $len; $i++) {
			$out[] = $a[$i][$prop];
		}
		return $out;
	}
	private function _flatten($a, $join = ' AND ') {
		if (!$a) {
			return '';
		} else if ($a && is_array($a)) {
			return implode($join, $a);
		}
		return $a;
	}
}