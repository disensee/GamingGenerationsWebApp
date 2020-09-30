<?php
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
	$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: ' . $redirect);
	exit();
}
require("authentication-check.inc.php");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="styles/reset.css">
    <link rel="stylesheet" type="text/css" href="styles/main.css">

    <script src="js/main.js"></script>
    <script src="js/ajax.js"></script>

    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Generations | Inventory</title>
</head>
<body>
    <div id="header">
        <img src=images/gg-logo.jpg><p>Gaming Generations Inventory</p>
    </div>
    <div id="content-pane">
        <div id="left-column" class="column left"></div>
        <div id ="mid-column" class ="column mid"></div>
        <div id="right-column" class="column right"></div>
    </div>
    <div id="footer">
        Gaming Generations &copy;2020
    </div>

    <script src="js/customer-module.js"></script>
    <script src="js/tradein-module.js"></script>
    <script src="js/product-module.js"></script>
</body>
</html>