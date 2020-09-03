<?php
include_once("../includes/models/TradeInProduct.inc.php");

$testResults = array();

constructorTest();
isValidTest();

echo(implode($testResults, "<br />"));

function constructorTest(){
    global $testResults;
    $testResults[] = "<h3>Testing constructor...</h3>";

    //Test to ensure a tradeInProduct object can be made
    $tradeInProduct = new TradeInProduct();

    if($tradeInProduct){
        $testResults[] = "PASS - Created instance of tradein model object";
    }else{
        $testResults[] = "FAIL - Unable to create instance of tradein model object";
    }

    //Testing if tradeInId gets set properly
    $options = array('tradeInId' => 1);

    $tradeInProduct = new TradeInProduct($options);

    if($tradeInProduct->tradeInId == 1){
        $testResults[] = "PASS - Set tradeInId properly";
    }else{
        $testResults[] = "FAIL - Did not set tradeInId properly";
    }
}

function isValidTest(){
    global $testResults;
    $testResults[] = "<h3>Testing isValid()...</h3>";

    //Test 1 - returns false if tradeInProduct is invalid
    $tradeInProduct = new TradeInProduct(array(
        'tpId' => null,
        'tradeInId' => null,
        'productId' => null
    ));

    if($tradeInProduct->isValid() === false){
        $testResults[] = "FAIL - Did not validate tradeInProduct properly";
    }else{
        $testResults[] = "Pass - Validated tradeInProduct properly";
    }

    var_dump($tradeInProduct);
}
?>