<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// CHECK: validation AND auth
$pid = $_SESSION['pid'];
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
// POST: edit
if (isset($_POST['edit'])) {
	$nickname = sql_quote($_POST['nickname']);
	$country = sql_quote($_POST['country']);
	$gender = sql_quote($_POST['gender']);
	$pic = $_FILES[pic];
	$userUPDATE = mysql_query("UPDATE users SET nickname = '$nickname', country = '$country', gender = '$gender' WHERE pid = '$pid'", $connection);
	// PIC
	$filepath = "./images/avatars/";
	if ($pic[name] != "") {
		move_uploaded_file($pic[tmp_name], $filepath.$pic[name]);
		@chmod($filepath.$pic[name], 0755);
		$file_ext=strtolower(substr($pic[name], strrpos($pic[name], ".")));
		$file=$pid.$file_ext;
		rename($filepath.$pic[name], $filepath.$file);
		$picUPDATE = mysql_query("UPDATE users SET pic='$file' WHERE pid='$pid'", $connection);
	}
	// REDDIRECT
	redirectto("index.php?site=personinfo&pid=$pid"); exit;
} 
else {
	// QUERY: person
	$personQ = mysql_query("SELECT * FROM users WHERE pid = '$pid'", $connection);
	$personR = mysql_fetch_array($personQ);
	$nickname = $personR["nickname"];
	$country = $personR["country"];
	$countryNAME = strtoupper(countryname($country));
	$gender = $personR["gender"];
	$genderCAPS = strtoupper($gender);
	// OUTPUT: personedit
	eval ("\$personedit = \"".gettemplate("personedit")."\";");
	echo $personedit;
}
?>