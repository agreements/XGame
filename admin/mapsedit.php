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
$mapID = sql_quote($_GET['mapID']);
// ACTION: delete
if ($action == "delete") {
	$mapDEL = mysql_query("DELETE FROM maps WHERE mapID = '$mapID' LIMIT 1", $connection);
	echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=maps'>"; exit;
}
// ACTION: add
elseif (isset($_POST['add'])) {
	// GET VARIABLES
	$title = sql_quote($_POST['title']);
	$ladderID = sql_quote($_POST['ladderID']);
	// DATEBASE QUERY
	$mapADD = mysql_query("INSERT INTO maps (title, ladderID) VALUES ('$title', '$ladderID')", $connection);
	echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=maps'>"; exit;
} 
// FORM: add
else {
	// LADDER QUERY
	$ladderQ = mysql_query("SELECT * FROM ladders", $connection);
	while ($ladderR = mysql_fetch_array($ladderQ)) {
		$ladderID = $ladderR["ladderID"];
		$ladderTITLE = $ladderR["title"];
		$gameID = $ladderR["game"];
		// QUERY: game
		$gameQ= mysql_query("SELECT * FROM games WHERE gameID = '$gameID'", $connection);
		$gameR = mysql_fetch_array($gameQ);	
		$gameTITLE = $gameR["title"];
		$ladderLIST .= "<option value='$ladderID'$selected>$ladderTITLE [ $gameTITLE ]</option>";
	}
	eval ("\$mapsadd = \"".gettemplate("mapsadd")."\";");
	echo $mapsadd;
}
?>

