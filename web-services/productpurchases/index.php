<?php
include_once("../includes/config.inc.php");
include_once("../includes/dataaccess/ProductPurchaseDataAccess.inc.php");

$da = new ProductPurchaseDataAccess(get_link());

$method = $_SERVER['REQUEST_METHOD'];
$url_path = $_GET['url_path'] ?? ""; 

/*
header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
header("Cache-Control: no-store, no-cache, must-revalidate"); // HTTP/1.1
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache"); // HTTP/1.0
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT"); // Date in the past
*/

///////////////////////////////////
// Handle the Request /////////////
///////////////////////////////////
// This IF statement will check to see if the requested URL (and method) is supported by this web service
// If so, then we need to formulate the proper response, if not then we return a 404 status code

switch($method){
  case "GET":
    
    if(empty($url_path)){
      $prodPurchases = $da->getAll();
      $json = json_encode($prodPurchases);
      header("Content-Type: application/json");
      echo($json);
      die();
    }else if(preg_match('/^([0-9]*\/?)$/', $url_path, $matches)){
        $prodPurchaseId = $matches[0];
        $prodPurchase = $da->getById($prodPurchaseId);
        if($prodPurchase == false){
            header('HTTP/1.1 404 Not Found', true, 404);
            die();
        }else{
            $json = json_encode($prodPurchase);
            header("Content-Type: application/json");
            echo($json);
            die();
        }
    }else if(preg_match('/^purchase([0-9]*\/?)$/', $url_path, $matches)){ 
      $purchaseId = $matches[1];
      $prodPurchase = $da->getProductPurchaseByPurchaseId($purchaseId);
      if($prodPurchase == false){
        header('HTTP/1.1 404 Not Found', true, 404);
        die();
      }else{
        $json = json_encode($prodPurchase);
        header("Content-Type: application/json");
        echo($json);
        die();
      }
    }else if(preg_match('/^product([0-9]*\/?)$/', $url_path, $matches)){
      $purchasedId = $matches[1];
      $purchasedProd = $da->getProductInfoFromPurchaseId($purchasedId);
      if($purchasedProd == false){
        header('HTTP/1.1 404 Not Found', true, 404);
        die();
      }else{
        $json = json_encode($purchasedProd);
        header("Content-Type: application/json");
        echo($json);
        die();
      }
    }else{
      header('HTTP/1.1 400 - Invalid Request', true, 400);
      die();
    }
    
    break;

  case "POST":

    if(empty($url_path)){
      $requestBody = file_get_contents("php://input");
      $assoc = json_decode($requestBody, TRUE);
      $prodPurchase = new ProductPurchase($assoc);
      
      if($prodPurchase->isValid() == false){
        header('HTTP/1.1 400 - INVALID REQUEST - INVALID product purchase DATA', true, 400);
        die();
      }
      
      $prodPurchase = $da->insert($prodPurchase);
      
      $json = json_encode($prodPurchase);
      header("Content-Type: application/json");
      echo($json);
      die();
    }else{
      die("INVALID POST REQUEST TO: $url_path");
    }

    break;

  case "PUT":

    if(preg_match('/^([0-9]*\/?)$/', $url_path, $matches)){
        
      $prodPurchaseId = $matches[1];
      $requestBody = file_get_contents("php://input");
      $assoc = json_decode($requestBody, TRUE);
      $prodPurchase = new ProductPurchase($assoc);
      
      if($prodPurchase->isValid() == false){
        header('HTTP/1.1 400 - INVALID REQUEST - INVALID product purchase DATA', true, 400);
        die();
      }

      $prodPurchase = $da->update($prodPurchase);
      
      $json = json_encode($prodPurchase);
      header("Content-Type: application/json");
      echo($json);
      die();
    }else{
      die("INVALID PUT REQUEST TO " . $url_path);
    }

    break;

  case "DELETE":
    
    if(preg_match('/^([0-9]*\/?)$/', $url_path, $matches)){
      $prodPurchaseId = $matches[1];
      $result = $da->delete($prodPurchaseId);
      if($result){
        header('HTTP/1.1 200', true, 200);
        die();
      }else{
        header('HTTP/1.1 500 - UNABLE TO DELETE product purchase', true, 500);
        die();
      }
    }else{
      die("INVALID DELETE REQUEST TO " . $url_path);
    }

    break;

case "OPTIONS":

    // To allow the 'preflight' checks to work,
    header('HTTP/1.1 200', true, 200);

    break;
default:

    header('HTTP/1.1 404 Not Found', true, 404);
    die("We're sorry, we can't find this page: {$_SERVER['REQUEST_URI']}");

}

?>