<?php // 
// INCLUDES
require_once("../includes/session.php");
require_once("../includes/connect.php");
require_once("../includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// OUTPUT: tournaments
eval ("\$tournaments = \"".gettemplate("tournaments")."\";");
echo $tournaments;	
// QUERY: tournaments
$tournamentQ = mysql_query("SELECT * FROM tournaments", $connection);
while ($tournamentR = mysql_fetch_array($tournamentQ)) {
	$tournamentsID = $tournamentR["tournamentsID"];
	$title = $tournamentR["title"];
	$size = $tournamentR["size"];
	$date = date("d.m.Y", $tournamentR[date]);
	// OUTPUT: tournaments
	eval ("\$tournamentsLIST = \"".getlist("tournamentsLIST")."\";"); echo $tournamentsLIST;
}
// OUTPUT: add new ladder
echo "<div align='left'><INPUT type=\"button\" value=\"add tournament\" name=\"button6\" onClick=\"window.location='./admincp.php?site=tournamentsedit&action=add'\"></div>";
?>