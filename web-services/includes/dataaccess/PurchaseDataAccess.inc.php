<?php
include_once("../includes/config.inc.php");
include_once('DataAccess.inc.php');
include_once(__DIR__ . "/../models/Purchase.inc.php");

class PurchaseDataAccess extends DataAccess{

    function __construct($link){

        parent::__construct($link);
        
	}

	/**
	* 'Cleans' the data in a purchase object to prevent SQL injection attacks
	* @param {purchase}	A purchase model object
	* @return {purchase} A new instance of urchase object with clean data in it
	*/
	function cleanDataGoingIntoDB($purchase){

		if($purchase instanceOf Purchase){
			$cleanPurchase = new Purchase();
			$cleanPurchase->purchaseId= mysqli_real_escape_string($this->link, $purchase->purchaseId);
			$cleanPurchase->customerId = mysqli_real_escape_string($this->link, $purchase->customerId);
            $cleanPurchase->purchaseDateTime = mysqli_real_escape_string($this->link, $purchase->purchaseDateTime);
			$cleanPurchase->purchaseEmployee = mysqli_real_escape_string($this->link, $purchase->purchaseEmployee);
			$cleanPurchase->cashReceived = mysqli_real_escape_string($this->link, $purchase->cashReceived);
			$cleanPurchase->creditReceived = mysqli_real_escape_string($this->link, $purchase->creditReceived);
			$cleanPurchase->storeCreditReceived = mysqli_real_escape_string($this->link, $purchase->storeCreditReceived);
			$cleanPurchase->totalPurchasePrice = mysqli_real_escape_string($this->link, $purchase->totalPurchasePrice);
            
			return $cleanPurchase;
		}else{
			$cleanParam = mysqli_real_escape_string($this->link, $purchase);
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
		$cleanRow['purchaseId'] = htmlentities($row['purchaseId']);
        $cleanRow['customerId'] = htmlentities($row['customerId']);
        $cleanRow['purchaseDateTime'] = htmlentities($row['purchaseDateTime']);
        $cleanRow['purchaseEmployee'] = htmlentities($row['purchaseEmployee']);
        $cleanRow['cashReceived'] = htmlentities($row['cashReceived']);
        $cleanRow['creditReceived'] = htmlentities($row['creditReceived']);
        $cleanRow['storeCreditReceived'] = htmlentities($row['storeCreditReceived']);
		$cleanRow['totalPurchasePrice'] = htmlentities($row['totalPurchasePrice']);

		return $cleanRow;
    }
    
    /**
	* Gets all purchases from a in the database
	* @param {assoc array} 	This optional param would allow you to filter the result set
	* 						For example, you could use it to somehow add a WHERE claus to the query
	* 
	* @return {array}		Returns an array of purchase objects
	*/
	function getAll($args = []){
		$qStr = "SELECT purchaseId, customerId, purchaseDateTime, purchaseEmployee, cashReceived, creditReceived, storeCreditReceived, totalPurchasePrice FROM purchases";
		//die($qStr);

		//Many people run queries like this. Shows error messages to users. 
		//$result = mysqli_query($this->link, $qStr) or die(mysqli_error($this->link));
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		$allPurchases = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$purchase = new Purchase($cleanRow);

				$allPurchases[] = $purchase;
			}
		}
		return $allPurchases;
	}
	    
    /**
	* Gets a purchase from the database by its id
	* @param {number} 	 The id of the purchase to get from a row in the database
	* @return {purchase} Returns an instance of a purchase model object
	*/
	function getById($purchaseId){
		$cleanPurchaseId = $this->cleanDataGoingIntoDB($purchaseId);
		$qStr = "SELECT purchaseId, customerId, purchaseDateTime, purchaseEmployee, cashReceived, creditReceived, storeCreditReceived, totalPurchasePrice FROM purchases WHERE purchaseId = '$cleanPurchaseId'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		if(mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$cleanRow = $this->cleanDataComingFromDB($row);
			$purchase = new Purchase($cleanRow);
			return $purchase;
		}

		return false;
    }

    /**
	* Gets all purchases from the database by customer id
	* @param {number} 	 The id of the customer whose purchases are to be retreived from db
	* @return {array} Returns an array of purchase objects
	*/

	function getPurchaseByCustomerId($customerId){
        $cleanCustomerId = $this->cleanDataGoingIntoDB($customerId);
		$qStr = "SELECT purchaseId, customerId, purchaseDateTime, purchaseEmployee, cashReceived, creditReceived, storeCreditReceived, totalPurchasePrice FROM purchases WHERE customerId = '$cleanCustomerId'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		$allPurchasesByCustomer = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$purchase = new Purchase($cleanRow);

				$allPurchasesByCustomer[] = $purchase;
			}
		}
		return $allPurchasesByCustomer;
	}
	
	function getPurchaseByCustomerIdAscending($customerId){
        $cleanCustomerId = $this->cleanDataGoingIntoDB($customerId);
		$qStr = "SELECT purchaseId, customerId, purchaseDateTime, purchaseEmployee, cashReceived, creditReceived, storeCreditReceived, totalPurchasePrice FROM purchases WHERE customerId = '$cleanCustomerId' ORDER BY purchaseDateTime ASC";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		$allPurchasesByCustomer = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$purchase = new Purchase($cleanRow);

				$allPurchasesByCustomer[] = $purchase;
			}
		}
		return $allPurchasesByCustomer;
	}
	
	function getPurchaseByCustomerIdDescending($customerId){
        $cleanCustomerId = $this->cleanDataGoingIntoDB($customerId);
		$qStr = "SELECT purchaseId, customerId, purchaseDateTime, purchaseEmployee, cashReceived, creditReceived, storeCreditReceived, totalPurchasePrice FROM purchases WHERE customerId = '$cleanCustomerId' ORDER BY purchaseDateTime DESC";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		$allPurchasesByCustomer = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$purchase = new Purchase($cleanRow);

				$allPurchasesByCustomer[] = $purchase;
			}
		}
		return $allPurchasesByCustomer;
    }


    
    /**
	* Inserts a purchase into a table in the database
	* @param {purchase}	The purchase object to be inserted
	* @return {purchase}	Returns the same purchase object, but with the purchaseId property set (the purchaseId is assigned by the database)
	*/
	function insert($purchase){
		$cleanPurchase = $this->cleanDataGoingIntoDB($purchase);
		$qStr = "INSERT INTO purchases (customerId, purchaseDateTime, purchaseEmployee, cashReceived, creditReceived, storeCreditReceived, totalPurchasePrice) VALUES (
			'{$cleanPurchase->customerId}',
            '{$cleanPurchase->purchaseDateTime}',
            '{$cleanPurchase->purchaseEmployee}',
            '{$cleanPurchase->cashReceived}',
            '{$cleanPurchase->creditReceived}',
            '{$cleanPurchase->storeCreditReceived}',
            '{$cleanPurchase->totalPurchasePrice}'
		)";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if($result){
			$cleanPurchase->purchaseId = mysqli_insert_id($this->link);
		}else{
			$this->handleError("Unable to insert purchase");
		}

		return $cleanPurchase;
    }
    
    /**
	* Updates a purchase object in the database
	* @param {purchase}	 The purchase model object to be updated
	* @return {purchase} Returns the same purchase model object that was passed in as the param
	*/
	function update($purchase){
		$cleanPurchase = $this->cleanDataGoingIntoDB($purchase);
		$getPurchase = $this->getById($cleanPurchase->purchaseId);
		$qStr = "UPDATE purchases SET 
				customerId = '{$cleanPurchase->customerId}',
				purchaseDateTime = '{$getPurchase->purchaseDateTime}',
                purchaseEmployee = '{$cleanPurchase->purchaseEmployee}',
				cashReceived = '{$cleanPurchase->cashReceived}',
				creditReceived = '{$cleanPurchase->creditReceived}',
				storeCreditReceived = '{$cleanPurchase->storeCreditReceived}',
				totalPurchasePrice = '{$cleanPurchase->totalPurchasePrice}'
                WHERE purchaseId = '{$cleanPurchase->purchaseId}'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		$cleanPurchase->purchaseDateTime = $getPurchase->purchaseDateTime;
		return $cleanPurchase;
    }
    
    /**
	* Deletes a purchase from a table in the database
	* @param {number} 	The id of the purchase to delete
	* @return {boolean}	Returns true if the row was sucessfully deleted, false otherwise
	*/
	function delete($purchaseId){
		$cleanPurchaseId = $this->cleanDataGoingIntoDB($purchaseId);
		$qStr = "DELETE FROM purchases WHERE purchaseId = $cleanPurchaseId";
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if(mysqli_affected_rows($this->link) == 1){
			return true;
        }
        
        return false;
        
	}
}