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
eval ("\$maps = \"".gettemplate("maps")."\";");
echo $maps;
// QUERY: maps
$mapQ = mysql_query("SELECT * FROM maps", $connection);
while ($mapR = mysql_fetch_array($mapQ)) {
	$mapID = $mapR["mapID"];
	$mapTITLE = $mapR["title"];
	$ladderID =  $mapR["ladderID"];
	// QUERY: ladder
	$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection); 
	$ladderR = mysql_fetch_array($ladderQ); 
	$ladderTITLE = $ladderR["title"];
	// OUTPUT
	eval ("\$mapsLIST = \"".getlist("mapsLIST")."\";"); echo $mapsLIST;
}
echo "<div align='left'><INPUT type=\"button\" value=\"add map\" name=\"button6\" onClick=\"window.location='./admincp.php?site=mapsedit&action=add'\"></div>";
?>