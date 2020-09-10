<?php

include_once("../includes/config.inc.php");
include_once("../includes/dataaccess/ProductPurchaseDataAccess.inc.php");

$testResults = [];

testConstructor();
testSanitizeHtml();
testCleanDataGoingIntoDB();
testCleanDataComingFromDB();
//testGetById();
//testGetProductPurchaseByPurchaseId();
//testGetAll();
//testInsert();
//testUpdate();
testDelete();

echo(implode("<br>", $testResults));

function testConstructor(){

	global $testResults;
	$testResults[] = "<h3>TESTING constructor...</h3>";

	// TEST 1 - create an instance of the ProductPurchaseDataAccess class
	$da = new ProductPurchaseDataAccess(get_link());
	
	if($da){
		$testResults[] = "PASS - Created instance of ProductPurchaseDataAccess";
	}else{
		$testResults[] = "FAIL - DID NOT create instance of ProductPurchaseDataAccess";
	}
}

function testSanitizeHtml(){
	
	global $testResults;
	$testResults[] = "<h3>TESTING sanitizeHtml()...</h3>";
	
	$da = new ProductPurchaseDataAccess(get_link());

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

	$da = new ProductPurchaseDataAccess(get_link());

	// TEST 1 - Make sure that ppId property is 'cleaned' properly
	// We need a productPurchase model object to pass in as a parameter
	$prodPurchase = new ProductPurchase([
		'ppId' => "1';DROP TABLE users;"
	]);

	$cleanProd = $da->cleanDataGoingIntoDB($prodPurchase);
	$expectedResult = "1\';DROP TABLE users;"; // The single quote should be escaped
	$actualResult = $cleanProd->ppId;
	
	if($expectedResult == $actualResult){
		$testResults[] = "PASS - Properly cleaned the ppId property";
	}else{
		$testResults[] = "FAIL - DID NOT properly clean the ppId property";
	}


	// MORE TESTS.....test to make sure each property of a productPurchase object is 'cleaned' -- if time permits
}

function testCleanDataComingFromDB(){
	global $testResults;
	$testResults[] = "<h3>TESTING cleanDataComingFromDB()...</h3>";

	$da = new ProductPurchaseDataAccess(get_link());

	// TEST 1 - Make sure to clean malicious HTML from the productId column
	// We need to simulate a row coming from the database (as an associative array)
	// There should be malicious HTML in one of the values
	$row = [
        'ppId' => 1,
        'purchaseId' => 2,
        'productId' => "1<script>location.href='www.badsite.com'</script>",
	];

	$cleanRow = $da->cleanDataComingFromDB($row);
	$expectedResult = "1&lt;script&gt;location.href='www.badsite.com'&lt;/script&gt;";
	// < and > characters should be replaced with &lt; and &gt'
	$actualResult = $cleanRow['productId'];
	
		if($expectedResult == $actualResult){
		$testResults[] = "PASS - Properly removed script element from the productId property";
	}else{
		$testResults[] = "FAIL - DID NOT properly remove script element from the productId property";
	}

}

function testGetById(){
	global $testResults;
	$testResults[] = "<h3>TESTING getById()...</h3>";

	$da = new ProductPurchaseDataAccess(get_link());
	$prodPurchase = $da->getById(1);
	$testresults[] = var_dump($prodPurchase);
}

function testGetProductPurchaseByPurchaseId(){
	global $testResults;
	$testResults[] = "<h3>TESTING getProductById()...</h3>";

	$da = new ProductPurchaseDataAccess(get_link());
	$productPurchases = $da->getProductPurchaseByPurchaseId(2);
	var_dump($productPurchases);
}

function testGetAll(){
	global $testResults;
	$testResults[] = "<h3>TESTING getAll()...</h3>";

	// We need an instance of a ProductPurchaseDataAccess object so that we can call the method we want to test
	$da = new ProductPurchaseDataAccess(get_link());
	$prodPurchases = $da->getAll();
	var_dump($prodPurchases);
}

function testInsert(){
	global $testResults;
	$testResults[] = "<h3>TESTING insert()...</h3>";

	$da = new ProductPurchaseDataAccess(get_link());
	$prodPurchase = new ProductPurchase([
		'purchaseId' => 2,
        'productId'=> 1965
	]);

	$newProd = $da->insert($prodPurchase);

	var_dump($newProd);
}

function testUpdate(){
	global $testResults;
	$testResults[] = "<h3>TESTING update()...</h3>";

	$da = new ProductPurchaseDataAccess(get_link());
	
	$prodPurchaseToUpdate = $da->getById(1);
	$prodPurchaseToUpdate->productId = 1968;
	$prodPurchaseToUpdate = $da->update($prodPurchaseToUpdate);

	var_dump($prodPurchaseToUpdate);

}

function testDelete(){
	global $testResults;
	$testResults[] = "<h3>TESTING delete()...</h3>";

	$da = new ProductPurchaseDataAccess(get_link());
	$prodPurchaseDelete = $da->delete(2);

	var_dump($prodPurchaseDelete);
}


?>