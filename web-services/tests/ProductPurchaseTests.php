<?php
include_once("../includes/models/ProductPurchase.inc.php");

$testResults = array();

constructorTest();
isValidTest();

echo(implode($testResults, "<br />"));

function constructorTest(){
    global $testResults;
    $testResults[] = "<h3>Testing constructor...</h3>";

    //Test to ensure a productPurchase object can be made
    $prodPurchase = new ProductPurchase();

    if($prodPurchase){
        $testResults[] = "PASS - Created instance of productPurchase model object";
    }else{
        $testResults[] = "FAIL - Unable to create instance of productPurchase model object";
    }

    //Testing if productId gets set properly
    $options = array('productId' => 1);

    $prodPurchase = new ProductPurchase($options);

    if($prodPurchase->productId == 1){
        $testResults[] = "PASS - Set productId properly";
    }else{
        $testResults[] = "FAIL - Did not set productId properly";
    }
}

function isValidTest(){
    global $testResults;
    $testResults[] = "<h3>Testing isValid()...</h3>";

    //Test 1 - returns false if productPurchase is invalid
    $prodPurchase = new ProductPurchase(array(
        'ppId' => null,
        'purchaseId' => null,
        'productId' => null
    ));

    if($prodPurchase->isValid() === false){
        $testResults[] = "FAIL - Did not validate productPurchase properly";
    }else{
        $testResults[] = "Pass - Validated productPurchase properly";
    }

    var_dump($prodPurchase);
}
?>