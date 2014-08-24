<?php // 
// INCLUDES
require_once("../includes/session.php");
require_once("../includes/connect.php");
require_once("../includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// GET ACTION
$action = sql_quote($_GET['action']);
$gameID = sql_quote($_GET['gameID']);
// ACTION: delete
if ($action == "delete") {
	$gameID = sql_quote($_GET['gameID']);
	$gameDEL = mysql_query("DELETE FROM games WHERE gameID = '$gameID' LIMIT 1", $connection);
	echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=games'>"; exit;
}
// ACTION: edit
if ($action == "edit") {
	// POST: edit
	if (isset($_POST['edit'])) {
		// GET VARIABLES
		$gameID = sql_quote($_GET['gameID']);
		$title = sql_quote($_POST['title']);
		$pic = $_FILES[pic];
		// DATEBASE QUERY
		$gameEDIT = mysql_query("UPDATE games SET title = '$title' WHERE gameID = '$gameID'", $connection);
		// PIC
		$filepath = "../images/games/";
		if ($pic[name] != "") {
			move_uploaded_file($pic[tmp_name], $filepath.$pic[name]);
			@chmod($filepath.$pic[name], 0755);
			$file_ext=strtolower(substr($pic[name], strrpos($pic[name], ".")));
			$file=$gameID.$file_ext;
			rename($filepath.$pic[name], $filepath.$file);
			$picUPDATE = mysql_query("UPDATE games SET pic='$file' WHERE gameID='$gameID'", $connection);
		}
		echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=games'>"; exit;
	} else {
		// QUERY: ladder
		$gameQ = mysql_query("SELECT * FROM games WHERE gameID = '$gameID'", $connection);
		$gameR = mysql_fetch_array($gameQ);
		$gameID = $gameR["gameID"];
		$title = $gameR["title"];
		$pic = $gameR["pic"];
		// OUTPUT: ladderedit
		eval ("\$gamesedit = \"".gettemplate("gamesedit")."\";");
		echo $gamesedit;
	}
}
// ACTION: add
elseif (isset($_POST['add'])) {
	// GET VARIABLES
	$title = sql_quote($_POST['title']);
	$pic = $_FILES[pic];
	// PIC
	$filepath = "../images/games/";
	// DATEBASE QUERY
	$gameADD = mysql_query("INSERT INTO games (title) VALUES ('$title')", $connection);
	$gameID = mysql_insert_id();
	if ($pic[name] != "") {
		move_uploaded_file($pic[tmp_name], $filepath.$pic[name]);
		@chmod($filepath.$pic[name], 0755);
		$file_ext=strtolower(substr($pic[name], strrpos($pic[name], ".")));
		$file=$gameID.$file_ext;
		rename($filepath.$pic[name], $filepath.$file);
		$picUPDATE = mysql_query("UPDATE games SET pic='$file' WHERE gameID='$gameID'", $connection);
	}
	echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=games'>"; exit;
} 
// ACTION: form
else {
	eval ("\$gamesadd = \"".gettemplate("gamesadd")."\";");
	echo $gamesadd;
}
?>