<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use App\Models\ValidUniqueModel;

class ValidUnique implements Rule{
    private $Filter;
	 private $status;
     private $Valid_Unique_Model;
     private $error;
	 private $ErrMsg;
    public function __construct($filter,$ErrMsg=null){
		$this->Filter=$filter;
		$this->ErrMsg=$ErrMsg;
		$this->Valid_Unique_Model=new ValidUniqueModel();
    }
    public function passes($attribute, $value){
		$error=0;
		if($this->Valid_Unique_Model->Validate_Unique($this->Filter)==false){
			$error=1;$this->error=1;
		}
		if($error==0){
			return true;
		}else{
			return false;
		}
    }
    public function message(){
		if($this->error==1){
			if(($this->ErrMsg==NULL)||($this->ErrMsg=="")){
				return 'This value has already been taken.';
			}else{
				return $this->ErrMsg;
			}
		}
    }
}
