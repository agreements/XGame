<?php // 
// INCLUDES
require_once("../includes/session.php");
require_once("../includes/connect.php");
require_once("../includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// OUTPUT: title
eval ("\$games = \"".gettemplate("games")."\";");
echo $games;
// QUERY: games
$gamesQ = mysql_query("SELECT * FROM games", $connection);
while ($gamesR = mysql_fetch_array($gamesQ)) {
	$gameID = $gamesR["gameID"];
	$title = $gamesR["title"];
	// OUTPUT: games
	eval ("\$gamesLIST = \"".getlist("gamesLIST")."\";"); echo $gamesLIST;
}
// OUTPUT: add game category
echo "<div align='left'><INPUT type=\"button\" value=\"add game\" name=\"button6\" onClick=\"window.location='./admincp.php?site=gamesedit&action=add'\"></div>";
?>