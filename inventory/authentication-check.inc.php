<?php
session_start();
if(empty($_SESSION['gginv_authenticated']) || $_SESSION['gginv_authenticated'] !== "yes"){
	header("Location: login.php");
	exit();
}
