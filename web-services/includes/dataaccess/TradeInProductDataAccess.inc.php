<?php
include_once('DataAccess.inc.php');
include_once(__DIR__ . "/../models/TradeInProduct.inc.php");

class TradeInProductDataAccess extends DataAccess{

    function __construct($link){

        parent::__construct($link);
        
	}

	/**
	* 'Cleans' the data in a TradeInProduct object to prevent SQL injection attacks
	* @param {tradeInProduct}	A tradeInProduct model object
	* @return {tradeInProduct} A new instance of tradeInProduct object with clean data in it
	*/
	function cleanDataGoingIntoDB($tradeInProd){

		if($tradeInProd instanceOf TradeInProduct){
			$cleanTradeInProd = new TradeInProduct();
			$cleanTradeInProd->tpId= mysqli_real_escape_string($this->link, $tradeInProd->tpId);
			$cleanTradeInProd->tradeInId = mysqli_real_escape_string($this->link, $tradeInProd->tradeInId);
			$cleanTradeInProd->productId = mysqli_real_escape_string($this->link, $tradeInProd->productId);
			$cleanTradeInProd->serialNumber = mysqli_real_escape_string($this->link, $tradeInProd->serialNumber);
            
			return $cleanTradeInProd;
		}else{
			$cleanParam = mysqli_real_escape_string($this->link, $tradeInProd);
			return $cleanParam;
		}
    }

    /**
	* 'Cleans' the data in a row from the database (a row should be an associative array)
	* @param {array}	An associative array with key value pairs for each column in the table
	* @return {array} 	A new associative array with clean data in it
	*/
	function cleanDataComingFromDB($row){
		$cleanRow = [];
		$cleanRow['tpId'] = htmlentities($row['tpId']);
        $cleanRow['tradeInId'] = htmlentities($row['tradeInId']);
        $cleanRow['productId'] = htmlentities($row['productId']);
		$cleanRow['serialNumber'] = htmlentities($row['serialNumber']);

		return $cleanRow;
    }
    
    /**
	* Gets all tradeInProducts from a in the database
	* @param {assoc array} 	This optional param would allow you to filter the result set
	* 						For example, you could use it to somehow add a WHERE claus to the query
	* 
	* @return {array}		Returns an array of trade in objects
	*/
	function getAll($args = []){
		$qStr = "SELECT tpId, tradeInId, productId, serialNumber FROM tradeinproducts";
		//die($qStr);

		//Many people run queries like this. Shows error messages to users. 
		//$result = mysqli_query($this->link, $qStr) or die(mysqli_error($this->link));
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		$allTradeInProds = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$tradeInProd = new TradeInProduct($cleanRow);

				$allTradeInProds[] = $tradeInProd;
			}
		}
		return $allTradeInProds;
	}
	
    
    /**
	* Gets a tradeInProduct from the database by its id
	* @param {number} 	 The id of the trade in to get from a row in the database
	* @return {tradeInProduct} Returns an instance of a tradeInProduct model object
	*/
	function getById($tradeInProdId){
		$cleanTradeInProdId = $this->cleanDataGoingIntoDB($tradeInProdId);
		$qStr = "SELECT tpId, tradeInId, productId, serialNumber FROM tradeinproducts WHERE tpId = '$cleanTradeInProdId'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		if(mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$cleanRow = $this->cleanDataComingFromDB($row);
			$tradeInProd = new TradeInProduct($cleanRow);
			return $tradeInProd;
		}

		return false;
    }

    /**
	* Gets all tradeInProduct rows from the database by trade in id
	* @param {number} 	 The id of the trade in whose products are to be retreived from db
	* @return {tradeInProduct} Returns an array of tradeInProduct objects
	*/
	function getTradeInProductByTradeInId($tradeInId){
        $cleanTradeInProdId = $this->cleanDataGoingIntoDB($tradeInId);
		$qStr = "SELECT tpId, tradeInId, productId, serialNumber FROM tradeinproducts WHERE tradeInId = $cleanTradeInProdId";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		$allTradeInProducts = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$tradeInProduct = new TradeInProduct($cleanRow);

				$allTradeInProducts[] = $tradeInProduct;
			}
		}
		return $allTradeInProducts;
    }


    
    /**
	* Inserts a tradeInProduct into a table in the database
	* @param {tradeInProduct}	The tradeInProduct object to be inserted
	* @return {tradeInProduct}	Returns the same tradeInProduct object, but with the tpId property set (the tpId is assigned by the database)
	*/
	function insert($tradeInProd){
		$cleanTradeInProd = $this->cleanDataGoingIntoDB($tradeInProd);
		$qStr = "INSERT INTO tradeinproducts (tradeInId, productId, serialNumber) VALUES (
			'{$cleanTradeInProd->tradeInId}',
            '{$cleanTradeInProd->productId}',
            '{$cleanTradeInProd->serialNumber}'
		)";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if($result){
			$cleanTradeInProd->tpId = mysqli_insert_id($this->link);
		}else{
			$this->handleError("Unable to insert tradeInProduct");
		}

		return $cleanTradeInProd;
    }
    
    /**
	* Updates a tradeInProduct object in the database
	* @param {tradeInProduct}	 The tradeInProduct model object to be updated
	* @return {tradeInProduct} Returns the same tradeInProduct model object that was passed in as the param
	*/
	function update($tradeInProd){
		$cleanTradeInProd = $this->cleanDataGoingIntoDB($tradeInProd);
		$qStr = "UPDATE tradeinproducts SET 
				tradeInId = '{$cleanTradeInProd->tradeInId}',
                productId = '{$cleanTradeInProd->productId}',
                serialNumber = '{$cleanTradeInProd->serialNumber}'
                WHERE tpId = '{$cleanTradeInProd->tpId}'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		return $cleanTradeInProd;
    }
    
    /**
	* Deletes a tradeInProduct from a table in the database
	* @param {number} 	The id of the tradeInProduct to delete
	* @return {boolean}	Returns true if the row was sucessfully deleted, false otherwise
	*/
	function delete($tpId){
		$cleanTradeInProdId = $this->cleanDataGoingIntoDB($tpId);
		$qStr = "DELETE FROM tradeinproducts WHERE tpId = $cleanTradeInProdId";
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if(mysqli_affected_rows($this->link) == 1){
			return true;
        }
        
        return false;
        
	}
}