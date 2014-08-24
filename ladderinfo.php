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
$ladderID = sql_quote($_GET['ladderID']);
$action = sql_quote($_GET['action']);
// SITE PAGE CONTROL
if (isset($_GET['action'])) {$action = sql_quote($_GET['action']);} else {$action = 'ranking';}
// QUERY: ladder 
$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection);
if (mysql_num_rows($ladderQ) == '0') {echo "<h4>&#8226; Wrong ladderID</h4>"; return;}
$ladderR = mysql_fetch_array($ladderQ);
$ladderID = $ladderR["ladderID"];
$ladderTITLE = $ladderR["title"];
$date = date("d.m.Y", $ladderR[date]);
$gamemode = $ladderR["gamemode"];
$game = $ladderR["game"];
// QUERY: game
$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$game'", $connection); $gameR = mysql_fetch_array($gameQ); $gameTITLE = $gameR["title"]; $pic = $gameR["pic"];
// JOIN BTN
if (!empty($pid)) {$joinBT = "<li><a href='index.php?site=ladderinfo&amp;ladderID=$ladderID&amp;action=join' title='DHTML Forums'>JOIN</a></li>";} else {$joinBT = "";}
// OUTPUT
eval ("\$ladderinfo = \"".gettemplate("ladderinfo")."\";");
echo $ladderinfo;

// ACTION: joinladder
if (isset($_POST['joinladder'])) {
	//CHECK: auth
	if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
	// GET: variables
	$ladderID = sql_quote($_POST['ladderID']);
	$teamID = sql_quote($_POST['teamIDlist']);
	$date = time();
	// CHECK: if exist
	$ladderQ = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND teamID = '$teamID'", $connection);
	if (mysql_num_rows($ladderQ) == '0') {
		$joinLADDER = mysql_query("INSERT INTO ladderteams (ladderID, teamID, joined, accepted, points) VALUES ('$ladderID', '$teamID', '$date', '0', '1000')", $connection);
		// REDDIRECT
		redirectto("index.php?site=ladderinfo&amp;ladderID=$ladderID&amp;action=join"); exit;
	}
	else {echo "<h4>This team is already joined to a ladder.</li><meta http-equiv='refresh' content='2;URL=index.php?site=ladders'></h4>"; return;}
} 

// ACTION: rules
if ($action == "rules") {
	$rules = showBBcodes($ladderR["rules"]);
	// OUTPUT: subRULES
	eval ("\$subRULES = \"".gettemplate("subRULES")."\";"); 
	echo $subRULES;
}

// ACTION: maps
if ($action == "maplist") {
	// OUTPUT: subMAPS
	eval ("\$subMAPS = \"".gettemplate("subMAPS")."\";"); 
	echo $subMAPS;
	$mapQ = mysql_query("SELECT * FROM maps WHERE ladderID = '$ladderID'", $connection);
	while ($mapR = mysql_fetch_array($mapQ)) {
		$mapTITLE = $mapR["title"];
		// LIST: subMAPS
		eval ("\$subMAPS = \"".getlist("subMAPS")."\";"); echo $subMAPS;
	}
}

// PAGES
$rowsPerPage = $settingsR["rankingPP"]; // how many rows to show per page
$pageNum = 1; // by default we show first page
if(isset($_GET['page'])) {$pageNum = $_GET['page'];} // if $_GET['page'] defined, use it as page number
$offset = ($pageNum - 1) * $rowsPerPage; // counting the offset
$result = mysql_query("SELECT COUNT(id) AS numrows FROM ladderteams WHERE ladderID = '$ladderID' AND accepted = '1'", $connection);
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$numrows = $row['numrows']; // how many rows we have in database
// ACTION: ranking
if ($action == "ranking") {
	// OUTPUT: subRANKING
	eval ("\$subRANKING = \"".gettemplate("subRANKING")."\";"); 
	echo $subRANKING;
	$rankQ = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND accepted = '1' ORDER BY points DESC LIMIT $offset, $rowsPerPage", $connection);
	$rank =  ($pageNum - 1) * $rowsPerPage + 1;
	while ($rankR = mysql_fetch_array($rankQ)) {
		$teamID = $rankR["teamID"];
		$points = $rankR["points"];
		// QUERY: teams 
		$teamsQ = mysql_query("SELECT * FROM teams WHERE teamID = '$teamID'", $connection);
		$teamsR = mysql_fetch_array($teamsQ);
		$teamNAME = $teamsR["name"];
		$teamTAG = $teamsR["tag"];
		$adminID = $teamsR["adminID"];
// QUERY: lose
$winsQ = mysql_query("SELECT * FROM matches WHERE ((opponent1 = '$teamID' AND score1 > score2) OR (opponent2 = '$teamID' AND score1 < score2)) AND ladderID = '$ladderID'", $connection);
$win = mysql_num_rows($winsQ);
// QUERY: lose
$loseQ = mysql_query("SELECT * FROM matches WHERE ((opponent1 = '$teamID' AND score1 < score2) OR (opponent2 = '$teamID' AND score1 > score2)) AND ladderID = '$ladderID'", $connection);
$lose = mysql_num_rows($loseQ);
// QUERY: draw
$drawQ = mysql_query("SELECT * FROM matches WHERE ((opponent1 = '$teamID' AND score1 = score2) OR (opponent2 = '$teamID' AND score1 = score2)) AND ladderID = '$ladderID' 
AND score1 REGEXP '^-?[0-9]+$'", $connection); $draw = mysql_num_rows($drawQ);
// QUERY: ch button
$teammembers1Q = mysql_query("SELECT * FROM teammembers WHERE teamID = '$teamID' AND pid = '$pid' AND rights > '1'", $connection);
if (mysql_num_rows($teammembers1Q) == '0') {
	$chB = "<a href='index.php?site=challange&amp;opponentID=$teamID&amp;ladderID=$ladderID'><img src='./images/icons/challange.png' width='16' height='16' /></a>";
} else {
	$chB = "";
}
// QUERY: acitivity
$lastmatch = strtotime("- 1 month");
$activityQ = mysql_query("SELECT * FROM matches WHERE (opponent1 = '$teamID' OR opponent2 = '$teamID') AND ladderID = '$ladderID' AND date > '$lastmatch'", $connection); 
$matchesNUM = mysql_num_rows($activityQ);
if ($matchesNUM < 3) {$graph = "<img src='./images/icons/graph1.gif' width='13' height='8' />";}
if ($matchesNUM > 2 && $matchesNUM < 5) {$graph = "<img src='./images/icons/graph2.gif' width='13' height='8' />";}
if ($matchesNUM > 4 && $matchesNUM < 7) {$graph = "<img src='./images/icons/graph3.gif' width='13' height='8' />";}
if ($matchesNUM > 6 && $matchesNUM < 9) {$graph = "<img src='./images/icons/graph4.gif' width='13' height='8' />";}
if ($matchesNUM > 8 && $matchesNUM < 11) {$graph = "<img src='./images/icons/graph5.gif' width='13' height='8' />";}
if ($matchesNUM > 10 && $matchesNUM < 13) {$graph = "<img src='./images/icons/graph6.gif' width='13' height='8' />";}
if ($matchesNUM > 12) {$graph = "<img src='./images/icons/graph7.gif' width='13' height='8' />";}
		// LIST: subRANKING
		eval ("\$subRANKING = \"".getlist("subRANKING")."\";"); echo $subRANKING;
		$rank++;
	}
	// INCLUDE PAGES
	include("pages/rankingpages.php");
}

// ACTION: ranking
if ($action == "join") {
	// QUERY: team member
	$teammemberQ = mysql_query("SELECT * FROM teammembers WHERE pid = '$pid' AND accepted = '1' AND rights > '1'", $connection);
	if (mysql_num_rows($teammemberQ) == '0') {echo "<h4>&#8226; Please create team first!</h4>"; return;}
	while ($teammemberR = mysql_fetch_array($teammemberQ)) {
		$teamID = $teammemberR["teamID"];
		// QUERY: team
		$teamQ = mysql_query("SELECT * FROM teams WHERE teamID = '$teamID'", $connection);
		$teamR = mysql_fetch_array($teamQ);
		$teamIDlist = $teamR["teamID"];
		$teamNAME = $teamR["name"];
		// CHECK: if team is joined
		$checkQ = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND teamID = '$teamIDlist'", $connection);
		if (mysql_num_rows($checkQ) == '0') {$teamLIST .= "<option value='$teamIDlist'>$teamNAME</option>";}
	}
	if (!isset($teamLIST)) {echo "<h4>&#8226; Your team is competing in this ladder!</h4>"; return;}
	eval ("\$ladderjoin = \"".gettemplate("ladderjoin")."\";");
	echo $ladderjoin;
}
?>