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
    public $totalPaid;
    public $comments;
    public $location;


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
        $this->totalPaid = $args['totalPaid'] ?? ($this->creditPaid+$this->cashPaid+$this->checkPaid);
        $this->comments = $args['comments'] ?? "";
        $this->location = $args['location'] ?? "";

    }

    public function isValid(){
        //validation not needed on tradeInId
        //validation not needed on customerId
        $valid = true;

        if(empty($this->tradeInDateTime)){
            $valid = false;
        }

        if(empty($this->tradeInEmployee)){
            $valid = false;
        }

        if(!preg_match("/\b[a-zA-Z]{2,3}\b/", $this->tradeInEmployee)){
            $valid = false;
        }

        if(!is_numeric($this->cashPaid)){
            $valid = false;
        }

        if(empty($this->cashPaid)){
            if((!empty($this->creditPaid) && isset($this->creditPaid)) || (!empty($this->checkPaid) && isset($this->checkPaid))){
                $valid = true;
            }else{
                $valid = false;
            }
        }

        if(!is_numeric($this->creditPaid)){
            $valid = false;
        }

        if(empty($this->creditPaid)){
            
            if((!empty($this->cashPaid) && isset($this->cashPaid)) || (!empty($this->checkPaid) && isset($this->checkPaid))){
                $valid = true;
            }else{
                $valid = false;
            }
        }

        if(!is_numeric($this->checkPaid)){
            $valid = false;
        }

        if(empty($this->checkPaid)){
            if((!empty($this->creditPaid) && isset($this->creditPaid)) || (!empty($this->cashPaid) && isset($this->cashPaid))){
                $valid = true;
            }else{
                $valid = false;
            }

        }

        if(!is_numeric($this->checkNumber)){
            $valid = false;
        }

        if(empty($this->checkNumber)){
            if(empty($this->checkPaid)){
                $valid = true;
            }else{
                $valid = false;
            }
        }

        if(empty($this->totalPaid)){
            $valid = false;
        }

        if($this->totalPaid != ($this->cashPaid + $this->creditPaid + $this->checkPaid)){
            $valid = false;
        }

        return $valid;
    }
}


?>