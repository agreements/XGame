<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
if (isset($_POST['ban'])) {
	// QUERY: news
	$memberID = sql_quote($_POST['memberID']);
	$reason = sql_quote($_POST['reason']);
	$duration = sql_quote($_POST['duration']);
	$date = time();
	$expire = strtotime("+".$duration);
	$memberQ = mysql_query("SELECT * FROM users where pid = '$memberID'", $connection);
	if (mysql_num_rows($memberQ) == '0') {echo "<h4>&#8226; There is no members with that ID!</h4>"; return;}
	// CHECK: if admin
	$gameadminsQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid'", $connection);
	if (mysql_num_rows($gameadminsQ) == '0') {echo "<h4>&#8226; You are not admin!</h4>"; return;}
	// QUERY: insert
	$banINS = mysql_query("INSERT INTO bans (memberID, adminID, reason, date, expire) VALUES ('$memberID', '$pid', '$reason', '$date', '$expire')", $connection);
	echo "<meta http-equiv='refresh' content='0;URL=index.php?site=bans'>"; exit;
}
// QUERY: gameadmins
$gameadminsQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid'", $connection);
if (mysql_num_rows($gameadminsQ) == '0') {echo "<h4>&#8226; You cant acces this area!</h4>"; return;}
// OUTPUT: bans
eval ("\$bansadd = \"".gettemplate("bansadd")."\";");
echo $bansadd;
?>