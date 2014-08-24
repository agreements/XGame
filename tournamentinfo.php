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
$tournamentID = sql_quote($_GET['tournamentID']);
$action = sql_quote($_GET['action']);
// SITE PAGE CONTROL
if (isset($_GET['action'])) {$action = sql_quote($_GET['action']);} else {$action = 'brackets';}
// QUERY: tournament 
$tournamentQ = mysql_query("SELECT * FROM tournaments WHERE tournamentsID = '$tournamentID'", $connection);
if (mysql_num_rows($tournamentQ) == '0') {echo "<h4>&#8226; Wrong tournamentID</h4>"; return;}
$tournamentR = mysql_fetch_array($tournamentQ);
$title = $tournamentR["title"];
$gamemode = $tournamentR["gamemode"];
$game = $tournamentR["game"];
$size = $tournamentR["size"];
$prize1 = $tournamentR["prize1"];
$prize2 = $tournamentR["prize2"];
$prize3 = $tournamentR["prize3"];
$signupOPEN = date("d.m.Y H:i", $tournamentR[signupOPEN]);
$signupCLOSED = date("d.m.Y H:i", $tournamentR[signupCLOSED]);
$start = date("d.m.Y H:i", $tournamentR[start]);
$now = time();
// TEAM ID's --- NAMES
require_once("includes/none/tournamentinfo.php");
// CHECK: status
if (($now > $tournamentR["signupOPEN"]) && ($now < $tournamentR["signupCLOSED"])) {$signups = "<h5 class='green'>SIGN UP</h5>";}
if (($now > $tournamentR["signupCLOSED"]) && ($now < $tournamentR["start"])) {$signups = "<h5 class='yellow'>START SOON</h5>";}
if ($now > $tournamentR["start"]) {$signups = "<h5 class='red'>STARTED</h5>";}
if ($now < $tournamentR["signupOPEN"]) {$signups = "<h5 class='yellow'> SIGN UP SOON</h5>";}
// QUERY: game
$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$game'", $connection); $gameR = mysql_fetch_array($gameQ); $gameTITLE = $gameR["title"]; $pic = $gameR["pic"];
// OUTPUT
eval ("\$tournamentinfo = \"".gettemplate("tournamentinfo")."\";");
echo $tournamentinfo;

// ACTION: signup
if ($action == "signup") {
	// ACTION: joinladder
	if (isset($_POST['join'])) {
		$teamID = sql_quote($_POST['teamIDlist']);
		$date = time();
		// CHECK: if exist
		$tournamentQ = mysql_query("SELECT * FROM tournamentteams WHERE tournamentID = '$tournamentID' AND teamID = '$teamID'", $connection);
		if (mysql_num_rows($tournamentQ) == '0') {
			$joinTOURNAMENT = mysql_query("INSERT INTO tournamentteams (tournamentID, teamID, joined, accepted) VALUES ('$tournamentID', '$teamID', '$date', '0')", $connection);
			// REDDIRECT
			redirectto("index.php?site=tournamentinfo&amp;tournamentID=$tournamentID&amp;action=signup"); exit;
		}
		else {echo "<h4>This team is already joined to a ladder.</li><meta http-equiv='refresh' content='2;URL=index.php?site=tournamentinfo&amp;tournamentID=$tournamentID&amp;action=signup'></h4>"; return;}
	}
	//CHECK: auth
	if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
	// CHECK: status
	if ($now > $tournamentR["start"]) {echo "<h4>&#8226; Tournament have been started</h4>"; return;}
	if ($now < $tournamentR["signupOPEN"]) {echo "<h4>&#8226; Sign ups will be started on $signupOPEN</h4>"; return;}
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
		$checkQ = mysql_query("SELECT * FROM tournamentteams WHERE tournamentID = '$tournamentID' AND teamID = '$teamIDlist'", $connection);
		if (mysql_num_rows($checkQ) == '0') {$teamLIST .= "<option value='$teamIDlist'>$teamNAME</option>";}
	}
	if (!isset($teamLIST)) {echo "<h4>&#8226; Your teams are competing in this tournament!</h4>"; return;}
	eval ("\$tournamentSIGNUP = \"".gettemplate("tournamentSIGNUP")."\";");
	echo $tournamentSIGNUP;
}

// ACTION: maps
if ($action == "teams") {
	// OUTPUT: subMAPS
	eval ("\$tournamentTEAMS = \"".gettemplate("tournamentTEAMS")."\";"); 
	echo $tournamentTEAMS;
	$tournamentteamsQ = mysql_query("SELECT * FROM tournamentteams WHERE tournamentID = '$tournamentID'", $connection);
	while ($tournamentteamsR = mysql_fetch_array($tournamentteamsQ)) {
		$teamID = $tournamentteamsR["teamID"];
		// QUERY: teams
		$teamsQ = mysql_query("SELECT * FROM teams WHERE teamID = '$teamID'", $connection);
		if (mysql_num_rows($teamsQ) == '0') {echo "<h4>&#8226; There is no teams with that ID!</h4>"; return;}
		$teamsR = mysql_fetch_array($teamsQ);
		$teamNAME = $teamsR["name"];
		$teamTAG = $teamsR["tag"];
		// LIST: subMAPS
		eval ("\$tournamentTEAMS = \"".getlist("tournamentTEAMS")."\";"); echo $tournamentTEAMS;
	}
}

// ACTION: brackets
if ($action == "brackets") {
	if ($size == '8') {
		eval ("\$tournamentBRACKETS8 = \"".gettemplate("tournamentBRACKETS8")."\";"); echo $tournamentBRACKETS8;}
	if ($size == '16') {
		eval ("\$tournamentBRACKETS16 = \"".gettemplate("tournamentBRACKETS16")."\";"); echo $tournamentBRACKETS16;}
	if ($size == '32') {
		eval ("\$tournamentBRACKETS32 = \"".gettemplate("tournamentBRACKETS32")."\";"); echo $tournamentBRACKETS32;
	}
}

// ACTION: my matches
if ($action == "matches") {
	// OUTPUT: subMAPS
	eval ("\$tournamentMATCHES = \"".gettemplate("tournamentMATCHES")."\";"); 
	echo $tournamentMATCHES;
	if ($size == '32') {eval ("\$tournamentMATCHES32 = \"".gettemplate("tournamentMATCHES32")."\";"); echo $tournamentMATCHES32;}
	if ($size == '16') {eval ("\$tournamentMATCHES16 = \"".gettemplate("tournamentMATCHES16")."\";"); echo $tournamentMATCHES16;}
	if ($size == '8') {eval ("\$tournamentMATCHES8 = \"".gettemplate("tournamentMATCHES8")."\";"); echo $tournamentMATCHES8;}
}
?>