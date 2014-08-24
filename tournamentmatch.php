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
$bracketID = sql_quote($_GET['bracketID']);
// BRACKET SELECT
require_once("includes/none/bracketsselect.php");
// QUERY: tournament 
$tournamentQ = mysql_query("SELECT * FROM tournaments WHERE tournamentsID = '$tournamentID'", $connection);
if (mysql_num_rows($tournamentQ) == '0') {echo "<h4>&#8226; Wrong tournamentID</h4>"; return;}
$tournamentR = mysql_fetch_array($tournamentQ);
$title = $tournamentR["title"];
$gamemode = $tournamentR["gamemode"];
$game = $tournamentR["game"];
$opponent1 = $tournamentR[$opponent1SPOT];
$opponent2 = $tournamentR[$opponent2SPOT];
$score1 = $tournamentR[$score1SPOT];
$score2 = $tournamentR[$score2SPOT];
if (empty($opponent1) && empty($opponent2)) {echo "<h4>&#8226; This group is not set yet!</h4>"; return;}

// QUERY: tournament match
$tournamentmatchQ = mysql_query("SELECT * FROM tournamentmatches WHERE tournamentID = '$tournamentID' AND bracketID = '$bracketID'", $connection);
$tournamentmatchR = mysql_fetch_array($tournamentmatchQ);
$opponent1score1 = $tournamentmatchR[opponent1score1];
$opponent1score2 = $tournamentmatchR[opponent1score2];
$opponent2score1 = $tournamentmatchR[opponent2score1];
$opponent2score2 = $tournamentmatchR[opponent2score2];
if (!empty($opponent1score1) && !empty($opponent1score2)) {$scorereport1 = "Match result is ".$opponent1score1.":".$opponent1score2;} else {$scorereport1 = "Waiting for result...";}
if (!empty($opponent2score1) && !empty($opponent2score2)) {$scorereport2 = "Match result is ".$opponent2score1.":".$opponent2score2;} else {$scorereport2 = "Waiting for result...";}
// QUERY: team 1
$team1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $team1R = mysql_fetch_array($team1Q); $team1NAME = $team1R["name"];
// QUERY: team 2
$team2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $team2R = mysql_fetch_array($team2Q); $team2NAME = $team2R["name"];
// ISSET: report
if (isset($_POST['report'])) {
	$scorereport1 = sql_quote($_POST['scorereport1']);
	$scorereport2 = sql_quote($_POST['scorereport2']);
	// CHECK: if is admin of both clans
	$teammembersQ = mysql_query("SELECT * FROM teammembers WHERE (teamID = '$opponent1' OR teamID = '$opponent2') AND pid = '$pid' AND rights > '2'", $connection);
	if (mysql_num_rows($teammembersQ) != '0') {
		$teammembersR = mysql_fetch_array($teammembersQ);
		$teamID = $teammembersR["teamID"];
		if ($teamID == $opponent1) {
			mysql_query("UPDATE tournamentmatches SET opponent1score1 = '$scorereport1', opponent1score2 = '$scorereport2' WHERE tournamentID = '$tournamentID' AND bracketID = '$bracketID'", $connection);
			redirectto("index.php?site=tournamentmatch&tournamentID=$tournamentID&bracketID=$bracketID"); exit;
		}
		if ($teamID == $opponent2) {
			mysql_query("UPDATE tournamentmatches SET opponent2score1 = '$scorereport1', opponent2score2 = '$scorereport2' WHERE tournamentID = '$tournamentID' AND bracketID = '$bracketID'", $connection);
			redirectto("index.php?site=tournamentmatch&tournamentID=$tournamentID&bracketID=$bracketID"); exit;
		}
	}
}

// ISSET: accept
if (isset($_POST['accept'])) {
	// CHECK: admin
	$gamesadminQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid' AND gameID = '$game'", $connection);
	if (mysql_num_rows($gamesadminQ) == '0') {echo "<h4>&#8226; You are not admin!</h4>"; return;}
	$adminreport1 = sql_quote($_POST['adminreport1']);
	$adminreport2 = sql_quote($_POST['adminreport2']);
	if ($adminreport1 > $adminreport2) {$winner = $opponent1;}
	if ($adminreport1 < $adminreport2) {$winner = $opponent2;}
	// SET: scoreplace1, scoreplace2, bracketplace
	if ($bracketID == "r6m1") {$scoreplace1 = "r6s1s"; $scoreplace2 = "r6s2s"; $bracketplace = "r5s1";}
	if ($bracketID == "r6m2") {$scoreplace1 = "r6s3s"; $scoreplace2 = "r6s4s"; $bracketplace = "r5s2";}
	if ($bracketID == "r6m3") {$scoreplace1 = "r6s5s"; $scoreplace2 = "r6s6s"; $bracketplace = "r5s3";}
	if ($bracketID == "r6m4") {$scoreplace1 = "r6s7s"; $scoreplace2 = "r6s8s"; $bracketplace = "r5s4";}
	if ($bracketID == "r6m5") {$scoreplace1 = "r6s9s"; $scoreplace2 = "r6s10s"; $bracketplace = "r5s5";}
	if ($bracketID == "r6m6") {$scoreplace1 = "r6s11s"; $scoreplace2 = "r6s12s"; $bracketplace = "r5s6";}
	if ($bracketID == "r6m7") {$scoreplace1 = "r6s13s"; $scoreplace2 = "r6s14s"; $bracketplace = "r5s7";}
	if ($bracketID == "r6m8") {$scoreplace1 = "r6s15s"; $scoreplace2 = "r6s16s"; $bracketplace = "r5s8";}
	if ($bracketID == "r6m9") {$scoreplace1 = "r6s17s"; $scoreplace2 = "r6s18s"; $bracketplace = "r5s9";}
	if ($bracketID == "r6m10") {$scoreplace1 = "r6s19s"; $scoreplace2 = "r6s20s"; $bracketplace = "r5s10";}
	if ($bracketID == "r6m11") {$scoreplace1 = "r6s21s"; $scoreplace2 = "r6s22s"; $bracketplace = "r5s11";}
	if ($bracketID == "r6m12") {$scoreplace1 = "r6s23s"; $scoreplace2 = "r6s24s"; $bracketplace = "r5s12";}
	if ($bracketID == "r6m13") {$scoreplace1 = "r6s25s"; $scoreplace2 = "r6s26s"; $bracketplace = "r5s13";}
	if ($bracketID == "r6m14") {$scoreplace1 = "r6s27s"; $scoreplace2 = "r6s28s"; $bracketplace = "r5s14";}
	if ($bracketID == "r6m15") {$scoreplace1 = "r6s29s"; $scoreplace2 = "r6s30s"; $bracketplace = "r5s15";}
	if ($bracketID == "r6m16") {$scoreplace1 = "r6s31s"; $scoreplace2 = "r6s32s"; $bracketplace = "r5s16";}
	if ($bracketID == "r5m1") {$scoreplace1 = "r5s1s"; $scoreplace2 = "r5s2s"; $bracketplace = "r4s1";}
	if ($bracketID == "r5m2") {$scoreplace1 = "r5s3s"; $scoreplace2 = "r5s4s"; $bracketplace = "r4s2";}
	if ($bracketID == "r5m3") {$scoreplace1 = "r5s5s"; $scoreplace2 = "r5s6s"; $bracketplace = "r4s3";}
	if ($bracketID == "r5m4") {$scoreplace1 = "r5s7s"; $scoreplace2 = "r5s8s"; $bracketplace = "r4s4";}
	if ($bracketID == "r5m5") {$scoreplace1 = "r5s9s"; $scoreplace2 = "r5s10s"; $bracketplace = "r4s5";}
	if ($bracketID == "r5m6") {$scoreplace1 = "r5s11s"; $scoreplace2 = "r5s12s"; $bracketplace = "r4s6";}
	if ($bracketID == "r5m7") {$scoreplace1 = "r5s13s"; $scoreplace2 = "r5s14s"; $bracketplace = "r4s7";}
	if ($bracketID == "r5m8") {$scoreplace1 = "r5s15s"; $scoreplace2 = "r5s16s"; $bracketplace = "r4s8";}
	if ($bracketID == "r4m1") {$scoreplace1 = "r4s1s"; $scoreplace2 = "r4s2s"; $bracketplace = "r3s1";}
	if ($bracketID == "r4m2") {$scoreplace1 = "r4s3s"; $scoreplace2 = "r4s4s"; $bracketplace = "r3s2";}
	if ($bracketID == "r4m3") {$scoreplace1 = "r4s5s"; $scoreplace2 = "r4s6s"; $bracketplace = "r3s3";}
	if ($bracketID == "r4m4") {$scoreplace1 = "r4s7s"; $scoreplace2 = "r4s8s"; $bracketplace = "r3s4";}
	if ($bracketID == "r3m1") {$scoreplace1 = "r3s1s"; $scoreplace2 = "r3s2s"; $bracketplace = "r2s1";}
	if ($bracketID == "r3m2") {$scoreplace1 = "r3s3s"; $scoreplace2 = "r3s4s"; $bracketplace = "r2s2";}
	if ($bracketID == "r2m1") {$scoreplace1 = "r2s1s"; $scoreplace2 = "r2s2s"; $bracketplace = "r1s1";}
	// UPDATE
	mysql_query("UPDATE tournaments SET $scoreplace1 = '$adminreport1', $scoreplace2 = '$adminreport2', $bracketplace = '$winner' 
	WHERE tournamentsID = '$tournamentID'", $connection);
	redirectto("index.php?site=tournamentmatch&tournamentID=$tournamentID&bracketID=$bracketID"); exit;
}
// OUTPUT
eval ("\$tournamentmatch = \"".gettemplate("tournamentmatch")."\";");
echo $tournamentmatch;
// CHECK: if is admin of  both clans
$teammembers1Q = mysql_query("SELECT * FROM teammembers WHERE teamID = '$opponent1' AND pid = '$pid' AND rights > '1'", $connection);
$teammembers2Q = mysql_query("SELECT * FROM teammembers WHERE teamID = '$opponent2' AND pid = '$pid' AND rights > '1'", $connection);
if (mysql_num_rows($teammembers1Q) != '0' && mysql_num_rows($teammembers2Q) != '0') {echo "<div id='list'><h4>&#8226; You have admin rights of both teams. We cant allow that.</h4></div>"; return;}
// CHECK: if is admin of clan
$teammembersQ = mysql_query("SELECT * FROM teammembers WHERE (teamID = '$opponent1' OR teamID = '$opponent2') AND pid = '$pid' AND rights > '2'", $connection);
if (mysql_num_rows($teammembersQ) != '0') {
	eval ("\$tournamentmatchreport = \"".gettemplate("tournamentmatchreport")."\";");
	echo $tournamentmatchreport;
}
// CHECK: admin
$gamesadminQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid' AND gameID = '$game'", $connection);
if (mysql_num_rows($gamesadminQ) != '0') {
	eval ("\$tournamentmatchaccept = \"".gettemplate("tournamentmatchaccept")."\";");
	echo $tournamentmatchaccept;
}
?>