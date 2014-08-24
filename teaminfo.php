<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// GET: variables
$pid = $_SESSION['pid'];
$teamID = sql_quote($_GET['teamID']);
$action = sql_quote($_GET['action']);
// SITE PAGE CONTROL
if (isset($_GET['action'])) {$action = sql_quote($_GET['action']);} else {$action = 'members';}
// QUERY: teams
$teamsQ = mysql_query("SELECT * FROM teams WHERE teamID = '$teamID'", $connection);
if (mysql_num_rows($teamsQ) == '0') {echo "<h4>&#8226; There is no teams with that ID!</h4>"; return;}
$teamsR = mysql_fetch_array($teamsQ);
$teamNAME = $teamsR["name"];
$date = date("d.m.Y", $teamsR[date]);
$teamTAG = $teamsR["tag"];
$web = $teamsR["web"];
// SET: buttons
$adminQ = mysql_query("SELECT * FROM teams WHERE teamID = '$teamID' AND adminID = '$pid'", $connection);
if (mysql_num_rows($adminQ) == '1') {
	$editB = "<FORM><INPUT type='button' value='edit' onClick=\"window.location='index.php?site=teamedit&teamID=$teamID'\"></FORM>";
	$addB = "<FORM><INPUT type='button' value='edit members' onClick=\"window.location='index.php?site=teammembers&teamID=$teamID'\"></FORM>";
} else {
	$editB = "";  $addB = "";
}
// QUERY: rating
$ratingQ = mysql_query("SELECT * FROM teamrating WHERE teamID = '$teamID'", $connection);
$ratingROWS = mysql_num_rows($ratingQ);
$rating = 0;
while ($ratingR = mysql_fetch_array($ratingQ)) {$rating += $ratingR["rating"];}
$ratingVALUE = $rating / $ratingROWS;
$ratingNUM =  round($ratingVALUE, 0);
$ratingIMG = "./images/icons/level".$ratingNUM.".jpg";
// OUTPUT: teaminfo
eval ("\$teaminfo = \"".gettemplate("teaminfo")."\";");
echo $teaminfo;
// ACTION: members
if ($action == "members") {
	// QUERY: team members
	$membersQ = mysql_query("SELECT * FROM teammembers WHERE teamID = '$teamID' AND accepted = '1'", $connection);
	// OUTPUT: subTEAMMEMBERS
	eval ("\$subTEAMMEMBERS = \"".gettemplate("subTEAMMEMBERS")."\";");
	echo $subTEAMMEMBERS;
	while ($membersR = mysql_fetch_array($membersQ)) {
		$memberID = $membersR["pid"];
		$joined = date("d.m.Y", $membersR[joined]);
		// QUERY: users
		$memberQ = mysql_query("SELECT * FROM users WHERE pid = '$memberID'", $connection); $memberR = mysql_fetch_array($memberQ); $memberNAME = $memberR["nickname"];
		// LIST: subTEAMMEMBERS
		eval ("\$subTEAMMEMBERS = \"".getlist("subTEAMMEMBERS")."\";"); echo $subTEAMMEMBERS;
	}
}
// ACTION: ladders
if ($action == "ladders") {
	// QUERY: team ladders
	$ladderteamsQ = mysql_query("SELECT * FROM ladderteams WHERE teamID = '$teamID' AND accepted = '1'", $connection);
	// OUTPUT: subTEAMLADDERS
	eval ("\$subTEAMLADDERS = \"".gettemplate("subTEAMLADDERS")."\";");
	echo $subTEAMLADDERS;
	while ($ladderteamsR = mysql_fetch_array($ladderteamsQ)) {
		$ladderID = $ladderteamsR["ladderID"];
		$joined = date("d.m.Y", $ladderteamsR["joined"]);
		$points = $ladderteamsR["points"];
		// QUERY: ladder
		$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection); $ladderR = mysql_fetch_array($ladderQ);
		$ladderTITLE = $ladderR["title"];
		$game = $ladderR["game"];
		// QUERY: game
		$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$game'", $connection);
		$gameR = mysql_fetch_array($gameQ);
		$gameTITLE = $gameR["title"];
		$teammembers1Q = mysql_query("SELECT * FROM teammembers WHERE teamID = '$teamID' AND pid = '$pid' AND rights > '1'", $connection);
		if (mysql_num_rows($teammembers1Q) == '0') {
			$chB = "<a href='index.php?site=challange&amp;opponentID=$teamID&amp;ladderID=$ladderID'><img src='./images/icons/challange.png' width='16' height='16' /></a>";
		} else {
			$chB = "";
		}
		// LIST: subTEAMMEMBERS
		eval ("\$subTEAMLADDERS = \"".getlist("subTEAMLADDERS")."\";"); echo $subTEAMLADDERS;
	}
}

// ACTION: laddermatches
if ($action == "laddermatches") {
	$ladderID = sql_quote($_GET['ladderID']);
	// QUERY: matches
	$matchQ = mysql_query("SELECT * FROM matches WHERE (confirmed1 = '1' && confirmed2 = '1') AND (opponent1 = '$teamID' OR opponent2 = '$teamID') 
	AND accepted1 ='1' AND accepted2 = '1' AND ladderID = '$ladderID'", $connection);
	if (mysql_num_rows($matchQ) == '0') {echo "<div id='list'><h4>&#8226; There is no matches played in this ladder!</h4></div>"; return;}
	// QUERY: ladder
	$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection); $ladderR = mysql_fetch_array($ladderQ);
	$ladderID = $ladderR["ladderID"];
	$ladderTITLE = $ladderR["title"];
	$gamemode = $ladderR["gamemode"];
	$gameID = $ladderR["game"];
	// OUTPUT: matches
	eval ("\$subLADDERMATCHES = \"".gettemplate("subLADDERMATCHES")."\";");
	echo $subLADDERMATCHES;
	while ($matchR = mysql_fetch_array($matchQ)) {
		$matchID = $matchR["matchID"];
		$opponent1 = $matchR["opponent1"];
		$opponent2 = $matchR["opponent2"];
		$map1 = $matchR["map1"];
		$map2 = $matchR["map2"];
		$score1 = $matchR["score1"];
		$score2 = $matchR["score2"];
		$date = date("d.m.Y H:i", $matchR[date]);
		// QUERY: opponent1
		$opponent1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $opponent1R = mysql_fetch_array($opponent1Q); $opponent1name = $opponent1R["name"];
		// QUERY: opponent2
		$opponent2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $opponent2R = mysql_fetch_array($opponent2Q); $opponent2name = $opponent2R["name"];
		// QUERY: map1
		$map1Q = mysql_query("SELECT * FROM maps WHERE mapID = '$map1'", $connection); $map1R = mysql_fetch_array($map1Q); $map1TITLE = $map1R["title"];
		// QUERY: map2
		$map2Q = mysql_query("SELECT * FROM maps WHERE mapID = '$map2'", $connection); $map2R = mysql_fetch_array($map2Q); $map2TITLE = $map2R["title"];
		// CHECK: opponent
		if ($teamID == $opponent1) {
			$opponentNAME = $opponent2name;
			$opponentID = $opponent2;
			$points = $matchR["points1"];
			if ($score1 > $score2) {$bgcolor = "5bc91b";} // GREEN
			if ($score1 < $score2) {$bgcolor = "CC0000";} // RED
			if ($score1 == $score2) {$bgcolor = "666666";} // GRAY
		}
		if ($teamID == $opponent2) {
			$opponentNAME = $opponent1name;
			$opponentID = $opponent1;
			$points = $matchR["points2"];
			if ($score2 > $score1) {$bgcolor = "5bc91b";} // GREEN
			if ($score2 < $score1) {$bgcolor = "CC0000";} // RED
			if ($score2 == $score1) {$bgcolor = "666666";} // GRAY
		}
		// SCORE: output
		$score = $score1." - ".$score2;
		if ($score1 == "noshow1" && $score2 == "noshow1") {$score = $opponent1name." (no show)";}
		if ($score1 == "noshow2" && $score2 == "noshow2") {$score = $opponent2name." (no show)";}
		if ($score1 == "canceled" && $score2 == "canceled") {$score = "match is canceled";}
		// QUERY: game
		$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$gameID'", $connection); $gameR = mysql_fetch_array($gameQ); $gameTITLE = $gameR["title"];
		// CHECK: conflict
		$conflictQ = mysql_query("SELECT * FROM conflict WHERE status = '0' AND (opponent1 = '$teamID' OR opponent2 = '$teamID') AND matchID = '$matchID'", $connection);
		if (mysql_num_rows($conflictQ) != '0') {$points = "<h4>conflict</h4>";}
		// LIST: subTEAMMEMBERS
		eval ("\$subLADDERMATCHES = \"".getlist("subLADDERMATCHES")."\";"); echo $subLADDERMATCHES;
	}
}

// ACTION: last 20 matches
if ($action == "lastmatches") {
	// QUERY: matches
	$matchQ = mysql_query("SELECT * FROM matches WHERE (confirmed1 = '1' && confirmed2 = '1') AND (opponent1 = '$teamID' OR opponent2 = '$teamID')
	AND accepted1 ='1' AND accepted2 = '1'", $connection);
	if (mysql_num_rows($matchQ) == '0') {echo "<div id='list'><h4>&#8226; There is no matches played!</h4></div>"; return;}
	// OUTPUT: matches
	eval ("\$subLASTMATCHES = \"".gettemplate("subLASTMATCHES")."\";");
	echo $subLASTMATCHES;
	while ($matchR = mysql_fetch_array($matchQ)) {
		$matchID = $matchR["matchID"];
		$opponent1 = $matchR["opponent1"];
		$opponent2 = $matchR["opponent2"];
		$map1 = $matchR["map1"];
		$map2 = $matchR["map2"];
		$score1 = $matchR["score1"];
		$score2 = $matchR["score2"];
		$date = date("d.m.Y H:i", $matchR[date]);
		// QUERY: opponent1
		$opponent1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $opponent1R = mysql_fetch_array($opponent1Q); $opponent1name = $opponent1R["name"];
		// QUERY: opponent2
		$opponent2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $opponent2R = mysql_fetch_array($opponent2Q); $opponent2name = $opponent2R["name"];
		// QUERY: map1
		$map1Q = mysql_query("SELECT * FROM maps WHERE mapID = '$map1'", $connection); $map1R = mysql_fetch_array($map1Q); $map1TITLE = $map1R["title"];
		// QUERY: map2
		$map2Q = mysql_query("SELECT * FROM maps WHERE mapID = '$map2'", $connection); $map2R = mysql_fetch_array($map2Q); $map2TITLE = $map2R["title"];
		// CHECK: opponent
		if ($teamID == $opponent1) {
			$opponentNAME = $opponent2name;
			$opponentID = $opponent2;
			$points = $matchR["points1"];
			if ($score1 > $score2) {$bgcolor = "5bc91b";} // GREEN
			if ($score1 < $score2) {$bgcolor = "CC0000";} // RED
			if ($score1 == $score2) {$bgcolor = "666666";} // GRAY
		}
		if ($teamID == $opponent2) {
			$opponentNAME = $opponent1name;
			$opponentID = $opponent1;
			$points = $matchR["points2"];
			if ($score2 > $score1) {$bgcolor = "5bc91b";} // GREEN
			if ($score2 < $score1) {$bgcolor = "CC0000";} // RED
			if ($score2 == $score1) {$bgcolor = "666666";} // GRAY
		}
		// SCORE: output
		$score = $score1." - ".$score2;
		if ($score1 == "noshow1" && $score2 == "noshow1") {$score = $opponent1name." (no show)";}
		if ($score1 == "noshow2" && $score2 == "noshow2") {$score = $opponent2name." (no show)";}
		if ($score1 == "canceled" && $score2 == "canceled") {$score = "match is canceled";}
		// LIST: subTEAMMEMBERS
		eval ("\$subLASTMATCHES = \"".getlist("subLASTMATCHES")."\";"); echo $subLASTMATCHES;
	}
}
?>