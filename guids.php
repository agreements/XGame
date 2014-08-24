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
// POST: add
if (isset($_POST['add'])) {
	// GET: variables
	$guidID = sql_quote($_POST['guidID']);
	$value = sql_quote($_POST['value']);
	$note = sql_quote($_POST['note']);
	$date = time();
	// CHECK: duplicate
	$duplicateQ = mysql_query("SELECT * FROM playerguids WHERE value = '$value' AND guidID = '$guidID' AND active = '1'", $connection); 
	if (mysql_num_rows($duplicateQ) != '0') {echo "<h4>&#8226; That GUID is registered by someone else, please contact WEB admin!</h4>"; return;}
	// QUERY: update
	$guidUPD = mysql_query("UPDATE playerguids SET active = '0' WHERE pid = '$pid' AND guidID = '$guidID'", $connection);
	// QUERY: add
	$guidADD = mysql_query("INSERT INTO playerguids (pid, guidID, value, note, date, active) VALUES ('$pid', '$guidID', '$value', '$note', '$date', 1)", $connection);
	redirectto("index.php?site=personinfo&pid=$pid&action=guids"); exit;
}
// LIST: guids
$guidsQ = mysql_query("SELECT * FROM guids", $connection);
while ($guidsR = mysql_fetch_array($guidsQ)) {
	$guidID = $guidsR["guidID"];
	$title = $guidsR["title"];
	$guidLIST .= "<option value='$guidID'>$title</option>";
}
// OUTPUT: guids
eval ("\$guidADD = \"".gettemplate("guidADD")."\";");
echo $guidADD;
?>