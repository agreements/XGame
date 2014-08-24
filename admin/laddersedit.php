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
$ladderID = sql_quote($_GET['ladderID']);
// ACTION: delete
if ($action == "delete") {
	$ladderDEL = mysql_query("DELETE FROM ladders WHERE ladderID = '$ladderID' LIMIT 1", $connection);
	echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=ladders'>"; exit;
}
// ACTION: edit
if ($action == "edit") {
	// POST: edit
	if (isset($_POST['edit'])) {
		// GET VARIABLES
		$ladderID = sql_quote($_GET['ladderID']);
		$title = sql_quote($_POST['title']);
		$guid = sql_quote($_POST['guid']);
		$game = sql_quote($_POST['game']);
		$gamemode = sql_quote($_POST['gamemode']);
		$active = sql_quote($_POST['active']);
		$ratingsystem = sql_quote($_POST['ratingsystem']);
		$rules = sql_quote(br2nl($_POST['rules']));
		$pic = $_FILES[pic];
		// DATEBASE QUERY
		$ladderEDIT = mysql_query("UPDATE ladders SET title = '$title', guid = '$guid', gamemode = '$gamemode', game = '$game', active = '$active', 
		ratingsystem = '$ratingsystem', rules = '$rules' WHERE ladderID = '$ladderID'", $connection);
		// PIC
		$filepath = "../images/ladders/";
		if ($pic[name] != "") {
			move_uploaded_file($pic[tmp_name], $filepath.$pic[name]);
			@chmod($filepath.$pic[name], 0755);
			$file_ext=strtolower(substr($pic[name], strrpos($pic[name], ".")));
			$file=$ladderID.$file_ext;
			rename($filepath.$pic[name], $filepath.$file);
			$picUPDATE = mysql_query("UPDATE ladders SET pic='$file' WHERE ladderID='$ladderID'", $connection);
		}
		echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=ladders'>"; exit;
	} else {
		// QUERY: ladder
		$ladderQ = mysql_query("SELECT * FROM ladders WHERE ladderID = '$ladderID'", $connection);
		$ladderR = mysql_fetch_array($ladderQ);
		$ladderID = $ladderR["ladderID"];
		$title = $ladderR["title"];
		$guid = $ladderR["guid"];
		$gamemode = $ladderR["gamemode"];
		$game = $ladderR["game"];
		$active = $ladderR["active"];
		$ratingsystem = $ladderR["ratingsystem"]; 
		$rules = $ladderR["rules"];
		// GAME CATEGORY
		$gameQ = mysql_query("SELECT * FROM games", $connection);
		while ($gameR = mysql_fetch_array($gameQ)) {
			$gameID = $gameR["gameID"];
			$gameTITLE = $gameR["title"];
			if ($gameID == $game) {$selected = "selected='selected'";} else {$selected = "";}
			$gameLIST .= "<option value='$gameID'$selected>$gameTITLE</option>";
		}
		// REQUIRED GUID CATEGORY
		$guidQ = mysql_query("SELECT * FROM guids", $connection);
		while ($guidR = mysql_fetch_array($guidQ)) {
			$guidID = $guidR["guidID"];
			$guidTITLE = $guidR["title"];
			if ($guidID == $guid) {$selected2 = "selected='selected'";} else {$selected2 = "";}
			$guidLIST .= "<option value='$guidID'$selected2>$guidTITLE</option>";
		}
		// ACTIVITY
		if ($active == "0") {$selected2 = "selected='selected'";} else {$selected2 = "";}
		if ($active == "1") {$selected3 = "selected='selected'";} else {$selected3 = "";}
		// GAME MODE
		if ($gamemode == "dm") {$selected4 = "selected='selected'";} else {$selected4 = "";}
		if ($gamemode == "sd") {$selected5 = "selected='selected'";} else {$selected5 = "";}
		if ($gamemode == "ctf") {$selected6 = "selected='selected'";} else {$selected6 = "";}
		if ($gamemode == "race") {$selected7 = "selected='selected'";} else {$selected7 = "";}
		if ($gamemode == "match") {$selected8 = "selected='selected'";} else {$selected8 = "";}
		// RATING SYSTEM
		if ($ratingsystem == "elo") {$selected9 = "selected='selected'";} else {$selected9 = "";}
		if ($ratingsystem == "entish") {$selected10 = "selected='selected'";} else {$selected10 = "";}
		// EDIT: output
		eval ("\$ladderedit = \"".gettemplate("ladderedit")."\";");
		echo $ladderedit;
	}

}
//POST: add
elseif (isset($_POST['add'])) {
	// GET VARIABLES
	$title = sql_quote($_POST['title']);
	$guid = sql_quote($_POST['guid']);
	$gamemode = sql_quote($_POST['gamemode']);
	$date = time();
	$game = sql_quote($_POST['game']);
	$active = sql_quote($_POST['active']);
	$ratingsystem = sql_quote($_POST['ratingsystem']);
	$rules = sql_quote(br2nl($_POST['rules']));
	// DATEBASE QUERY
	$ladderADD = mysql_query("INSERT INTO ladders (title, guid, gamemode, date, game, active, ratingsystem, rules) 
	VALUES ('$title', '$guid', '$gamemode', '$date', '$game', '$active', '$ratingsystem', '$rules')"
	, $connection);
	echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=ladders'>"; exit;
}
// ACTION: form	
else {
	// GAME CATEGORY
	$gameQ = mysql_query("SELECT * FROM games", $connection);
	while ($gameR = mysql_fetch_array($gameQ)) {
		$gameID = $gameR["gameID"];
		$game = $gameR["title"];
		$gameLIST .= "<option value='$gameID'>$game</option>";
	}
	// REQUIRED GUID CATEGORY
	$guidQ = mysql_query("SELECT * FROM guids", $connection);
	while ($guidR = mysql_fetch_array($guidQ)) {
		$guidID = $guidR["guidID"];
		$guid = $guidR["title"];
		$guidLIST .= "<option value='$guidID'>$guid</option>";
	}
	eval ("\$ladderadd = \"".gettemplate("ladderadd")."\";");
	echo $ladderadd;
}

?>