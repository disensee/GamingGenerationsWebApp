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
        $this->customerIdNumber = $args['customerIdNumber'] ?? "Has not been entered";
        $this->customerEmail = $args['customerEmail'] ?? "";
        $this->gamestopPrice = $args['customerPhone'] ?? "0000000000";
    }

    /**
	* Validates the state of a Product object. Returns true if it is valid, false otherwise
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
        if(!preg_match("/^[a-zA-Z ]*$/",$customerFirstName)){
            return false;
        }

        if(!preg_match("/^[a-zA-Z ]*$/",$customerLastName)){
            return false;
        }

        //customer email must be a valid email address
        if(empty($this->customerEmail)){
            return false;
        }

        if(!empty($this->customerEmail)){
            if(!filter_var($this->customerEmail, FILTER_VALIDATE_EMAIL)){
                return false;
            }
        }
       
        return true;
    }
}