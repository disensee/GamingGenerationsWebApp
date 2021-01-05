<?php
session_start();
$adminKey = 'gginv_admin_authenticated';
$onaKey = 'gginv_ona_authenticated';
$ecKey = 'gginv_ec_authenticated';
$spKey = 'gginv_sp_authenticated';
$shebKey = 'gginv_sheb_authenticated';
$managerKey = 'gginv_manager_authenticated';
if(!empty($_SESSION)){
	foreach($_SESSION as $key=>$value){
		if($key == $adminKey || $key == $onaKey || $key == $ecKey || $key == $spKey || $key == $shebKey || $key == $managerKey){
			if($_SESSION[$key] !== 'yes'){
				header("Location: login.php");
				exit();
			}
		} //else{
		// 	header("Location: login.php");
		// 	exit();
		// }
	}
}else{
	header("Location: login.php");
}

/*if(empty($_SESSION['gginv_authenticated']) || $_SESSION['gginv_authenticated'] !== "yes"){
	header("Location: login.php");
	exit();
}*/
