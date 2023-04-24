<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Hash;
use Illuminate\Validation\Rules\Password;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Redirect;
use App\Models\DocNum;
use App\Models\general;
use App\Models\ServerSideProcess;
use DB;
use App\Models\User;
use Auth;
use App\Rules\ValidUnique;
use App\Http\Controllers\logController;
class PasswordController extends Controller
{
    private $general;
	private $DocNum;
	private $UserID;
	private $ActiveMenuName;
	private $PageTitle;
	private $Menus;
	private $CRUD;
	private $logs;
	private $Settings;
	public function __construct(){
		$this->ActiveMenuName="PASSWORD-CHANGE";
		$this->PageTitle="Password Change";
        $this->middleware('auth');
        $this->DocNum=new DocNum();
    
		$this->middleware(function ($request, $next) {
			$this->UserID=auth()->user()->UserID;
			$this->general=new general($this->UserID,$this->ActiveMenuName);
			$this->Menus=$this->general->loadMenu();
			$this->CRUD=$this->general->getCrudOperations($this->ActiveMenuName);
			$this->logs=new logController();
			$this->Settings=$this->general->getSettings();
			return $next($request);
		});
    }

	public function PasswordChange(Request $req){
		$FormData=$this->general->UserInfo;
		$FormData['ActiveMenuName']=$this->ActiveMenuName;
		$FormData['PageTitle']=$this->PageTitle;
		$FormData['menus']=$this->Menus;
		$FormData['crud']=$this->CRUD;
        return view('Users.PasswordChange.PasswordChange',$FormData);
	}

	public function UpdatePassword(Request $req){
		$user = User::where('UserID',$this->UserID)->get();
		$OldData=$NewData=array();
		$rules=array(			
				'Password' =>'required|min:3|max:20',
				'ConfirmPassword' =>'required|min:3|max:20|same:Password',
				'CurrentPassword' =>['required',function($attribute, $value, $fail){
					$hasher = app('hash');
					if (!$hasher->check($value, Auth::user()->password)) {
						return $fail(__('The current password is incorrect.'));
					}
				}],
				);
			$message=array(
				'Password.required'=>'Password  is required',
				'Password.min'=>'Password  must be at least 3 characters',
				'Password.max'=>'Password  may not be greater than 20 characters',
				'CurrentPassword.required'=>'Current Password  is required',
				'ConfirmPassword.required'=>'Confirm Password  is required',
				'ConfirmPassword.min'=>'Confirm Password  must be at least 3 characters',
				'ConfirmPassword.max'=>'Confirm Password  may not be greater than 20 characters',
			);
			$validator = Validator::make($req->all(), $rules,$message);
			if ($validator->fails()) {
				return array('success'=>false,'message'=>"Password Change failed",'errors'=>$validator->errors());			
			}
			DB::beginTransaction();
			$status=false;
			try{
				$OldData=User::where('UserID',$this->UserID)->get();
				$Password=Hash::make($req->Password);
				$Password1=$this->general->EncryptDecrypt("ENCRYPT",$req->Password);
				$sql="Update users set Password='".$Password."',Password1='".$Password1."',Updated_at='".date("Y-m-d H:i:s")."',UpdatedBy='".$this->UserID."' where UserID='".$this->UserID."'";
				$status=DB::update($sql);
			}catch(Exception $e) {
					$status=false;
			}
			if($status==true){
				DB::commit();
				$NewData=User::where('UserID',$this->UserID)->get();
				$logData=array("Description"=>"Password Changed successfully ","ModuleName"=>"Password Change","Action"=>"Update","ReferID"=>$this->UserID,"OldData"=>$OldData,"NewData"=>$NewData,"UserID"=>$this->UserID,"IP"=>$req->ip());
                $this->logs->Store($logData);
				return array('status'=>true,'message'=>"Password Changed successfully");
			}else{
				DB::rollback();
				return array('status'=>false,'message'=>"Password Change Failed");
			}
	}

}
