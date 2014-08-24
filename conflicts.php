<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// CHECK: variables AND auth
$pid = $_SESSION['pid'];
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
// QUERY: gameadmins
$gameadminsQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid'", $connection);
if (mysql_num_rows($gameadminsQ) == '0') {echo "<h4>&#8226; You cant acces this area!</h4>"; return;}
// QUERY: conflict
$conflictQ = mysql_query("SELECT * FROM conflict WHERE status = '0'", $connection);
if (mysql_num_rows($conflictQ) == '0') {echo "<h4>&#8226; There is no conflicts to display</h4>"; return;}
// OUTPUT: conflicts
eval ("\$conflicts = \"".gettemplate("conflicts")."\";");
echo $conflicts;
while ($conflictR = mysql_fetch_array($conflictQ)) {
	$conflictID = $conflictR["conflictID"];
	$matchID = $conflictR["matchID"];
	$ladderID = $conflictR["ladderID"];
	$opponent1 = $conflictR["opponent1"];
	$opponent2 = $conflictR["opponent2"];
	// QUERY: ladder
	$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection); $ladderR = mysql_fetch_array($ladderQ);
	$ladderID = $ladderR["ladderID"];
	$ladderTITLE = $ladderR["title"];
	$gamemode = $ladderR["gamemode"];
	$gameID = $ladderR["game"];
	// QUERY: opponent1
	$opponent1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $opponent1R = mysql_fetch_array($opponent1Q); 
	$opponent1name = $opponent1R["name"];
	// QUERY: opponent2
	$opponent2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $opponent2R = mysql_fetch_array($opponent2Q); 
	$opponent2name = $opponent2R["name"];
	// QUERY: gameadminsQ
	$gameadminsQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid' AND gameID ='$gameID'", $connection);
	while ($gameadminsR = mysql_fetch_array($gameadminsQ)) {
		eval ("\$conflicts = \"".getlist("conflicts")."\";"); echo $conflicts;
	}
}
?>