<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// CHECK: auth
$pid = $_SESSION['pid'];
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
// QUERY: gameadmins
$gameadminsQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid'", $connection);
if (mysql_num_rows($gameadminsQ) == '0') {echo "<h4>&#8226; You cant acces this area!</h4>"; return;}
// OUTPUT: admin
eval ("\$admin = \"".gettemplate("admin")."\";"); 
echo $admin;
while ($gameadminsR = mysql_fetch_array($gameadminsQ)) {
	$gameID = $gameadminsR["gameID"];
	// QUERY: game
	$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$gameID'", $connection);
	$gameR = mysql_fetch_array($gameQ);
	$gameTITLE = $gameR["title"];
	// LIST: gamelist
	eval ("\$admin = \"".getlist("admin")."\";"); echo $admin;
}
?>