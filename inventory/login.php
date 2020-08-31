<?php
if(empty($_SERVER['HTTPS']) || $_SERVER['HTTPS'] == "off"){
	$redirect = 'https://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
	header('HTTP/1.1 301 Moved Permanently');
	header('Location: ' . $redirect);
	exit();
}
session_start();
if($_SERVER['REQUEST_METHOD'] == "POST"){
    $userNameEntered = $_POST['username'] ?? NULL;
    $passwordEntered = $_POST['password'] ?? NULL;

    $userName = "admin";
    $password = "Gamesrock12";

    if($userNameEntered == $userName && $passwordEntered == $password){
        
        session_regenerate_id(true);
        $_SESSION['gginv_authenticated'] = "yes";
		header("Location: index.php");
		exit();
	}
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <link href="https://fonts.googleapis.com/css2?family=Hind+Siliguri&display=swap" rel="stylesheet">

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gaming Generations | Inventory Login</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    
</head>
<body>
	<div style="margin-top: 5%" class="h-100 d-flex justify-content-center align-items-center">
		<div class="w-25 jumbotron">
            <img style="display: block; margin-left: auto; margin-right: auto; width: 30%; margin-bottom: 25px;" src=images/gg-logo.jpg>
            <h4 style="text-align: center; display: block; margin-left: auto; margin-right: auto; width: 70%; margin-bottom: 25px;">Gaming Generations Inventory</h4>
			<form method="POST">
                <label for="username"><h5>Username:</h5></label>
                <input id="txtUsername" name="username" class="form-control">
                <br>
                <label for="password"><h5>Password:</h5></label>
                <input id="txtPassword" type="password" class="form-control" name="password">
                <br>
                <input type="submit" class="btn btn-outline-primary" value="LOG IN">
			</form>
		</div>
    </div>
</body>
</html>