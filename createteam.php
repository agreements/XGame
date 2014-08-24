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
// POST: create
if (isset($_POST['create'])) {
	// GET: variables
	$name = sql_quote($_POST['name']);
	$tag = sql_quote($_POST['tag']);
	$web = sql_quote($_POST['web']);
	$date = time();
	// CHECK: length
	if (strlen($name) < 1 ) {echo "<h4>&#8226; Your team name is to short!</h4>"; return;}
	if (strlen($tag) < 1 ) {echo "<h4>&#8226; Your tag name is to short!</h4>"; return;}
	if (strlen($name) > 50 ) {echo "<h4>&#8226; Your team name is to long!</h4>"; return;}
	if (strlen($tag) > 10 ) {echo "<h4>&#8226; Your tag name is to long!</h4>"; return;}
	// QUERY: add
	$teamADD = mysql_query("INSERT INTO teams (name, tag, date, web, adminID) VALUES ('$name', '$tag', '$date', '$web', '$pid')", $connection);
	$teamID = mysql_insert_id();
	// QUERY: teammember
	$memberADD = mysql_query("INSERT INTO teammembers (teamID, pid, joined, accepted, rights, notes) VALUES ('$teamID', '$pid', '$date', '1', '3', '$notes')", $connection);
	// REDDIRECT
	redirectto("index.php?site=teaminfo&teamID=$teamID"); exit;
} else {
	// OUTPUT: createteam
	eval ("\$createteam = \"".gettemplate("createteam")."\";");
	echo $createteam;
}
?>