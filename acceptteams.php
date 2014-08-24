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
$conflictID = sql_quote($_POST['conflictID']);
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
// QUERY: gameadmins
$gameadminsQ = mysql_query("SELECT * FROM ladderteams WHERE pid = '$pid'", $connection);
if (mysql_num_rows($gameadminsQ) == '0') {echo "<h4>&#8226; You cant acces this area!</h4>"; return;}
// ACTION: accept
if (isset($_POST['accept'])) {
	$status = sql_quote($_POST['status']);
	$id = sql_quote($_POST['id']);
	if ($status == "accept") {mysql_query("UPDATE ladderteams SET accepted = '1' WHERE id = '$id'", $connection);}
	if ($status == "decline") {mysql_query("DELETE FROM ladderteams WHERE id = '$id' LIMIT 1", $connection);}
	redirectto("index.php?site=acceptteams"); exit;
}
// QUERY: teams
$ladderteamsQ = mysql_query("SELECT * FROM ladderteams WHERE accepted = '0'", $connection);
if (mysql_num_rows($ladderteamsQ) == '0') {echo "<h4>&#8226; There is no teams to accept!</h4>"; return;}
// OUTPUT: teammembers
eval ("\$acceptteams = \"".gettemplate("acceptteams")."\";");
echo $acceptteams;
while ($ladderteamsR = mysql_fetch_array($ladderteamsQ)) {
	$ladderID = $ladderteamsR["ladderID"];
	$teamID = $ladderteamsR["teamID"];
	$joined = date("d.m.Y - H:i", $ladderteamsR[joined]);
	$id = $ladderteamsR["id"];
	// QUERY: ladder 
	$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection);
	$ladderR = mysql_fetch_array($ladderQ);
	$ladderTITLE = $ladderR["title"];
	$gamemode = $ladderR["gamemode"];
	$gameID = $ladderR["game"];
	// QUERY: game
	$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$gameID'", $connection); $gameR = mysql_fetch_array($gameQ); $gameTITLE = $gameR["title"];
	// QUERY: teams
	$teamsQ = mysql_query("SELECT * FROM teams WHERE teamID = '$teamID'", $connection); $teamsR = mysql_fetch_array($teamsQ); $teamNAME = $teamsR["name"];
	// LIST: acceptteams
	eval ("\$acceptteams = \"".getlist("acceptteams")."\";"); echo $acceptteams;
}
?>