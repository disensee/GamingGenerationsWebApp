<?php 
include_once("Model.inc.php");

class TradeIn extends Model{
    //instance variables
    public $tradeInId;
    public $customerId;
    public $tradeInDateTime;
    public $tradeInEmployee;
    public $cashPaid;
    public $creditPaid;
    public $checkPaid;
    public $checkNumber;

    //set datetime
    public function createDateTimeNow(){
        $tz_object = new DateTimeZone('America/Chicago');
        //default timezone is central time

        $dateTime = new DateTime();
        $dateTime->setTimezone($tz_object);
        return $dateTime->format('Y-m-d H:i:s');
    }

    //Constructor
    public function __construct($args = []){
        $this->tradeInId = $args['tradeInId'] ?? 0;
        $this->customerId = $args['customerId'] ?? 0;
        $this->tradeInDateTime = $args['tradeInDateTime'] ?? $this->createDateTimeNow();
        $this->tradeInEmployee = $args['tradeInEmployee'] ?? "";
        $this->cashPaid = $args['cashPaid'] ?? 0.00;
        $this->creditPaid = $args['creditPaid'] ?? 0.00;
        $this->checkPaid = $args['checkPaid'] ?? 0.00;
        $this->checkNumber = $args['checkNumber'] ?? "";
    }
    //Need to validate paid value vars
    public function isValid(){
        //validation not needed on tradeInId
        //validation not needed on customerId

        if(empty($this->tradeInDateTime)){
            return false;
        }

        if(empty($this->tradeInEmployee)){
            return false;
        }

        if(!preg_match("/\b[a-zA-Z]{3}\b/", $this->tradeInEmployee)){
            return false;
        }

        return true;
    }
}


?>