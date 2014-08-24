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
$matchID = sql_quote($_POST['matchID']);
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
// QUERY: match
$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection);
// CHECK: if this matchID exist
if (mysql_num_rows($matchQ) == '0') {echo "<h4>&#8226; There is no match with that ID!</h4>"; return;}
$matchR = mysql_fetch_array($matchQ);
$opponent1 = $matchR["opponent1"];
$opponent2 = $matchR["opponent2"];
// CHECK: if is admin of  both clans
$teammembers1Q = mysql_query("SELECT * FROM teammembers WHERE teamID = '$opponent1' AND pid = '$pid' AND rights > '1'", $connection);
$teammembers2Q = mysql_query("SELECT * FROM teammembers WHERE teamID = '$opponent2' AND pid = '$pid' AND rights > '1'", $connection);
if (mysql_num_rows($teammembers1Q) != '0' && mysql_num_rows($teammembers2Q) != '0') {echo "<h4>&#8226; You have admin rights of both teams. We cant allow that.</h4>"; return;}
// CHECK: if is admin of clans
$teammembersQ = mysql_query("SELECT * FROM teammembers WHERE (teamID = '$opponent1' OR teamID = '$opponent2') AND pid = '$pid' AND rights > '2'", $connection);
if (mysql_num_rows($teammembersQ) == '0') {echo "<h4>&#8226; You are not admin of this clan!</h4>"; return;}
$teammembersR = mysql_fetch_array($teammembersQ);
$teamID = $teammembersR["teamID"];
// RESULT: enter
if (isset($_POST['enter'])) {
	$status = sql_quote($_POST['status']);
	$score1 = sql_quote($_POST['score1']);
	$score2 = sql_quote($_POST['score2']);
	$lastedit = time();
	// CHECK: opponent
	if ($teamID == $opponent1) {$confirmed1 = '1'; $confirmed2 = '0';}
	if ($teamID == $opponent2) {$confirmed1 = '0'; $confirmed2 = '1';}
	// CHECK: score
	if ($status == "canceled") {$score1 = "canceled"; $score2 = "canceled";}
	if ($status == "noshow1") {$score1 = "noshow1"; $score2 = "noshow1";}
	if ($status == "noshow2") {$score1 = "noshow2"; $score2 = "noshow2";}
	mysql_query("UPDATE matches SET score1 = '$score1', score2 = '$score2', confirmed1 = '$confirmed1', confirmed2 = '$confirmed2', lastedit = '$lastedit'
	WHERE matchID = '$matchID'", $connection);
	redirectto("index.php?site=matches"); exit;
}
// RESULT: edit
if (isset($_POST['edit'])) {
	$status = sql_quote($_POST['status']);
	$score1 = sql_quote($_POST['score1']);
	$score2 = sql_quote($_POST['score2']);
	$lastedit = time();
	// CHECK: opponent
	if ($teamID == $opponent1) {$confirmed1 = '1'; $confirmed2 = '0';}
	if ($teamID == $opponent2) {$confirmed1 = '0'; $confirmed2 = '1';}
	// CHECK: score
	if ($status == "canceled") {$score1 = "canceled"; $score2 = "canceled";}
	if ($status == "noshow1") {$score1 = "noshow1"; $score2 = "noshow1";}
	if ($status == "noshow2") {$score1 = "noshow2"; $score2 = "noshow2";}
	mysql_query("UPDATE matches SET score1 = '$score1', score2 = '$score2', confirmed1 = '$confirmed1', confirmed2 = '$confirmed2', lastedit = '$lastedit' 
	WHERE matchID = '$matchID'", $connection);
	redirectto("index.php?site=matches"); exit;
}
// RESULT: accept
if (isset($_POST['accept'])) {
	$status = sql_quote($_POST['status']);
	// CHECK: opponent
	if ($status == "correct") {
		// QUERY: match
		$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection);
		$matchR = mysql_fetch_array($matchQ);
		$ladderID = $matchR["ladderID"];
		$opponent1 = $matchR["opponent1"];
		$opponent2 = $matchR["opponent2"];
		$score1 = $matchR["score1"];
		$score2 = $matchR["score2"];
		// QUERY: ladder 
		$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection); $ladderR = mysql_fetch_array($ladderQ); $ratingsystem = $ladderR["ratingsystem"];
		// TEAM POINTS
		$points1Q = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND teamID = '$opponent1'", $connection); $points1R = mysql_fetch_array($points1Q);
		$points1 = $points1R["points"];
		$points2Q = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND teamID = '$opponent2'", $connection); $points2R = mysql_fetch_array($points2Q);
		$points2 = $points2R["points"];
		$time = time();
		// POINTS CALCULATION
		if ($ratingsystem == "elo") {require_once("includes/ratings/eloformula.php");} //elo formula for calculating rateing}
		if ($ratingsystem == "entish") {require_once("includes/ratings/formular.php");} //elo formula for calculating rateing}
		// QUERY
		mysql_query("UPDATE matches SET confirmed1 = '1', confirmed2 = '1', points1 = '$elo1', points2 = '$elo2', acceptdate = '$time' WHERE matchID = '$matchID'", $connection);
		mysql_query("UPDATE ladderteams SET points = '$newpoints1' WHERE teamID = '$opponent1' AND ladderID = '$ladderID'", $connection);
		mysql_query("UPDATE ladderteams SET points = '$newpoints2' WHERE teamID = '$opponent2' AND ladderID = '$ladderID'", $connection);
		redirectto("index.php?site=matches"); exit;
	}
	if ($status == "notcorrect") {
		// QUERY: match
		$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection);
		$matchR = mysql_fetch_array($matchQ);
		$opponent1 = $matchR["opponent1"];
		$opponent2 = $matchR["opponent2"];
		$score1 = $matchR["score1"];
		$score2 = $matchR["score2"];
		// QUERY: opponent1
		$opponent1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $opponent1R = mysql_fetch_array($opponent1Q); 
		$opponent1name = $opponent1R["name"];
		// QUERY: opponent2
		$opponent2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $opponent2R = mysql_fetch_array($opponent2Q); 
		$opponent2name = $opponent2R["name"];
		// CHECK: score
		if ($score1 == "canceled" && $score2 == "canceled") {$selected1 = "selected='selected'";}
		if ($score1 == "noshow1" && $score2 == "noshow1") {$selected2 = "selected='selected'";}
		if ($score1 == "noshow2" && $score2 == "noshow2") {$selected3 = "selected='selected'";}
		if (is_numeric($score1) && is_numeric($score2)) {$selected4 = "selected='selected'";}
		eval ("\$resultEDIT = \"".gettemplate("resultEDIT")."\";");
		echo $resultEDIT;
	}
	if ($status == "conflict") {
		// QUERY: match
		$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection);
		$matchR = mysql_fetch_array($matchQ);
		$opponent1 = $matchR["opponent1"];
		$opponent2 = $matchR["opponent2"];
		$challengedate = $matchR["challengedate"];
		$conflictdate = $challengedate + (1 * 24 * 60 * 60);
		$datenow = time();
		$before = date("d.m.Y - H:i", $conflictdate);
		//if ($datenow <= $conflictdate) {echo "<h4>&#8226; You canot start conflict before $before! Until then try to solve problem with your opponent.</h4>"; return;}
		// QUERY: opponent1
		$opponent1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $opponent1R = mysql_fetch_array($opponent1Q); 
		$opponent1name = $opponent1R["name"];
		// QUERY: opponent2
		$opponent2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $opponent2R = mysql_fetch_array($opponent2Q); 
		$opponent2name = $opponent2R["name"];
		// SCORE
		$score1 = $matchR["score1"];
		$score2 = $matchR["score2"];
		eval ("\$resultCONFLICT = \"".gettemplate("resultCONFLICT")."\";");
		echo $resultCONFLICT;
	}
}
// RESULT: conflict
if (isset($_POST['conflict'])) {
	$status = sql_quote($_POST['status']);
	$claim1 = sql_quote($_POST['claim1']);
	$claim2 = sql_quote($_POST['claim2']);
	$content = sql_quote($_POST['content']);
	$time = time();
	// QUERY: match
	$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection);
	$matchR = mysql_fetch_array($matchQ);
	$opponent1 = $matchR["opponent1"];
	$opponent2 = $matchR["opponent2"];
	$ladderID = $matchR["ladderID"];
	// CHECK: who submited
	if ($teamID == $opponent1) {$startedby = $opponent1;}
	if ($teamID == $opponent2) {$startedby = $opponent2;}
	// CHECK: score
	if ($status == "canceled") {$claim1 = "canceled"; $claim2 = "canceled";}
	if ($status == "noshow1") {$claim1 = "noshow1"; $claim2 = "noshow1";}
	if ($status == "noshow2") {$claim1 = "noshow2"; $claim2 = "noshow2";}
	// UPDATE: matches
	mysql_query("UPDATE matches SET confirmed1 = '1', confirmed2 = '1' WHERE matchID = '$matchID'", $connection);
	// INSERT: conflict (SATUS = 0 - open , 1 - taken by admin, 2 - solved)
	mysql_query("INSERT INTO conflict (matchID, ladderID, status, opponent1, opponent2, claim1, claim2, content, startedby, date)
	VALUES ('$matchID', '$ladderID', '0', '$opponent1', '$opponent2', '$claim1', '$claim2', '$content', '$startedby', '$time')", $connection);
	redirectto("index.php?site=matches"); exit;				
}
?>