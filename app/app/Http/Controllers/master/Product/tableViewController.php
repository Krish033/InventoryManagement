<?php

namespace App\Http\Controllers\master\Product;

use App\Http\Controllers\Controller;
use App\Models\general;
use App\Models\ServerSideProcess;
use Illuminate\Http\Request;

class tableViewController extends Controller {
    /**
     * Handle the incoming request.
     */
    public function __construct() {
        $this->middleware(['auth', function ($request, $next) {
            $this->UserID = auth()->user()->UserID;
            $this->general = new general($this->UserID, $this->ActiveMenuName);
            $this->Menus = $this->general->loadMenu();
            $this->CRUD = $this->general->getCrudOperations($this->ActiveMenuName);
            $this->logs = new logController();
            $this->Settings = $this->general->getSettings();
            return $next($request);
        }]);
    }

    public function __invoke(Request $request) {
        if ($this->general->isCrudAllow($this->CRUD, "view") == true) {
            $ServerSideProcess = new ServerSideProcess();
            $columns = array(
                array('db' => 'img', 'dt' => '0'),
                array('db' => 'name', 'dt' => '1'),
                array('db' => 'categoryId', 'dt' => '2'),
                array('db' => 'subCategoryId', 'dt' => '3'),
                array('db' => 'taxId', 'dt' => '4'),
                array('db' => 'maxQuantity', 'dt' => '5'),
                array('db' => 'minQuantity', 'dt' => '6'),
                array('db' => 'salesRate', 'dt' => '7'),
                array('db' => 'purchaseRate', 'dt' => '8'),
                array('db' => 'salesRate', 'dt' => '9'),
                array('db' => 'salesRate', 'dt' => '10'),
                // array('db' => 'stateId', 'dt' => '6'),
                array(
                    'db' => 'is_active',
                    'dt' => '11',
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
                    'dt' => '12',
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
            $data['TABLE'] = 'tbl_suppliers';
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
}