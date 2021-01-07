<?php
include_once("../includes/config.inc.php");
include_once('DataAccess.inc.php');
include_once(__DIR__ . "/../models/TradeIn.inc.php");

class TradeInDataAccess extends DataAccess{

    function __construct($link){

        parent::__construct($link);
        
	}

	/**
	* 'Cleans' the data in a TradeIn object to prevent SQL injection attacks
	* @param {tradeIn}	A tradeIn model object
	* @return {tradeIn} A new instance of tradeIn object with clean data in it
	*/
	function cleanDataGoingIntoDB($tradeIn){

		if($tradeIn instanceOf TradeIn){
			$cleanTradeIn = new TradeIn();
			$cleanTradeIn->tradeInId= mysqli_real_escape_string($this->link, $tradeIn->tradeInId);
			$cleanTradeIn->customerId = mysqli_real_escape_string($this->link, $tradeIn->customerId);
            $cleanTradeIn->tradeInDateTime = mysqli_real_escape_string($this->link, $tradeIn->tradeInDateTime);
			$cleanTradeIn->tradeInEmployee = mysqli_real_escape_string($this->link, $tradeIn->tradeInEmployee);
			$cleanTradeIn->cashPaid = mysqli_real_escape_string($this->link, $tradeIn->cashPaid);
			$cleanTradeIn->creditPaid = mysqli_real_escape_string($this->link, $tradeIn->creditPaid);
			$cleanTradeIn->checkPaid = mysqli_real_escape_string($this->link, $tradeIn->checkPaid);
			$cleanTradeIn->checkNumber = mysqli_real_escape_string($this->link, $tradeIn->checkNumber);
			$cleanTradeIn->totalPaid = mysqli_real_escape_string($this->link, $tradeIn->totalPaid);
			$cleanTradeIn->comments = mysqli_real_escape_string($this->link, $tradeIn->comments);
			$cleanTradeIn->location = mysqli_real_escape_string($this->link, $tradeIn->location);
			
			return $cleanTradeIn;
		}else{
			$cleanParam = mysqli_real_escape_string($this->link, $tradeIn);
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
		$cleanRow['tradeInId'] = htmlentities($row['tradeInId']);
        $cleanRow['customerId'] = htmlentities($row['customerId']);
        $cleanRow['tradeInDateTime'] = htmlentities($row['tradeInDateTime']);
        $cleanRow['tradeInEmployee'] = htmlentities($row['tradeInEmployee']);
        $cleanRow['cashPaid'] = htmlentities($row['cashPaid']);
        $cleanRow['creditPaid'] = htmlentities($row['creditPaid']);
        $cleanRow['checkPaid'] = htmlentities($row['checkPaid']);
		$cleanRow['checkNumber'] = htmlentities($row['checkNumber']);
		$cleanRow['totalPaid'] = htmlentities($row['totalPaid']);
		$cleanRow['comments'] = htmlentities($row['comments']);
		$cleanRow['location'] = htmlentities($row['location']);

		return $cleanRow;
    }
    
    /**
	* Gets all trade ins from a in the database
	* @param {assoc array} 	This optional param would allow you to filter the result set
	* 						For example, you could use it to somehow add a WHERE claus to the query
	* 
	* @return {array}		Returns an array of trade in objects
	*/
	function getAll($args = []){
		$qStr = "SELECT tradeInId, customerId, tradeInDateTime, tradeInEmployee, cashPaid, creditPaid, checkPaid, checkNumber, totalPaid, comments, location FROM tradeins";
		//die($qStr);

		//Many people run queries like this. Shows error messages to users. 
		//$result = mysqli_query($this->link, $qStr) or die(mysqli_error($this->link));
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		$allTradeIns = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$tradeIn = new TradeIn($cleanRow);

				$allTradeIns[] = $tradeIn;
			}
		}
		return $allTradeIns;
	}
	
    
    /**
	* Gets a trade in from the database by its id
	* @param {number} 	 The id of the trade in to get from a row in the database
	* @return {tradeIn} Returns an instance of a trade in model object
	*/
	function getById($tradeInId){
		$cleanTradeInId = $this->cleanDataGoingIntoDB($tradeInId);
		$qStr = "SELECT tradeInId, customerId, tradeInDateTime, tradeInEmployee, cashPaid, creditPaid, checkPaid, checkNumber, totalPaid, comments, location FROM tradeins WHERE tradeInId = '$cleanTradeInId'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		if(mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$cleanRow = $this->cleanDataComingFromDB($row);
			$tradeIn = new TradeIn($cleanRow);
			return $tradeIn;
		}

		return false;
    }

    /**
	* Gets all tradeIns from the database by customer id
	* @param {number} 	 The id of the customer whose trade ins are to be retreived from db
	* @return {array} Returns an array of trade in objects
	*/

	function getTradeInByCustomerId($customerId){
        $cleanCustomerId = $this->cleanDataGoingIntoDB($customerId);
		$qStr = "SELECT tradeInId, customerId, tradeInDateTime, tradeInEmployee, cashPaid, creditPaid, checkPaid, checkNumber, totalPaid, comments, location FROM tradeins WHERE customerId = '$cleanCustomerId'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		$allTradeInsByCustomer = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$tradeIn = new TradeIn($cleanRow);

				$allTradeInsByCustomer[] = $tradeIn;
			}
		}
		return $allTradeInsByCustomer;
	}
	
	function getTradeInByCustomerIdAscending($customerId){
        $cleanCustomerId = $this->cleanDataGoingIntoDB($customerId);
		$qStr = "SELECT tradeInId, customerId, tradeInDateTime, tradeInEmployee, cashPaid, creditPaid, checkPaid, checkNumber, totalPaid, comments, location FROM tradeins WHERE customerId = '$cleanCustomerId' ORDER BY tradeInDateTime ASC";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		$allTradeInsByCustomer = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$tradeIn = new TradeIn($cleanRow);

				$allTradeInsByCustomer[] = $tradeIn;
			}
		}
		return $allTradeInsByCustomer;
	}
	
	function getTradeInByCustomerIdDescending($customerId){
        $cleanCustomerId = $this->cleanDataGoingIntoDB($customerId);
		$qStr = "SELECT tradeInId, customerId, tradeInDateTime, tradeInEmployee, cashPaid, creditPaid, checkPaid, checkNumber, totalPaid, comments, location FROM tradeins WHERE customerId = '$cleanCustomerId' ORDER BY tradeInDateTime DESC";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		$allTradeInsByCustomer = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$tradeIn = new TradeIn($cleanRow);

				$allTradeInsByCustomer[] = $tradeIn;
			}
		}
		return $allTradeInsByCustomer;
	}
	
	function getTradeInsByLocationAndDate($location, $startDate, $endDate){
		$cleanLocation = $this->cleanDataGoingIntoDB($location);
		$cleanStartDate = $this->cleanDataGoingIntoDB($startDate);
		$cleanEndDate = $this->cleanDataGoingIntoDB($endDate);

		$qStr = "SELECT tradeInId, customerId, tradeInDateTime, tradeInEmployee, cashPaid, creditPaid, checkPaid, checkNumber, totalPaid, comments, location FROM tradeins WHERE location = '$location' AND tradeInDateTime BETWEEN '$startDate 00:00:00' AND '$endDate 23:59:59' ORDER BY tradeInDateTime ASC";
		
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		$allTisByLocAndDate = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$tradeIn = new TradeIn($cleanRow);

				$allTisByLocAndDate[] = $tradeIn;
			}
		}

		return $allTisByLocAndDate;
	}


    
    /**
	* Inserts a trade in into a table in the database
	* @param {tradeIn}	The trade in object to be inserted
	* @return {trade in}	Returns the same trade in object, but with the tradeInId property set (the tradeInId is assigned by the database)
	*/
	function insert($tradeIn){
		$cleanTradeIn = $this->cleanDataGoingIntoDB($tradeIn);
		$qStr = "INSERT INTO tradeins (customerId, tradeInDateTime, tradeInEmployee, cashPaid, creditPaid, checkPaid, checkNumber, totalPaid, comments, location) VALUES (
			'{$cleanTradeIn->customerId}',
            '{$cleanTradeIn->tradeInDateTime}',
            '{$cleanTradeIn->tradeInEmployee}',
            '{$cleanTradeIn->cashPaid}',
            '{$cleanTradeIn->creditPaid}',
            '{$cleanTradeIn->checkPaid}',
            '{$cleanTradeIn->checkNumber}',
            '{$cleanTradeIn->totalPaid}',
			'{$cleanTradeIn->comments}',
			'{$cleanTradeIn->location}'
		)";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if($result){
			$cleanTradeIn->tradeInId = mysqli_insert_id($this->link);
		}else{
			$this->handleError("Unable to insert trade in");
		}

		return $cleanTradeIn;
    }
    
    /**
	* Updates a tradeIn object in the database
	* @param {tradeIn}	 The tradeIn model object to be updated
	* @return {tradeIn} Returns the same tradeIn model object that was passed in as the param
	*/
	function update($tradeIn){
		$cleanTradeIn = $this->cleanDataGoingIntoDB($tradeIn);
		$getTradeIn = $this->getById($cleanTradeIn->tradeInId);
		$qStr = "UPDATE tradeins SET 
				customerId = '{$cleanTradeIn->customerId}',
				tradeInDateTime = '{$getTradeIn->tradeInDateTime}',
                tradeInEmployee = '{$cleanTradeIn->tradeInEmployee}',
				cashPaid = '{$cleanTradeIn->cashPaid}',
				creditPaid = '{$cleanTradeIn->creditPaid}',
				checkPaid = '{$cleanTradeIn->checkPaid}',
				checkNumber = '{$cleanTradeIn->checkNumber}',
				totalPaid = '{$cleanTradeIn->totalPaid}',
				comments = '{$cleanTradeIn->comments}',
				location = '{$cleanTradeIn->location}'
                WHERE tradeInId = '{$cleanTradeIn->tradeInId}'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		$cleanTradeIn->tradeInDateTime = $getTradeIn->tradeInDateTime;
		return $cleanTradeIn;
    }
    
    /**
	* Deletes a tradeIn from a table in the database
	* @param {number} 	The id of the tradeIn to delete
	* @return {boolean}	Returns true if the row was sucessfully deleted, false otherwise
	*/
	function delete($tradeInId){
		$cleanTradeInId = $this->cleanDataGoingIntoDB($tradeInId);
		$qStr = "DELETE FROM tradeins WHERE tradeInId = $cleanTradeInId";
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if(mysqli_affected_rows($this->link) == 1){
			return true;
        }
        
        return false;
        
	}
}