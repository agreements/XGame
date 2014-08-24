<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// CHECK: login or loged
if (isset($_SESSION['pid'])) {
	$pid = $_SESSION['pid'];
	$righs = $_SESSION['rights'];
	// QUERY: users
	$userQ = mysql_query("SELECT * FROM users WHERE pid = '$pid'", $connection); $userR = mysql_fetch_array($userQ);
	$avatarURL = $userR["pic"];
	$nick = $userR["nickname"];
	// ADMIN VIEW
	if ($righs == '3') {$adminCP = "admin";} else {$adminCP = "";}
	// COUNT: clan invitation
	$claninvQ = mysql_query("SELECT * FROM teammembers WHERE pid = '$pid' AND accepted ='0'", $connection);  $claninvCOUNT = mysql_num_rows($claninvQ);
	// COUNT: ch outgoing
	$choutgoingCOUNT = 0;
	$choutQ = mysql_query("SELECT * FROM teammembers WHERE pid = '$pid' AND rights > '2'", $connection);
	while ($choutR = mysql_fetch_array($choutQ)) {
		$teamID = $choutR["teamID"];
		$matchQ = mysql_query("SELECT * FROM matches WHERE opponent1 = '$teamID' AND accepted1 = '1' AND accepted2 = '0'", $connection);
		while ($matchR = mysql_fetch_array($matchQ)) {
		$choutgoingCOUNT++;
		}
	}
	// COUNT: ch incomming
	$chinQ = mysql_query("SELECT * FROM matches WHERE accepted1 = '1' AND accepted2 = '0'", $connection);
	$chincommingCOUNT = 0;
	while ($chinR = mysql_fetch_array($chinQ)) {
		$opponent2 = $chinR["opponent2"];
		$clanadmin2Q = mysql_query("SELECT * FROM teammembers WHERE teamID = '$opponent2' AND pid = '$pid' AND rights > '1'", $connection);
		if (mysql_num_rows($clanadmin2Q) != '0') {$chincommingCOUNT++;}
	}
	// COUNT: number
	$count = $claninvCOUNT + $choutgoingCOUNT + $chincommingCOUNT;
	// OUTPUT: logged
	eval ("\$logged = \"".gettemplate("logged")."\";");
	echo $logged;
} else {
	if ($site == "login") {
		// OUTPUT: loginpage
		eval ("\$loginpage = \"".gettemplate("loginpage")."\";");
		echo $loginpage;
	} else {
		// OUTPUT: login
		eval ("\$login = \"".gettemplate("login")."\";");
		echo $login;
	}
}

?>