<?php
include_once('Model.inc.php');

class TradeInProduct extends Model{
    //instance variables
    public $tpId;
    public $tradeInId;
    public $productId;
    public $serialNumber;
    public $retailPrice;
    public $cashValue;
    public $creditValue;

    //constructor
    public function __construct($args = []){
        $this->tpId = $args['tpId'] ?? 0;
        $this->tradeInId = $args['tradeInId'] ?? 0;
        $this->productId = $args['productId'] ?? 0;
        $this->serialNumber = $args['serialNumber'] ?? "";
        $this->retailPrice = $args['retailPrice'] ?? "";
        $this->cashValue = $args['cashValue'] ?? "";
        $this->creditValue = $args['creditValue'] ?? "";
    }

    public function isValid(){
        if(!isset($this->tpId)){
            return false;
        }

        if(!isset($this->tradeInId)){
            return false;
        }
        
        if(!isset($this->productId)){
            return false;
        }

        if(empty($this->retailPrice)){
            return false;
        }

        if(empty($this->cashValue && empty($this->creditValue))){
            return false;
        }

        return true;
    }
}
?>