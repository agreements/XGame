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
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
// SITE PAGE CONTROL
if (isset($_GET['action'])) {$action = sql_quote($_GET['action']);} else {$action = 'brackets';}
// POST: set32
if (isset($_POST['set32'])) {
	$r6s1 = sql_quote($_POST['r6s1']); $r6s9 = sql_quote($_POST['r6s9']);   $r6s17 = sql_quote($_POST['r6s17']); $r6s25 = sql_quote($_POST['r6s25']);
	$r6s2 = sql_quote($_POST['r6s2']); $r6s10 = sql_quote($_POST['r6s10']); $r6s18 = sql_quote($_POST['r6s18']); $r6s26 = sql_quote($_POST['r6s26']);
	$r6s3 = sql_quote($_POST['r6s3']); $r6s11 = sql_quote($_POST['r6s11']); $r6s19 = sql_quote($_POST['r6s19']); $r6s27 = sql_quote($_POST['r6s27']);
	$r6s4 = sql_quote($_POST['r6s4']); $r6s12 = sql_quote($_POST['r6s12']); $r6s20 = sql_quote($_POST['r6s20']); $r6s28 = sql_quote($_POST['r6s28']);
	$r6s5 = sql_quote($_POST['r6s5']); $r6s13 = sql_quote($_POST['r6s13']); $r6s21 = sql_quote($_POST['r6s21']); $r6s29 = sql_quote($_POST['r6s29']);
	$r6s6 = sql_quote($_POST['r6s6']); $r6s14 = sql_quote($_POST['r6s14']); $r6s22 = sql_quote($_POST['r6s22']); $r6s30 = sql_quote($_POST['r6s30']);
	$r6s7 = sql_quote($_POST['r6s7']); $r6s15 = sql_quote($_POST['r6s15']); $r6s23 = sql_quote($_POST['r6s23']); $r6s31 = sql_quote($_POST['r6s31']);
	$r6s8 = sql_quote($_POST['r6s8']); $r6s16 = sql_quote($_POST['r6s16']); $r6s24 = sql_quote($_POST['r6s24']); $r6s32 = sql_quote($_POST['r6s32']);	
	// QUERY: update
	$ladderEDIT = mysql_query("UPDATE tournaments SET 
	r6s1 = '$r6s1', r6s9 = '$r6s9',   r6s17 = '$r6s17', r6s25 = '$r6s25', r6s2 = '$r6s2', r6s10 = '$r6s10', r6s18 = '$r6s18', r6s26 = '$r6s26',
	r6s3 = '$r6s3', r6s11 = '$r6s11', r6s19 = '$r6s19', r6s27 = '$r6s27', r6s4 = '$r6s4', r6s12 = '$r6s12', r6s20 = '$r6s20', r6s28 = '$r6s28',
	r6s5 = '$r6s5', r6s13 = '$r6s13', r6s21 = '$r6s21', r6s29 = '$r6s29', r6s6 = '$r6s6', r6s14 = '$r6s14', r6s22 = '$r6s22', r6s30 = '$r6s30',
	r6s7 = '$r6s7', r6s15 = '$r6s15', r6s23 = '$r6s23', r6s31 = '$r6s31', r6s8 = '$r6s8', r6s16 = '$r6s16', r6s24 = '$r6s24', r6s32 = '$r6s32'
	WHERE tournamentsID = '$tournamentID'", $connection);
}
// POST: set16
if (isset($_POST['set16'])) {
	$r5s1 = sql_quote($_POST['r5s1']); $r5s9 = sql_quote($_POST['r5s9']);  
	$r5s2 = sql_quote($_POST['r5s2']); $r5s10 = sql_quote($_POST['r5s10']); 
	$r5s3 = sql_quote($_POST['r5s3']); $r5s11 = sql_quote($_POST['r5s11']);
	$r5s4 = sql_quote($_POST['r5s4']); $r5s12 = sql_quote($_POST['r5s12']); 
	$r5s5 = sql_quote($_POST['r5s5']); $r5s13 = sql_quote($_POST['r5s13']); 
	$r5s6 = sql_quote($_POST['r5s6']); $r5s14 = sql_quote($_POST['r5s14']);
	$r5s7 = sql_quote($_POST['r5s7']); $r5s15 = sql_quote($_POST['r5s15']);
	$r5s8 = sql_quote($_POST['r5s8']); $r5s16 = sql_quote($_POST['r5s16']);
	// QUERY: update
	$ladderEDIT = mysql_query("UPDATE tournaments SET 
	r5s1 = '$r5s1', r5s2 = '$r5s2',   r5s3 = '$r5s3', r5s4 = '$r5s4', r5s5 = '$r5s5', r5s6 = '$r5s6', r5s7 = '$r5s7', r5s8 = '$r5s8',
	r5s9 = '$r5s9', r5s10 = '$r5s10', r5s11 = '$r5s11', r5s12 = '$r5s12', r5s13 = '$r5s13', r5s14 = '$r5s14', r5s15 = '$r5s15', r5s16 = '$r5s16'
	WHERE tournamentsID = '$tournamentID'", $connection);
}
// POST: set8
if (isset($_POST['set8'])) {
	$r4s1 = sql_quote($_POST['r4s1']); $r4s2 = sql_quote($_POST['r4s2']); 
	$r4s3 = sql_quote($_POST['r4s3']); $r4s4 = sql_quote($_POST['r4s4']);
	$r4s5 = sql_quote($_POST['r4s5']); $r4s6 = sql_quote($_POST['r4s6']);
	$r4s7 = sql_quote($_POST['r4s7']); $r4s8 = sql_quote($_POST['r4s8']);
	// QUERY: update
	$ladderEDIT = mysql_query("UPDATE tournaments SET 
	r4s1 = '$r4s1', r4s2 = '$r4s2', r4s3 = '$r4s3', r4s4 = '$r4s4', r4s5 = '$r4s5', r4s6 = '$r4s6', r4s7 = '$r4s7', r4s8 = '$r4s8'
	WHERE tournamentsID = '$tournamentID'", $connection);
}
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
// CHECK: admin
$gamesadminQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid' AND gameID = '$game'", $connection);
if (mysql_num_rows($gamesadminQ) == '0') {echo "<h4>&#8226; You are not admin!</h4>"; return;}
// CHECK: status
if (($now > $tournamentR["signupOPEN"]) && ($now < $tournamentR["signupCLOSED"])) {$signups = "<h5 class='green'>SIGN UP</h5>";}
if (($now > $tournamentR["signupCLOSED"]) && ($now < $tournamentR["start"])) {$signups = "<h5 class='yellow'>START SOON</h5>";}
if ($now > $tournamentR["start"]) {$signups = "<h5 class='red'>STARTED</h5>";}
if ($now < $tournamentR["signupOPEN"]) {$signups = "<h5 class='yellow'> SIGN UP SOON</h5>";}
// QUERY: game
$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$game'", $connection); $gameR = mysql_fetch_array($gameQ); $gameTITLE = $gameR["title"]; $pic = $gameR["pic"];
// OUTPUT
eval ("\$tournamentedit = \"".gettemplate("tournamentedit")."\";");
echo $tournamentedit;

// ACTION: brackets
if ($action == "brackets") {
	// QUERY: teamlist
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
		$teamLIST .= "<option value='$teamID'>$teamNAME</option>";
	}
	// OUTPUT: tournamentROUND6
	if($size == 32) {eval ("\$tournamenteditBRACKETS32 = \"".gettemplate("tournamenteditBRACKETS32")."\";"); echo $tournamenteditBRACKETS32;}
	if($size == 16) {eval ("\$tournamenteditBRACKETS16 = \"".gettemplate("tournamenteditBRACKETS16")."\";"); echo $tournamenteditBRACKETS16;}
	if($size == 8)  {eval ("\$tournamenteditBRACKETS8  = \"".gettemplate("tournamenteditBRACKETS8")."\";");  echo $tournamenteditBRACKETS8; }
}

// ACTION: matches
if ($action == "matches") {
	// OUTPUT: subMAPS
	eval ("\$tournamentMATCHES = \"".gettemplate("tournamentMATCHES")."\";"); 
	echo $tournamentMATCHES;
	// UNSET
	unset($r6m1style, $r6m2style, $r6m3style, $r6m4style, $r6m5style, $r6m6style, $r6m7style, $r6m8style, $r6m9style, $r6m10style, $r6m11style,
	$r6m12style, $r6m13style, $r6m14style, $r6m15style, $r6m16style, $r5m1style, $r5m2style, $r5m3style, $r5m4style, $r5m5style, $r5m6style,
	 $r5m7style, $r5m8style, $r4m1style, $r4m1style, $r3m1style, $r3m2style, $r3m3style, $r3m4style, $r2m1style);
	if ($size == '32') {eval ("\$tournamentMATCHES32 = \"".gettemplate("tournamentMATCHES32")."\";"); echo $tournamentMATCHES32;}
	if ($size == '16') {eval ("\$tournamentMATCHES16 = \"".gettemplate("tournamentMATCHES16")."\";"); echo $tournamentMATCHES16;}
	if ($size == '8') {eval ("\$tournamentMATCHES8 = \"".gettemplate("tournamentMATCHES8")."\";"); echo $tournamentMATCHES8;}
}
?>