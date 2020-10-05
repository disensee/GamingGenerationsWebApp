<?php
include_once("../includes/config.inc.php");
include_once("../includes/dataaccess/ProductDataAccess.inc.php");

$da = new ProductDataAccess(get_link());

$method = $_SERVER['REQUEST_METHOD'];

if(empty($_GET['url_path'])){
  $url_path = null;
}else{
  $url_path = rawurlencode($_GET['url_path']);
}

$consoles = $da->getAllConsoles();
$selectedConsole;

///////////////////////////////////
// Handle the Request /////////////
///////////////////////////////////
// This IF statement will check to see if the requested URL (and method) is supported by this web service
// If so, then we need to formulate the proper response, if not then we return a 404 status code

switch($method){
  case "GET":
    if(in_array(strtolower(rawurldecode($url_path)), $consoles)){
      $selectedConsole = rawurldecode($url_path);
    }else if(strpos(strtolower(rawurldecode($url_path)), "/") !== false){
      $split = explode("/", rawurldecode($url_path));
      $selectedConsole = $split[0];
      $selectedProduct = $split[1];
    }
    
   
    if(empty($url_path)){
      $products = $da->getAll();
      $json = json_encode($products);
      header("Content-Type: application/json");
      echo($json);
      die();
    }else if(preg_match('/^[0-9]{12}$/', $url_path, $matches)){
      $productUpc = $matches[0];
      $product = $da->getByUpc($productUpc);
    
      if($product == false){
        header('HTTP/1.1 404 Not Found', true, 404);
        die();
      }else{
        $json = json_encode($product);
        header("Content-Type: application/json");
        echo($json);
        die();
      }
    }else if(preg_match('/^([0-9]*\/?)$/', $url_path, $matches)){ 
      $productId = $matches[1];
      $product = $da->getById($productId);
      if($product == false){
        header('HTTP/1.1 404 Not Found', true, 404);
        die();
      }else{
        $json = json_encode($product);
        header("Content-Type: application/json");
        echo($json);
        die();
      }
    }else if(rawurldecode($url_path) == $selectedConsole){
      $products = $da->getByConsoleName($selectedConsole);
      $json = json_encode($products);
      header("Content-Type: application/json");
      echo($json);
      die();
    }else if(strcasecmp($url_path, rawurlencode($selectedConsole) . '/' . rawurlencode($selectedProduct))){ 
        $product = $da->getByProductName($selectedConsole, $selectedProduct);
        if($product == false){
          header('HTTP/1.1 404 Not Found', true, 404);
          die();
        }else{
          $json = json_encode($product);
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
      $product = new Product($assoc);
      
      if($product->isValid() == false){
        header('HTTP/1.1 400 - INVALID REQUEST - INVALID product DATA', true, 400);
        die();
      }
      
      $product = $da->insert($product);
      
      $json = json_encode($product);
      header("Content-Type: application/json");
      echo($json);
      die();
    }else{
      die("INVALID POST REQUEST TO: $url_path");
    }

  break;

  case "PUT":

    if(preg_match('/^([0-9]*\/?)$/', $url_path, $matches)){
        
      $productId = $matches[1];
      $requestBody = file_get_contents("php://input");
      $assoc = json_decode($requestBody, TRUE);
      $product = new Product($assoc);
      
      if($product->isValid() == false){
        header('HTTP/1.1 400 - INVALID REQUEST - INVALID product DATA', true, 400);
        die();
      }

      $product = $da->update($product);
      
      $json = json_encode($product);
      header("Content-Type: application/json");
      echo($json);
      die();
    }else{
      die("INVALID PUT REQUEST TO " . $url_path);
    }

  break;

  case "DELETE":
    
    if(preg_match('/^([0-9]*\/?)$/', $url_path, $matches)){
      $productId = $matches[1];
      $result = $da->delete($productId);
      if($result){
        header('HTTP/1.1 200', true, 200);
        die();
      }else{
        header('HTTP/1.1 500 - UNABLE TO DELETE product', true, 500);
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