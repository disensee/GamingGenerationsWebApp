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
    <link rel="stylesheet" type="text/css" href="styles/reset.css">
    <link rel="stylesheet" type="text/css" href="styles/main.css">
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri&display=swap" rel="stylesheet">
    
    <script src="js/ajax.js"></script>
    <script src="js/customer-module.js"></script>
    <script src="js/main.js"></script>
    
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Generations | Inventory</title>
</head>
<body>
    <div id="header"></div>
    <div id="content-pane">
        <div id="left-column" class="column left"></div>
        <div id ="mid-column" class ="column mid"></div>
        <div id="right-column" class="column right"></div>
    </div>
    <div id="footer"></div>
</body>
</html>