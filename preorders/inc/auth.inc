<?
/*
//$store = array("La Crosse","Stevens Point","Wausau","Winona", "Onalaska", "Rosedale", "Brooklyn Park", "Maplewood");

//$x_store = isset($_COOKIE['x_store']) ? $_COOKIE['x_store'] : 0;
$valid_passwords = array();
$valid_passwords["La Crosse"] = array("x_store" => 0,"Minnwild12" => "golfisfun996812", "La Crosse" => "gamesrock");
$valid_passwords["Stevens Point"] = array("x_store" => 1,"Minnwild12" => "golfisfun996812", "Stevens Point" => "gamesrock");
$valid_passwords["Wausau"] = array("x_store" => 2,"Minnwild12" => "golfisfun996812", "Wausau" => "gamesrock");
$valid_passwords["Winona"] = array("x_store" => 3,"Minnwild12" => "golfisfun996812", "Winona" => "gamesrock");
$valid_passwords["Onalaska"] = array("x_store" => 4,"Minnwild12" => "golfisfun996812", "Onalaska" => "gamesrock");
$valid_passwords["Rosedale"] = array("x_store" => 5,"Minnwild12" => "golfisfun996812", "Rosedale" => "gamesrock");
$valid_passwords["Brooklyn Park"] = array("x_store" => 6,"Minnwild12" => "golfisfun996812", "Brooklyn Park" => "gamesrock");
$valid_passwords["Maplewood"] = array("x_store" => 7,"Minnwild12" => "golfisfun996812", "Maplewood" => "gamesrock");
*/
session_start();

/*
$valid_passwords = array ("la crosse" => "gamesrock#","stevens point" => "gamesrock#","wausau" => "gamesrock#","winona" => "gamesrock#","onalaska" => "gamesrock#","rosedale" => "gamesrock#","brooklyn Park" => "gamesrock","maplewood" => "gamesrock#","sheboygan" => "gamesrock#", "eau claire" => "gamesrock#");
$valid_users = array_keys($valid_passwords);

$user = strtolower($_SERVER['PHP_AUTH_USER']);
$pass = $_SERVER['PHP_AUTH_PW'];

$validated = (in_array($user, $valid_users)) && ($pass == $valid_passwords[$user]);

if (!$validated)
{
        header('WWW-Authenticate: Basic realm="Gaming Generation\'s Preorder Admin"');
        header('HTTP/1.0 401 Unauthorized');
        die ("Not authorized");
}

$store_hash = array();
$store_hash["la crosse"] = 0;
$store_hash["stevens point"] = 1;
$store_hash["wausau"] = 2;
$store_hash["onalaska"] = 4;
$store_hash["rosedale"] = 5;
$store_hash["brooklyn park"] = 6;
$store_hash["maplewood"] = 7;
$store_hash["sheboygan"] = 8;
$store_hash["eau claire"] = 9;
*/

if(!isset($_SESSION['loggedin']))
{
    header("Location: login.php");
    exit;
}
$x_store = substr(base64_decode($_SESSION['loggedin']), 0, 1);

//setcookie("r_store", $valid_passwords[$user]['x_store']);
//header("Location: index.php");

/*

$store_hash = array();
$store_hash[md5(0)] = 0;
$store_hash[md5(1)] = 1;
$store_hash[md5(2)] = 2;
$store_hash[md5(4)] = 4;
$store_hash[md5(5)] = 5;
$store_hash[md5(6)] = 6;
$store_hash[md5(7)] = 7;
$store_hash[md5(8)] = 8;
$store_hash[md5(9)] = 9;

if(!isset($_COOKIE['x_store']) && !$store_hash[$_COOKIE['x_store']])
{
        header("Location: login.php");
}

$valid_passwords = array();
$valid_passwords["la crosse"] = array("x_store" => 0,"Minnwild12" => "golfisfun996812", "la crosse" => "gamesrock");
$valid_passwords["stevens point"] = array("x_store" => 1,"Minnwild12" => "golfisfun996812", "stevens point" => "gamesrock");
$valid_passwords["wausau"] = array("x_store" => 2,"Minnwild12" => "golfisfun996812", "wausau" => "gamesrock");
//$valid_passwords["Winona"] = array("x_store" => 3,"Minnwild12" => "golfisfun996812", "Winona" => "gamesrock");
$valid_passwords["onalaska"] = array("x_store" => 4,"Minnwild12" => "golfisfun996812", "onalaska" => "gamesrock");
$valid_passwords["rosedale"] = array("x_store" => 5,"Minnwild12" => "golfisfun996812", "rosedale" => "gamesrock");
$valid_passwords["brooklyn park"] = array("x_store" => 6,"Minnwild12" => "golfisfun996812", "brooklyn park" => "gamesrock");
$valid_passwords["maplewood"] = array("x_store" => 7,"Minnwild12" => "golfisfun996812", "maplewood" => "gamesrock");

//Sheyboygan, Eau Claire
$valid_passwords["sheboygan"] = array("x_store" => 8, "sheboygan" => "gamesrock");
$valid_passwords["eau claire"] = array("x_store" => 9, "eau claire" => "gamesrock");
*/
?>