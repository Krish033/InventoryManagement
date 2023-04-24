<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use DB;

class MobileNumberValidate implements Rule{
    private $CountryID;
    private $ErrMsg;
    private $isError;
    public function __construct($CountryID=null,$ErrMsg=null){
        $this->CountryID=$CountryID;
        $this->ErrMsg=$ErrMsg;
        $this->error=0;
    }
    private function getPhoneLength(){
        if($this->CountryID!=null){
            $result=DB::Table('tbl_countries')->where('CountryID',$this->CountryID)->get();
            if(count($result)>0){
                return $result[0]->PhoneLength;
            }
        }
        return 0;
    }
    public function passes($attribute, $value){
        $this->error=0;
        $PhoneLength=$this->getPhoneLength();
        if($PhoneLength!=0){
            if(strpos($PhoneLength,"-")>0){
                $PhoneLength=explode("-",$PhoneLength);
                if(count($PhoneLength)>0){
                    if((strlen($value)<$PhoneLength[0])&&(strlen($value)>$PhoneLength[0])){
                        $this->error=1;
                    }
                }
            }elseif(strpos($PhoneLength,",")>0){
                $PhoneLength=explode(",",$PhoneLength);
                if(count($PhoneLength)>0){
                    if(!in_array(strlen($value),$PhoneLength)){
                        $this->error=1;
                    }
                }
            }else{
                if(strlen($value)!=$PhoneLength){
                    $this->error=1;
                }
            }
        }
        if($this->error==1){
            return false;
        }else{
            return true;
        }
    }
    public function message(){
		if($this->error==1){
			if(($this->ErrMsg==NULL)||($this->ErrMsg=="")){
				return 'This Mobile Number Not valid';
			}else{
				return $this->ErrMsg;
			}
		}else{
            return "";
        }
    }
}
