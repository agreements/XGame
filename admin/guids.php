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
eval ("\$guids = \"".gettemplate("guids")."\";");
echo $guids;
// GUIDS: query
$guidQ = mysql_query("SELECT * FROM guids", $connection);
while ($guidR = mysql_fetch_array($guidQ)) {
	$guidID = $guidR["guidID"];
	$title = $guidR["title"];
	// OUTPUT: guids
	eval ("\$guidsLIST = \"".getlist("guidsLIST")."\";"); echo $guidsLIST;
}
// OUTPUT: add new guid
echo "<div align='left'><INPUT type=\"button\" value=\"add guid\" name=\"button6\" onClick=\"window.location='./admincp.php?site=guidsedit&action=add'\"></div>";
?>