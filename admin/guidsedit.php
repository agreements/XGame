<?php // 
// INCLUDES
require_once("../includes/session.php");
require_once("../includes/connect.php");
require_once("../includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// GET ACTION
$action = sql_quote($_GET['action']);
// ACTION: delete
if ($action == "delete") {
	$guidID = sql_quote($_GET['guidID']);
	$quidDEL = mysql_query("DELETE FROM guids WHERE guidID = '$guidID' LIMIT 1", $connection);
	echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=guids'>"; exit;
}
// ACTION: add
elseif (isset($_POST['add'])) {
	// GET VARIABLES
	$title = sql_quote($_POST['title']);
	// DATEBASE QUERY
	$quidsADD = mysql_query("INSERT INTO guids (title) VALUES ('$title')", $connection);
	echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=guids'>"; exit;
} 
// ACTION: form
else {
	eval ("\$guidsadd = \"".gettemplate("guidsadd")."\";");
	echo $guidsadd;
}
?>