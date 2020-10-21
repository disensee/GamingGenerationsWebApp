<?php
include_once("../includes/config.inc.php");
include_once("../includes/dataaccess/PurchaseDataAccess.inc.php");

$da = new PurchaseDataAccess(get_link());

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
      $purchases = $da->getAll();
      $json = json_encode($purchases);
      header("Content-Type: application/json");
      echo($json);
      die();
    }else if(preg_match('/^([0-9]*\/?)$/', $url_path, $matches)){
        $purchaseId = $matches[0];
        $purchase = $da->getById($purchaseId);
        if($purchase == false){
            header('HTTP/1.1 404 Not Found', true, 404);
            die();
        }else{
            $json = json_encode($purchase);
            header("Content-Type: application/json");
            echo($json);
            die();
        }
    }else if(preg_match('/^customer([0-9]*\/?)$/', $url_path, $matches)){ 
      $customerId = $matches[1];
      $purchase = $da->getPurchaseByCustomerId($customerId);
      if($purchase == false){
        header('HTTP/1.1 404 Not Found', true, 404);
        die();
      }else{
        $json = json_encode($purchase);
        header("Content-Type: application/json");
        echo($json);
        die();
      }
    }else if(preg_match('/^customerascending([0-9]*\/?)$/', $url_path, $matches)){ 
      $customerId = $matches[1];
      $purchase = $da->getPurchaseByCustomerIdAscending($customerId);
      if($purchase == false){
        header('HTTP/1.1 404 Not Found', true, 404);
        die();
      }else{
        $json = json_encode($purchase);
        header("Content-Type: application/json");
        echo($json);
        die();
      }
    }else if(preg_match('/^customerdescending([0-9]*\/?)$/', $url_path, $matches)){ 
      $customerId = $matches[1];
      $purchase = $da->getPurchaseByCustomerIdDescending($customerId);
      if($purchase == false){
        header('HTTP/1.1 404 Not Found', true, 404);
        die();
      }else{
        $json = json_encode($purchase);
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
      $purchase = new Purchase($assoc);
      
      if($purchase->isValid() == false){
        header('HTTP/1.1 400 - INVALID REQUEST - INVALID purchase DATA', true, 400);
        die();
      }
      
      $purchase = $da->insert($purchase);
      
      $json = json_encode($purchase);
      header("Content-Type: application/json");
      echo($json);
      die();
    }else{
      die("INVALID POST REQUEST TO: $url_path");
    }

    break;

  case "PUT":

    if(preg_match('/^([0-9]*\/?)$/', $url_path, $matches)){
        
      $purchaseId = $matches[1];
      $requestBody = file_get_contents("php://input");
      $assoc = json_decode($requestBody, TRUE);
      $purchase = new Purchase($assoc);

      $purchase->purchaseDateTime = $da->convertDateForMySql($purchase->purchaseDateTime);
      
      if($purchase->isValid() == false){
        header('HTTP/1.1 400 - INVALID REQUEST - INVALID purchase DATA', true, 400);
        die();
      }

      $purchase = $da->update($purchase);
      
      $json = json_encode($purchase);
      header("Content-Type: application/json");
      echo($json);
      die();
    }else{
      die("INVALID PUT REQUEST TO " . $url_path);
    }

    break;

  case "DELETE":
    
    if(preg_match('/^([0-9]*\/?)$/', $url_path, $matches)){
      $purchaseId = $matches[1];
      $result = $da->delete($purchaseId);
      if($result){
        header('HTTP/1.1 200', true, 200);
        die();
      }else{
        header('HTTP/1.1 500 - UNABLE TO DELETE purchase', true, 500);
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