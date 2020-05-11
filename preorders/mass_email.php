<?
include("./inc/auth.inc");
include("./inc/class.mysql.inc");
include('./inc/func.email.inc');

//$db = new DB;
//$x_store = isset($_COOKIE['x_store']) ? $_COOKIE['x_store'] : 0;

$db = new DB;
if($x_store != 8) 
{
    $db->init("localhost", "gamingg2", "P4ssw0rd#@$", "gamingg2_preorders");
}
else
{
    $db->init("localhost", "gamingg2", "P4ssw0rd#@$", "gamingg2_main");
}

if(isset($_POST['do']) && $_POST['do'] == "massemail")
{
    massEmail($_POST['s_game'], $_POST['subject'], $_POST['mass_msg']);
    header("Location: index.php");
}
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="LTR" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gaming Generations : Repair Center</title>
<link media="only screen and (min-device-width: 768px) and (max-device-width: 1024px)" href="<html:rewrite page='/ipad.css' />" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="./stylesheet.css" />
<link rel="stylesheet" type="text/css" href="./style.css" />
<script type="text/javascript" src="./js/jquery.js"></script>
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

function setFocus()
{
     document.getElementById("Deposit").focus();
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

<table cellspacing="2" cellpadding="3" width="100%">
<tr>
<td>

<form method="post" action="" class="iform">
<ul>
<li><label for="Subject">Subject</label><input class="itext" type="text" name="subject" id="Subject" value="" /></li>
<li><label for="Game">Game</label>
<select class="iselect" name="s_game" id="Game">
<?
$sql = "SELECT * FROM games WHERE inv = 1 ORDER BY game_name ASC";
$result = $db->query($sql);

while($row = mysql_fetch_array($result))
{
?>
<option value="<?=$row['game_id'];?>"><?=$row['game_name'];?> ($<?=$row['game_price'];?>)</option>
<?
}
?>
</select>
</li>
</ul>
<textarea name="mass_msg" rows="1" cols="1" style="width: 98%; height: 300px"></textarea>
<ul>
<li><label>&nbsp;</label><input type="hidden" name="do" value="massemail" /><input type="submit" class="ibutton" name="Submit" id="Submit" value="Submit!" /></li>
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