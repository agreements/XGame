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
$newsID = sql_quote($_GET['newsID']);
// ACTION: delte
if ($action == "delete") {
	$newsDEL = mysql_query("DELETE FROM news WHERE newsID = '$newsID' LIMIT 1", $connection);
	echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=news'>"; exit;
}
// ACTION: edit
if ($action == "edit") {
	// POST: edit
	if (isset($_POST['edit'])) {
		// GET VARIABLES
		$newsID = sql_quote($_GET['newsID']);
		$title = sql_quote($_POST['title']);
		$content = sql_quote(br2nl($_POST['content']));
		// DATEBASE QUERY
		$newsUPDATE = mysql_query("UPDATE news SET title = '$title', content = '$content' WHERE newsID = '$newsID'", $connection);
		echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=news'>"; exit;
	} else {
		// NEWS 
		$newsQ = mysql_query("SELECT * FROM news WHERE newsID = '$newsID'", $connection);
		$newsR = mysql_fetch_array($newsQ);
		$newsID = $newsR["newsID"];
		$title = $newsR["title"];
		$author = $newsR["author"];
		$content = br2n($newsR["content"]);
		// OUTPUT
		eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
		eval ("\$newsedit = \"".gettemplate("newsedit")."\";");
		echo $newsedit;
	}
}
// ACTION: add
if ($action == "add") {
	if (isset($_POST['add'])) {
		// GET VARIABLES
		$title = sql_quote($_POST['title']);
		$content = sql_quote(br2nl($_POST['content']));
		$date = time();
		$authorID = $_SESSION['pid'];
		// DATEBASE QUERY
		$newsADD = mysql_query("INSERT INTO news (title, date, author, content) VALUES ('$title', '$date', '$authorID', '$content')", $connection);
		echo "<meta http-equiv='refresh' content='0;URL=admincp.php?site=news'>"; exit;
	} else {
		// NEWS ADD
		eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
		eval ("\$newsadd = \"".gettemplate("newsadd")."\";");
		echo $newsadd;
	}
}
?>