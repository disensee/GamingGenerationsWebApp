<?
include("./inc/auth.inc");
include("./inc/class.mysql.inc");
include('./inc/func.email.inc');

$db = new DB;
$x_store = isset($_COOKIE['x_store']) ? $_COOKIE['x_store'] : 0;

$db = new DB;
if($x_store != 8) 
{
    $db->init("localhost", "gamingg2", "P4ssw0rd#@$", "gamingg2_preorders");
}
else
{
    $db->init("localhost", "gamingg2", "P4ssw0rd#@$", "gamingg2_main");
}


function generate_calendar($year, $month, $days = array(), $day_name_length = 3, $month_href = NULL, $first_day = 0, $pn = array())
{
        $first_of_month = gmmktime(0,0,0,$month,1,$year);
        #remember that mktime will automatically correct if invalid dates are entered
        # for instance, mktime(0,0,0,12,32,1997) will be the date for Jan 1, 1998
        # this provides a built in "rounding" feature to generate_calendar()

        $day_names = array(); #generate all the day names according to the current locale
        for($n=0,$t=(3+$first_day)*86400; $n<7; $n++,$t+=86400) #January 4, 1970 was a Sunday
                $day_names[$n] = ucfirst(gmstrftime('%A',$t)); #%A means full textual day name

        list($month, $year, $month_name, $weekday) = explode(',',gmstrftime('%m,%Y,%B,%w',$first_of_month));
        $weekday = ($weekday + 7 - $first_day) % 7; #adjust for $first_day
        $title   = htmlentities(ucfirst($month_name)).'&nbsp;'.$year;  #note that some locales don't capitalize month and day names

        #Begin calendar. Uses a real <caption>. See http://diveintomark.org/archives/2002/07/03
        @list($p, $pl) = each($pn); @list($n, $nl) = each($pn); #previous and next links, if applicable
        if($p) $p = '<span class="calendar-prev">'.($pl ? '<a href="'.htmlspecialchars($pl).'">'.$p.'</a>' : $p).'</span>&nbsp;';
        if($n) $n = '&nbsp;<span class="calendar-next">'.($nl ? '<a href="'.htmlspecialchars($nl).'">'.$n.'</a>' : $n).'</span>';
        $calendar = '<table class="calendar" cellpadding="0" cellspacing="0" width="100%">'."\n".
                '<caption class="calendar-month"><h2>'.$p.($month_href ? '<a href="'.htmlspecialchars($month_href).'">'.$title.'</a>' : $title).$n."</h2></caption>\n<tr class='calendar-row'>";

        if($day_name_length){ #if the day names should be shown ($day_name_length > 0)
                #if day_name_length is >3, the full name of the day will be printed
                foreach($day_names as $d)
                        $calendar .= '<td abbr="'.htmlentities($d).'" class="calendar-day-head">'.htmlentities($day_name_length < 4 ? substr($d,0,$day_name_length) : $d).'</td>';
                $calendar .= "</tr>\n<tr class='calendar-row'>";
        }

        if($weekday > 0) $calendar .= '<td colspan="'.$weekday.'" class="calendar-day-np">&nbsp;</td>'; #initial 'empty' days
        for($day=1,$days_in_month=gmdate('t',$first_of_month); $day<=$days_in_month; $day++,$weekday++){
                if($weekday == 7){
                        $weekday   = 0; #start a new week
                        $calendar .= "</tr>\n<tr class='calendar-row'>";
                }
                if(isset($days[$day]) and is_array($days[$day])){
                        @list($link, $classes, $content) = $days[$day];
                        if(is_null($content))  $content  = $day;
                        $calendar .= '<td valign="top" class="calendar-day"><div class="day-number">'.$day.'</div>'.
                                ($link ? '<a href="'.htmlspecialchars($link).'">'.$content.'</a>' : '<div style="overflow: auto; height: 150px;">'.$content.'</div>').'</td>';
                }
                else $calendar .= "<td class=\"calendar-day\"><div class='day-number'>$day</div></td>";
        }
        if($weekday != 7) $calendar .= '<td colspan="'.(7-$weekday).'">&nbsp;</td>'; #remaining "empty" days

        return $calendar."</tr>\n</table>\n";
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
<style type="text/css">
/* calendar */
table.calendar		{ border-left:1px solid #999; width: 100%}
tr.calendar-row	{  }
td.calendar-day	{ height:150px; font-size:11px; position:relative;} * html div.calendar-day { height:80px; }
td.calendar-day:hover	{ background:#eceff5; }
td.calendar-day-np	{ background:#eee; min-height:80px; } * html div.calendar-day-np { height:80px; }
td.calendar-day-head { background:#ccc; font-weight:bold; text-align:center; width:120px; padding:5px; border-bottom:1px solid #999; border-top:1px solid #999; border-right:1px solid #999; }
div.day-number		{ background:#999; padding:5px; color:#fff; font-weight:bold; float:right; margin:-5px -5px 0 0; width:20px; text-align:center; position: absolute; top:10px; right:10px;}
/* shared */
td.calendar-day, td.calendar-day-np { width:120px; padding:5px; border-bottom:1px solid #999; border-right:1px solid #999; }


</style>
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
<table cellspacing="0" cellpadding="0" width="100%">
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
<td valign="top"><!-- header_eof //-->

<!-- body //-->
<?
$time = time();
$today = date('j',$time);

$month = isset($_GET['m']) ? $_GET['m'] : date('m',$time);
$year = isset($_GET['y']) ? $_GET['y'] : date('y',$time);

$nmonth = sprintf('%02d', $month + 1);
$pmonth = sprintf('%02d', $month - 1);
$pcyear = $year;
$cyear = $year;
if($month == 12)
{
        $nmonth = 1;
        $cyear = $year + 1;
}
elseif($month == 1)
{
        $pmonth = 12;
        $cyear = $year;
        $pcyear = $year - 1;
}
  

$sql = "SELECT * FROM games
        WHERE inv = 1
        AND game_release_date >= '".sprintf('%02d',($month))."/01/20".$year."'
        AND game_release_date <= '".sprintf('%02d',($month))."/31/20".$year."'";

if(!$result = mysql_query($sql))
{
 
}
$games = array();

while($row = mysql_fetch_array($result))
{
    $row['game_release_date'] = strtotime($row['game_release_date']);
        $games[date('j',$row['game_release_date'])][] = '<div style="border: 1px solid #333; border-radius: 2px; padding:2px;">'.$row['game_name'].'</div>';
}

foreach ($games as $key => $value) {
    //echo "Key: $key; Value: ".print_r($value)."<br />\n";

    for($i=0;$i<count($value);$i++)
    {
        $n_val .= $value[$i]."<br/>";
        
    }

    $days[$key] = array(null, null, $n_val);
    unset($n_val);
}            
$pl = array('&laquo;'=>'?m='.$pmonth.'&y='.$pcyear, '&raquo;'=>'?m='.$nmonth.'&y='.$cyear);
echo generate_calendar($year, $month, $days, 3, NULL, 0, $pl);

?>

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