<?php
include_once("../includes/models/Product.inc.php");

$testResults = array();

constructorTest();
isValidTest();

echo(implode($testResults, "<br />"));

function constructorTest(){
    global $testResults;
    $testResults[] = "<h3>Testing constructor...</h3>";

    //Test to ensure a product object can be made
    $prod = new Product();

    if($prod){
        $testResults[] = "PASS - Created instance of Product model object";
    }else{
        $testResults[] = "FAIL - Unable to create instance of Product model object";
    }

    //Testing if console name gets set properly
    $options = array('consoleName' => "Playstation 4");

    $prod = new Product($options);

    if($prod->consoleName == "Playstation 4"){
        $testResults[] = "PASS - Set consoleName properly";
    }else{
        $testResults[] = "FAIL - Did not set consoleName properly";
    }
}

function isValidTest(){
    global $testResults;
    $testResults[] = "<h3>Testing isValid()...</h3>";

    //Test 1 - returns false if console name is empty
    $prod = new Product(array(
        'consoleName' => "",
        'productName' => "Bloodborne",
        'loosePrice' => "9.73",
        'cibPrice' => "12.35",
        'gamestopPrice' => "14.99",
        'gamestopTradeValue' => "3",
        'upc' => "711719053156",
        'onaQuantity' => "0",
        'ecQuantity' => "1",
        'spQuantity' => "2",
        'shebQuantity' => "3"
    ));

    if($prod->isValid() === false){
        $testResults[] = "PASS - Validated invalid empty consoleName properly";
    }else{
        $testResults[] = "FAIL - Did not validate empty consoleName properly";
    }

    //Test 2 - returns false if product name is empty
    $prod = new Product(array(
        'consoleName' => "Playstation 4",
        'productName' => "",
        'loosePrice' => "9.73",
        'cibPrice' => "12.35",
        'gamestopPrice' => "14.99",
        'gamestopTradeValue' => "3",
        'upc' => "711719053156",
        'onaQuantity' => "0",
        'ecQuantity' => "1",
        'spQuantity' => "2",
        'shebQuantity' => "3"
    ));

    if($prod->isValid() === false){
        $testResults[] = "PASS - Validated invalid empty productName properly";
    }else{
        $testResults[] = "FAIL - Did not validate empty productName properly";
    }

    //Test 3 - returns false if loose price is empty
    $prod = new Product(array(
        'consoleName' => "Playstation 4",
        'productName' => "Bloodborne",
        'loosePrice' => "",
        'cibPrice' => "12.35",
        'gamestopPrice' => "14.99",
        'gamestopTradeValue' => "3",
        'upc' => "711719053156",
        'onaQuantity' => "0",
        'ecQuantity' => "1",
        'spQuantity' => "2",
        'shebQuantity' => "3"
    ));

    if($prod->isValid() === false){
        $testResults[] = "PASS - Validated invalid empty loose price properly";
    }else{
        $testResults[] = "FAIL - Did not validate empty loose price properly";
    }

    //Test 4 - returns false if loose price is not a number
    $prod = new Product(array(
        'consoleName' => "Playstation 4",
        'productName' => "Bloodborne",
        'loosePrice' => "asdf",
        'cibPrice' => "12.35",
        'gamestopPrice' => "14.99",
        'gamestopTradeValue' => "3",
        'upc' => "711719053156",
        'onaQuantity' => "0",
        'ecQuantity' => "1",
        'spQuantity' => "2",
        'shebQuantity' => "3"
    ));

    if($prod->isValid() === false){
        $testResults[] = "PASS - Validated invalid loose price properly";
    }else{
        $testResults[] = "FAIL - Did not validate loose price properly";
    }

    //Test 5 - returns false if cib price is empty
    $prod = new Product(array(
        'consoleName' => "Playstation 4",
        'productName' => "Bloodborne",
        'loosePrice' => "9.73",
        'cibPrice' => "",
        'gamestopPrice' => "14.99",
        'gamestopTradeValue' => "3",
        'upc' => "711719053156",
        'onaQuantity' => "0",
        'ecQuantity' => "1",
        'spQuantity' => "2",
        'shebQuantity' => "3"
    ));

    if($prod->isValid() === false){
        $testResults[] = "PASS - Validated invalid empty cib price properly";
    }else{
        $testResults[] = "FAIL - Did not validate empty cib price properly";
    }

    //Test 6 - returns false if cib price is not a number
    $prod = new Product(array(
        'consoleName' => "Playstation 4",
        'productName' => "Bloodborne",
        'loosePrice' => "9.73",
        'cibPrice' => "asdf",
        'gamestopPrice' => "14.99",
        'gamestopTradeValue' => "3",
        'upc' => "711719053156",
        'onaQuantity' => "0",
        'ecQuantity' => "1",
        'spQuantity' => "2",
        'shebQuantity' => "3"
    ));

    if($prod->isValid() === false){
        $testResults[] = "PASS - Validated invalid cib price properly";
    }else{
        $testResults[] = "FAIL - Did not validate invalid cib price properly";
    }

    //Test 7 - returns false if gamestop price is empty
    $prod = new Product(array(
        'consoleName' => "Playstation 4",
        'productName' => "Bloodborne",
        'loosePrice' => "9.73",
        'cibPrice' => "12.35",
        'gamestopPrice' => "",
        'gamestopTradeValue' => "3",
        'upc' => "711719053156",
        'onaQuantity' => "0",
        'ecQuantity' => "1",
        'spQuantity' => "2",
        'shebQuantity' => "3"
    ));

    if($prod->isValid() === false){
        $testResults[] = "PASS - Validated invalid empty gamestop price properly";
    }else{
        $testResults[] = "FAIL - Did not validate empty gamestop price properly";
    }

    //Test 8 - returns false if gametop price is not a number
    $prod = new Product(array(
        'consoleName' => "Playstation 4",
        'productName' => "Bloodborne",
        'loosePrice' => "9.73",
        'cibPrice' => "12.35",
        'gamestopPrice' => "asdf",
        'gamestopTradeValue' => "3",
        'upc' => "711719053156",
        'onaQuantity' => "0",
        'ecQuantity' => "1",
        'spQuantity' => "2",
        'shebQuantity' => "3"
    ));

    if($prod->isValid() === false){
        $testResults[] = "PASS - Validated invalid gamestop price properly";
    }else{
        $testResults[] = "FAIL - Did not validate invalid gamestop price properly";
    }

    //Test 9 - returns false if gamestop trade price is empty
    $prod = new Product(array(
        'consoleName' => "Playstation 4",
        'productName' => "Bloodborne",
        'loosePrice' => "9.73",
        'cibPrice' => "12.35",
        'gamestopPrice' => "14.99",
        'gamestopTradeValue' => "",
        'upc' => "711719053156",
        'onaQuantity' => "0",
        'ecQuantity' => "1",
        'spQuantity' => "2",
        'shebQuantity' => "3"
    ));

    if($prod->isValid() === false){
        $testResults[] = "PASS - Validated invalid empty gamestop trade price";
    }else{
        $testResults[] = "FAIL - Did not validate empty gamestop trade price properly";
    }

    //Test 10 - returns false if gamestop trade value is NaN
    $prod = new Product(array(
        'consoleName' => "Playstation 4",
        'productName' => "Bloodborne",
        'loosePrice' => "9.73",
        'cibPrice' => "12.35",
        'gamestopPrice' => "14.99",
        'gamestopTradeValue' => "asdf",
        'upc' => "711719053156",
        'onaQuantity' => "0",
        'ecQuantity' => "1",
        'spQuantity' => "2",
        'shebQuantity' => "3"
    ));

    if($prod->isValid() === false){
        $testResults[] = "PASS - Validated invalid gamestop trade price";
    }else{
        $testResults[] = "FAIL - Did not validate invalid gamestop trade price properly";
    }


    //Test 11 - returns false if upc is empty
    $prod = new Product(array(
        'consoleName' => "Playstation 4",
        'productName' => "Bloodborne",
        'loosePrice' => "9.73",
        'cibPrice' => "12.35",
        'gamestopPrice' => "14.99",
        'gamestopTradeValue' => "3",
        'upc' => "",
        'onaQuantity' => "0",
        'ecQuantity' => "1",
        'spQuantity' => "2",
        'shebQuantity' => "3"
    ));

    if($prod->isValid() === false){
        $testResults[] = "PASS - Validated invalid empty upc";
    }else{
        $testResults[] = "FAIL - Did not validate invalid empty upc";
    }

    //Test 12 - returns false if upc is not 12 digits
    $prod = new Product(array(
        'consoleName' => "Playstation 4",
        'productName' => "Bloodborne",
        'loosePrice' => "9.73",
        'cibPrice' => "12.35",
        'gamestopPrice' => "14.99",
        'gamestopTradeValue' => "3",
        'upc' => "01",
        'onaQuantity' => "0",
        'ecQuantity' => "1",
        'spQuantity' => "2",
        'shebQuantity' => "3"
    ));

    if($prod->isValid() === false){
        $testResults[] = "PASS - Validated invalid upc";
    }else{
        $testResults[] = "FAIL - Did not validate invalid upc";
    }

    //Test 13 - returns false if qty is empty
    $prod = new Product(array(
        'consoleName' => "Playstation 4",
        'productName' => "Bloodborne",
        'loosePrice' => "9.73",
        'cibPrice' => "12.35",
        'gamestopPrice' => "14.99",
        'gamestopTradeValue' => "3",
        'upc' => "711719053156",
        'onaQuantity' => "",
        'ecQuantity' => 1,
        'spQuantity' => 2,
        'shebQuantity' => 3
    ));

    if($prod->isValid() === false){
        $testResults[] = "PASS - Validated invalid empty quantity";
    }else{
        $testResults[] = "FAIL - Did not validate invalid empty quantity";
    }

    //Test 14 - returns false if qty is NaN
    $prod = new Product(array(
        'consoleName' => "Playstation 4",
        'productName' => "Bloodborne",
        'loosePrice' => "9.73",
        'cibPrice' => "12.35",
        'gamestopPrice' => "14.99",
        'gamestopTradeValue' => "3",
        'upc' => "711719053156",
        'onaQuantity' => "asdf",
        'ecQuantity' => 1,
        'spQuantity' => 2,
        'shebQuantity' => 3
    ));

    if($prod->isValid() === false){
        $testResults[] = "PASS - Validated invalid  quantity";
    }else{
        $testResults[] = "FAIL - Did not validate invalid quantity";
    }

    //Test 15 - returns true if product object is valid
    $prod = new Product(array(
        'consoleName' => "Playstation 4",
        'productName' => "Bloodborne",
        'loosePrice' => "9.73",
        'cibPrice' => "12.35",
        'gamestopPrice' => "14.99",
        'gamestopTradeValue' => "3",
        'upc' => "711719053156",
        'onaQuantity' => 0,
        'ecQuantity' => 1,
        'spQuantity' => 2,
        'shebQuantity' => 3
    ));

    if($prod->isValid()){
        $testResults[] = "PASS - Validated product object properly";
    }else{
        $testResults[] = "FAIL - Did not validate product object properly";
    }

}
?>