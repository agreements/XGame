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
$matchID = sql_quote($_GET['matchID']);
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
// QUERY: match
$matchQ = mysql_query("SELECT * FROM matches WHERE (confirmed1 = '0' OR confirmed2 = '0') AND matchID = '$matchID' AND accepted1 = '1' AND accepted2 = '1'", $connection);
// CHECK: if this matchID exist
if (mysql_num_rows($matchQ) == '0') {echo "<h4>&#8226; There is no match with that ID!</h4>"; return;}
$matchR = mysql_fetch_array($matchQ);
$opponent1 = $matchR["opponent1"];
$opponent2 = $matchR["opponent2"];
$confirmed1 = $matchR["confirmed1"];
$confirmed2 = $matchR["confirmed2"];
$score1 = $matchR["score1"];
$score2 = $matchR["score2"];
// QUERY: opponent1
$opponent1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $opponent1R = mysql_fetch_array($opponent1Q); $opponent1name = $opponent1R["name"];
// QUERY: opponent2
$opponent2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $opponent2R = mysql_fetch_array($opponent2Q); $opponent2name = $opponent2R["name"];
// CHECK: if is admin of both clans
$teammembersQ = mysql_query("SELECT * FROM teammembers WHERE (teamID = '$opponent1' OR teamID = '$opponent2') AND pid = '$pid' AND rights > '2'", $connection);
if (mysql_num_rows($teammembersQ) == '0') {echo "<h4>&#8226; You are not admin of this clan!</h4>"; return;}
$teammembersR = mysql_fetch_array($teammembersQ);
$teamID = $teammembersR["teamID"];
// CHECK: score
if ($score1 == "canceled" && $score2 == "canceled") {$selected1 = "selected='selected'";}
if ($score1 == "noshow1" && $score2 == "noshow1") {$selected2 = "selected='selected'";}
if ($score1 == "noshow2" && $score2 == "noshow2") {$selected3 = "selected='selected'";}
if (is_numeric($score1) && is_numeric($score2)) {$selected4 = "selected='selected'";}
// RESULT: enter
if ($confirmed1 == '0' && $confirmed2 == '0') {
	eval ("\$resultENTER = \"".gettemplate("resultENTER")."\";");
	echo $resultENTER;
}
// RESULT: edit
if ($confirmed1 == '1' && $confirmed2 == '0' && $teamID == $opponent1) {
	eval ("\$resultEDIT = \"".gettemplate("resultEDIT")."\";");
	echo $resultEDIT;
}
// RESULT: edit
if ($confirmed1 == '0' && $confirmed2 == '1' && $teamID == $opponent2) {
	eval ("\$resultEDIT = \"".gettemplate("resultEDIT")."\";");
	echo $resultEDIT;
}
// RESULT: accept
$score = $score1." - ".$score2;
if ($score1 == "noshow1" && $score2 == "noshow1") {$score = $opponent1name." didnt show up";}
if ($score1 == "noshow2" && $score2 == "noshow2") {$score = $opponent2name." didnt show up";}
if ($score1 == "canceled" && $score2 == "canceled") {$score = "match is canceled";}
if ($confirmed1 == '1' && $confirmed2 == '0' && $teamID == $opponent2) {
	eval ("\$resultACCEPT = \"".gettemplate("resultACCEPT")."\";");
	echo $resultACCEPT;
}
// RESULT: accept
if ($confirmed1 == '0' && $confirmed2 == '1' && $teamID == $opponent1) {
	eval ("\$resultACCEPT = \"".gettemplate("resultACCEPT")."\";");
	echo $resultACCEPT;
}
?>