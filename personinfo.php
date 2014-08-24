<?php
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
$member = sql_quote($_GET['pid']);
$action = sql_quote($_GET['action']);
// SITE PAGE CONTROL
if (isset($_GET['action'])) {$action = sql_quote($_GET['action']);} else {$action = 'teams';}
// SET: buttons
if (isset($pid) && ($member == $pid)) {
	$editB = "<FORM><INPUT type='button' value='edit' onClick=\"window.location='index.php?site=personedit'\"></FORM>";
	$guidB = "<FORM><INPUT type='button' value='add guid' onClick=\"window.location='index.php?site=guids'\"></FORM>";
	$clanB = "<FORM><INPUT type='button' value='create team' onClick=\"window.location='index.php?site=createteam'\"></FORM>";
	$messageB = "";
} else {
	$editB = "";
	$guidB = "";
	$clanB = "";
	$messageB = "<button type='button' onclick=\"window.location='index.php?site=messagenew&memberID=$member'\">send pm</button>";
}
// QUERY: menuBAN
$bansQ = mysql_query("SELECT * FROM bans WHERE memberID = '$member'", $connection);
if (mysql_num_rows($bansQ)) {$menuBAN = "<li><a href='index.php?site=personinfo&amp;pid=$member&amp;action=bans' title='New'>BANS</a></li>";} else {$menuBAN = "";}
// QUERY: users
$memberQ = mysql_query("SELECT * FROM users WHERE pid = '$member'", $connection);
if (mysql_num_rows($memberQ) == '0') {echo "<h4>&#8226; There is no player with that ID!</h4>"; return;}
$memberR = mysql_fetch_array($memberQ);
$nickname = $memberR["nickname"];
$country = $memberR["country"];
$countryCODE = strtolower($country);
$countryNAME = countryname($country);
$gender = $memberR["gender"];
$country = $memberR["flags"];
$membersince = date("d.m.Y", $memberR[joined]);
$avatarURL = $memberR["pic"];
$lastactive = $memberR["lastactive"];
// QUERY: status
$userSTATUS = getstatus($lastactive);
// OUTPUT: personinfo
eval ("\$personinfo = \"".gettemplate("personinfo")."\";");
echo $personinfo;
// ACTION: guids
if ($action == "guids") {
	// OUTPUT: subGUIDS
	eval ("\$subGUIDS = \"".gettemplate("subGUIDS")."\";");
	echo $subGUIDS;
	// QUERY: player guids
	$playerguidQ = mysql_query("SELECT * FROM playerguids WHERE pid = '$member' AND active = '1' ORDER BY guidID DESC", $connection);
	if (mysql_num_rows($playerguidQ) == '0') {$guidLIST = "<h4>&nbsp;There is no guids to display.</h4>";}
	while ($playerguidR = mysql_fetch_array($playerguidQ)) {
		$guidVALUE = $playerguidR["value"];
		$guidDATE = date("d.m.Y H:i", $playerguidR[date]);
		$guidID = $playerguidR["guidID"];
		// QUERY: guid
		$guidQ = mysql_query("SELECT * FROM guids WHERE guidID = '$guidID'", $connection); $guidR = mysql_fetch_array($guidQ); $guidTITLE = $guidR["title"];
		// LIST: matches
		eval ("\$subGUIDS = \"".getlist("subGUIDS")."\";"); echo $subGUIDS;
	}
}
// ACTION: teams
if ($action == "teams") {
	// OUTPUT: subTEAMS
	eval ("\$subTEAMS = \"".gettemplate("subTEAMS")."\";");
	echo $subTEAMS;
	// QUERY: player teams
	$playerteamsQ = mysql_query("SELECT * FROM teammembers WHERE pid = '$member' AND accepted = '1' ORDER BY id DESC", $connection);
	if (mysql_num_rows($playerteamsQ) == '0') {$teamLIST = "<h4>&nbsp;There is no teams to display.</h4>";}
	while ($playerteamsR = mysql_fetch_array($playerteamsQ)) {
		$teamID = $playerteamsR["teamID"];
		$joined = date("d.m.Y - H:i", $playerteamsR[joined]);
		$rights = $playerteamsR["rights"];
		// QUERY: team
		$teamQ = mysql_query("SELECT * FROM teams WHERE teamID = '$teamID'", $connection); 
		$teamR = mysql_fetch_array($teamQ); $teamNAME = $teamR["name"]; 
		$teamTAG = $teamR["tag"];
		// LIST: matches
		if (mysql_num_rows($teamQ) != '0') {
			eval ("\$subTEAMS = \"".getlist("subTEAMS")."\";"); echo $subTEAMS;
		}
	}
}

// ACTION: guid history
if ($action == "guidslog") {
	$guidID = sql_quote($_GET['guidID']);
	// QUERY: guid
	$guidQ = mysql_query("SELECT * FROM guids WHERE guidID = '$guidID'", $connection); $guidR = mysql_fetch_array($guidQ); $guidTITLE = $guidR["title"];
	// OUTPUT: subTEAMS
	eval ("\$subGUIDSLOG = \"".gettemplate("subGUIDSLOG")."\";");
	echo $subGUIDSLOG;
	// QUERY: player teams
	$playerguidQ = mysql_query("SELECT * FROM playerguids WHERE pid = '$member' AND guidID = '$guidID' ORDER BY date DESC", $connection);
	while ($playerguidR = mysql_fetch_array($playerguidQ)) {
		$guidVALUE = $playerguidR["value"];
		$guidDATE = date("d.m.Y H:i", $playerguidR[date]);
		$guidID = $playerguidR["guidID"];
		$active = $playerguidR["active"];
		if ($active == '1') {$guidVALUE = "<h4 class='green'>".$guidVALUE."</h4>";}
		// LIST: matches
		eval ("\$subGUIDSLOG = \"".getlist("subGUIDSLOG")."\";"); echo $subGUIDSLOG;
	}
}

// ACTION: ban history
if ($action == "bans") {
	// OUTPUT: subTEAMS
	eval ("\$subBANS = \"".gettemplate("subBANS")."\";");
	echo $subBANS;
	// QUERY: bans
	$banQ = mysql_query("SELECT * FROM bans WHERE memberID = '$member'", $connection);
	while ($banR = mysql_fetch_array($banQ)) {
		$adminID = $banR["adminID"];
		$reason = $banR["reason"];
		$bandate = date("d.m.Y H:i", $banR[date]);
		$expire = date("d.m.Y H:i", $banR[expire]);
		$expireSTAMP = $banR["expire"];
		$date = time();
		if ($expireSTAMP < $date) {$class = "class='green'";} else {$class = "class='red'";}
		// QUERY: admin
		$adminQ = mysql_query("SELECT * FROM users WHERE pid = '$adminID'", $connection); $adminR = mysql_fetch_array($adminQ); $adminNAME = $adminR["nickname"];
		// LIST: matches
		eval ("\$subBANS = \"".getlist("subBANS")."\";"); echo $subBANS;
	}
}
?>