<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
if (isset($_POST['delete'])) {
	$banID = sql_quote($_POST['banID']);
	// QUERY: news
	$banQ = mysql_query("SELECT * FROM bans where banID = '$banID'", $connection);
	if (mysql_num_rows($banQ) == '0') {echo "<h4>&#8226; There is no bans with this ID!</h4>"; return;}
	// CHECK: if admin
	$gameadminsQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid'", $connection);
	if (mysql_num_rows($gameadminsQ) == '0') {echo "<h4>&#8226; You are not admin!</h4>"; return;}
	// QUERY: delete
	mysql_query("DELETE FROM bans WHERE banID = '$banID' LIMIT 1", $connection);
	echo "<meta http-equiv='refresh' content='0;URL=index.php?site=bans'>"; exit;
}
// PAGES
$rowsPerPage = 80; // rows per page
$pageNum = 1; // by default we show first page
if(isset($_GET['page'])) {$pageNum = $_GET['page'];} // if $_GET['page'] defined, use it as page number
$offset = ($pageNum - 1) * $rowsPerPage; // counting the offset
$result = mysql_query("SELECT COUNT(banID) AS numrows FROM bans", $connection);
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$numrows = $row['numrows']; // how many rows we have in database
// QUERY: bans
$banQ = mysql_query("SELECT * FROM bans ORDER BY date DESC LIMIT $offset, $rowsPerPage", $connection);
if (mysql_num_rows($banQ) == '0') {echo "<h4>&#8226; There is no bans to display!</h4>"; return;}
// CHECK: if admin
$gameadminsQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid'", $connection);
if (mysql_num_rows($gameadminsQ)) {$deleteB = "<input type='submit' name='delete' id='delete' value='delete' />";} else {$deleteB ="";}
// OUTPUT: bans
eval ("\$bans = \"".gettemplate("bans")."\";");
echo $bans;
while ($banR = mysql_fetch_array($banQ)) {
	$banID = $banR["banID"];
	$reason = $banR["reason"];
	$date = date("d.m.Y - H:i", $banR[date]);
	$expire = date("d.m.Y - H:i", $banR[expire]);
	$adminID = $banR["adminID"];
	$memberID = $banR["memberID"];
	// QUERY: member
	$memberQ = mysql_query("SELECT * FROM users WHERE pid = '$memberID'", $connection); $memberR = mysql_fetch_array($memberQ); $memberNAME = $memberR["nickname"];
	// QUERY: admin
	$adminQ = mysql_query("SELECT * FROM users WHERE pid = '$adminID'", $connection); $adminR = mysql_fetch_array($adminQ); $adminNAME = $adminR["nickname"];
	// LIST: matches
	eval ("\$bans = \"".getlist("bans")."\";"); echo $bans;
}
// INCLUDE PAGES
include("pages/newspages.php");
?>