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
// OUTPUT: search
eval ("\$search = \"".gettemplate("search")."\";");
echo $search;
// POST: search
if (isset($_POST['search'])) {
	$for = sql_quote($_POST['for']);
	$term = sql_quote($_POST['term']);
	// OUTPUT: searchlist
	eval ("\$subSEARCH = \"".gettemplate("subSEARCH")."\";");
	echo $subSEARCH;
	// SEARCH: users
	if ($for == "users") {
		$usersQ = mysql_query("SELECT * FROM users WHERE (pid LIKE '%$term%') OR (nickname LIKE '%$term%') OR (email LIKE '%$term%') OR (country LIKE '%$term%') LIMIT 50", $connection);
		while ($usersR = mysql_fetch_array($usersQ)) {
			$memberID = $usersR["pid"];
			$nickname = $usersR["nickname"];
			$country = $usersR["country"];
			$countryCODE = strtolower($country);
			$gender = $usersR["gender"];
			$lastactive = $usersR["lastactive"];
			// QUERY: status
			$userSTATUS = getstatus($lastactive);
			// LIST: subSEARCHusers
			eval ("\$subSEARCHusers = \"".getlist("subSEARCHusers")."\";"); echo $subSEARCHusers;
		}
	}
	// SEARCH: teams
	if ($for == "teams") {
		$teamQ = mysql_query("SELECT * FROM teams WHERE (teamID LIKE '%$term%') OR (name LIKE '%$term%') OR (tag LIKE '%$term%') OR (web LIKE '%$term%') LIMIT 50", $connection);
		while ($teamR = mysql_fetch_array($teamQ)) {
			$teamID = $teamR["teamID"];
			$name = $teamR["name"];
			$tag = $teamR["tag"];
			$web = $teamR["web"];
			// LIST: subSEARCHteams
			eval ("\$subSEARCHteams = \"".getlist("subSEARCHteams")."\";"); echo $subSEARCHteams;
		}
	}
}	
?>