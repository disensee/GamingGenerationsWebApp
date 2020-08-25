<?php
include_once("../includes/models/Customer.inc.php");

$testResults = array();

constructorTest();
isValidTest();

echo(implode($testResults, "<br />"));

function constructorTest(){
    global $testResults;
    $testResults[] = "<h3>Testing constructor...</h3>";

    //Test to ensure a customer object can be made
    $cust = new Customer();

    if($cust){
        $testResults[] = "PASS - Created instance of customer model object";
    }else{
        $testResults[] = "FAIL - Unable to create instance of customer model object";
    }

    //Testing if first name gets set properly
    $options = array('customerFirstName' => "Dylan");

    $cust = new Customer($options);

    if($cust->customerFirstName == "Dylan"){
        $testResults[] = "PASS - Set customer first name properly";
    }else{
        $testResults[] = "FAIL - Did not set customer first name properly";
    }
}

function isValidTest(){
    global $testResults;
    $testResults[] = "<h3>Testing isValid()...</h3>";

    //Test 1 - returns false if customerFirstName is empty
    $cust = new Customer(array(
        'customerFirstName' => "",
        'customerLastName' => "Isensee",
        'customerIdNumber' => "",
        'customerEmail' => "dylan@dylan.com",
        'customerPhone' => "1234567890"
    ));

    if($cust->isValid() === false){
        $testResults[] = "PASS - Validated invalid empty customerFirstName properly";
    }else{
        $testResults[] = "FAIL - Did not validate empty customerFirstName properly";
    }

   //Test 2 - returns false if customerLastName is empty
   $cust = new Customer(array(
        'customerFirstName' => "Dylan",
        'customerLastName' => "",
        'customerIdNumber' => "",
        'customerEmail' => "dylan@dylan.com",
        'customerPhone' => "1234567890"
    ));

    if($cust->isValid() === false){
        $testResults[] = "PASS - Validated invalid empty customerLastName properly";
    }else{
        $testResults[] = "FAIL - Did not validate empty customerLastName properly";
    }

    //Test 3 - returns false if customerFirstName contains non alphabetic characters
    $cust = new Customer(array(
        'customerFirstName' => "Dylan1",
        'customerLastName' => "Isensee",
        'customerIdNumber' => "",
        'customerEmail' => "dylan@dylan.com",
        'customerPhone' => "1234567890"
    ));

    if($cust->isValid() === false){
        $testResults[] = "PASS - Validated invalid customerFirstName format properly";
    }else{
        $testResults[] = "FAIL - Did not validate customerFirstName format properly";
    }

    //Test 4 - returns false if customerLastName contains non alphabetic characters
    $cust = new Customer(array(
        'customerFirstName' => "Dylan",
        'customerLastName' => "Isensee2",
        'customerIdNumber' => "",
        'customerEmail' => "dylan@dylan.com",
        'customerPhone' => "1234567890"
    ));

    if($cust->isValid() === false){
        $testResults[] = "PASS - Validated invalid customerLastName format properly";
    }else{
        $testResults[] = "FAIL - Did not validate customerLastName format properly";
    }

    //Test 5 - returns false if customerEmail is empty
    $cust = new Customer(array(
        'customerFirstName' => "Dylan",
        'customerLastName' => "Isensee",
        'customerIdNumber' => "",
        'customerEmail' => "",
        'customerPhone' => "1234567890"
    ));

    if($cust->isValid() === false){
        $testResults[] = "PASS - Validated invalid empty customerEmail properly";
    }else{
        $testResults[] = "FAIL - Did not validate empty customerEmail properly";
    }

    //Test 5 - returns false if customerEmail is invalid
    $cust = new Customer(array(
        'customerFirstName' => "Dylan",
        'customerLastName' => "Isensee",
        'customerIdNumber' => "",
        'customerEmail' => "asdf",
        'customerPhone' => "1234567890"
    ));

    if($cust->isValid() === false){
        $testResults[] = "PASS - Validated invalid customerEmail format properly";
    }else{
        $testResults[] = "FAIL - Did not validate customerEmail format properly";
    }

    //Test 5 - returns false if customerPhone is invalid
    $cust = new Customer(array(
        'customerFirstName' => "Dylan",
        'customerLastName' => "Isensee",
        'customerIdNumber' => "",
        'customerEmail' => "asdf@asdf.com",
        'customerPhone' => "1234567"
    ));

    if($cust->isValid() === false){
        $testResults[] = "PASS - Validated invalid customerEmail format properly";
    }else{
        $testResults[] = "FAIL - Did not validate customerEmail format properly";
    }

     //Test 15 - returns true if customer object is valid
     $cust = new Customer(array(
        'customerFirstName' => "Dylan",
        'customerLastName' => "Isensee",
        'customerIdNumber' => "I2521234543902",
        'customerEmail' => "asdf@asdf.com",
        'customerPhone' => "1234567890"
    ));

    if($cust->isValid()){
        $testResults[] = "PASS - Validated customer object properly";
    }else{
        $testResults[] = "FAIL - Did not validate customer object properly";
    }
}
?>