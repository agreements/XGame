<?php // 
// INCLUDES
require_once("../includes/session.php");
require_once("../includes/connect.php");
require_once("../includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// OUTPUT: title
eval ("\$admins = \"".gettemplate("admins")."\";");
echo $admins;
// QUERY: users
$usersQ = mysql_query("SELECT * FROM users WHERE rights >= '2'", $connection);
while ($usersR = mysql_fetch_array($usersQ)) {
	$memberID = $usersR["pid"];
	$nickname = $usersR["nickname"];
	$country = $usersR["country"];
	$rights = $usersR["rights"];
	if ($rights == '1') {$right = "User";}
	if ($rights == '2') {$right = "Admin";}
	if ($rights == '3') {$right = "Super admin";}
	// OUTPUT: news
	eval ("\$adminsLIST = \"".getlist("adminsLIST")."\";"); echo $adminsLIST;
}

	
?>