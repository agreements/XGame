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
$conflictID = sql_quote($_POST['conflictID']);
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
// ACTION: solveconflict
if (isset($_POST['solveconflict'])) {
	$matchID = sql_quote($_POST['matchID']);
	$conflictID = sql_quote($_POST['conflictID']);
	$status = sql_quote($_POST['status']);
	$score1 = sql_quote($_POST['score1']);
	$score2 = sql_quote($_POST['score2']);
	$verdict = sql_quote(br2nl($_POST['content']));
	// QUERY: match
	$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection);
	$matchR = mysql_fetch_array($matchQ);
	$ladderID = $matchR["ladderID"];
	$opponent1 = $matchR["opponent1"];
	$opponent2 = $matchR["opponent2"];
	// QUERY: ladder 
	$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection); $ladderR = mysql_fetch_array($ladderQ); $ratingsystem = $ladderR["ratingsystem"];
	// TEAM POINTS
	$points1Q = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND teamID = '$opponent1'", $connection);$points1R = mysql_fetch_array($points1Q);
	$points1 = $points1R["points"];
	$points2Q = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND teamID = '$opponent2'", $connection);$points2R = mysql_fetch_array($points2Q);
	$points2 = $points2R["points"];
	$time = time();
	// POINTS CALCULATION
	if ($ratingsystem == "elo") {require_once("includes/ratings/eloformula.php");} //elo formula for calculating rating}
	if ($ratingsystem == "entish") {require_once("includes/ratings/formular.php");} //elo formula for calculating rating}
	// QUERY: update
	mysql_query("UPDATE matches SET confirmed1 = '1', confirmed2 = '1', score1 = '$score1', score2 = '$score2', points1 = '$elo1', points2 = '$elo2', confirmdate = '$time' 
	WHERE matchID = '$matchID'", $connection);
	mysql_query("UPDATE ladderteams SET points = '$newpoints1' WHERE teamID = '$opponent1' AND ladderID = '$ladderID'", $connection);
	mysql_query("UPDATE ladderteams SET points = '$newpoints2' WHERE teamID = '$opponent2' AND ladderID = '$ladderID'", $connection);
	mysql_query("UPDATE conflict SET status = '1', verdict1 = '$score1', verdict2 = '$score2', adminverdict = '$verdict', adminID = '$pid', solveddate = '$time' 
	WHERE conflictID = '$conflictID'", $connection);
	redirectto("index.php?site=conflicts"); exit;
}
// QUERY: conflict
$conflictQ = mysql_query("SELECT * FROM conflict WHERE conflictID = '$conflictID'", $connection);
while ($conflictR = mysql_fetch_array($conflictQ)) {
	$matchID = $conflictR["matchID"];
	$ladderID = $conflictR["ladderID"];
	$opponent1 = $conflictR["opponent1"];
	$opponent2 = $conflictR["opponent2"];
	$claim1 = $conflictR["claim1"];
	$claim2 = $conflictR["claim2"];
	$description = $conflictR["content"];
	// QUERY: match
	$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection);
	if (mysql_num_rows($matchQ) == '0') {echo "<h4>&#8226; There is no match with that ID!</h4>"; return;}
	$matchR = mysql_fetch_array($matchQ);
	$map1 = $matchR["map1"];
	$map2 = $matchR["map2"];
	$date = date("d.m.Y H:i", $matchR[date]);
	$serverIP = $matchR["serverIP"];
	$serverPASS = $matchR["serverPASS"];
	$score1 = $matchR["score1"];
	$score2 = $matchR["score2"];
	// QUERY: ladder
	$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection); $ladderR = mysql_fetch_array($ladderQ);
	$ladderID = $ladderR["ladderID"];
	$ladderTITLE = $ladderR["title"];
	$gamemode = $ladderR["gamemode"];
	$gameID = $ladderR["game"];
	// QUERY: opponent1
	$opponent1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $opponent1R = mysql_fetch_array($opponent1Q); $opponent1name = $opponent1R["name"];
	// QUERY: opponent2
	$opponent2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $opponent2R = mysql_fetch_array($opponent2Q); $opponent2name = $opponent2R["name"];
	// QUERY: game
	$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$gameID'", $connection);$gameR = mysql_fetch_array($gameQ);$gameTITLE = $gameR["title"];
	// QUERY: map1
	$map1Q = mysql_query("SELECT * FROM maps WHERE mapID = '$map1'", $connection); $map1R = mysql_fetch_array($map1Q); $map1TITLE = $map1R["title"];
	// QUERY: map2
	$map2Q = mysql_query("SELECT * FROM maps WHERE mapID = '$map2'", $connection); $map2R = mysql_fetch_array($map2Q); $map2TITLE = $map2R["title"];
	// SCORE OUTPUT
	$score = $score1." - ".$score2;
	if ($score1 == "noshow1" && $score2 == "noshow1") {$score = $opponent1name." didnt show up";}
	if ($score1 == "noshow2" && $score2 == "noshow2") {$score = $opponent2name." didnt show up";}
	if ($score1 == "canceled" && $score2 == "canceled") {$score = "match is canceled";}
	$claim = $claim1." - ".$claim2;
	if ($claim1 == "noshow1" && $claim2 == "noshow1") {$claim = $opponent1name." didnt show up";}
	if ($claim1 == "noshow2" && $claim2 == "noshow2") {$claim = $opponent2name." didnt show up";}
	if ($claim1 == "canceled" && $claim1 == "canceled") {$claim = "match is canceled";}
	// QUERY: gameadminsQ
	$gameadminsQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid' AND gameID ='$gameID'", $connection);
	while ($gameadminsR = mysql_fetch_array($gameadminsQ)) {
		// OUTPUT: conflicts
		eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
		eval ("\$conflictedit = \"".gettemplate("conflictedit")."\";");
		echo $conflictedit;
	}
}
	

?>