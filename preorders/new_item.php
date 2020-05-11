<?
/*
if(isset($_GET['logout']))
{
    $_SERVER['PHP_AUTH_USER'] = '';
    $_SERVER['PHP_AUTH_PW'] = '';
}

$valid_passwords = array ("Minnwild12" => "golfisfun996812",);
$valid_users = array_keys($valid_passwords);

$user = $_SERVER['PHP_AUTH_USER'];
$pass = $_SERVER['PHP_AUTH_PW'];

$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

if (!$validated)
{
        header('WWW-Authenticate: Basic realm="Gaming Generation\'s Preorder Admin"');
        header('HTTP/1.0 401 Unauthorized');
        die ("Not authorized");
}
*/

include("./inc/auth.inc");
include("./inc/class.mysql.inc");

$db = new DB;

if($x_store != 8) 
{
    $db->init("localhost", "gamingg2", "P4ssw0rd#@$", "gamingg2_preorders");
}
else
{
    $db->init("localhost", "gamingg2", "P4ssw0rd#@$", "gamingg2_main");
}

if(isset($_POST['new']) && $_POST['new'] == "entry")
{
    $sql = "INSERT INTO games (game_name, game_price, game_release_date, inv) VALUES
            ('".$_POST['p_name']."', '".$_POST['p_price']."', '".$_POST['p_rdate']."', 1);";
    $db->query($sql);
    
    header("Location: index.php");
}

if(isset($_POST['update']))
{
    foreach($_POST['product'] as $id => $value) 
    {
        $sql = "UPDATE games SET game_name = '".$value['game_name']."', game_price = '".$value['game_price']."', game_release_date = '".$value['game_release_date']."' WHERE game_id = ".$id;
        
        $db->query($sql);
    } 
    header("Location: new_item.php");
}

if(isset($_GET['do']) && $_GET['do'] == 'item_del')
{
    $sql = "UPDATE games SET inv = 0 WHERE game_id = ".$_GET['id'];
    $db->query($sql);
    
    header("Location: new_item.php");
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="LTR" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gaming Generations : Preorders</title>
<link media="only screen and (min-device-width: 768px) and (max-device-width: 1024px)" href="<html:rewrite page='/ipad.css' />" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="./stylesheet.css" />
<link rel="stylesheet" type="text/css" href="./style.css" />
<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
<script src="//code.jquery.com/jquery-1.10.2.js"></script>
<script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
<script type="text/javascript">
function updateStore(store)
{
    createCookie('x_store', store, 365);
    window.location.reload();
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

$(document).ready(function(){
    $( ".datepicker" ).datepicker();
});
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
<!--
Select Store: <select name="store" onchange="updateStore(this.value);">
<?
$store = array("La Crosse","Stevens Point","Wausau","Winona", "Onalaska", "Rosedale", "Brooklyn Park", "Maplewood");
$s = '';
for($i=0;$i<count($store);$i++)
{
    $s .= '<option value="'.$i.'" '.($x_store==$i ? 'selected' : '').'>'.$store[$i].'</option>';
}
echo $s;
?>
</select>
-->
<center>
<h2><?=$store[$x_store];?></h2>
</center>
<br />
<center>
<input type="button" onclick="location.href='logout.php'" value="Logout" />
</center>
<br />
<table cellspacing="0" cellpadding="0">
<tr>
<td><img alt="" src="./images/m28.gif" width="207" height="40"></td>
</tr>
<tr>
<td class="bg1">
<form name="quick_find" action="index.php" method="get">
<table cellspacing="0" cellpadding="0" width="175" align="center" border="0">
<tr><td height="8" colspan="2">Search By Name</td></tr>
<tr><td valign="top" align="right"><input type="text" name="cname" class="go" size="20" maxlength="30" class="go" placeholder="Search by name">&nbsp;</td><td valign="top" align="left">&nbsp;<input type="image" src="./images/m30.gif" width="31" height="21"></td></tr>
<tr><td colspan="2"></td></tr>
</table>
</form>
<form name="phone_find" action="index.php" method="get">
<table cellspacing="0" cellpadding="0" width="175" align="center" border="0">
<tr><td height="8" colspan="2">Search By Phone</td></tr>
<tr><td valign="top" align="right"><input type="text" name="cphone" class="go" size="20" maxlength="30" class="go" placeholder="Search by phone">&nbsp;</td><td valign="top" align="left">&nbsp;<input type="image" src="./images/m30.gif" width="31" height="21"></td></tr>
<tr><td colspan="2"></td></tr>
<tr><td height="8" colspan="2"></td></tr>
</table>
</form>
</td>
</tr>
<tr>
<td><img alt="" src="./images/m27.gif" width="207" height="6"></td>
</tr>
</table>
<br />
<table cellspacing="0" cellpadding="0">
<tr>
<td><img alt="" src="./images/m24.gif" width="207" height="40"></td>
</tr>
<tr>
<td class="bg1">

<table cellspacing="2" cellpadding="1" width="100%">
<tr>
<td>
<?
$sql = "SELECT * FROM games WHERE inv = 1 ORDER BY game_name ASC";
$result = $db->query($sql);

while($row = mysql_fetch_array($result))
{
?>
<a href="index.php?gid=<?=$row['game_id'];?>"><?=$row['game_name'];?></a><br />
<?
}
?>
</td>
</tr>
<tr>
<td align="center">
<input type="button" value="Create Preorder" onclick="window.location.href='new.php'" />
</td>
</tr>
<tr>
<td align="center">
<input type="button" value="Add/Manage Product" onclick="window.location.href='new_item.php';"/>
</td>
</tr>
<tr>
<td align="center">
<input type="button" value="Calendar" onclick="window.location.href='calendar.php';"/>
</td>
</tr>
<tr>
<td align="center">
<input type="button" value="Mass Email" onclick="window.location.href='mass_email.php';"/>
</td>
</tr>
</table>

</td>
</tr>
<tr>
<td><img alt="" src="./images/m27.gif" width="207" height="6"></td>
</tr>
</table>

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

<form action="" method="POST">
<table cellspacing="2" cellpadding="3" width="100%">
<tr>
<td>Product Name</td>
<td>Price</td>
<td>Release Date</td>
<td>Action</td>
</tr>
<tr>
<td class="dv" height="1" colspan="6">
</td>
</tr>
<?
$sql = "SELECT * FROM games WHERE inv = 1 ORDER BY game_name ASC";
$result = $db->query($sql); 

while($row = mysql_fetch_array($result))
{
?>
<tr>
<td>
<input type="text" name="product[<?=$row['game_id'];?>][game_name]" value="<?=$row['game_name'];?>" style="width: 250px"/>
</td>
<td>
<input type="text" name="product[<?=$row['game_id'];?>][game_price]" value="<?=$row['game_price'];?>" />
</td>
<td>
<input type="text" name="product[<?=$row['game_id'];?>][game_release_date]" value="<?=$row['game_release_date'];?>" class="datepicker" />
</td>
<td>
<input type="button" value="Remove" onclick="if(confirm('Are you sure you want to remove <?=addslashes($row['game_name']);?>?')) { location.href='new_item.php?do=item_del&id=<?=$row['game_id'];?>'; }" />
</td>
</tr>
<?
}
?>
<tr>
<td align="center" colspan="6">
<input type="submit" name="update" value="Update All" />
</td>
</tr>
<tr>
<td class="dv" height="1" colspan="6">
</td>
</tr>
</table>
</form>

<table cellspacing="2" cellpadding="3" width="100%">
<tr>
<td>

<form method="post" action="new_item.php" class="iform">
<ul>
<li class="iheader">Merchnadise Info</li>
<li><label for="mName">Product Name</label><input class="itext" type="text" name="p_name" id="mName" value="" /></li>
<li><label for="mPrice">Product Price</label><input class="itext" type="text" name="p_price" id="mPrice" value="0.00" /></li>
<li><label for="mReleaseD">Product Release</label><input class="itext datepicker" type="text" name="p_rdate" id="mReleaseD" value="" placeholder="<?=date("m/d/Y", time());?>" /></li>
<li><label>&nbsp;</label><input type="hidden" name="new" value="entry" /><input type="submit" class="ibutton" name="Submit" id="Submit" value="Submit!" /></li>
<li class="iseparator">&nbsp;</li>
</ul>
</form>

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