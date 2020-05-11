<?
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
?>
<!doctype html public "-//W3C//DTD HTML 4.01 Transitional//EN">
<html dir="LTR" lang="en">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<title>Gaming Generations : Preorders</title>
<link media="only screen and (min-device-width: 768px) and (max-device-width: 1024px)" href="<html:rewrite page='/ipad.css' />" type="text/css" rel="stylesheet" />
<link rel="stylesheet" type="text/css" href="./stylesheet.css" />
<link rel="stylesheet" type="text/css" href="./style.css" />
<script type="text/javascript" src="./js/jquery.js"></script>
</head>
<style type="text/css">
body {
    background:  none;;
}
</style>
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
<a href="#"><img src="./images/gg-logo.png" alt="Gaming Generations" /></a>
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

<table cellspacing="2" cellpadding="3" width="600">
<tr>
<td height="150" valign="top">

<?
$sql = "SELECT o.*, g.game_name, g.game_price FROM orders AS o, games as g WHERE o.order_id = '".$_GET['id']."' AND o.game_id = g.game_id LIMIT 1";

$result = $db->query($sql);
$row = mysql_fetch_array($result);

$store1 = array("La Crosse","Stevens Point","Wausau","Winona", "Onalaska", "Rosedale", "Brooklyn Park", "Maplewood");
?>

<table cellspacing="0" cellpadding="0" width="100%">
<tr>
<td valign="top">
<h3>Customer Information</h3>
<b>Customer Name</b>: <?=$row['order_cust_name'];?><br />
<b>Customer Phone</b>: <?=formatPhone($row['order_phone']);?><br />
<b>Customer Email</b>: <?=$row['order_email'];?><br />

<h3>Store Information</h3>
<b>Store</b>: <?=$store1[$row['order_store']];?><br />
<b>Date</b>: <?=date("F j, Y, g:i a", $row['order_timestamp']);?><br />
<b>Employee Name</b>: <?=$row['employee'];?><br />
<b>Preorder Item</b>: <?=$row['game_name'];?><br />
<b>Console</b>: <?=$row['order_console'];?><br />
<b>Item Price</b>: $<?=$row['game_price'];?><br />
<b>Amount down</b>: $<?=$row['order_amount'];?><br />
</td>
<td align="right" valign="top">
<input type="button" onclick="window.print()" value="Print" />
</td>
</tr>
</table>

</td>
</tr>
<tr>
<td class="dv" height="1" colspan="2">
</td>
</tr>
<tr>
<td>
<br />
<br />
<br />
<br />
<b>Terms &amp; Conditions:</b>
<br />
You may cancel a pre-order at any time. Please do your best to pick up your pre-order w/in 7 business days after the item(s) are released. If 30 days have gone by after the item was released and you have still not picked up your pre-order, we will put the item(s) back on the sales floor and will refund your payment in full.
<br />
<br />
Thank you for your pre-order and for supporting a local business.
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