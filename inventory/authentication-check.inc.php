<?php
session_start();
if(empty($_SESSION['authenticated']) || $_SESSION['authenticated'] !== "yes"){
	header("Location: login.php");
	exit();
}
