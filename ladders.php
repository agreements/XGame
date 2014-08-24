<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// QUERY: games
$gameQ = mysql_query("SELECT * FROM games", $connection);
if (mysql_num_rows($gameQ) == '0') {echo "<h4>&#8226; There is no games to display!</h4>"; return;}
while ($gameR = mysql_fetch_array($gameQ)) {
	$gameID = $gameR["gameID"];
	$gameTITLE = $gameR["title"];
	$pic = $gameR["pic"];
	// QUERY: ladder
	$ladderQ = mysql_query("SELECT * FROM ladders WHERE game = '$gameID'", $connection);
	if (mysql_num_rows($ladderQ) != '0') {
		// OUTPUT: ladders
		eval ("\$ladders = \"".gettemplate("ladders")."\";");
		echo $ladders;
		while ($ladderR = mysql_fetch_array($ladderQ)) {
			$ladderID = $ladderR["ladderID"];
			$ladderTITLE = $ladderR["title"];
			$ladderDATE = date("d.m.Y", $ladderR[date]);
			$gamemode = $ladderR["gamemode"];
			// check activity
			$activeQ = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND accepted = '1'", $connection);
			$active = mysql_num_rows($activeQ);
			// number 1 team
			$number1Q = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND accepted = '1' ORDER BY points ASC LIMIT 1", $connection);
			$number1R = mysql_fetch_array($number1Q);
			$number1teamID = $number1R["teamID"];
			// QUERY: teams 
			$team1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$number1teamID'", $connection);
			$team1R = mysql_fetch_array($team1Q);
			$team1NAME = $team1R["name"];
			// LIST: ladders
			eval ("\$ladders = \"".getlist("ladders")."\";"); echo $ladders;
		}
	}
}
?>