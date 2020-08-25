<?php

include_once("../includes/config.inc.php");
include_once("../includes/dataaccess/CustomerDataAccess.inc.php");

$testResults = [];

testConstructor();
testSanitizeHtml();
testCleanDataGoingIntoDB();
testCleanDataComingFromDB();
testGetById();
testGetByCustomerLastName();
testGetAll();
//testInsert();
//testUpdate();
//testDelete();

echo(implode("<br>", $testResults));

function testConstructor(){

	global $testResults;
	$testResults[] = "<h3>TESTING constructor...</h3>";

	// TEST 1 - create an instance of the CustomerDataAccess class
	$da = new CustomerDataAccess(get_link());
	
	if($da){
		$testResults[] = "PASS - Created instance of CustomerDataAccess";
	}else{
		$testResults[] = "FAIL - DID NOT create instance of CustomerDataAccess";
	}
}

function testSanitizeHtml(){
	
	global $testResults;
	$testResults[] = "<h3>TESTING sanitizeHtml()...</h3>";
	
	$da = new CustomerDataAccess(get_link());

	// TEST 1 - Make sure it removes 'script' tags from the HTML string
	$dirtyHtml = "<h3><script>some script</sript></h3>";
	$expectedResult = "<h3>some script</h3>";
	$actualResult = $da->sanitizeHtml($dirtyHtml);
	
	if($expectedResult == $actualResult){
		$testResults[] = "PASS - Removed script tag from HTML string";
	}else{
		$testResults[] = "FAIL - DID NOT remove script tag from HTML string";
	}

	// TEST X - MORE TESTS TO DO...make sure it removes other tags and malicious attributes -- if time permits
}


// function testConvertDateForMySQL(){
// 	global $testResults;
// 	$testResults[] = "<h3>TESTING convertDateForMySQL()...</h3>";

// 	$da = new CustomerDataAccess(get_link());

// 	// TEST 1 - Make sure it removes 'script' tags from the HTML string
// 	$stringToFormat = "2/1/2020";
// 	$expectedResult = "2020-02-01";
// 	$actualResult = $da->convertDateForMySQL($stringToFormat);

// 	if($expectedResult == $actualResult){
// 		$testResults[] = "PASS - Formatted 2/1/202 into 2020-02-01";
// 	}else{
// 		$testResults[] = "FAIL - DID NOT format 2/1/202 into 2020-02-01";
// 	}
// }


function testCleanDataGoingIntoDB(){
	global $testResults;
	$testResults[] = "<h3>TESTING cleanDataGoingIntoDB()...</h3>";

	$da = new CustomerDataAccess(get_link());

	// TEST 1 - Make sure that customerId property is 'cleaned' properly
	// We need a customer model object to pass in as a parameter
	$customer = new Customer([
		'customerId' => "1';DROP TABLE users;"
	]);

	$cleanCustomer = $da->cleanDataGoingIntoDB($customer);
	$expectedResult = "1\';DROP TABLE users;"; // The single quote should be escaped
	$actualResult = $cleanCustomer->customerId;
	
	if($expectedResult == $actualResult){
		$testResults[] = "PASS - Properly cleaned the customerId propety";
	}else{
		$testResults[] = "FAIL - DID NOT properly cleaned the customerId property";
	}


	// MORE TESTS.....test to make sure each property of a customer object is 'cleaned' -- if time permits
}

function testCleanDataComingFromDB(){
	global $testResults;
	$testResults[] = "<h3>TESTING cleanDataComingFromDB()...</h3>";

	$da = new CustomerDataAccess(get_link());

	// TEST 1 - Make sure to clean malicious HTML from the consoleName column
	// We need to simulate a row coming from the database (as an associative array)
	// There should be malicious HTML in one of the values
	$row = [
		'customerId' => 1,
		'customerFirstName' => "Dylan<script>location.href='www.badsite.com'</script>",
        'customerLastName' => "Isensee",
        'customerIdNumber' => "12342345",
        'customerEmail' => "dylan@d.com",
        'customerPhone' => "1234567890"
	];

	$cleanRow = $da->cleanDataComingFromDB($row);
	$expectedResult = "Dylan&lt;script&gt;location.href='www.badsite.com'&lt;/script&gt;";
	// < and > characters should be replaced with &lt; and &gt'
	$actualResult = $cleanRow['customerFirstName'];
	
		if($expectedResult == $actualResult){
		$testResults[] = "PASS - Properly removed script element from the customerFirstName property";
	}else{
		$testResults[] = "FAIL - DID NOT properly remove script element from the customerFirstName property";
	}

}

function testGetById(){
	global $testResults;
	$testResults[] = "<h3>TESTING getById()...</h3>";

	$da = new CustomerDataAccess(get_link());
	$customer = $da->getById(1);
	$testresults[] = var_dump($customer);
}

function testGetByCustomerLastName(){
	global $testResults;
	$testResults[] = "<h3>TESTING getByCustomerName()...</h3>";

	$da = new CustomerDataAccess(get_link());
	$customers = $da->getByCustomerLastName("isensee");
	var_dump($customers);
}

function testGetAll(){
	global $testResults;
	$testResults[] = "<h3>TESTING getAll()...</h3>";

	// We need an instance of a CustomerDataAccess object so that we can call the method we want to test
	$da = new CustomerDataAccess(get_link());
	$customers = $da->getAll();
	var_dump($customers);
}

function testInsert(){
	global $testResults;
	$testResults[] = "<h3>TESTING insert()...</h3>";

	$da = new CustomerDataAccess(get_link());
	$customer = new Customer([
		'customerFirstName' => "Emma",
        'customerLastName' => "Maybanks",
        'customerIdNumber' => "",
        'customerEmail' => "emma@emamma.com",
        'customerPhone' => "1237893456"
	]);

	//$newCustomer = $da->insert($customer);

	//var_dump($newCustomer);
}

function testUpdate(){
	global $testResults;
	$testResults[] = "<h3>TESTING update()...</h3>";

	$da = new CustomerDataAccess(get_link());
	
	$customerToUpdate = $da->getById(2);
	$customerToUpdate->customerFirstName = "Emmaemmers";
	$customerToUpdate = $da->update($customerToUpdate);

	var_dump($customerToUpdate);

}

function testDelete(){
	global $testResults;
	$testResults[] = "<h3>TESTING delete()...</h3>";

	$da = new CustomerDataAccess(get_link());
	$customerDeleted = $da->delete(2);

	var_dump($customerDeleted);
}


?>