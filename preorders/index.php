<?
include("./inc/auth.inc");
include("./inc/class.mysql.inc");

function formatPhone($phone) 
{
        $phone = preg_replace('/(\W*)/', '', $phone);
        if (empty($phone)) return "";
        if (strlen($phone) == 7)
                sscanf($phone, "%3s%4s", $prefix, $exchange);
        else if (strlen($phone) == 10)
                sscanf($phone, "%3s%3s%4s", $area, $prefix, $exchange);
        else if (strlen($phone) > 10)
                sscanf($phone, "%3s%3s%4s%s", $area, $prefix, $exchange, $extension);
        else
                return "unknown phone format: $phone";
        $out = "";
        $out .= isset($area) ? '(' . $area . ') ' : "";
        $out .= $prefix . '-' . $exchange;
        $out .= isset($extension) ? ' x' . $extension : "";
        return $out;
}
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

$gid = $_GET['gid'];

$do = $_GET['do'];

if($do == 'paid_full')
{
    $sql = "SELECT g.game_price, o.order_id FROM orders AS o, games AS g WHERE g.game_id = o.game_id AND o.order_id = ".$_GET['id'];
    
    $result = $db->query($sql);
    
    $row2 = mysql_fetch_array($result);

    $sql = "UPDATE orders SET order_amount = '".$row2['game_price']."' WHERE order_id = ".$_GET['id'];
    $db->query($sql);
    
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit;
}
elseif($do == 'append')
{
    $sql = "UPDATE orders SET order_amount = order_amount +".number_format($_GET['add'], 2)." WHERE order_id = ".$_GET['id'];
    $db->query($sql);
    
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit;
}
elseif($do == 'subtract')
{
    $sql = "UPDATE orders SET order_amount = order_amount -".number_format($_GET['sub'], 2)." WHERE order_id = ".$_GET['id'];
    $db->query($sql);
    
    //echo $_SERVER['HTTP_REFERER'];
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit;
}
elseif($do == 'complete')
{
    $sql = "UPDATE orders SET order_complete = '1' WHERE order_id = ".$_GET['id'];
    $db->query($sql);
    
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit;
}
elseif($do == 'cancel')
{
    $sql = "UPDATE orders SET order_complete = '10', order_amount = '0' WHERE order_id = ".$_GET['id'];
    $db->query($sql);
    
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit;
}
elseif($do == 'reopen')
{
    $sql = "UPDATE orders SET order_complete = '0', order_amount = '0' WHERE order_id = ".$_GET['id'];
    $db->query($sql);
    
    header("Location: ".$_SERVER['HTTP_REFERER']);
    exit;
}

if(!isset($_GET['cphone']) && !isset($_GET['cname']) && !isset($gid))
{
    //header("Location: new.php");
    //exit;
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

function popUp(URL) {
    day = new Date();
    id = day.getTime();
    eval("page" + id + " = window.open(URL, '" + id + "', 'toolbar=0,scrollbars=1,location=0,statusbar=0,menubar=0,resizable=1,width=910,height=800,left = 450,top = 225');");
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
$store = array("La Crosse","Stevens Point","Wausau","Winona", "Onalaska", "Rosedale", "Brooklyn Park", "Maplewood", "Sheboygan", "Eau Claire");
$s = '';
for($i=0;$i<count($store);$i++)
{
    $s .= '<option value="'.$i.'" '.($x_store==$i ? 'selected' : '').'>'.$store[$i].'</option>';
}
echo $s;
?>
</select>
-->
<?=$store[$x_store];?>
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
Console: 
<select class="iselect" name="s_console" id="Console" onchange="location.href='index.php?gid=<?=$gid;?>&console='+this.value">
<?
$consoles = array('All', 'PS3', 'Wii', 'Xbox 360', 'PSP', "Switch", "DS", "PS4", "Xbox One");
sort($consoles);
for($i=0;$i<count($consoles);$i++)
{
    echo '<option value="'.$consoles[$i].'" '.($consoles[$i] == $_GET['console'] ? 'selected="selected"' : '').'>'.$consoles[$i].'</option>';
}
?>
</select>
</td>
</tr>
<tr>
<td height="150" valign="top">
<?
unset($sql);
if(isset($gid))
{
    if($_GET['console'] != 'All' && isset($_GET['console']))
    {
        $byconsole = " AND o.order_console = '".$_GET['console']."'";
    }
    //$sql = "SELECT o.*, g.game_name, g.game_price FROM orders AS o, games as g WHERE o.game_id = '$gid' AND g.game_id = o.game_id AND o.order_store = ".$x_store." AND o.order_complete < 10 $byconsole ORDER BY order_timestamp DESC";
    $sql = "SELECT o.*, g.game_name, g.game_price FROM orders AS o, games as g WHERE o.game_id = '$gid' AND g.game_id = o.game_id AND o.order_store = ".$x_store." $byconsole ORDER BY order_console DESC";
}
elseif(isset($_GET['cname']))
{
    //$sql = "SELECT o.*, g.game_name, g.game_price FROM orders AS o, games as g WHERE o.order_cust_name LIKE '%".$_GET['cname']."%' AND o.game_id = g.game_id AND o.order_store = ".$x_store." AND o.order_complete < 10 GROUP BY order_id ORDER BY order_timestamp DESC";
    $sql = "SELECT o.*, g.game_name, g.game_price FROM orders AS o, games as g WHERE o.order_cust_name LIKE '%".$_GET['cname']."%' AND o.game_id = g.game_id AND o.order_store = ".$x_store." ORDER BY order_timestamp DESC";
}
elseif(isset($_GET['cphone']))
{
    $phone = preg_replace('/(\W*)/', '', $_GET['cphone']);
    //$sql = "SELECT o.*, g.game_name, g.game_price FROM orders AS o, games as g WHERE o.order_phone = '".$phone."' AND o.game_id = g.game_id AND o.order_store = ".$x_store." AND o.order_complete < 10 GROUP BY order_id ORDER BY order_timestamp DESC";
    $sql = "SELECT o.*, g.game_name, g.game_price FROM orders AS o, games as g WHERE o.order_phone = '".$phone."' AND o.game_id = g.game_id AND o.order_store = ".$x_store." ORDER BY order_timestamp DESC";
    
}

if($sql)
{
    $result = $db->query($sql);
    
    if(mysql_num_rows($result) > 0)
    {
        while($row = mysql_fetch_array($result))
        {
        ?>
        <table cellspacing="0" cellpadding="0" width="100%" border="0">
        <tr>
        <td width="180"><?=$row['order_console'];?><br /><?=$row['game_name'];?></td>
        <td width="100">(<?=date("m/d/y", $row['order_timestamp']);?>)<br/><?=$row['order_cust_name'];?></td>
        <td width="100"><?=formatPhone($row['order_phone']);?></td>
        <?
        if($row['order_amount'] == $row['game_price'])
        {
        ?>
        <td width="80" align="center"><b><span style="color: green;"><?=number_format($row['order_amount'], 2);?>/<?=number_format($row['game_price'], 2);?></span></b></td>
        <?
        }
        else
        {
        ?>
        <td width="80" align="center"><b><span style="color: red;"><?=number_format($row['order_amount'], 2);?>/<?=number_format($row['game_price'], 2);?></span></b></td>
        <?
        }
        ?>
        <td>
        <?
        if($row['order_complete'] == 10)
        {
        ?>
        <center><b><font color="#ff0000">Cancelled</font></b><br /><input type="button" name="" value="Re-Open" onclick="if(confirm('Are you sure you want to re-open this preorder')) { location.href='?do=reopen&id=<?=$row['order_id'];?>' }"/></center>
        <?
        }
        else
        {
        ?>
        <input type="button" name="" value="-" onclick="if(sub = prompt('Amount to subtract?')) { location.href='index.php?do=subtract&sub='+sub+'&id=<?=$row['order_id'];?>'; }" <?=($row['order_amount'] <= 5 ? 'disabled="disabled"' : '');?> />
        <input type="button" name="" value="+" onclick="if(add = prompt('Amount to add?')) { location.href='index.php?do=append&add='+add+'&id=<?=$row['order_id'];?>'; }" <?=($row['order_amount'] != $row['game_price'] ? '' : 'disabled="disabled"');?> />
        <input type="button" name="" value="Paid" <?=($row['order_amount'] != $row['game_price'] ? '' : 'disabled="disabled"');?> onclick="location.href='?do=paid_full&id=<?=$row['order_id'];?>'"/>
        <input type="button" name="" value="Picked Up" <?=(($row['order_amount'] == $row['game_price']) && $row['order_complete'] == 0 ? '' : 'disabled="disabled"');?> onclick="if(confirm('The customer is picking up the item now?')) { location.href='?do=complete&id=<?=$row['order_id'];?>' }"/>
        <br />
        <input type="button" name="" value="Modify" onclick="location.href='modify.php?id=<?=$row['order_id'];?>'"/>
        <input type="button" name="" value="Invoice" onclick="popUp('invoice.php?id=<?=$row['order_id'];?>');"/>
        <input type="button" name="" value="Cancel" onclick="if(confirm('Are you sure you want to cancel this preorder?\nRefund amount $<?=number_format($row['order_amount'], 2);?>')) { location.href='?do=cancel&id=<?=$row['order_id'];?>' }"/>
        <?
        }
        ?>
        </td>
        </tr>
        <tr>
        <td class="dv" height="1" colspan="6">
        </td>
        </tr>
        </table>
        <?
        }
    }
    else
    {
?>
<h3>There are no preorders for this game.</h3>
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