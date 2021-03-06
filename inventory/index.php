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
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.15.1/css/all.css" integrity="sha384-vp86vTRFVJgpjF9jiIGPEEqYqlDwgyBgEF109VFjmqGmIY/Y4HV4d3Gp2irVfcrp" crossorigin="anonymous">
    
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
    <input type="hidden" id="store_user" value="<?=$_COOKIE['ggUserName']?>"/>
    <header id="header">
        <img id="gg-logo" src=images/gg-logo.jpg>
        <a class="logout" href="login.php">Log Out</a>
        <p>Gaming Generations Inventory</p>
    </header>
    <div id="content-pane">
        <div id="left-column" class="column left"></div>
        <div id ="mid-column" class ="column mid"></div>
        <div id="right-column" class="column right"></div>
    </div>
    <footer id="footer"></footer>

    <script>
        var currentYear = new Date().getFullYear();
        document.querySelector('#footer').innerHTML = `Dylan Isensee &copy; ${currentYear}`;
    </script>
    <script src="js/customer-module.js"></script>
    <script src="js/tradein-module.js"></script>
    <script src="js/purchase-module.js"></script>
    <script src="js/product-module.js"></script>
</body>
</html>