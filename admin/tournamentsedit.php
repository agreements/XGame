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
$tournamentID = sql_quote($_GET['tournamentID']);
// ACTION: delete
if ($action == "delete") {
	$tournamentDEL = mysql_query("DELETE FROM tournaments WHERE tournamentsID = '$tournamentID' LIMIT 1", $connection);
	echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=tournaments'>"; exit;
}
// ACTION: edit
if ($action == "edit") {
	// POST: edit
	if (isset($_POST['edit'])) {
		// GET VARIABLES
		$tournamentID = sql_quote($_GET['tournamentID']);
		$title = sql_quote($_POST['title']);
		$game = sql_quote($_POST['game']);
		$gamemode = sql_quote($_POST['gamemode']);
		$guid = sql_quote($_POST['guid']);
		$size = sql_quote($_POST['size']);
		$prize1 = sql_quote($_POST['prize1']);
		$prize2 = sql_quote($_POST['prize2']);
		$prize3 = sql_quote($_POST['prize3']);
		$signup = strtotime(sql_quote($_POST['signup']));
		$closed = strtotime(sql_quote($_POST['closed']));
		$start = strtotime(sql_quote($_POST['start']));
		$rules = sql_quote(br2nl($_POST['rules']));
		// DATEBASE QUERY
		$ladderEDIT = mysql_query("UPDATE tournaments SET title = '$title', guid = '$guid', gamemode = '$gamemode', game = '$game', rules = '$rules', prize1 = '$prize1', prize2 = '$prize2', prize3 = '$prize3', size = '$size',
		signupOPEN = '$signup', signupCLOSED = '$closed', start = '$start' WHERE tournamentsID = '$tournamentID'", $connection);
		echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=tournaments'>"; exit;
	} else {
		// QUERY: ladder
		$tournamentQ = mysql_query("SELECT * FROM tournaments WHERE tournamentsID = '$tournamentID'", $connection);
		$tournamentR = mysql_fetch_array($tournamentQ);
		$title = $tournamentR["title"];
		$guid = $tournamentR["guid"];
		$gamemode = $tournamentR["gamemode"];
		$game = $tournamentR["game"];
		$size = $tournamentR["size"];
		$prize1 = $tournamentR["prize1"];
		$prize2 = $tournamentR["prize2"];
		$prize3 = $tournamentR["prize3"];
		$signup = date("Y-m-d H:i", $tournamentR[signupOPEN]);
		$closed = date("Y-m-d H:i", $tournamentR[signupCLOSED]);
		$start = date("Y-m-d H:i", $tournamentR[start]);
		$rules = $tournamentR["rules"];
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
		// GAME MODE
		if ($gamemode == "dm") {$selected4 = "selected='selected'";} else {$selected4 = "";}
		if ($gamemode == "sd") {$selected5 = "selected='selected'";} else {$selected5 = "";}
		if ($gamemode == "ctf") {$selected6 = "selected='selected'";} else {$selected6 = "";}
		if ($gamemode == "race") {$selected7 = "selected='selected'";} else {$selected7 = "";}
		if ($gamemode == "match") {$selected8 = "selected='selected'";} else {$selected8 = "";}
		// GAME MODE
		if ($size == "8") {$selected9 = "selected='selected'";} else {$selected9 = "";}
		if ($size == "16") {$selected10 = "selected='selected'";} else {$selected10 = "";}
		if ($size == "32") {$selected11 = "selected='selected'";} else {$selected11 = "";}
		if ($size == "64") {$selected12 = "selected='selected'";} else {$selected12 = "";}
		// EDIT: output
		eval ("\$tournamentedit = \"".gettemplate("tournamentedit")."\";");
		echo $tournamentedit;
	}

}
//POST: add
elseif (isset($_POST['add'])) {
	// GET VARIABLES
	$title = sql_quote($_POST['title']);
	$game = sql_quote($_POST['game']);
	$gamemode = sql_quote($_POST['gamemode']);
	$guid = sql_quote($_POST['guid']);
	$size = sql_quote($_POST['size']);
	$prize1 = sql_quote($_POST['prize1']);
	$prize2 = sql_quote($_POST['prize2']);
	$prize3 = sql_quote($_POST['prize3']);
	$signup = strtotime(sql_quote($_POST['signup']));
	$closed = strtotime(sql_quote($_POST['closed']));
	$start = strtotime(sql_quote($_POST['start']));
	$rules = sql_quote(br2nl($_POST['rules']));
	// DATEBASE QUERY
	$tournamentADD = mysql_query("INSERT INTO tournaments (title, guid, gamemode, game, rules, prize1, prize2, prize3, size, signupOPEN, signupCLOSED, start) 
	VALUES ('$title', '$guid', '$gamemode', '$game', '$rules', '$prize1', '$prize2', '$prize3', '$size', '$signup', '$closed', '$start')", $connection);
	$tournamentID = mysql_insert_id();
	// ROUND 6
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m1')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m2')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m3')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m4')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m5')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m6')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m7')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m8')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m9')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m10')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m11')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m12')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m13')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m14')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m15')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r6m16')", $connection);
	// ROUND 5
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r5m1')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r5m2')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r5m3')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r5m4')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r5m5')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r5m6')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r5m7')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r5m8')", $connection);
	// ROUND 4
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r4m1')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r4m2')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r4m3')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r4m4')", $connection);
	// ROUND 3
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r3m1')", $connection);
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r3m2')", $connection);
	// ROUND 2
	mysql_query("INSERT INTO tournamentmatches (tournamentID, bracketID) VALUES ('$tournamentID', 'r2m1')", $connection);
	// REDIRECT
	echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=tournaments'>"; exit;
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
	eval ("\$tournamentadd = \"".gettemplate("tournamentadd")."\";");
	echo $tournamentadd;
}

?>