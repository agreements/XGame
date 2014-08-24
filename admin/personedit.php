<?php // 
// INCLUDES
require_once("../includes/session.php");
require_once("../includes/connect.php");
require_once("../includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// ACTION: edit
if (isset($_POST['edit'])) {
	$memberID = sql_quote($_POST['memberID']);
	$nickname = sql_quote($_POST['nickname']);
	$country = sql_quote($_POST['country']);
	$gender = sql_quote($_POST['gender']);
	$email = sql_quote($_POST['email']);
	$rights = sql_quote($_POST['rights']);
	$status = sql_quote($_POST['status']);
	$pic = $_FILES[pic];
	$userUPDATE = mysql_query("UPDATE users SET nickname = '$nickname', country = '$country', gender = '$gender', email = '$email', rights = '$rights', activated = '$status' WHERE pid = '$memberID'", $connection);
	// PIC
	$filepath = "../images/avatars/";
	if ($pic[name] != "") {
		move_uploaded_file($pic[tmp_name], $filepath.$pic[name]);
		@chmod($filepath.$pic[name], 0755);
		$file_ext=strtolower(substr($pic[name], strrpos($pic[name], ".")));
		$file=$pid.$file_ext;
		rename($filepath.$pic[name], $filepath.$file);
		$picUPDATE = mysql_query("UPDATE users SET pic='$file' WHERE pid='$memberID'", $connection);
	}
	// REDDIRECT
	redirectto("./admincp.php?site=personedit&amp;memberID=$memberID"); exit;
}
// ACTION: add
if (isset($_POST['add'])) {
	$memberID = sql_quote($_POST['memberID']);
	$gamesLIST = sql_quote($_POST['gamesLIST']);
	$date = time();
	$gameadminsADD = mysql_query("INSERT INTO gameadmins (pid, gameID, date) VALUES ('$memberID', '$gamesLIST', '$date')", $connection);
	// REDDIRECT
	redirectto("./admincp.php?site=personedit&amp;memberID=$memberID"); exit;
}
// ACTION: delte
if (isset($_POST['delete'])) {
	$memberID = sql_quote($_POST['memberID']);
	$gameadminID = sql_quote($_POST['gameadminID']);
	$gameadminDEL = mysql_query("DELETE FROM gameadmins WHERE gameadminID = '$gameadminID' LIMIT 1", $connection);
	// REDDIRECT
	redirectto("./admincp.php?site=personedit&amp;memberID=$memberID"); exit;
}
if (isset($_POST['search'])) {$memberID = sql_quote($_POST['memberID']);} else {$memberID = sql_quote($_GET['memberID']);}
if (!is_numeric($memberID)) {
	// OUTPUT: person
	eval ("\$person = \"".gettemplate("person")."\";");
	echo $person;
} else {
	// QUERY: member
	$memberQ = mysql_query("SELECT * FROM users WHERE pid = '$memberID'", $connection);
	if (mysql_num_rows($memberQ) == '0') {echo "<h4>&#8226; There is no player with that ID!</h4>"; return;}
	$memberR = mysql_fetch_array($memberQ);
	$nickname = $memberR["nickname"];
	$country = $memberR["country"];
	$countryCAPS = strtoupper($country);
	$gender = $memberR["gender"];
	$genderCAPS = strtoupper($gender);
	$avatarURL = $memberR["pic"];
	$email = $memberR["email"];
	$ip = $memberR["ip"];
	$rights = $memberR["rights"];
	$rightsCAPS = strtoupper($rights);
	$status = $memberR["activated"];
	$statusCAPS = strtoupper($status);
	$membersince = date("d.m.Y", $memberR[joined]);
	// QUERY: game
	$gameQ = mysql_query("SELECT * FROM games", $connection);
	while ($gameR = mysql_fetch_array($gameQ)) {
		$gameID = $gameR["gameID"];
		$gameTITLE = $gameR["title"];
		// QUERY: gameadmins
		$gameadminsQ = mysql_query("SELECT * FROM gameadmins WHERE gameID = '$gameID' AND pid = '$memberID'", $connection);
		if (mysql_num_rows($gameadminsQ) == '0') {$gamesLIST .= "<option value='$gameID'>$gameTITLE</option>";}
	}
	if (isset($gamesLIST)) {$addBUTTON = "<input type='submit' name='add' id='add' value='add' />";} else {$addBUTTON = "";}
	// QUERY: gamesadmin
	$gamesadminQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$memberID'", $connection);
	if (mysql_num_rows($gamesadminQ) == '0') {$adminLIST = "<h4>&#8226; This user is not game admin</h4>";}
	// OUTPUT: personinfo
	eval ("\$personedit = \"".gettemplate("personedit")."\";");
	echo $personedit;
	// OUTPUT: list
	while ($gamesadminR = mysql_fetch_array($gamesadminQ)) {
		$gameID = $gamesadminR["gameID"];
		$adminDATE = date("d.m.Y H:i", $gamesadminR[date]);
		$gameadminID = $gamesadminR["gameadminID"];
		// QUERY: game
		$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$gameID'", $connection);
		$gameR = mysql_fetch_array($gameQ);
		$gameTITLE = $gameR["title"];
		// LIST: adminLIST
		eval ("\$personeditLIST = \"".getlist("personeditLIST")."\";"); echo $personeditLIST;
	}
}

?>