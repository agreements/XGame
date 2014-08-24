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

// NOTIFICATION: team invitation
$invitationQ = mysql_query("SELECT * FROM teammembers WHERE pid = '$pid' AND accepted = '0'", $connection);
eval ("\$notificationTEAMINV = \"".gettemplate("notificationTEAMINV")."\";");
echo $notificationTEAMINV;
while ($invitationR = mysql_fetch_array($invitationQ)) {
	$teaminvID = $invitationR["id"];
	$teamID = $invitationR["teamID"];
	$joined = date("d.m.Y - H:i", $invitationR[date]);
	// QUERY: team
	$teamQ = mysql_query("SELECT * FROM teams WHERE teamID = '$teamID'", $connection); $teamR = mysql_fetch_array($teamQ); $teamNAME = $teamR["name"]; $teamTAG = $teamR["tag"];
	// LIST: matches
	eval ("\$notificationTEAMINV = \"".getlist("notificationTEAMINV")."\";"); echo $notificationTEAMINV;
}
if (mysql_num_rows($invitationQ) == '0') {echo "<div id='list'><h4>You have 0 invitations!</h4></div>";}

// NOTIFICATION: ch outgoing
eval ("\$notificationCHOUT = \"".gettemplate("notificationCHOUT")."\";");
echo $notificationCHOUT;
// QUERY: teammembers
$choutCOUNT = '0';
$choutQ = mysql_query("SELECT * FROM teammembers WHERE pid = '$pid' AND rights > '2'", $connection);
while ($choutR = mysql_fetch_array($choutQ)) {
	$teamID = $choutR["teamID"];
	// QUERY: match
	$match1Q = mysql_query("SELECT * FROM matches WHERE opponent1 = '$teamID' AND accepted1 = '1' AND accepted2 = '0'", $connection);
	while ($match1R = mysql_fetch_array($match1Q)) {
		$opponent1 = $match1R["opponent1"];
		$opponent2 = $match1R["opponent2"];
		$matchID = $match1R["matchID"];
		$ladderID = $match1R["ladderID"];
		$chdate = date("d.m.Y - H:i", $match1R[date]);
		// QUERY: opponent1
		$opponent1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $opponent1R = mysql_fetch_array($opponent1Q); $opponent1name = $opponent1R["name"];
		// QUERY: opponent2
		$opponent2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $opponent2R = mysql_fetch_array($opponent2Q); $opponent2name = $opponent2R["name"];
		// QUERY: ladder
		$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection); $ladderR = mysql_fetch_array($ladderQ);
		$ladderTITLE = $ladderR["title"];
		$game = $ladderR["game"];
		// QUERY: game
		$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$game'", $connection); $gameR = mysql_fetch_array($gameQ); $gameTITLE = $gameR["title"];
		// LIST: matches
		eval ("\$notificationCHOUT = \"".getlist("notificationCHOUT")."\";"); echo $notificationCHOUT;
		$choutCOUNT++;
	}
}
if ($choutCOUNT == '0') {echo "<div id='list'><h4>You have 0 outgoing challanges!</h4></div>";}

// NOTIFICATION: ch incomming
eval ("\$notificationCHIN = \"".gettemplate("notificationCHIN")."\";");
echo $notificationCHIN;
// QUERY: teammembers
$chinCOUNT = '0';
$chinQ = mysql_query("SELECT * FROM teammembers WHERE pid = '$pid' AND rights > '2'", $connection);
while ($chinR = mysql_fetch_array($chinQ)) {
	$teamID = $chinR["teamID"];
	// QUERY: match
	$match2Q = mysql_query("SELECT * FROM matches WHERE opponent2 = '$teamID' AND accepted1 = '1' AND accepted2 = '0'", $connection);
	while ($match2R = mysql_fetch_array($match2Q)) {
		$opponent1 = $match2R["opponent1"];
		$opponent2 = $match2R["opponent2"];
		$matchID = $match2R["matchID"];
		$ladderID = $match2R["ladderID"];
		// QUERY: opponent1
		$opponent1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $opponent1R = mysql_fetch_array($opponent1Q); $opponent1name = $opponent1R["name"];
		// QUERY: opponent2
		$opponent2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $opponent2R = mysql_fetch_array($opponent2Q); $opponent2name = $opponent2R["name"];
		// QUERY: ladder
		$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection); $ladderR = mysql_fetch_array($ladderQ);
		$ladderID = $ladderR["ladderID"];
		$ladderTITLE = $ladderR["title"];
		$game = $ladderR["game"];
		// QUERY: game
		$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$game'", $connection); $gameR = mysql_fetch_array($gameQ); $gameTITLE = $gameR["title"];
		// LIST: matches
		eval ("\$notificationCHIN = \"".getlist("notificationCHIN")."\";"); echo $notificationCHIN;
		$chinCOUNT++;
	}
}
if ($chinCOUNT == '0') {echo "<div id='list'><h4>You have 0 incomming challanges!</h4></div>";}
?>