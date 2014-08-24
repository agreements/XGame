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
$opponentID = sql_quote($_GET['opponentID']);
$ladderID = sql_quote($_GET['ladderID']);
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
// ACTION: challange
if (isset($_POST['challange'])) {
	$calendar = sql_quote($_POST['date']);
	$hours = sql_quote($_POST['hours']);
	$minutes = sql_quote($_POST['minutes']);
	$date = strtotime($calendar." ".$hours.":".$minutes);
	$time = time();
	$opponentID = sql_quote($_POST['opponentID']);
	$myteamID = sql_quote($_POST['myteamID']);
	$ladderID = sql_quote($_POST['ladderID']);
	$map = sql_quote($_POST['map']);
	$serverIP = sql_quote($_POST['serverip']);
	$serverPASS = sql_quote($_POST['serverpass']);
	// CHECK: date
	if ((!is_numeric($hours)) || (!is_numeric($minutes)) || ($hours < '0') || ($minutes < '0') || ($hours > '24') || ($minutes > '59')) {echo "<h4>&#8226; Wrong date!</h4>"; return;}
	if ($time > $date) {echo "<h4>&#8226; You cant challange opponent in past!</h4>"; return;}
	// CHECK: if opponent and ladder exist
	$opponentCHECK = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND teamID = '$opponentID' AND accepted = '1'", $connection);
	if (mysql_num_rows($opponentCHECK) == '0') {echo "<h4>&#8226; This team is not joined to this ladder, or this ladder don't exist.</h4>"; return;}
	// CHECK: if user is admin or trusted
	$adminCHECK = mysql_query("SELECT * FROM teams WHERE teamID = '$opponentID' AND adminID = '$pid'", $connection);
	if (mysql_num_rows($adminCHECK) != '0') {echo "<h4>&#8226; You cant challange yourself!</h4>"; return;}
	// CHECK: my team
	$myteamCHECK = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND teamID = '$myteamID' AND accepted = '1'", $connection);
	if (mysql_num_rows($myteamCHECK) == '0') {echo "<h4>&#8226; Your team is not joined to this ladder, or this ladder don't exist.</h4>"; return;}
	// NO ERRORS
	$challangeADD = mysql_query("INSERT INTO matches 
	(opponent1, opponent2, accepted1, accepted2, map1, ladderID, date, challengedate, serverIP, serverPASS) 
	VALUES 
	('$myteamID', '$opponentID', '1', '0', '$map', '$ladderID', '$date', '$time', '$serverIP', '$serverPASS')", $connection);
	echo "<meta http-equiv='refresh' content='0;URL=index.php?site=notifications'>"; exit;
}
// CHECK: if opponent and ladder exist
$opponentCHECK = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND teamID = '$opponentID' AND accepted = '1'", $connection);
if (mysql_num_rows($opponentCHECK) == '0') {echo "<h4>&#8226; This team is not joined to this ladder, or this ladder don't exist.</h4>"; return;}
// CHECK: if user is admin or trusted
$adminCHECK = mysql_query("SELECT * FROM teams WHERE teamID = '$opponentID' AND adminID = '$pid'", $connection);
if (mysql_num_rows($adminCHECK) != '0') {echo "<h4>&#8226; You cant challange yourself!</h4>"; return;}
// QUERY: ladder
$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection);
$ladderR = mysql_fetch_array($ladderQ);
$ladderTITLE = $ladderR["title"];
$gamemode = $ladderR["gamemode"];
$gameID = $ladderR["game"];
// QUERY: opponent
$opponentQ = mysql_query("SELECT * FROM teams WHERE teamID = '$opponentID'", $connection); $opponentR = mysql_fetch_array($opponentQ); $opponentNAME = $opponentR["name"];
// QUERY: game
$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$gameID'", $connection); $gameR = mysql_fetch_array($gameQ); $gameTITLE = $gameR["title"];
// QUERY: maps
$mapQ = mysql_query("SELECT * FROM maps WHERE ladderID = '$ladderID'", $connection);
while ($mapR = mysql_fetch_array($mapQ)) {
	$mapID = $mapR["mapID"];
	$mapTITLE = $mapR["title"];
	$mapLIST .= "<option value='$mapID'>$mapTITLE</option>";
}
// QUERY: my team list
$haveteam = 0;
$teamlistQ = mysql_query("SELECT * FROM teammembers WHERE pid = '$pid' AND rights > '1'", $connection);
while ($teamlistR = mysql_fetch_array($teamlistQ)) {
	$myteamID = $teamlistR["teamID"];
	// QUERY: team
	$myteamQ = mysql_query("SELECT * FROM teams WHERE teamID = '$myteamID'", $connection); $myteamR = mysql_fetch_array($myteamQ); $myteamNAME = $myteamR["name"];
	// CHECK: myteam
	$myteamCHECK = mysql_query("SELECT * FROM ladderteams WHERE ladderID = '$ladderID' AND teamID = '$myteamID' AND accepted = '1'", $connection);
	if (mysql_num_rows($myteamCHECK) == '1') {
		$myteamLIST .= "<option value='$myteamID'>$myteamNAME</option>";
		$haveteam++;
	}
}
// OUTPUT: users
if ($haveteam == '0') {echo "<h4>&#8226; You dont have any team in this ladder to challange opponent!</h4>"; return;}
eval ("\$challange = \"".gettemplate("challange")."\";");
echo $challange;
?>