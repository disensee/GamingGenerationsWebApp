<?
session_start();

$valid_passwords = array();
$valid_passwords["la crosse"] = array("x_store" => 0, "Minnwild12" => "golfisfun996812", "la crosse" => "gamesrock#");
$valid_passwords["stevens point"] = array("x_store" => 1, "Minnwild12" => "golfisfun996812", "stevens point" => "gamesrock#");
$valid_passwords["wausau"] = array("x_store" => 2, "Minnwild12" => "golfisfun996812", "wausau" => "gamesrock#");
//$valid_passwords["Winona"] = array("x_store" => 3, "Minnwild12" => "golfisfun996812", "Winona" => "gamesrock");
$valid_passwords["onalaska"] = array("x_store" => 4, "Minnwild12" => "golfisfun996812", "onalaska" => "gamesrock#");
$valid_passwords["rosedale"] = array("x_store" => 5, "Minnwild12" => "golfisfun996812", "rosedale" => "gamesrock#");
$valid_passwords["brooklyn park"] = array("x_store" => 6, "Minnwild12" => "golfisfun996812", "brooklyn park" => "gamesrock#");
$valid_passwords["maplewood"] = array("x_store" => 7, "Minnwild12" => "golfisfun996812", "maplewood" => "gamesrock#");

//Sheyboygan, Eau Claire
$valid_passwords["sheboygan"] = array("x_store" => 8, "sheboygan" => "gamesrock#");
$valid_passwords["eau claire"] = array("x_store" => 9, "eau claire" => "gamesrock#");


if($_GET['clearall'] == 1)
{
    setcookie("x_store", "");
    header("Location: index.php");
}

if(isset($_POST['username']) && isset($_POST['password']))
{
    $valid_users = @array_keys($valid_passwords[strtolower($_POST['username'])]);
    if(@in_array(strtolower($_POST['username']), $valid_users) && ($_POST['password'] == $valid_passwords[strtolower($_POST['username'])][strtolower($_POST['username'])]))
    {
        //setcookie("x_store", md5($valid_passwords[strtolower($_POST['username'])]['x_store']), time()+3600);
        $_SESSION['loggedin'] = base64_encode($valid_passwords[strtolower($_POST['username'])]['x_store'] . time());
        
        header("Location: index.php");
    }
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="LTR" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gaming Generations : Preorders</title>
<link media="only screen and (min-device-width: 768px) and (max-device-width: 1024px)" href="<html:rewrite page='/ipad.css' />" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="./stylesheet.css" />
<script type="text/javascript">
function updateStore(store)
{
    createCookie('r_store', store, 365);
    window.location.reload();
    window.location.href = 'index.php';
    return false;
}

function createCookie(name,value,days) {
	if (days) {
		var date = new Date();
		date.setTime(date.getTime()+(days*24*60*60*1000));
		var expires = "; expires="+date.toGMTString();
	}
	else var expires = "";
	document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}

function eraseCookie(name) {
	createCookie(name,"",-1);
}
</script>
</head>
<body>
<!-- header //-->

<a name="top"></a>
<div id="wall-container">
<table cellspacing="0" cellpadding="0" width="900" align="center" style="background-color: #FFFFFF;">
<tr>
<td>

<table cellspacing="2" cellpadding="0" width="900" align="center">
<tr>
<td>
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td valign="top">

<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td>
<a href="index.php"><img src="./images/gg-logo.png" alt="Gaming Generations" /></a>
</td>
<td></td>
<td valign="top">

</td>
</tr>
<tr>
<td colspan="3">

<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td>
<img src="./images/header/header_bar_l.gif" alt="" />
</td>
<td style="width: 100%; background: transparent url(./images/header/header_bar_m.gif) repeat-x;">


</td>
<td>
<img src="./images/header/header_bar_r.gif" alt="" />
</td></tr>
</table>

</td>
</tr>
</table>
<table cellspacing="0" cellpadding="0">
<tr>
<td colspan="3" height="3">
</td>
</tr>
<tr>
<td width="207" valign="top">

</td>
<td width="3" valign="top"></td>
<td width="526" valign="top"><!-- header_eof //-->

<!-- body //-->
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td width="100%" valign="top">
<table border="0" width="100%" cellspacing="0" cellpadding="0">
<tr>
<td>

<table cellspacing="0" cellpadding="0">
<tr>
<td><img alt="" src="./images/mm60.gif" width="685" height="5" /></td>
</tr>
<tr>
<td class="bgm1" valign="top">

<table cellspacing="2" cellpadding="3" width="100%">
<tr>
<td height="150" valign="top" align="center">
<form action="" method="POST">
<table cellspacing="2" cellpadding="3" width="50%">
<tr>
<td>
Username:
</td>
<td>
<input type="text" name="username" />
</td>
</tr>
<tr>
<td>
Password:
</td>
<td>
<input type="password" name="password" />
</td>
</tr>
<tr>
<td colspan="2">
<input type="submit" value="Login" />
</td>
</tr>
</table>
</form>

</td>
</tr>
<tr>
<td class="dv" height="1">
</td>
</tr>
<tr>
<td>
<?
if($is_exisiting)
{
    $sql = "SELECT * FROM notes WHERE service_id = ".$row['service_id']." AND archived = 0 ORDER BY timestamp ASC";
    
    $result = $db->query($sql);
    
    while($row = mysql_fetch_array($result))
    {
?>
<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td>
<i>Notes by <?=$row['tech_id'];?> on <?=date("F j, Y, g:i a", $row['timestamp']);?></i><br /><?=nl2br(stripslashes($row['notes']));?>
</td>
</tr>
<tr>
<td class="dv" height="1">
</td>
</tr>
</table>
<?
    }
}
?>
</td>
</tr>
</table>

</td>
</tr>
<tr>
<td><img alt="" src="./images/mm61.gif" width="685" height="5" /></td>
</tr>
</table>


</td>
</tr>
</table>
</td>
</tr>
</table>

</td>
</tr>
</table>

<table cellspacing="0" cellpadding="0">
<tr>
<td height="5" colspan="4"></td>
</tr>
<tr>
<td width="900" height="6" bgcolor="#d9d9d9" colspan="4"></td>
</tr>
<tr>
<td height="14" colspan="4"></td>
</tr>
<tr>
<td width="21"></td>
<td width="700">
<table cellspacing="0" cellpadding="0">
<tr>
<td height="15"></td>
</tr>
<tr>
<td>
</tr>
<tr>
<td height="10"></td>
</tr>
<tr><td height="30"></td>
</tr>
</table>

</td>
</tr>
<tr>
<td height="20" colspan="4"></td>
</tr>
</table>

</td>
</tr>
</table>


</td>
</tr>
</table>
</div><!-- footer_eof //-->
</body>
</html>