<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// CHECK: auth
$pid = $_SESSION['pid'];
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
// QUERY: gameadmins
$gameadminsQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid'", $connection);
if (mysql_num_rows($gameadminsQ) == '0') {echo "<h4>&#8226; You cant acces this area!</h4>"; return;}
// POST: edit
if (isset($_POST['edit'])) {
	$matchID = sql_quote($_POST['matchID']);
	// QUERY: match
	$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection);
	if (mysql_num_rows($matchQ) == '0') {echo "<h4>&#8226; There is no match with that ID!</h4>"; return;}
	$matchR = mysql_fetch_array($matchQ);
	$matchID = $matchR["matchID"];
	$ladderID = $matchR["ladderID"];
	$opponent1 = $matchR["opponent1"];
	$opponent2 = $matchR["opponent2"];
	$map1 = $matchR["map1"];
	$map2 = $matchR["map2"];
	$date = date("d.m.Y H:i", $matchR[date]);
	$serverIP = $matchR["serverIP"];
	$serverPASS = $matchR["serverPASS"];
	$accepted1 = $matchR["accepted1"];
	$accepted2 = $matchR["accepted2"];
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
	// QUERY: map1
	$map1Q = mysql_query("SELECT * FROM maps WHERE mapID = '$map1'", $connection); $map1R = mysql_fetch_array($map1Q); $map1TITLE = $map1R["title"];
	// QUERY: map2
	$map2Q = mysql_query("SELECT * FROM maps WHERE mapID = '$map2'", $connection); $map2R = mysql_fetch_array($map2Q); $map2TITLE = $map2R["title"];
	// QUERY: game
	$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$gameID'", $connection); $gameR = mysql_fetch_array($gameQ); $gameTITLE = $gameR["title"];
	// IS ACCEPTED
	if ($accepted1 == '1' && $accepted2 == '0') {$acceptedS = $opponent2name." did not accept match.";}
	if ($accepted2 == '1' && $accepted1 == '0') {$acceptedS = $opponent1name." did not accept match.";}
	if ($accepted2 == '1' && $accepted1 == '1') {$acceptedS = "Match is accepted by both of teams.";}
	// SCORE OUTPUT
	$score = $score1." - ".$score2;
	if ($score1 == "noshow1" && $score2 == "noshow1") {$score = $opponent1name." didnt show up";}
	if ($score1 == "noshow2" && $score2 == "noshow2") {$score = $opponent2name." didnt show up";}
	if ($score1 == "canceled" && $score2 == "canceled") {$score = "match is canceled";}
	// CHECK: admin
	$gameadminsQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid' AND gameID ='$gameID'", $connection);
	if (mysql_num_rows($gameadminsQ) == '0') {echo "<h4>&#8226; You are not admin of this league!</h4>"; return;}
	// OUTPUT: admin
	eval ("\$editmatchID = \"".gettemplate("editmatchID")."\";"); 
	echo $editmatchID;
} elseif (isset($_POST['editmatch'])) {
	$matchID = sql_quote($_POST['matchID']);
	$status = sql_quote($_POST['status']);
	$score1 = sql_quote($_POST['score1']);
	$score2 = sql_quote($_POST['score2']);
	// QUERY: match
	$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection);
	if (mysql_num_rows($matchQ) == '0') {echo "<h4>&#8226; There is no match with that ID!</h4>"; return;}
	$matchR = mysql_fetch_array($matchQ);
	$ladderID = $matchR["ladderID"];
	$opponent1 = $matchR["opponent1"];
	$opponent2 = $matchR["opponent2"];
	$oldelo1 = $matchR["points1"];
	$oldelo2 = $matchR["points2"];
	// CHECK: score
	if ($status == "canceled") {$score1 = "canceled"; $score2 = "canceled";}
	if ($status == "noshow1") {$score1 = "noshow1"; $score2 = "noshow1";}
	if ($status == "noshow2") {$score1 = "noshow2"; $score2 = "noshow2";}
	// QUERY: ladder 
	$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection); $ladderR = mysql_fetch_array($ladderQ); $ratingsystem = $ladderR["ratingsystem"];
	// ELO 1
	$rank1Q = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND teamID = '$opponent1'", $connection); 
	$rank1R = mysql_fetch_array($rank1Q);
	$oldpoints1 = $rank1R["points"];
	$points1 = $oldpoints1 - ($oldelo1);
	// ELO 2
	$rank2Q = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND teamID = '$opponent2'", $connection); 
	$rank2R = mysql_fetch_array($rank2Q);
	$oldpoints2 = $rank2R["points"];
	$points2 = $oldpoints2 - ($oldelo2);
	// POINTS CALCULATION
	if ($ratingsystem == "elo") {require_once("includes/ratings/eloformula.php");} //elo formula for calculating rateing}
	if ($ratingsystem == "entish") {require_once("includes/ratings/formular.php");} //elo formula for calculating rateing}
	// QUERY
	mysql_query("UPDATE matches SET points1 = '$elo1', points2 = '$elo2', score1 = '$score1', score2 = '$score2' WHERE matchID = '$matchID'", $connection);
	mysql_query("UPDATE ladderteams SET points = '$newpoints1' WHERE teamID = '$opponent1' AND ladderID = '$ladderID'", $connection);
	mysql_query("UPDATE ladderteams SET points = '$newpoints2' WHERE teamID = '$opponent2' AND ladderID = '$ladderID'", $connection);
	redirectto("index.php?site=editmatch"); exit;
	//echo $oldelo1." : ".$oldelo2." old elo<br>";
	//echo $oldpoints1." : ".$oldpoints2." old points<br>";
	//echo $points1." : ".$points2." POINTS<br>";
	//echo $elo1." : ".$elo2." new elo<br>";
	//echo $newpoints1." : ".$newpoints2." new points<br>";
} else {
	// OUTPUT: admin
	eval ("\$editmatch = \"".gettemplate("editmatch")."\";"); 
	echo $editmatch;
}
?>