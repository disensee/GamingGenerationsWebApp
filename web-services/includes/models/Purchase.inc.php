<?php 
include_once("Model.inc.php");

class Purchase extends Model{
    //instance variables
    public $purchaseId;
    public $customerId;
    public $purchaseDateTime;
    public $purchaseEmployee;
    public $cashReceived;
    public $creditReceived;
    public $storeCreditReceived;
    public $totalPurchasePrice;

    //Constructor
    public function __construct($args = []){
        $this->purchaseId = $args['purchaseId'] ?? 0;
        $this->customerId = $args['customerId'] ?? 0;
        $this->purchaseDateTime = $args['purchaseDateTime'] ?? $this->createDateTimeNow();
        $this->purchaseEmployee = $args['purchaseEmployee'] ?? "";
        $this->cashReceived = $args['cashReceived'] ?? 0.00;
        $this->creditReceived = $args['creditReceived'] ?? 0.00;
        $this->storeCreditReceived = $args['storeCreditReceived'] ?? 0.00;
        $this->totalPurchasePrice = $args['totalPurchasePrice'] ?? ($this->cashReceived+$this->creditReceived+$this->storeCreditReceived);
    }

    public function isValid(){
        //validation not needed on purchaseId
        //validation not needed on customerId
        $valid = true;

        if(empty($this->purchaseDateTime)){
            $valid = false;
        }

        if(empty($this->purchaseEmployee)){
            $valid = false;
        }

        if(!preg_match("/\b[a-zA-Z]{2,3}\b/", $this->purchaseEmployee)){
            $valid = false;
        }

        if(!is_numeric($this->cashReceived)){
            $valid = false;
        }

        if(empty($this->cashReceived)){
            if((!empty($this->creditReceived) && isset($this->creditReceived)) || (!empty($this->storeCreditReceived) && isset($this->storeCreditReceived))){
                $valid = true;
            }else{
                $valid = false;
            }

        }

        if(!is_numeric($this->creditReceived)){
            $valid = false;
        }

        if(empty($this->creditReceived)){
            
            if((!empty($this->cashReceived) && isset($this->cashReceived)) || (!empty($this->storeCreditReceived) && isset($this->storeCreditReceived))){
                $valid = true;
            }else{
                $valid = false;
            }
        }

        if(!is_numeric($this->storeCreditReceived)){
            $valid = false;
        }

        if(empty($this->storeCreditReceived)){
            if((!empty($this->creditReceived) && isset($this->creditReceived)) || (!empty($this->cashReceived) && isset($this->cashReceived))){
                $valid = true;
            }else{
                $valid = false;
            }
        }

        if(empty($this->totalPurchasePrice)){
            $valid = false;
        }

        if($this->totalPurchasePrice != ($this->cashReceived + $this->creditReceived + $this->storeCreditReceived)){
            $valid = false;
        }

        return $valid;
    }
}


?>