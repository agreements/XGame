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
// POST: post
if (isset($_POST['post'])) {
	$section = sql_quote($_POST['section']);
	$content = sql_quote(br2nl($_POST['content']));
	$id = sql_quote($_POST['id']);
	$date = time();
	// QUERY: insrt
	$commentADD = mysql_query("INSERT INTO comments (section, id, pid, content, date) VALUES ('$section', '$id', '$pid', '$content', '$date')", $connection);
	// REDIRECT
	if ($section == "matches") {$link = "<meta http-equiv='refresh' content='0;URL=index.php?site=match&matchID=$id'>";}
	if ($section == "news") {$link = "<meta http-equiv='refresh' content='0;URL=index.php?site=newsview&newsID=$id'>";}
	echo $link; exit;
}
// POST: delete
if (isset($_POST['delete'])) {
	$commentID = sql_quote($_POST['commentID']);
	$section = sql_quote($_POST['section']);
	$id = sql_quote($_POST['id']);
	// QUERY: delete
	$commentDEL = mysql_query("DELETE FROM comments WHERE commentID = '$commentID' AND pid = '$pid' LIMIT 1", $connection);
	// REDIRECT
	if ($section == "matches") {$link = "<meta http-equiv='refresh' content='0;URL=index.php?site=match&matchID=$id'>";}
	if ($section == "news") {$link = "<meta http-equiv='refresh' content='0;URL=index.php?site=newsview&newsID=$id'>";}
	echo $link; exit;
}
?>