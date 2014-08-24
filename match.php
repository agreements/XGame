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
$matchID = sql_quote($_GET['matchID']);
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
// IS CONFLICT
$conflictQ = mysql_query("SELECT * FROM conflict WHERE status = '0' AND matchID = '$matchID'", $connection);
if (mysql_num_rows($conflictQ) != '0') {$conflictS = "This match is in conflict.";}
// CHECK: if is admin of clans
$teammembersQ = mysql_query("SELECT * FROM teammembers WHERE (teamID = '$opponent1' OR teamID = '$opponent2') AND pid = '$pid' AND rights > '2'", $connection);
if (mysql_num_rows($teammembersQ) != '0') {$btnREPORT = "<button type='button' onclick=\"window.location='index.php?site=matchreport&matchID=$matchID'\">match report</button>";}

// OUTPUT: matches
eval ("\$match = \"".gettemplate("match")."\";");
echo $match;
$conflictQ = mysql_query("SELECT * FROM conflict WHERE status = '1' AND matchID = '$matchID'", $connection);
if (mysql_num_rows($conflictQ) != '0') {
	$conflictR = mysql_fetch_array($conflictQ);
	$adminverdict = showBBcodes("[QUOTE]".$conflictR["adminverdict"]."[/QUOTE]");
	$startedby = $conflictR["startedby"];
	$claim1 = $conflictR["claim1"];
	$claim2 = $conflictR["claim2"];
	$solvedby = $conflictR["adminID"];
	// SCORE OUTPUT
	$claim = $claim1." - ".$claim2;
	if ($claim1 == "noshow1" && $claim2 == "noshow1") {$claim = $opponent1name." didnt show up";}
	if ($claim1 == "noshow2" && $claim2 == "noshow2") {$claim = $opponent2name." didnt show up";}
	if ($claim1 == "canceled" && $claim2 == "canceled") {$claim = "match is canceled";}
	// QUERY: startedbyQ
	$startedbyQ = mysql_query("SELECT * FROM users WHERE pid = '$startedby'", $connection); $startedbyR = mysql_fetch_array($startedbyQ); $startedBY = $startedbyR["nickname"];
	// QUERY: admin
	$solvedbyQ = mysql_query("SELECT * FROM users WHERE pid = '$solvedby'", $connection); $solvedbyR = mysql_fetch_array($solvedbyQ); $solvedBY = $solvedbyR["nickname"];
	// OUTPUT: matches
	eval ("\$matchconflict = \"".gettemplate("matchconflict")."\";");
	echo $matchconflict;
}
// OUTPUT: newcomment
$section = "matches"; // coment section
if(isset($pid)) {
	$id = $matchID; // ID
	eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
	eval ("\$commentnew = \"".gettemplate("commentnew")."\";");
	echo $commentnew;
}
$commentsQ = mysql_query("SELECT * FROM comments WHERE section = '$section' AND id = '$matchID' ORDER BY commentID ASC", $connection);
// OUTPUT: comments
if (mysql_num_rows($commentsQ) != '0') {
	eval ("\$comments = \"".gettemplate("comments")."\";");
	echo $comments;
}
while ($commentsR = mysql_fetch_array($commentsQ)) {
	$commentID = $commentsR["commentID"];
	$content = showBBcodes($commentsR["content"]);
	$memberID = $commentsR["pid"];
	$date = date("d.m.Y H:i", $commentsR[date]);
	// DELETE BUTTON
	if ($memberID == $pid) {$deleteB = "<input type='submit' name='delete' id='delete' value='delte'>";} else {$deleteB = "";}
	// QUERY: users
	$memberQ = mysql_query("SELECT * FROM users WHERE pid = '$memberID'", $connection); $memberR = mysql_fetch_array($memberQ); $memberNAME = $memberR["nickname"];
	// LIST: matchcomments
	eval ("\$matchcomments = \"".getlist("matchcomments")."\";"); echo $matchcomments;
}
?>