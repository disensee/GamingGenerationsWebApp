<?php
include_once("Model.inc.php");

class Product extends Model{
    //instace variables
    public $productId;
    public $consoleName;
    public $productName;
    public $loosePrice;
    public $cibPrice;
    public $gamestopPrice;
    public $gamestopTradeValue;
    public $upc;
    public $onaQuantity;
    public $ecQuantity;
    public $spQuantity;
    public $shebQuantity;

    //Constructor
    public function __construct($args = []){
        $this->productId = $args['productId'] ?? 0;
        $this->consoleName = $args['consoleName'] ?? "";
        $this->productName = $args['productName'] ?? "";
        $this->loosePrice = $args['loosePrice'] ?? 0;
        $this->cibPrice = $args['cibPrice'] ?? 0;
        $this->gamestopPrice = $args['gamestopPrice'] ?? 0;
        $this->gamestopTradeValue = $args['gamestopTradeValue'] ?? 0;
        $this->upc = $args['upc'] ?? "000000000000";
        $this->onaQuantity = $args['onaQuantity'] ?? 0;
        $this->ecQuantity = $args['ecQuantity'] ?? 0;
        $this->spQuantity = $args['spQuantity'] ?? 0;
        $this->shebQuantity = $args['shebQuantity'] ?? 0;
    }

    /**
	* Validates the state of a Product object. Returns true if it is valid, false otherwise
	* @return {boolean}
    */
    public function isValid(){
        //validation not needed on productId
        
        //consoleName must not be empty
        if(empty($this->consoleName)){
            return false;
        }
        //productName must not be empty
        if(empty($this->productName)){
            return false;
        }
        //loosePrice must not be empty and must be a number
        if(empty($this->loosePrice) || !is_numeric($this->loosePrice)){
            return false;
        }
        //cibPrice must not be empty and must be a number
        if(empty($this->cibPrice) || !is_numeric($this->cibPrice)){
            return false;
        }
        //gamestopPrice must not be empty and must be a number
        if(empty($this->gamestopPrice) || !is_numeric($this->gamestopPrice)){
            return false;
        }
        //gamestopTradeValue must not be empty and must be a number
        if(empty($this->gamestopTradeValue) || !is_numeric($this->gamestopTradeValue)){
            return false;
        }
        
        if(!empty($this->upc)){
        //upc must be 12 digits
            if(!preg_match('/^[0-9]{12}$/', $this->upc)){
                return false;
            }
        }
        //quantity must not be empty and must be a number
        if($this->onaQuantity !== 0){
            if(!(is_numeric($this->onaQuantity)) || empty($this->onaQuantity)){
                return false;
            }
        }

        if($this->ecQuantity != 0){
            if(!(is_numeric($this->ecQuantity)) || empty($this->ecQuantity)){
                return false;
            }
        }

        if($this->spQuantity != 0){
            if(!(is_numeric($this->spQuantity)) || empty($this->spQuantity)){
                return false;
            }
        }

        if($this->shebQuantity != 0){
            if(!(is_numeric($this->shebQuantity)) || empty($this->shebQuantity)){
                return false;
            }
        }
        return true;
    }
}