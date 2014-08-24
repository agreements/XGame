<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
$pid = $_SESSION['pid'];
// QUERY: tournament
$tournamentQ = mysql_query("SELECT * FROM tournaments ORDER BY start DESC", $connection);
if (mysql_num_rows($tournamentQ) == '0') {echo "<h4>&#8226; There is no tournaments to display!</h4>"; return;}
// OUTPUT: ladders
eval ("\$tournaments = \"".gettemplate("tournaments")."\";");
echo $tournaments;
$now = time();
while ($tournamentR = mysql_fetch_array($tournamentQ)) {
	$tournamentID = $tournamentR["tournamentsID"];
	$title = $tournamentR["title"];
	$size = $tournamentR["size"];
	$gamemode = $tournamentR["gamemode"];
	$game = $tournamentR["game"];
	// CHECK: admin
	$gamesadminQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid' AND gameID = '$game'", $connection);
	if (mysql_num_rows($gamesadminQ) == '0') {$admin = "";} else {$admin = "<button type='button' onclick=\"window.location='index.php?site=tournamentedit&tournamentID=$tournamentID'\">settings</button>";}
	// QUERY: game
	$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$game'", $connection); $gameR = mysql_fetch_array($gameQ); $gameTITLE = $gameR["title"]; $pic = $gameR["pic"];
	// TOURNAMENT: status
	if (($now > $tournamentR["signupOPEN"]) && ($now < $tournamentR["signupCLOSED"])) {$signups = "<h5 class='green'>SIGN UP</h5>";}
	if (($now > $tournamentR["signupCLOSED"]) && ($now < $tournamentR["start"])) {$signups = "<h5 class='yellow'>START SOON</h5>";}
	if ($now > $tournamentR["start"]) {$signups = "<h5 class='red'>STARTED</h5>";}
	if ($now < $tournamentR["signupOPEN"]) {$signups = "<h5 class='yellow'>SOON</h5>";}
	// LIST: ladders
	eval ("\$tournaments = \"".getlist("tournaments")."\";"); echo $tournaments;
}
?>