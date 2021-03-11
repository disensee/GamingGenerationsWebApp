<?php
include_once('DataAccess.inc.php');
include_once(__DIR__ . "/../models/Product.inc.php");

class ProductDataAccess extends DataAccess{


	// Constructor

	function __construct($link){

        parent::__construct($link);
        
	}

	/**
	* 'Cleans' the data in a product object to prevent SQL injection attacks
	* @param {product}	A Product model object
	* @return {product} A new instance of Product object with clean data in it
	*/
	function cleanDataGoingIntoDB($product){

		if($product instanceOf Product){
			$cleanProduct = new Product();
			$cleanProduct->productId = mysqli_real_escape_string($this->link, $product->productId);
			$cleanProduct->consoleName = mysqli_real_escape_string($this->link, $product->consoleName);
            $cleanProduct->productName = mysqli_real_escape_string($this->link, $product->productName);
            $cleanProduct->loosePrice = mysqli_real_escape_string($this->link, $product->loosePrice);
            $cleanProduct->cibPrice = mysqli_real_escape_string($this->link, $product->cibPrice);
            $cleanProduct->gamestopPrice = mysqli_real_escape_string($this->link, $product->gamestopPrice);
            //$cleanProduct->gamestopTradeValue = mysqli_real_escape_string($this->link, $product->gamestopTradeValue);
			$cleanProduct->upc = mysqli_real_escape_string($this->link, $product->upc);
			$cleanProduct->onaQuantity = mysqli_real_escape_string($this->link, $product->onaQuantity);
			$cleanProduct->ecQuantity = mysqli_real_escape_string($this->link, $product->ecQuantity);
			$cleanProduct->spQuantity = mysqli_real_escape_string($this->link, $product->spQuantity);
			$cleanProduct->shebQuantity = mysqli_real_escape_string($this->link, $product->shebQuantity);

			return $cleanProduct;
		}else{
			$cleanParam = mysqli_real_escape_string($this->link, $product);
			return $cleanParam;
		}
    }

    /**
	* 'Cleans' the data in a row from the database (a row should be an associative array)
	* @param {array}	An associative array with key value pairs for each column in the table
	* @return {array} 	A new associative array with clean data in it
	*/
	function cleanDataComingFromDB($row){
		$cleanRow = [];
		$cleanRow['productId'] = htmlentities($row['productId']);
        $cleanRow['consoleName'] = htmlentities($row['consoleName']);
        $cleanRow['productName'] = htmlentities($row['productName']);
        $cleanRow['loosePrice'] = htmlentities($row['loosePrice']);
        $cleanRow['cibPrice'] = htmlentities($row['cibPrice']);
        $cleanRow['gamestopPrice'] = htmlentities($row['gamestopPrice']);
        //$cleanRow['gamestopTradeValue'] = htmlentities($row['gamestopTradeValue']);
		$cleanRow['upc'] = htmlentities($row['upc']);
		$cleanRow['onaQuantity'] = htmlentities($row['onaQuantity']);
		$cleanRow['ecQuantity'] = htmlentities($row['ecQuantity']);
		$cleanRow['spQuantity'] = htmlentities($row['spQuantity']);
		$cleanRow['shebQuantity'] = htmlentities($row['shebQuantity']);

		return $cleanRow;
    }
    
    /**
	* Gets all products from a table in the database
	* @param {assoc array} 	This optional param would allow you to filter the result set
	* 						For example, you could use it to somehow add a WHERE claus to the query
	* 
	* @return {array}		Returns an array of product objects
	*/
	function getAll($args = []){
		$qStr = "SELECT productId, consoleName, productName, loosePrice, cibPrice, gamestopPrice, upc, onaQuantity, ecQuantity, spQuantity, shebQuantity FROM products";
		//die($qStr);

		//Many people run queries like this. Shows error messages to users. 
		//$result = mysqli_query($this->link, $qStr) or die(mysqli_error($this->link));
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		$allproducts = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$product = new Product($cleanRow);

				$allproducts[] = $product;
			}
		}
		return $allproducts;
	}
	
	function getAllConsoles(){
		$qStr = "SELECT DISTINCT consoleName FROM products";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		$allConsoles = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_array($result)){
				$console = $row['consoleName'];
				$allConsoles[] = strtolower($console);
			}
		}

		return $allConsoles;
	}

    
    /**
	* Gets a product from the database by its id
	* @param {number} 	 The id of the product to get from a row in the database
	* @return {product} Returns an instance of a product model object
	*/
	function getById($productId){
		$cleanProductId = $this->cleanDataGoingIntoDB($productId);
		$qStr = "SELECT productId, consoleName, productName, loosePrice, cibPrice, gamestopPrice, upc, onaQuantity, ecQuantity, spQuantity, shebQuantity FROM products WHERE productId = '$cleanProductId'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		if(mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$cleanRow = $this->cleanDataComingFromDB($row);
			$product = new Product($cleanRow);
			return $product;
		}

		return false;
    }

    /**
	* Gets a product from the database by its product name
	* @param {string} 	 The name of the product to get from a row in the database
	* @param {string} 	 The name of the console the product is for
	* @return {product} Returns an instance of a product model object
	*/
	function getByProductName($consoleName, $productName){
		$cleanProductName = $this->cleanDataGoingIntoDB($productName);
		$cleanConsoleName = $this->cleanDataGoingIntoDB($consoleName);

		if($cleanConsoleName === 'notselected'){
			$qStr = "SELECT productId, consoleName, productName, loosePrice, cibPrice, gamestopPrice, upc, onaQuantity, ecQuantity, spQuantity, shebQuantity FROM products WHERE productName LIKE '%$cleanProductName%'";
		}else{
			$qStr = "SELECT productId, consoleName, productName, loosePrice, cibPrice, gamestopPrice, upc, onaQuantity, ecQuantity, spQuantity, shebQuantity FROM products WHERE consoleName = '$cleanConsoleName' AND productName LIKE '%$cleanProductName%'";
		}

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		$allproducts = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$product = new Product($cleanRow);

				$allproducts[] = $product;
			}
		}
		return $allproducts;
    }

    /**
	* Gets a product from the database by its console name
	* @param {string} 	 The console name of the product to get from a row in the database
	* @return {product} Returns an instance of a product model object
	*/
	function getByConsoleName($consoleName){
		$cleanConsoleName = $this->cleanDataGoingIntoDB($consoleName);
		$qStr = "SELECT productId, consoleName, productName, loosePrice, cibPrice, gamestopPrice, upc, onaQuantity, ecQuantity, spQuantity, shebQuantity FROM products WHERE consoleName = '$cleanConsoleName' ORDER BY productName";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		$allproducts = [];
		if(mysqli_num_rows($result)){
			while($row = mysqli_fetch_assoc($result)){
				$cleanRow = $this->cleanDataComingFromDB($row);
				$product = new Product($cleanRow);

				$allproducts[] = $product;
			}
		}
		return $allproducts;
	}
	
    /**
	* Gets a product from the database by its upc
	* @param {string} 	 The upc of the product to get from a row in the database
	* @return {product} Returns an instance of a product model object
	*/
	function getByUpc($upc){
		$cleanUpc = $this->cleanDataGoingIntoDB($upc);
		$qStr = "SELECT productId, consoleName, productName, loosePrice, cibPrice, gamestopPrice, upc, onaQuantity, ecQuantity, spQuantity, shebQuantity FROM products WHERE upc = '$cleanUpc'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));
		if(mysqli_num_rows($result) == 1){
			$row = mysqli_fetch_assoc($result);
			$cleanRow = $this->cleanDataComingFromDB($row);
			$product = new Product($cleanRow);
			return $product;
		}

		return false;
    }
    
    /**
	* Inserts a product into a table in the database
	* @param {product}	The product object to be inserted
	* @return {product}	Returns the same product object, but with the productId property set (the productId is assigned by the database)
	*/
	function insert($product){
		$cleanProduct = $this->cleanDataGoingIntoDB($product);
		$qStr = "INSERT INTO products (consoleName, productName, loosePrice, cibPrice, gamestopPrice, upc, onaQuantity, ecQuantity, spQuantity, shebQuantity) VALUES (
			'{$cleanProduct->consoleName}',
			'{$cleanProduct->productName}',
            '{$cleanProduct->loosePrice}',
            '{$cleanProduct->cibPrice}',
            '{$cleanProduct->gamestopPrice}',
			'{$cleanProduct->upc}',
            '{$cleanProduct->onaQuantity}',
			'{$cleanProduct->ecQuantity}',
			'{$cleanProduct->spQuantity}',
			'{$cleanProduct->shebQuantity}'
		)";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if($result){
			$cleanProduct->productId = mysqli_insert_id($this->link);
		}else{
			$this->handleError("Unable to insert product");
		}

		return $cleanProduct;
    }
    
    /**
	* Updates a product in the database
	* @param {product}	 The product model object to be updated
	* @return {product} Returns the same product model object that was passed in as the param
	*/
	function update($product){
		$cleanProduct = $this->cleanDataGoingIntoDB($product);
		$qStr = "UPDATE products SET 
				consoleName = '{$cleanProduct->consoleName}',
				productName = '{$cleanProduct->productName}',
                loosePrice = '{$cleanProduct->loosePrice}',
                cibPrice = '{$cleanProduct->cibPrice}',
                gamestopPrice = '{$cleanProduct->gamestopPrice}',
				upc = '{$cleanProduct->upc}',
                onaQuantity = '{$cleanProduct->onaQuantity}',
				ecQuantity = '{$cleanProduct->ecQuantity}',
				spQuantity = '{$cleanProduct->spQuantity}',
				shebQuantity = '{$cleanProduct->shebQuantity}'
				WHERE productId = '{$cleanProduct->productId}'";

		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		return $cleanProduct;
    }
    
    /**
	* Deletes a product from a table in the database
	* @param {number} 	The id of the product to delete
	* @return {boolean}	Returns true if the row was sucessfully deleted, false otherwise
	*/
	function delete($productId){
		$cleanProductId = $this->cleanDataGoingIntoDB($productId);
		$qStr = "DELETE FROM products WHERE productId = $cleanProductId";
		$result = mysqli_query($this->link, $qStr) or $this->handleError(mysqli_error($this->link));

		if(mysqli_affected_rows($this->link) == 1){
			return true;
        }
        
        return false;
        
	}
}