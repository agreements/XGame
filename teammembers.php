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
// SUBMIT: delete
if (isset($_POST['delete'])) {
	// GET: variables
	$teamID = sql_quote($_POST['teamID']);
	$memberID = sql_quote($_POST['memberID']);
	// QUERY: delete
	$memberDEL = mysql_query("DELETE FROM teammembers WHERE pid = '$memberID' AND teamID = '$teamID' LIMIT 1", $connection);
	redirectto("index.php?site=teammembers&teamID=$teamID"); exit;
} 
// SUBMIT: edit
if (isset($_POST['edit'])) {
	$teamID = sql_quote($_POST['teamID']);
	$memberID = sql_quote($_POST['memberID']);
	$rights = sql_quote($_POST['rights']);
	$memberUPD = mysql_query("UPDATE teammembers SET rights = '$rights' WHERE pid = '$memberID' AND teamID = '$teamID'", $connection);
	// REDDIRECT
	redirectto("index.php?site=teammembers&teamID=$teamID"); exit;
}
// SUBMIT: add
if (isset($_POST['add'])) {
	// GET VARIABLES
	$teamID = sql_quote($_POST['teamID']);
	$newmemberID = sql_quote($_POST['newmemberID']);
	$date = time();
	// CHECK: if user exist
	$memberQ = mysql_query("SELECT * FROM users WHERE pid = '$newmemberID'", $connection);
	if (mysql_num_rows($memberQ) == '0') {echo "<h4>&#8226; There is no player with that ID!</h4>"; return;}
	// CHECK: if teammember exist
	$teammemberQ = mysql_query("SELECT * FROM teammembers WHERE teamID = '$teamID' AND pid = '$newmemberID'", $connection);
	if (mysql_num_rows($teammemberQ) != '0') {echo "<h4>&#8226; This player is already in team!</h4>"; return;}
	// CHECK: admin rights
	$adminQ = mysql_query("SELECT * FROM teammembers WHERE teamID = '$teamID' AND pid = '$pid' AND rights > '2'", $connection);
	if (mysql_num_rows($adminQ) == '0') {echo "<h4>&#8226; You are not admin of this team!</h4>"; return;}
	// DATEBASE QUERY
	$memberADD = mysql_query("INSERT INTO teammembers (teamID, pid, joined, accepted, rights) VALUES ('$teamID', '$newmemberID', '$date', '0', '1')", $connection);
	redirectto("index.php?site=teammembers&teamID=$teamID"); exit;
}
// SUBMIT: clan invite
if (isset($_POST['go'])) {
	$action = sql_quote($_POST['action']);
	$teaminvID = sql_quote($_POST['teaminvID']);
	if ($action == "0") {$delete = mysql_query("DELETE FROM teammembers WHERE id = '$teaminvID' AND pid = '$pid' LIMIT 1", $connection); 
	redirectto("index.php?site=notifications"); exit;}
	if ($action == "1") {$update = mysql_query("UPDATE teammembers SET accepted = '1' WHERE id = '$teaminvID' AND pid = '$pid' ", $connection); 
	redirectto("index.php?site=notifications"); exit;}
}
// QUERY: teams
$teamQ = mysql_query("SELECT * FROM teams WHERE teamID = '$teamID'", $connection);
if (mysql_num_rows($teamQ) == '0') {echo "<h4>&#8226; There is no teams with that ID!</h4>"; return;}
$teamR = mysql_fetch_array($teamQ); 
$teamNAME = $teamR["name"]; 
$teamTAG = $teamR["tag"];
// CHECK: admin rights
$adminQ = mysql_query("SELECT * FROM teammembers WHERE teamID = '$teamID' AND pid = '$pid' AND rights > '2'", $connection);
if (mysql_num_rows($adminQ) == '0') {echo "<h4>&#8226; You are not admin of this team!</h4>"; return;}
// OUTPUT: teammembers
eval ("\$teammembers = \"".gettemplate("teammembers")."\";");
echo $teammembers;
// QUERY: TEAM MEMBERS
$membersQ = mysql_query("SELECT * FROM teammembers WHERE teamID = '$teamID' ORDER BY pid DESC", $connection);
while ($membersR = mysql_fetch_array($membersQ)) {
	$memberID = $membersR["pid"];
	$joined = date("d.m.Y - H:i", $membersR[joined]);
	$rights = $membersR["rights"];
	// QUERY: user
	$memberQ = mysql_query("SELECT * FROM users WHERE pid = '$memberID'", $connection);
	$memberR = mysql_fetch_array($memberQ);
	$nickname = $memberR["nickname"];
	// ACTIVATION (1-auto 2-email 3-admin)
	if ($rights == 1) {$right1 = "selected='selected'";} else {$right1 = "";}
	if ($rights == 2) {$right2 = "selected='selected'";} else {$right2 = "";}
	if ($rights == 3) {$right3 = "selected='selected'";} else {$right3 = "";}
	// LIST: subTEAMMEMBERS
	eval ("\$teammembers = \"".getlist("teammembers")."\";"); echo $teammembers;
}
?>