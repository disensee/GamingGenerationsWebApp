<?php

include_once("../includes/config.inc.php");
include_once("../includes/dataaccess/PurchaseDataAccess.inc.php");

$testResults = [];

testConstructor();
testSanitizeHtml();
testConvertDateForMySQL();
testCleanDataGoingIntoDB();
testCleanDataComingFromDB();
//testGetById();
//testGetPurchaseByCustomerId();
testGetAll();
//testInsert();
//testUpdate();
//testDelete();

echo(implode("<br>", $testResults));

function testConstructor(){

	global $testResults;
	$testResults[] = "<h3>TESTING constructor...</h3>";

	// TEST 1 - create an instance of the PurchaseDataAccess class
	$da = new PurchaseDataAccess(get_link());
	
	if($da){
		$testResults[] = "PASS - Created instance of PurchaseDataAccess";
	}else{
		$testResults[] = "FAIL - DID NOT create instance of PurchaseDataAccess";
	}
}

function testSanitizeHtml(){
	
	global $testResults;
	$testResults[] = "<h3>TESTING sanitizeHtml()...</h3>";
	
	$da = new PurchaseDataAccess(get_link());

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


function testConvertDateForMySQL(){
	global $testResults;
	$testResults[] = "<h3>TESTING convertDateForMySQL()...</h3>";

	$da = new PurchaseDataAccess(get_link());

	// TEST 1 - Make sure it removes 'script' tags from the HTML string
	$stringToFormat = "2/1/2020";
	$expectedResult = "2020-02-01 00:00:00";
	$actualResult = $da->convertDateForMySQL($stringToFormat);

	if($expectedResult == $actualResult){
		$testResults[] = "PASS - Formatted 2/1/202 into 2020-02-01";
	}else{
		$testResults[] = "FAIL - DID NOT format 2/1/202 into 2020-02-01";
	}
}


function testCleanDataGoingIntoDB(){
	global $testResults;
	$testResults[] = "<h3>TESTING cleanDataGoingIntoDB()...</h3>";

	$da = new PurchaseDataAccess(get_link());

	// TEST 1 - Make sure that purchaseId property is 'cleaned' properly
	// We need a purchase model object to pass in as a parameter
	$purchase = new Purchase([
		'purchaseId' => "1';DROP TABLE users;"
	]);

	$cleanPurchase = $da->cleanDataGoingIntoDB($purchase);
	$expectedResult = "1\';DROP TABLE users;"; // The single quote should be escaped
	$actualResult = $cleanPurchase->purchaseId;
	
	if($expectedResult == $actualResult){
		$testResults[] = "PASS - Properly cleaned the purchaseId property";
	}else{
		$testResults[] = "FAIL - DID NOT properly clean the purchaseId property";
	}


	// MORE TESTS.....test to make sure each property of a purchase object is 'cleaned' -- if time permits
}

function testCleanDataComingFromDB(){
	global $testResults;
	$testResults[] = "<h3>TESTING cleanDataComingFromDB()...</h3>";

	$da = new PurchaseDataAccess(get_link());

	// TEST 1 - Make sure to clean malicious HTML from the purchaseEmployee column
	// We need to simulate a row coming from the database (as an associative array)
	// There should be malicious HTML in one of the values
	$row = [
        'purchaseId' => 1,
        'customerId' => 2,
        'purchaseDateTime' => '2020-08-11 15:30:54',
        'purchaseEmployee'=>"DKI<script>location.href='www.badsite.com'</script>",
        'cashReceived' => 0.00,
        'creditReceived' => 0.00,
        'storeCreditReceived' => 0.00,
        'totalPurchasePrice' => 0.00
        
	];

	$cleanRow = $da->cleanDataComingFromDB($row);
	$expectedResult = "DKI&lt;script&gt;location.href='www.badsite.com'&lt;/script&gt;";
	// < and > characters should be replaced with &lt; and &gt'
	$actualResult = $cleanRow['purchaseEmployee'];
	
		if($expectedResult == $actualResult){
		$testResults[] = "PASS - Properly removed script element from the purchaseEmployee property";
	}else{
		$testResults[] = "FAIL - DID NOT properly remove script element from the purchaseEmployee property";
	}

}

function testGetById(){
	global $testResults;
	$testResults[] = "<h3>TESTING getById()...</h3>";

	$da = new PurchaseDataAccess(get_link());
	$purchase = $da->getById(2);
	$testresults[] = var_dump($purchase);
}

function testGetPurchaseByCustomerId(){
	global $testResults;
	$testResults[] = "<h3>TESTING getPurchaseByCustomerId()...</h3>";

	$da = new PurchaseDataAccess(get_link());
	$purchases = $da->getPurchaseByCustomerId(4);
	var_dump($purchases);
}

function testGetAll(){
	global $testResults;
	$testResults[] = "<h3>TESTING getAll()...</h3>";

	// We need an instance of a PurchaseDataAccess object so that we can call the method we want to test
	$da = new PurchaseDataAccess(get_link());
	$purchases = $da->getAll();
	var_dump($purchases);
}

function testInsert(){
	global $testResults;
	$testResults[] = "<h3>TESTING insert()...</h3>";

	$da = new PurchaseDataAccess(get_link());
	$purchase = new Purchase([
		'customerId' => 4,
        'purchaseDateTime'=> null,
        'purchaseEmployee' => "QWE",
        'cashReceived' => 0.00,
        'creditReceived' => 25.00,
        'storeCreditReceived' => 0.00,
        'totalPurchasePrice' => null
	]);

	$newPurchase = $da->insert($purchase);

	var_dump($newPurchase);
}

function testUpdate(){
	global $testResults;
	$testResults[] = "<h3>TESTING update()...</h3>";

	$da = new PurchaseDataAccess(get_link());
	
	$purchaseToUpdate = $da->getById(1);
	$purchaseToUpdate->purchaseEmployee = "ERM";
	$purchaseToUpdate = $da->update($purchaseToUpdate);

	var_dump($purchaseToUpdate);

}

function testDelete(){
	global $testResults;
	$testResults[] = "<h3>TESTING delete()...</h3>";

	$da = new PurchaseDataAccess(get_link());
	$tradeInDeleted = $da->delete(1);

	var_dump($tradeInDeleted);
}

?>