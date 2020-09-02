<?php

include_once("../includes/config.inc.php");
include_once("../includes/dataaccess/TradeInDataAccess.inc.php");

$testResults = [];

testConstructor();
testSanitizeHtml();
testConvertDateForMySQL();
testCleanDataGoingIntoDB();
testCleanDataComingFromDB();
testGetById();
testGetTradeInByCustomerId();
testGetAll();
//testInsert();
//testUpdate();
//testDelete();

echo(implode("<br>", $testResults));

function testConstructor(){

	global $testResults;
	$testResults[] = "<h3>TESTING constructor...</h3>";

	// TEST 1 - create an instance of the TradeInDataAccess class
	$da = new TradeInDataAccess(get_link());
	
	if($da){
		$testResults[] = "PASS - Created instance of TradeInDataAccess";
	}else{
		$testResults[] = "FAIL - DID NOT create instance of TradeInDataAccess";
	}
}

function testSanitizeHtml(){
	
	global $testResults;
	$testResults[] = "<h3>TESTING sanitizeHtml()...</h3>";
	
	$da = new TradeInDataAccess(get_link());

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

	$da = new TradeInDataAccess(get_link());

	// TEST 1 - Make sure it removes 'script' tags from the HTML string
	$stringToFormat = "2/1/2020";
	$expectedResult = "2020-02-01";
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

	$da = new TradeInDataAccess(get_link());

	// TEST 1 - Make sure that tradeInId property is 'cleaned' properly
	// We need a tradeIn model object to pass in as a parameter
	$tradeIn = new TradeIn([
		'tradeInId' => "1';DROP TABLE users;"
	]);

	$cleanTradeIn = $da->cleanDataGoingIntoDB($tradeIn);
	$expectedResult = "1\';DROP TABLE users;"; // The single quote should be escaped
	$actualResult = $cleanTradeIn->tradeInId;
	
	if($expectedResult == $actualResult){
		$testResults[] = "PASS - Properly cleaned the tradeInId property";
	}else{
		$testResults[] = "FAIL - DID NOT properly clean the tradeInId property";
	}


	// MORE TESTS.....test to make sure each property of a customer object is 'cleaned' -- if time permits
}

function testCleanDataComingFromDB(){
	global $testResults;
	$testResults[] = "<h3>TESTING cleanDataComingFromDB()...</h3>";

	$da = new TradeInDataAccess(get_link());

	// TEST 1 - Make sure to clean malicious HTML from the tradeInEmployee column
	// We need to simulate a row coming from the database (as an associative array)
	// There should be malicious HTML in one of the values
	$row = [
        'tradeInId' => 1,
        'customerId' => 2,
        'tradeInDateTime' => '2020-08-11 15:30:54',
		'tradeInEmployee' => "DKI<script>location.href='www.badsite.com'</script>"
	];

	$cleanRow = $da->cleanDataComingFromDB($row);
	$expectedResult = "DKI&lt;script&gt;location.href='www.badsite.com'&lt;/script&gt;";
	// < and > characters should be replaced with &lt; and &gt'
	$actualResult = $cleanRow['tradeInEmployee'];
	
		if($expectedResult == $actualResult){
		$testResults[] = "PASS - Properly removed script element from the tradeInEmployee property";
	}else{
		$testResults[] = "FAIL - DID NOT properly remove script element from the tradeInEmployee property";
	}

}

function testGetById(){
	global $testResults;
	$testResults[] = "<h3>TESTING getById()...</h3>";

	$da = new TradeInDataAccess(get_link());
	$tradeIn = $da->getById(1);
	$testresults[] = var_dump($tradeIn);
}

function testGetTradeInByCustomerId(){
	global $testResults;
	$testResults[] = "<h3>TESTING getTradeInByCustomerId()...</h3>";

	$da = new TradeInDataAccess(get_link());
	$tradeIns = $da->getTradeInByCustomerId(4);
	var_dump($tradeIns);
}

function testGetAll(){
	global $testResults;
	$testResults[] = "<h3>TESTING getAll()...</h3>";

	// We need an instance of a TradeInDataAccess object so that we can call the method we want to test
	$da = new TradeInDataAccess(get_link());
	$tradeIns = $da->getAll();
	var_dump($tradeIns);
}

function testInsert(){
	global $testResults;
	$testResults[] = "<h3>TESTING insert()...</h3>";

	$da = new TradeInDataAccess(get_link());
	$tradeIn = new TradeIn([
		'customerId' => 4,
        'tradeInDateTime'=> null,
        'tradeInEmployee' => "QWE"
	]);

	//$newTradeIn = $da->insert($tradeIn);

	//var_dump($newCustomer);
}

function testUpdate(){
	global $testResults;
	$testResults[] = "<h3>TESTING update()...</h3>";

	$da = new TradeInDataAccess(get_link());
	
	$tradeInToUpdate = $da->getById(2);
	$tradeInToUpdate->tradeInEmployee = "ERM";
	//$tradeInToUpdate = $da->update($tradeInToUpdate);

	//var_dump($tradeInToUpdate);

}

function testDelete(){
	global $testResults;
	$testResults[] = "<h3>TESTING delete()...</h3>";

	$da = new TradeInDataAccess(get_link());
	$tradeInDeleted = $da->delete(3);

	var_dump($tradeInDeleted);
}


?>