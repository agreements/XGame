<?php // 
// INCLUDES
require_once("../includes/session.php");
require_once("../includes/connect.php");
require_once("../includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// UPDATE SETTINGS
if (isset($_POST['update'])) {
	// GET VARIABLES
	$activation = sql_quote($_POST['activation']);
	$url = sql_quote($_POST['url']);
	$pagetitle = sql_quote($_POST['pagetitle']);
	$adminmail = sql_quote($_POST['adminmail']);
	$errorreporting = sql_quote($_POST['errorreporting']);
	$newsPP = sql_quote($_POST['newsPP']);
	$newsPPlist = sql_quote($_POST['newsPPlist']);
	$rankingPP = sql_quote($_POST['rankingPP']);
	// DATEBASE QUERY
	$settings = mysql_query ("UPDATE settings SET activation = '$activation', url = '$url', pagetitle = '$pagetitle', adminmail = '$adminmail', errorreporting = '$errorreporting', 
	newsPP = '$newsPP', newsPPlist = '$newsPPlist', rankingPP = '$rankingPP' WHERE settingsID = '1'", $connection);
	echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=settings'>"; exit;
} else {
	// DATEBASE QUERY
	$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection);
	$settingsR = mysql_fetch_array($settingsQ);
	$activation = $settingsR["activation"];
	$url = $settingsR["url"];
	$pagetitle = $settingsR["pagetitle"];
	$adminmail = $settingsR["adminmail"];
	$errorreporting = $settingsR["errorreporting"];
	$newsPP = $settingsR["newsPP"];
	$newsPPlist = $settingsR["newsPPlist"];
	$rankingPP = $settingsR["rankingPP"];
	// ACTIVATION (1-auto 2-email 3-admin)
	if ($activation == 1) {$option1 = "selected='selected'";} else {$option1 = "";}
	if ($activation == 2) {$option2 = "selected='selected'";} else {$option2 = "";}
	if ($activation == 3) {$option3 = "selected='selected'";} else {$option3 = "";}
	// ERROR REPORTING
	if ($errorreporting == "E_ERROR") {$report1 = "selected='selected'";} else {$report1 = "";}
	if ($errorreporting == "E_WARNING") {$report2 = "selected='selected'";} else {$report2 = "";}
	if ($errorreporting == "E_PARSE") {$report3 = "selected='selected'";} else {$report3 = "";}
	if ($errorreporting == "E_NOTICE") {$report4 = "selected='selected'";} else {$report4 = "";}
	if ($errorreporting == "E_ALL") {$report5 = "selected='selected'";} else {$report5 = "";}
	if ($errorreporting == "0") {$report6 = "selected='selected'";} else {$report6 = "";}
	// OUTPUT
	eval ("\$settings = \"".gettemplate("settings")."\";");
	echo $settings;
}
?>