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
// QUERY: match ENTER RESULT
$teammembersQ = mysql_query("SELECT * FROM teammembers WHERE pid = '$pid' AND rights > '2'", $connection);
if (mysql_num_rows($teammembersQ) == '0') {echo "<h4>&#8226; There is no matches to display!</h4>"; return;}
// OUTPUT: matches
eval ("\$matches = \"".gettemplate("matches")."\";");
echo $matches;
while ($teammembersR = mysql_fetch_array($teammembersQ)) {
	$teamID = $teammembersR["teamID"];
	// QUERY: matches
	$matchQ = mysql_query("SELECT * FROM matches 
	WHERE (confirmed1 = '0' OR confirmed2 = '0') AND (opponent1 = '$teamID' OR opponent2 = '$teamID') AND (accepted1 ='1' AND accepted2 = '1')", $connection);
	while ($matchR = mysql_fetch_array($matchQ)) {
		$matchID = $matchR["matchID"];
		$ladderID = $matchR["ladderID"];
		$opponent1 = $matchR["opponent1"];
		$opponent2 = $matchR["opponent2"];
		$confirmed1 = $matchR["confirmed1"];
		$confirmed2 = $matchR["confirmed2"];
		$map1 = $matchR["map1"];
		$map2 = $matchR["map2"];
		$date = date("d.m.Y H:i", $matchR[date]);
		// QUERY: ladder
		$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection); $ladderR = mysql_fetch_array($ladderQ);
		$ladderID = $ladderR["ladderID"];
		$ladderTITLE = $ladderR["title"];
		$gamemode = $ladderR["gamemode"];
		$gameID = $ladderR["game"];
		// QUERY: opponent1
		$opponent1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $opponent1R = mysql_fetch_array($opponent1Q); 
		$opponent1name = $opponent1R["name"];
		// QUERY: opponent2
		$opponent2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $opponent2R = mysql_fetch_array($opponent2Q); 
		$opponent2name = $opponent2R["name"];
		// QUERY: game
		$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$gameID'", $connection);
		$gameR = mysql_fetch_array($gameQ);
		$gameTITLE = $gameR["title"];
		// SET: imgaes
		if ($confirmed1 == '0' && $confirmed2 == '0') {$image = "images/icons/matchenter.png";}
		if ($confirmed1 == '1' && $confirmed2 == '0' && $teamID == $opponent1) {$image = "images/icons/matchedit.png";}
		if ($confirmed1 == '0' && $confirmed2 == '1' && $teamID == $opponent2) {$image = "images/icons/matchedit.png";}
		if ($confirmed1 == '1' && $confirmed2 == '0' && $teamID == $opponent2) {$image = "images/icons/matchaccept.png";}
		if ($confirmed1 == '0' && $confirmed2 == '1' && $teamID == $opponent1) {$image = "images/icons/matchaccept.png";}
		// LIST: matches
		eval ("\$matchlist = \"".getlist("matchlist")."\";"); echo $matchlist;
		}
	// MATCHES WITH CONFLICT
	// QUERY: conflict
	$conflictQ = mysql_query("SELECT * FROM conflict WHERE status = '0' AND (opponent1 = '$teamID' OR opponent2 = '$teamID')", $connection);
	while ($conflictR = mysql_fetch_array($conflictQ)) {
		$matchID = $conflictR["matchID"];
		$ladderID = $conflictR["ladderID"];
		$opponent1 = $conflictR["opponent1"];
		$opponent2 = $conflictR["opponent2"];
		$status = $conflictR["status"];
		// QUERY: ladder
		$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection); $ladderR = mysql_fetch_array($ladderQ);
		$ladderID = $ladderR["ladderID"];
		$ladderTITLE = $ladderR["title"];
		$gamemode = $ladderR["gamemode"];
		$gameID = $ladderR["game"];
		// QUERY: opponent1
		$opponent1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $opponent1R = mysql_fetch_array($opponent1Q); 
		$opponent1name = $opponent1R["name"];
		// QUERY: opponent2
		$opponent2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $opponent2R = mysql_fetch_array($opponent2Q); 
		$opponent2name = $opponent2R["name"];
		// QUERY: game
		$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$gameID'", $connection);
		$gameR = mysql_fetch_array($gameQ);
		$gameTITLE = $gameR["title"];
		// LIST: matches
		eval ("\$matchlistconflict = \"".getlist("matchlistconflict")."\";"); echo $matchlistconflict;
	}
}
?>