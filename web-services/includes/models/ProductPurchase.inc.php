<?php
include_once('Model.inc.php');

class ProductPurchase extends Model{
    //instance variables
    public $ppId;
    public $purchaseId;
    public $productId;
    public $serialNumber;

    //constructor
    public function __construct($args = []){
        $this->ppId = $args['ppId'] ?? 0;
        $this->purchaseId = $args['purchaseId'] ?? 0;
        $this->productId = $args['productId'] ?? 0;
        $this->serialNumber = $args['serialNumber'] ?? 0;
    }

    public function isValid(){
        if(!isset($this->ppId)){
            return false;
        }

        if(!isset($this->purchaseId)){
            return false;
        }
        
        if(!isset($this->productId)){
            return false;
        }

        return true;
    }
}
?>