<?php
include_once('DataAccess.inc.php');
include_once(__DIR__ . "/../models/ProductPurchase.inc.php");

class ProductPurchaseDataAccess extends DataAccess{

    function __construct($link){

        parent::__construct($link);
        
	}

	/**
	* 'Cleans' the data in a ProductPurchase object to prevent SQL injection attacks
	* @param {productPurchase}	A productPurchase model object
	* @return {productPurchase} A new instance of productPurchase object with clean data in it
	*/
	function cleanDataGoingIntoDB($prodPurchase){

		if($prodPurchase instanceOf ProductPurchase){
			$cleanProdPurchase = new ProductPurchase();
			$cleanProdPurchase->ppId= mysqli_real_escape_string($this->link, $prodPurchase->ppId);
			$cleanProdPurchase->purchaseId = mysqli_real_escape_string($this->link, $prodPurchase->purchaseId);
			$cleanProdPurchase->productId = mysqli_real_escape_string($this->link, $prodPurchase->productId);
            
			return $cleanProdPurchase;
		}else{
			$cleanParam = mysqli_real_escape_string($this->link, $prodPurchase);
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
		$cleanRow['ppId'] = htmlentities($row['ppId']);
        $cleanRow['purchaseId'] = htmlentities($row['purchaseId']);
        $cleanRow['productId'] = htmlentities($row['productId']);

		return $cleanRow;
    }
    
    /**
	* Gets all productPurchases from a in the database
	* @param {assoc array} 	This optional param would allow you to filter the result set
	* 						For example, you could use it to somehow add a WHERE claus to the query
	* 
	* @return {array}		Returns an array of productPurchase objects
	*/
	function getAll($args = []){
		$qStr = "SELECT ppId, purchaseId, productId FROM productpurchases";
		//die($qStr);

		//Many people run queries like this. Shows error messages to users. 
		//$result = mysqli_query($this->link, $qStr) or die(mysqli_error($this->link));
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		$allProdPurchases = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$prodPurchase = new ProductPurchase($cleanRow);

				$allProdPurchases[] = $prodPurchase;
			}
		}
		return $allProdPurchases;
	}
	
    
    /**
	* Gets a productPurchase from the database by its id
	* @param {number} 	 The id of the productPurchase to get from a row in the database
	* @return {productPurchase} Returns an instance of a productPurchase model object
	*/
	function getById($prodPurchaseId){
		$cleanProdPurchaseId = $this->cleanDataGoingIntoDB($prodPurchaseId);
		$qStr = "SELECT ppId, purchaseId, productId FROM productpurchases WHERE ppId = '$cleanProdPurchaseId'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		if(mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$cleanRow = $this->cleanDataComingFromDB($row);
			$prodPurchase = new ProductPurchase($cleanRow);
			return $prodPurchase;
		}

		return false;
    }

    /**
	* Gets all productPurchase rows from the database by purchase id
	* @param {number} 	 The id of the purchase in whose products are to be retreived from db
	* @return {productPurchase} Returns an array of productPurchase objects
	*/
	function getProductPurchaseByPurchaseId($purchaseId){
        $cleanProdPurchaseId = $this->cleanDataGoingIntoDB($purchaseId);
		$qStr = "SELECT ppId, purchaseId, productId FROM productpurchases WHERE purchaseId = $cleanProdPurchaseId";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		$allProdPurchases = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$productPurchase = new ProductPurchase($cleanRow);

				$allProdPurchases[] = $productPurchase;
			}
		}
		return $allProdPurchases;
    }


    
    /**
	* Inserts a productPurchase into a table in the database
	* @param {productPurchase}	The productPurchase object to be inserted
	* @return {productPurchase}	Returns the same productPurchase object, but with the ppId property set (the ppId is assigned by the database)
	*/
	function insert($prodPurchase){
		$cleanProdPurchase = $this->cleanDataGoingIntoDB($prodPurchase);
		$qStr = "INSERT INTO productpurchases (purchaseId, productId) VALUES (
			'{$cleanProdPurchase->purchaseId}',
            '{$cleanProdPurchase->productId}'
		)";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if($result){
			$cleanProdPurchase->ppId = mysqli_insert_id($this->link);
		}else{
			$this->handleError("Unable to insert productPurchase");
		}

		return $cleanProdPurchase;
    }
    
    /**
	* Updates a productPurchase object in the database
	* @param {productPurchase}	 The productPurchase model object to be updated
	* @return {productPurchase} Returns the same productPurchase model object that was passed in as the param
	*/
	function update($prodPurchase){
		$cleanProdPurchase = $this->cleanDataGoingIntoDB($prodPurchase);
		$qStr = "UPDATE productpurchases SET 
				purchaseId = '{$cleanProdPurchase->purchaseId}',
                productId = '{$cleanProdPurchase->productId}'
                WHERE ppId = '{$cleanProdPurchase->ppId}'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		return $cleanProdPurchase;
    }
    
    /**
	* Deletes a productPurchase from a table in the database
	* @param {number} 	The id of the productPurchase to delete
	* @return {boolean}	Returns true if the row was sucessfully deleted, false otherwise
	*/
	function delete($ppId){
		$cleanProdPurchaseId = $this->cleanDataGoingIntoDB($ppId);
		$qStr = "DELETE FROM productpurchases WHERE ppId = $cleanProdPurchaseId";
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if(mysqli_affected_rows($this->link) == 1){
			return true;
        }
        
        return false;
        
	}
}