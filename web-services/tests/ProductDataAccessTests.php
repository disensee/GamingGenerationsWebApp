<?php

include_once("../includes/config.inc.php");
include_once("../includes/dataaccess/ProductDataAccess.inc.php");

$testResults = [];

testConstructor();
testSanitizeHtml();
testCleanDataGoingIntoDB();
testCleanDataComingFromDB();
testGetById();
//testGetByProductName();
//testGetByConsoleName();
testGetByUpc();
testGetAll();
testInsert();
testUpdate();
testDelete();

echo(implode("<br>", $testResults));

function testConstructor(){

	global $testResults;
	$testResults[] = "<h3>TESTING constructor...</h3>";

	// TEST 1 - create an instance of the ProductDataAccess class
	$da = new ProductDataAccess(get_link());
	
	if($da){
		$testResults[] = "PASS - Created instance of ProductDataAccess";
	}else{
		$testResults[] = "FAIL - DID NOT create instance of ProductDataAccess";
	}
}

function testSanitizeHtml(){
	
	global $testResults;
	$testResults[] = "<h3>TESTING sanitizeHtml()...</h3>";
	
	$da = new ProductDataAccess(get_link());

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

// 	$da = new ProductDataAccess(get_link());

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

	$da = new ProductDataAccess(get_link());

	// TEST 1 - Make sure that productId property is 'cleaned' properly
	// We need a product model object to pass in as a parameter
	$product = new Product([
		'productId' => "1';DROP TABLE users;"
	]);

	$cleanProduct = $da->cleanDataGoingIntoDB($product);
	$expectedResult = "1\';DROP TABLE users;"; // The single quote should be escaped
	$actualResult = $cleanProduct->productId;
	
	if($expectedResult == $actualResult){
		$testResults[] = "PASS - Properly cleaned the productId propety";
	}else{
		$testResults[] = "FAIL - DID NOT properly cleaned the productId property";
	}


	// MORE TESTS.....test to make sure each property of a customer object is 'cleaned' -- if time permits
}

function testCleanDataComingFromDB(){
	global $testResults;
	$testResults[] = "<h3>TESTING cleanDataComingFromDB()...</h3>";

	$da = new ProductDataAccess(get_link());

	// TEST 1 - Make sure to clean malicious HTML from the consoleName column
	// We need to simulate a row coming from the database (as an associative array)
	// There should be malicious HTML in one of the values
	$row = [
		'productId' => 1,
		'consoleName' => "Playstation<script>location.href='www.badsite.com'</script>",
        'productName' => "The Last Guardian",
        'loosePrice' => 7.23,
        'cibPrice' => 9.46,
        'gamestopPrice' => 10.43,
        'gamestopTradePrice' => 2.15,
        'upc' => "012345678910",
        'quantity' => 3
	];

	$cleanRow = $da->cleanDataComingFromDB($row);
	$expectedResult = "Playstation&lt;script&gt;location.href='www.badsite.com'&lt;/script&gt;";
	// < and > characters should be replaced with &lt; and &gt'
	$actualResult = $cleanRow['consoleName'];
	
		if($expectedResult == $actualResult){
		$testResults[] = "PASS - Properly removed script element from the consoleName property";
	}else{
		$testResults[] = "FAIL - DID NOT properly remove script element from the consoleName property";
	}

}

function testGetById(){
	global $testResults;
	$testResults[] = "<h3>TESTING getById()...</h3>";

	$da = new ProductDataAccess(get_link());
	$product = $da->getById(1964);
	var_dump($product);
}

function testGetByProductName(){
	global $testResults;
	$testResults[] = "<h3>TESTING getByProductName()...</h3>";

	$da = new ProductDataAccess(get_link());
	$products = $da->getByProductName("madden", "Playstation 4");
	var_dump($products);
}

function testGetByConsoleName(){
	global $testResults;
	$testResults[] = "<h3>TESTING getByConsoleName()...</h3>";

	$da = new ProductDataAccess(get_link());
	$products = $da->getByConsoleName("xbox one");
	var_dump($products);
}

function testGetByUpc(){
	global $testResults;
	$testResults[] = "<h3>TESTING getByUpc()...</h3>";

	$da = new ProductDataAccess(get_link());
	$product = $da->getByUpc("711719748120");
	var_dump($product);
}

function testGetAll(){
	global $testResults;
	$testResults[] = "<h3>TESTING getAll()...</h3>";

	// We need an instance of a ProductDataAccess object so that we can call the method we want to test
	$da = new ProductDataAccess(get_link());
	$products = $da->getAll();
	//var_dump($products);
}

function testInsert(){
	global $testResults;
	$testResults[] = "<h3>TESTING insert()...</h3>";

	$da = new ProductDataAccess(get_link());
	$product = new Product([
		'consoleName' => "Playstation 4",
        'productName' => "Deep Rock Galactica",
        'loosePrice' => 44.79,
        'cibPrice' => 59.99,
        'gamestopPrice' => 59.99,
        'gamestopTradePrice' => 25.43,
		'upc' => "012345678910",
		'quantity' => 5
	]);

	//$newProduct = $da->insert($product);

	//var_dump($newProduct);
}

function testUpdate(){
	global $testResults;
	$testResults[] = "<h3>TESTING update()...</h3>";

	$da = new ProductDataAccess(get_link());
	
	//$productToUpdate = $da->getById(71822);
	//$productToUpdate->productName = "DOOM: Eternal";
	//$productToUpdate = $da->update($productToUpdate);

	//var_dump($productToUpdate);

}

function testDelete(){
	global $testResults;
	$testResults[] = "<h3>TESTING delete()...</h3>";

	$da = new ProductDataAccess(get_link());
	//$productDeleted = $da->delete(71822);

	//var_dump($productDeleted);
}


?>