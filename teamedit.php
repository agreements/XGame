<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// CHECK: validation AND auth
$pid = $_SESSION['pid'];
$teamID = sql_quote($_GET['teamID']);
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
// ACTION: editteam
if (isset($_POST['editteam'])) {
	$teamID = sql_quote($_POST['teamID']);
	// GET: variables
	$teamNAME = sql_quote($_POST['teamNAME']);
	$teamTAG = sql_quote($_POST['teamTAG']);
	$web = sql_quote($_POST['web']);
	$teamID = sql_quote($_POST['teamID']);
	// QUERY: update
	$teamupdate = mysql_query("UPDATE teams SET name = '$teamNAME', tag = '$teamTAG', web = '$web' WHERE teamID = '$teamID'", $connection);
	// REDDIRECT:
	redirectto("index.php?site=teaminfo&teamID=$teamID"); exit;
} 
else {
	// CHECK: admin rights
	$adminQ = mysql_query("SELECT * FROM teams WHERE teamID = '$teamID' AND adminID = '$pid'", $connection);
	if (mysql_num_rows($adminQ) == '0') {echo "<h4>&#8226; You are not admin of this team!</h4>"; return;}
	// QUERY: team 
	$teamsQ = mysql_query("SELECT * FROM teams WHERE teamID = '$teamID'", $connection);
	$teamsR = mysql_fetch_array($teamsQ);
	$teamNAME = $teamsR["name"];
	$date = date("d.m.Y", $teamsR[date]);
	$teamTAG = $teamsR["tag"];
	$web = $teamsR["web"];
	// OUTPUT: team create
	eval ("\$teamedit = \"".gettemplate("teamedit")."\";");
	echo $teamedit;
}
?>