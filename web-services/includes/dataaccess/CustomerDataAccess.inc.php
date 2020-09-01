<?php
include_once('DataAccess.inc.php');
include_once(__DIR__ . "/../models/Customer.inc.php");

class CustomerDataAccess extends DataAccess{

    function __construct($link){

        parent::__construct($link);
        
	}

	/**
	* 'Cleans' the data in a customer object to prevent SQL injection attacks
	* @param {customer}	A customer model object
	* @return {customer} A new instance of customer object with clean data in it
	*/
	function cleanDataGoingIntoDB($customer){

		if($customer instanceOf customer){
			$cleanCustomer = new customer();
			$cleanCustomer->customerId = mysqli_real_escape_string($this->link, $customer->customerId);
			$cleanCustomer->customerFirstName = mysqli_real_escape_string($this->link, $customer->customerFirstName);
            $cleanCustomer->customerLastName = mysqli_real_escape_string($this->link, $customer->customerLastName);
            $cleanCustomer->customerIdNumber = mysqli_real_escape_string($this->link, $customer->customerIdNumber);
            $cleanCustomer->customerEmail = mysqli_real_escape_string($this->link, $customer->customerEmail);
            $cleanCustomer->customerPhone = mysqli_real_escape_string($this->link, $customer->customerPhone);
			return $cleanCustomer;
		}else{
			$cleanParam = mysqli_real_escape_string($this->link, $customer);
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
		$cleanRow['customerId'] = htmlentities($row['customerId']);
        $cleanRow['customerFirstName'] = htmlentities($row['customerFirstName']);
        $cleanRow['customerLastName'] = htmlentities($row['customerLastName']);
        $cleanRow['customerIdNumber'] = htmlentities($row['customerIdNumber']);
        $cleanRow['customerEmail'] = htmlentities($row['customerEmail']);
        $cleanRow['customerPhone'] = htmlentities($row['customerPhone']);

		return $cleanRow;
    }
    
    /**
	* Gets all customers from a table in the database
	* @param {assoc array} 	This optional param would allow you to filter the result set
	* 						For example, you could use it to somehow add a WHERE claus to the query
	* 
	* @return {array}		Returns an array of customer objects
	*/
	function getAll($args = []){
		$qStr = "SELECT customerId, customerFirstName, customerLastName, customerIdNumber, customerEmail, customerPhone FROM customers";
		//die($qStr);

		//Many people run queries like this. Shows error messages to users. 
		//$result = mysqli_query($this->link, $qStr) or die(mysqli_error($this->link));
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		$allCustomers = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$customer = new Customer($cleanRow);

				$allCustomers[] = $customer;
			}
		}
		return $allCustomers;
	}
	
    
    /**
	* Gets a customer from the database by its id
	* @param {number} 	 The id of the customer to get from a row in the database
	* @return {customer} Returns an instance of a customer model object
	*/
	function getById($customerId){
		$cleanCustomerId = $this->cleanDataGoingIntoDB($customerId);
		$qStr = "SELECT customerId, customerFirstName, customerLastName, customerIdNumber, customerEmail, customerPhone FROM customers WHERE customerId = '$cleanCustomerId'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		if(mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$cleanRow = $this->cleanDataComingFromDB($row);
			$customer = new Customer($cleanRow);
			return $customer;
		}

		return false;
    }

    /**
	* Gets a customer from the database by its customer name
	* @param {string} 	 The name of the customer to get from a row in the database
	* @param {string} 	 The name of the console the customer is for
	* @return {customer} Returns an instance of a customer model object
	*/
	function getByCustomerLastName($customerLastName){
        $cleanCustomerLastName = $this->cleanDataGoingIntoDB($customerLastName);
		$qStr = "SELECT customerId, customerFirstName, customerLastName, customerIdNumber, customerEmail, customerPhone FROM customers WHERE customerLastName LIKE '%$cleanCustomerLastName%'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		$allCustomers = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$customer = new Customer($cleanRow);

				$allCustomers[] = $customer;
			}
		}
		return $allCustomers;
    }


    
    /**
	* Inserts a customer into a table in the database
	* @param {customer}	The customer object to be inserted
	* @return {customer}	Returns the same customer object, but with the customerId property set (the customerId is assigned by the database)
	*/
	function insert($customer){
		$cleanCustomer = $this->cleanDataGoingIntoDB($customer);
		$qStr = "INSERT INTO customers (customerFirstName, customerLastName, customerIdNumber, customerEmail, customerPhone) VALUES (
			'{$cleanCustomer->customerFirstName}',
            '{$cleanCustomer->customerLastName}',
            '{$cleanCustomer->customerIdNumber}',
            '{$cleanCustomer->customerEmail}',
            '{$cleanCustomer->customerPhone}'
		)";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if($result){
			$cleanCustomer->customerId = mysqli_insert_id($this->link);
		}else{
			$this->handleError("Unable to insert customer");
		}

		return $cleanCustomer;
    }
    
    /**
	* Updates a customer in the database
	* @param {customer}	 The customer model object to be updated
	* @return {customer} Returns the same customer model object that was passed in as the param
	*/
	function update($customer){
		$cleanCustomer = $this->cleanDataGoingIntoDB($customer);
		$qStr = "UPDATE customers SET 
				customerFirstName = '{$cleanCustomer->customerFirstName}',
				customerLastName = '{$cleanCustomer->customerLastName}',
                customerIdNumber = '{$cleanCustomer->customerIdNumber}',
                customerEmail = '{$cleanCustomer->customerEmail}',
                customerPhone = '{$cleanCustomer->customerPhone}'
				WHERE customerId = '{$cleanCustomer->customerId}'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		return $cleanCustomer;
    }
    
    /**
	* Deletes a customer from a table in the database
	* @param {number} 	The id of the customer to delete
	* @return {boolean}	Returns true if the row was sucessfully deleted, false otherwise
	*/
	function delete($customerId){
		$cleanCustomerId = $this->cleanDataGoingIntoDB($customerId);
		$qStr = "DELETE FROM customers WHERE customerId = $cleanCustomerId";
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if(mysqli_affected_rows($this->link) == 1){
			return true;
        }
        
        return false;
        
	}
}
