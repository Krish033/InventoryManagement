<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Auth;
use App\Models\ValidUniqueModel;

class ValidDB implements Rule{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    private $ValidUniqueModel;
	private $data;
    public function __construct($data){
		$this->data=$data;
        $this->ValidUniqueModel=new ValidUniqueModel();
    }
    /**
     * Determine if the validation rule passes.
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value){
		return $this->ValidUniqueModel->check_data($this->data);
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message(){	
		if(array_key_exists("ErrMsg",$this->data)){
			return $this->data['ErrMsg'];
		}
		return "validation failed";
    }
}
