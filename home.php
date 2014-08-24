<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// OUTPUT: slider
eval ("\$homeSLIDER = \"".gettemplate("homeSLIDER")."\";");
echo $homeSLIDER;
// QUERY: news
$newsQ = mysql_query("SELECT * FROM news ORDER BY newsID DESC LIMIT 5", $connection);
if (mysql_num_rows($newsQ) == '0') {$lastNEWS = "&#8226; There is no news to display!";}
while ($newsR = mysql_fetch_array($newsQ)) {
	$newsID = $newsR["newsID"];
	$title = substr($newsR["title"], 0, 50);
	$lastNEWS .= "&#8226; <a href='index.php?site=newsview&amp;newsID=".$newsID."'>".$title."...</a></br>";
}
// OUTPUT: last news
eval ("\$homeNEWS = \"".gettemplate("homeNEWS")."\";");
echo $homeNEWS;
// QUERY: last matches
$matchQ = mysql_query("SELECT * FROM matches WHERE confirmed1 = '1' AND confirmed2 = '1' ORDER BY matchID DESC LIMIT 5", $connection);
if (mysql_num_rows($matchQ) == '0') {$lastNEWS = "&#8226; There is no matches to display!";}
while ($matchR = mysql_fetch_array($matchQ)) {
	$matchID = $matchR["matchID"];
	$opponent1 = $matchR["opponent1"];
	$opponent2 = $matchR["opponent2"];
	$score1 = $matchR["score1"];
	$score2 = $matchR["score2"];
	// CHECK: score
	$score = $score1." : ".$score2;
	if ($score1 == "noshow1" && $score2 == "noshow1") {$score = $opponent1name." didnt show up";}
	if ($score1 == "noshow2" && $score2 == "noshow2") {$score = $opponent2name." didnt show up";}
	if ($score1 == "canceled" && $score2 == "canceled") {$score = "match is canceled";}
	// QUERY: opponent1
	$opponent1Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent1'", $connection); $opponent1R = mysql_fetch_array($opponent1Q); $opponent1name = $opponent1R["name"];
	// QUERY: opponent2
	$opponent2Q = mysql_query("SELECT * FROM teams WHERE teamID = '$opponent2'", $connection); $opponent2R = mysql_fetch_array($opponent2Q); $opponent2name = $opponent2R["name"];
	$lastMATCHES .= "
	<table width='100%' border='0' cellspacing='0' cellpadding='0'>
	  <tr>
		<td><a href='index.php?site=teaminfo&teamID=$opponent1'>$opponent1name</a> vs. <a href='index.php?site=teaminfo&teamID=$opponent2'>$opponent2name</a></td>
		<td align='right'>$score</td>
		<td width='50' align='right'><button type='button' onclick=\"window.location='index.php?site=match&matchID=$matchID'\">view</button></td>
	  </tr>
	</table>";
}
// OUTPUT: last matches
eval ("\$homeMATCHES = \"".gettemplate("homeMATCHES")."\";");
echo $homeMATCHES;
?>