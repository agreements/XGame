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
eval ("\$ladders = \"".gettemplate("ladders")."\";");
echo $ladders;	
// QUERY: ladder
$ladderQ = mysql_query("SELECT * FROM ladders", $connection);
while ($ladderR = mysql_fetch_array($ladderQ)) {
	$ladderID = $ladderR["ladderID"];
	$title = $ladderR["title"];
	$date = date("d.m.Y", $ladderR[date]);
	// OUTPUT: ladders
	eval ("\$laddersLIST = \"".getlist("laddersLIST")."\";"); echo $laddersLIST;
}
// OUTPUT: add new ladder
echo "<div align='left'><INPUT type=\"button\" value=\"add ladder\" name=\"button6\" onClick=\"window.location='./admincp.php?site=laddersedit&action=add'\"></div>";
?>