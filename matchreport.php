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
// POST: variables
$matchID = sql_quote($_GET['matchID']);

// ACTION: report
if (isset($_POST['report'])) {
	//GET: variables
	$rating = sql_quote($_POST['rating']);
	$content = sql_quote($_POST['content']);
	$matchID = sql_quote($_POST['matchID']);
	$opponentID = sql_quote($_POST['opponentID']);
	$date = time();
	// QUERY: match
	$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection);
	if (mysql_num_rows($matchQ) == '0') {echo "<h4>&#8226; There is no match with that ID!</h4>"; return;}
	$matchR = mysql_fetch_array($matchQ);
	$opponent1 = $matchR["opponent1"];
	$opponent2 = $matchR["opponent2"];
	// CHECK: if is admin of clans
	$teammembersQ = mysql_query("SELECT * FROM teammembers WHERE (teamID = '$opponent1' OR teamID = '$opponent2') AND pid = '$pid' AND rights > '2'", $connection);
	if (mysql_num_rows($teammembersQ) == '0') {echo "<h4>&#8226; You are not admin of this clan!</h4>"; return;}
	// SET TEAMS
	if ($opponentID == $opponent1) {$teamID = $opponent2;}
	if ($opponentID == $opponent2) {$teamID = $opponent1;}
	// QUERY: insert
	$ratingADD = mysql_query("INSERT INTO teamrating (matchID, teamID, opponentID, pid, content, date, rating) 
	VALUES ('$matchID', '$opponentID', '$teamID', '$pid', '$content', '$date', '$rating')", $connection);
	// REDIRECT
	echo "<meta http-equiv='refresh' content='0;URL=index.php?site=match&matchID=$matchID'>"; exit;
} else {
	// QUERY: match
	$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection);
	if (mysql_num_rows($matchQ) == '0') {echo "<h4>&#8226; There is no match with that ID!</h4>"; return;}
	$matchR = mysql_fetch_array($matchQ);
	$opponent1 = $matchR["opponent1"];
	$opponent2 = $matchR["opponent2"];
	// CHECK: if is admin of clans
	$teammembersQ = mysql_query("SELECT * FROM teammembers WHERE (teamID = '$opponent1' OR teamID = '$opponent2') AND pid = '$pid' AND rights > '2'", $connection);
	if (mysql_num_rows($teammembersQ) == '0') {echo "<h4>&#8226; You are not admin of this clan!</h4>"; return;}
	$teammembersR = mysql_fetch_array($teammembersQ);
	$teamID = $teammembersR["teamID"];
	if ($teamID == $opponent1) {$opponentID = $opponent2;}
	if ($teamID == $opponent2) {$opponentID = $opponent1;}
	// QUERY: opponentNAME
	$opponentQ = mysql_query("SELECT * FROM teams WHERE teamID = '$opponentID'", $connection); $opponentR = mysql_fetch_array($opponentQ); $opponentNAME = $opponentR["name"];
	// OUTPUT: matchmatchreport
	eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
	eval ("\$matchreport = \"".gettemplate("matchreport")."\";");
	echo $matchreport;
}
?>