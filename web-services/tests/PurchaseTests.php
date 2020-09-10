<?php
include_once("../includes/models/Purchase.inc.php");

$testResults = array();

constructorTest();
isValidTest();

echo(implode($testResults, "<br />"));

function constructorTest(){
    global $testResults;
    $testResults[] = "<h3>Testing constructor...</h3>";

    //Test to ensure a purchase object can be made
    $purchase = new Purchase();

    if($purchase){
        $testResults[] = "PASS - Created instance of purchase model object";
    }else{
        $testResults[] = "FAIL - Unable to create instance of purchase model object";
    }

    //Testing if employee gets set properly
    $options = array('purchaseEmployee' => "DKI");

    $purchase = new Purchase($options);

    if($purchase->purchaseEmployee == "DKI"){
        $testResults[] = "PASS - Set employee name properly";
    }else{
        $testResults[] = "FAIL - Did not set employee properly";
    }
}

function isValidTest(){
    global $testResults;
    $testResults[] = "<h3>Testing isValid()...</h3>";

    //Test 1 - returns false if purchase is invalid
    $purchase = new Purchase(array(
        'purchaseId' => 0,
        'customerId' => 0,
        'purchaseDateTime'=>null,
        'purchaseEmployee' => "PP",
        'cashReceived' => 1.47,
        'creditReceived' => 15.53,
        'storeCreditReceived' => 1.47,
        'totalPurchasePrice' => 18.47
    ));

    if($purchase->isValid() === false){
        $testResults[] = "FAIL - Did not validate purchase properly";
    }else{
        $testResults[] = "Pass - Validated purchase properly";
    }

    var_dump($purchase);
}
?>