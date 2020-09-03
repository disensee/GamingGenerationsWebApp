<?php

include_once("../includes/config.inc.php");
include_once("../includes/dataaccess/TradeInProductDataAccess.inc.php");

$testResults = [];

testConstructor();
testSanitizeHtml();
testCleanDataGoingIntoDB();
testCleanDataComingFromDB();
testGetById();
testGetTradeInProductByTradeInId();
testGetAll();
//testInsert();
//testUpdate();
//testDelete();

echo(implode("<br>", $testResults));

function testConstructor(){

	global $testResults;
	$testResults[] = "<h3>TESTING constructor...</h3>";

	// TEST 1 - create an instance of the TradeInProductDataAccess class
	$da = new TradeInProductDataAccess(get_link());
	
	if($da){
		$testResults[] = "PASS - Created instance of TradeInProductDataAccess";
	}else{
		$testResults[] = "FAIL - DID NOT create instance of TradeInProductDataAccess";
	}
}

function testSanitizeHtml(){
	
	global $testResults;
	$testResults[] = "<h3>TESTING sanitizeHtml()...</h3>";
	
	$da = new TradeInProductDataAccess(get_link());

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



function testCleanDataGoingIntoDB(){
	global $testResults;
	$testResults[] = "<h3>TESTING cleanDataGoingIntoDB()...</h3>";

	$da = new TradeInProductDataAccess(get_link());

	// TEST 1 - Make sure that tpId property is 'cleaned' properly
	// We need a tradeInProduct model object to pass in as a parameter
	$tradeInProd = new TradeInProduct([
		'tpId' => "1';DROP TABLE users;"
	]);

	$cleanTradeInProd = $da->cleanDataGoingIntoDB($tradeInProd);
	$expectedResult = "1\';DROP TABLE users;"; // The single quote should be escaped
	$actualResult = $cleanTradeInProd->tpId;
	
	if($expectedResult == $actualResult){
		$testResults[] = "PASS - Properly cleaned the tpId property";
	}else{
		$testResults[] = "FAIL - DID NOT properly clean the tpId property";
	}


	// MORE TESTS.....test to make sure each property of a customer object is 'cleaned' -- if time permits
}

function testCleanDataComingFromDB(){
	global $testResults;
	$testResults[] = "<h3>TESTING cleanDataComingFromDB()...</h3>";

	$da = new TradeInProductDataAccess(get_link());

	// TEST 1 - Make sure to clean malicious HTML from the serialNumber column
	// We need to simulate a row coming from the database (as an associative array)
	// There should be malicious HTML in one of the values
	$row = [
        'tpId' => 1,
        'tradeInId' => 2,
        'productId' => 1,
		'serialNumber' => "1231245<script>location.href='www.badsite.com'</script>"
	];

	$cleanRow = $da->cleanDataComingFromDB($row);
	$expectedResult = "1231245&lt;script&gt;location.href='www.badsite.com'&lt;/script&gt;";
	// < and > characters should be replaced with &lt; and &gt'
	$actualResult = $cleanRow['serialNumber'];
	
		if($expectedResult == $actualResult){
		$testResults[] = "PASS - Properly removed script element from the tradeInEmployee property";
	}else{
		$testResults[] = "FAIL - DID NOT properly remove script element from the tradeInEmployee property";
	}

}

function testGetById(){
	global $testResults;
	$testResults[] = "<h3>TESTING getById()...</h3>";

	$da = new TradeInProductDataAccess(get_link());
	$tradeInProd = $da->getById(1);
	$testresults[] = var_dump($tradeInProd);
}

function testGetTradeInProductByTradeInId(){
	global $testResults;
	$testResults[] = "<h3>TESTING getTradeInProductByTradeInId()...</h3>";

	$da = new TradeInProductDataAccess(get_link());
	$tradeInProducts = $da->getTradeInProductByTradeInId(1);
	var_dump($tradeInProducts);
}

function testGetAll(){
	global $testResults;
	$testResults[] = "<h3>TESTING getAll()...</h3>";

	// We need an instance of a TradeInProductDataAccess object so that we can call the method we want to test
	$da = new TradeInProductDataAccess(get_link());
	$tradeInProds = $da->getAll();
	var_dump($tradeInProds);
}

function testInsert(){
	global $testResults;
	$testResults[] = "<h3>TESTING insert()...</h3>";

	$da = new TradeInProductDataAccess(get_link());
	$tradeInProd = new TradeInProduct([
		'tradeInId' => 1,
        'productId'=> 1965,
        'serialNumber' => "39405783490"
	]);

	$newTradeInProd = $da->insert($tradeInProd);

	var_dump($newTradeInProd);
}

function testUpdate(){
	global $testResults;
	$testResults[] = "<h3>TESTING update()...</h3>";

	$da = new TradeInProductDataAccess(get_link());
	
	$tradeInProdToUpdate = $da->getById(9);
	$tradeInProdToUpdate->productId = 1968;
	$tradeInProdToUpdate = $da->update($tradeInProdToUpdate);

	var_dump($tradeInProdToUpdate);

}

function testDelete(){
	global $testResults;
	$testResults[] = "<h3>TESTING delete()...</h3>";

	$da = new TradeInProductDataAccess(get_link());
	$tradeInProdDeleted = $da->delete(9);

	var_dump($tradeInProdDeleted);
}


?>