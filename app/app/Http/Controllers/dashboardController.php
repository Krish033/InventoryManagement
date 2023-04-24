<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\DocNum;
use App\Models\general;
use App\Models\ServerSideProcess;
use DB;
use Auth;
use App\Rules\ValidUnique;
use App\Http\Controllers\logController;

class dashboardController extends Controller {
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
		$this->PageTitle = "Dashboard";
		$this->ActiveMenuName = "Dashboard";
		$this->middleware('auth');
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
	public function dashboard() {
		$FormData = $this->general->UserInfo;
		$FormData['ActiveMenuName'] = $this->ActiveMenuName;
		$FormData['PageTitle'] = $this->PageTitle;
		$FormData['menus'] = $this->Menus;
		$FormData['crud'] = $this->CRUD;
		return view('dashboard', $FormData);
	}
}