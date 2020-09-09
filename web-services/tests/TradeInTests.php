<?php
include_once("../includes/models/TradeIn.inc.php");

$testResults = array();

constructorTest();
isValidTest();

echo(implode($testResults, "<br />"));

function constructorTest(){
    global $testResults;
    $testResults[] = "<h3>Testing constructor...</h3>";

    //Test to ensure a tradeIn object can be made
    $tradeIn = new TradeIn();

    if($tradeIn){
        $testResults[] = "PASS - Created instance of tradein model object";
    }else{
        $testResults[] = "FAIL - Unable to create instance of tradein model object";
    }

    //Testing if employee gets set properly
    $options = array('tradeInEmployee' => "DKI");

    $tradeIn = new TradeIn($options);

    if($tradeIn->tradeInEmployee == "DKI"){
        $testResults[] = "PASS - Set employee name properly";
    }else{
        $testResults[] = "FAIL - Did not set employee properly";
    }
}

function isValidTest(){
    global $testResults;
    $testResults[] = "<h3>Testing isValid()...</h3>";

    //Test 1 - returns false if tradeIn is invalid
    $tradeIn = new TradeIn(array(
        'tradeInId' => 0,
        'customerId' => 0,
        'tradeInEmployee' => "DKI",
        'cashPaid' => 0.00,
        'creditPaid' => 1.00,
        'checkPaid' => 0.00,
        'checkNumber' => ""
    ));

    if($tradeIn->isValid() === false){
        $testResults[] = "FAIL - Did not validate tradeIn properly";
    }else{
        $testResults[] = "Pass - Validated tradeIn properly";
    }

    var_dump($tradeIn);
}
?>