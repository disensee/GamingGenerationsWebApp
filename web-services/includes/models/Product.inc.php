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
    public $gamestopTradePrice;
    public $upc;
    public $quantity;

    //Constructor
    public function __construct($args = []){
        $this->productId = $args['productId'] ?? 0;
        $this->consoleName = $args['consoleName'] ?? "";
        $this->productName = $args['productName'] ?? "";
        $this->loosePrice = $args['loosePrice'] ?? 0;
        $this->cibPrice = $args['cibPrice'] ?? 0;
        $this->gamestopPrice = $args['gamestopPrice'] ?? 0;
        $this->gamestopTradePrice = $args['gamestopTradePrice'] ?? 0;
        $this->upc = $args['upc'] ?? "000000000000";
        $this->quantity = $args['quantity'] ?? 0;
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
        //gamestopTradePrice must not be empty and must be a number
        if(empty($this->gamestopTradePrice) || !is_numeric($this->gamestopTradePrice)){
            return false;
        }
        //upc must not be empty
        if(empty($this->upc)){
            return false;
        }
        //upc must be 12 digits
        if(!preg_match('/^[0-9]{12}$/', $this->upc)){
            return false;
        }
        //quantity must not be empty and must be a number
        if(empty($this->quantity) || !is_numeric($this->quantity)){
            return false;
        }
        return true;
    }
}