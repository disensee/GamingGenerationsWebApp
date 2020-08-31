<?php
include_once("Model.inc.php");

class Customer extends Model{
    //instace variables
    public $customerId;
    public $customerFirstName;
    public $customerLastName;
    public $customerIdNumber;
    public $customerEmail;
    public $customerPhone;

    //Constructor
    public function __construct($args = []){
        $this->customerId = $args['customerId'] ?? 0;
        $this->customerFirstName = $args['customerFirstName'] ?? "";
        $this->customerLastName = $args['customerLastName'] ?? "";
        $this->customerIdNumber = $args['customerIdNumber'] ?? "";
        $this->customerEmail = $args['customerEmail'] ?? "";
        $this->customerPhone = $args['customerPhone'] ?? "";
    }

    /**
	* Validates the state of a Customer object. Returns true if it is valid, false otherwise
	* @return {boolean}
    */
    public function isValid(){
        //validation not needed on customerId
        
        //customer first and last name must not be empty
        if(empty($this->customerFirstName)){
            return false;
        }
       
        if(empty($this->customerLastName)){
            return false;
        }
        
        //customer first and last name must only contain letters
        if(!preg_match("/^[a-zA-Z ]*$/",$this->customerFirstName)){
            return false;
        }

        if(!preg_match("/^[a-zA-Z ]*$/",$this->customerLastName)){
            return false;
        }

        //customer email must be provided and must be a valid email address
        if(empty($this->customerEmail)){
            return false;
        }

        if(!empty($this->customerEmail)){
            if(!filter_var($this->customerEmail, FILTER_VALIDATE_EMAIL)){
                return false;
            }
        }

        //if phone number is provided, it must be valid
        if(!empty($this->customerPhone)){
            if(!preg_match("/^[0-9]{10}$/",$this->customerPhone)){
                return false;
            }
            
        }
       
        return true;
    }
}