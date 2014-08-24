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
// QUERY: siteadmins
$siteadminsQ = mysql_query("SELECT * FROM users WHERE rights > '1'", $connection);
if (mysql_num_rows($siteadminsQ) == '0') {echo "<h4>&#8226; There is no admins to display</h4>"; return;}
// OUTPUT: siteadmins
eval ("\$siteadmins = \"".gettemplate("siteadmins")."\";");
echo $siteadmins;
while ($siteadminsR = mysql_fetch_array($siteadminsQ)) {
	$memberID = $siteadminsR["pid"];
	$nickname = $siteadminsR["nickname"];
	$country = $siteadminsR["country"];
	$countryCODE = strtolower($country);
	$countryNAME = countryname($country);
	$gender = $siteadminsR["gender"];
	$avatarURL = $siteadminsR["pic"];
	$rights = $siteadminsR["rights"];
	$membersince = date("d.m.Y", $siteadminsR[joined]);
	$lastactive = $siteadminsR["lastactive"];
	// QUERY: status
	$userSTATUS = getstatus($lastactive);
	// SET: buttons
	if (isset($pid) && ($memberID == $pid)) {
		$messageB = "";
	} else {
		$messageB = "<button type='button' onclick=\"window.location='index.php?site=messagenew&memberID=$memberID'\">send pm</button>";
	}
	// ADMINTITLE
	if ($rights == '2') {$admintitle = "admin";}
	if ($rights == '3') {$admintitle = "site admin";}
	eval ("\$siteadmins = \"".getlist("siteadmins")."\";"); echo $siteadmins;
}

// QUERY: gameadmins
$gameadminsQ = mysql_query("SELECT pid FROM gameadmins GROUP BY pid", $connection);
if (mysql_num_rows($gameadminsQ) == '0') {echo "<h4>&#8226; There is no game admins to display</h4>"; return;}
eval ("\$gameadmins = \"".gettemplate("gameadmins")."\";");
echo $gameadmins;
while ($gameadminsR = mysql_fetch_array($gameadminsQ)) {
	$memberID = $gameadminsR["pid"];
	// QUERY: users
	$memberQ = mysql_query("SELECT * FROM users WHERE pid = '$memberID'", $connection);
	$memberR = mysql_fetch_array($memberQ);
	$nickname = $memberR["nickname"];
	$country = $memberR["country"];
	$countryCODE = strtolower($country);
	$countryNAME = countryname($country);
	$gender = $memberR["gender"];
	$avatarURL = $memberR["pic"];
	$rights = $memberR["rights"];
	$membersince = date("d.m.Y", $memberR[joined]);
	$lastactive = $memberR["lastactive"];
	// QUERY: status
	$userSTATUS = getstatus($lastactive);
	// SET: buttons
	if (isset($pid) && ($memberID == $pid)) {
		$messageB = "";
	} else {
		$messageB = "<button type='button' onclick=\"window.location='index.php?site=messagenew&memberID=$memberID'\">send pm</button>";
	}
	// QUERY: gameslist
	$gamelistQ = mysql_query("SELECT * FROM gameadmins where pid = '$memberID'", $connection);
	while ($gamelistR = mysql_fetch_array($gamelistQ)) {
		$gameID = $gamelistR["gameID"];
		// QUERY: game
		$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$gameID'", $connection); $gameR = mysql_fetch_array($gameQ); $gameTITLE = $gameR["title"]."<br>";
		$gamesLIST .= $gameTITLE;
	}
	eval ("\$gameadmins = \"".getlist("gameadmins")."\";"); echo $gameadmins;
	unset($gamesLIST);
}
?>