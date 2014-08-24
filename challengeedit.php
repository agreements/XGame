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
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
// CHALLENGE: delete - FROM NOTIFICATIONS
if (isset($_POST['delete'])) {
	$matchID = sql_quote($_POST['matchID']);
	// QUERY: matches
	$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection); $matchR = mysql_fetch_array($matchQ); $opponent1 = $matchR["opponent1"];
	// ACTION: delete
	$clanadminQ = mysql_query("SELECT * FROM teammembers WHERE teamID = '$opponent1' AND pid = '$pid' AND rights > '1'", $connection);
	if (mysql_num_rows($clanadminQ)) {mysql_query("DELETE FROM matches WHERE matchID = '$matchID'", $connection); redirectto("index.php?site=notifications"); exit;}
}
// CHALLENGE: edit - FROM NOTIFICATIONS
if (isset($_POST['edit'])) {
	$matchID = sql_quote($_POST['matchID']);
	// QUERY: matches
	$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection); 
	$matchR = mysql_fetch_array($matchQ);
	$opponent1 = $matchR["opponent1"];
	$opponent2 = $matchR["opponent2"];
	$ladderID = $matchR["ladderID"];
	$serverPASS = $matchR["serverPASS"];
	$serverIP = $matchR["serverIP"];
	$map1 = $matchR["map1"];
	$olddate = date("d.m.Y H:i", $matchR[date]);
	// QUERY: opponent1
	$opponent1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $opponent1R = mysql_fetch_array($opponent1Q); $opponent1name = $opponent1R["name"];
	// QUERY: opponent2
	$opponent2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $opponent2R = mysql_fetch_array($opponent2Q); $opponent2name = $opponent2R["name"];
	// QUERY: ladder
	$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection); $ladderR = mysql_fetch_array($ladderQ);
	$ladderID = $ladderR["ladderID"];
	$ladderTITLE = $ladderR["title"];
	$gamemode = $ladderR["gamemode"];
	$gameID = $ladderR["game"];
	// QUERY: game
	$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$gameID'", $connection); $gameR = mysql_fetch_array($gameQ); $gameTITLE = $gameR["title"];
	// QUERY: maps
	$mapQ = mysql_query("SELECT * FROM maps WHERE ladderID = '$ladderID'", $connection);
	while ($mapR = mysql_fetch_array($mapQ)) {
		$mapID = $mapR["mapID"];
		$mapTITLE = $mapR["title"];
		if ($mapID == $map1) {$selected = "selected";} else {$selected = "";}
		$mapLIST .= "<option value='$mapID' $selected>$mapTITLE</option>";
	}
	// OUTPUT: challenge edit
	eval ("\$challengeEDIT = \"".gettemplate("challengeEDIT")."\";");
	echo $challengeEDIT;
}
// CHALLENGE: accept - FROM NOTIFICATIONS
if (isset($_POST['accept'])) {
	$matchID = sql_quote($_POST['matchID']);
	// QUERY: matches
	$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection); 
	$matchR = mysql_fetch_array($matchQ);
	$opponent1 = $matchR["opponent1"];
	$opponent2 = $matchR["opponent2"];
	$ladderID = $matchR["ladderID"];
	$serverPASS = $matchR["serverPASS"];
	$serverIP = $matchR["serverIP"];
	$map1 = $matchR["map1"];
	$chdate = date("d.m.Y - H:i", $matchR[date]);
	// QUERY: map
	$oponnentmapQ = mysql_query("SELECT * FROM maps WHERE mapID = '$map1'", $connection); $oponnentmapR = mysql_fetch_array($oponnentmapQ); $opmap = $oponnentmapR["title"];
	// QUERY: opponent1
	$opponent1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $opponent1R = mysql_fetch_array($opponent1Q); $opponent1name = $opponent1R["name"];
	// QUERY: opponent2
	$opponent2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $opponent2R = mysql_fetch_array($opponent2Q); $opponent2name = $opponent2R["name"];
	// QUERY: ladder
	$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection); $ladderR = mysql_fetch_array($ladderQ);
	$ladderID = $ladderR["ladderID"];
	$ladderTITLE = $ladderR["title"];
	$gamemode = $ladderR["gamemode"];
	$game = $ladderR["game"];
	// QUERY: game
	$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$game'", $connection); $gameR = mysql_fetch_array($gameQ); $gameTITLE = $gameR["title"];
	// QUERY: maps
	$mapQ = mysql_query("SELECT * FROM maps WHERE ladderID = '$ladderID'", $connection);
	while ($mapR = mysql_fetch_array($mapQ)) {
		$mapID = $mapR["mapID"];
		$mapTITLE = $mapR["title"];
		$mapLIST .= "<option value='$mapID'>$mapTITLE</option>";
	}
	// OUTPUT: challenge edit
	eval ("\$challengeACCEPT = \"".gettemplate("challengeACCEPT")."\";");
	echo $challengeACCEPT;
}
// ACTION: editch
if (isset($_POST['editch'])) {
	$matchID = sql_quote($_POST['matchID']);
	$calendar = sql_quote($_POST['date']);
	$hours = sql_quote($_POST['hours']);
	$minutes = sql_quote($_POST['minutes']);
	$date = strtotime($calendar." ".$hours.":".$minutes);
	$time = time();
	$map = sql_quote($_POST['map']);
	$serverIP = sql_quote($_POST['serverIP']);
	$serverPASS = sql_quote($_POST['serverPASS']);
	// CHECK: date
	if ((!is_numeric($hours)) || (!is_numeric($minutes)) || ($hours < '0') || ($minutes < '0') || ($hours > '24') || ($minutes > '59')) {echo "<h4>&#8226; Wrong date!</h4>"; return;}
	if ($time > $date) {echo "<h4>&#8226; You cant challange opponent in past!</h4>"; return;}
	// QUERY: matches
	$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection); $matchR = mysql_fetch_array($matchQ); $opponent1 = $matchR["opponent1"];
	// ACTION: edit
	$clanadminQ = mysql_query("SELECT * FROM teammembers WHERE teamID = '$opponent1' AND pid = '$pid' AND rights > '1'", $connection);
	if (mysql_num_rows($clanadminQ)) {
		mysql_query("UPDATE matches SET date = '$date', challengedate = '$time', map1 = '$map', serverIP = '$serverIP', serverPASS = '$serverPASS' WHERE matchID = '$matchID'", $connection);
		redirectto("index.php?site=notifications"); exit;
	}
}
// ACTION: acceptch
if (isset($_POST['acceptch'])) {
	$matchID = sql_quote($_POST['matchID']);
	$map = sql_quote($_POST['map']);
	$date = time();
	// QUERY: matches
	$matchQ = mysql_query("SELECT * FROM matches WHERE matchID = '$matchID'", $connection); $matchR = mysql_fetch_array($matchQ); $opponent2 = $matchR["opponent2"];
	// ACTION: edit
	$clanadminQ = mysql_query("SELECT * FROM teammembers WHERE teamID = '$opponent2' AND pid = '$pid' AND rights > '1'", $connection);
	if (mysql_num_rows($clanadminQ)) {
		mysql_query("UPDATE matches SET accepted2 = '1', map2 = '$map', acceptdate = '$date' WHERE matchID = '$matchID'", $connection);
		redirectto("index.php?site=matches"); exit;
	}
}
?>