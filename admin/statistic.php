<?php // 
// INCLUDES
require_once("../includes/session.php");
require_once("../includes/connect.php");
require_once("../includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// QUERY: numUSERS
$numUSERSq = mysql_query("SELECT COUNT(pid) AS numrows FROM users", $connection);
$numUSERSr = mysql_fetch_array($numUSERSq, MYSQL_ASSOC);
$numUSERS = $numUSERSr['numrows']; // how many rows we have in database
// OUTPUT: search
eval ("\$statistic = \"".gettemplate("statistic")."\";");
echo $statistic;
?>