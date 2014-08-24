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
$memberID = sql_quote($_GET['memberID']);
// ACTION: send
if (isset($_POST['send'])) {
	$memberID = sql_quote($_POST['memberID']);
	$title = sql_quote($_POST['title']);
	$content = sql_quote(br2nl($_POST['content']));
	$date = time();
	// DATEBASE QUERY
	if ($pid == $memberID) {echo "<h4>&#8226; You cant send message to yourself!</h4>"; return;}
	$messageINS = mysql_query("INSERT INTO messages (title, sender, reciever, lastdate, viewS, viewR) VALUES ('$title', '$pid', '$memberID', '$date', '0', '1')", $connection);
	$messageID = mysql_insert_id(); 
	$replyINS = mysql_query("INSERT INTO messagereply (messageID, memberID, content, date) VALUES ('$messageID', '$pid', '$content', '$date')", $connection);
	echo "<meta http-equiv='refresh' content='0;URL=index.php?site=messages'>"; exit;

}
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
// OUTPUT: matches
eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
eval ("\$messagenew = \"".gettemplate("messagenew")."\";");
echo $messagenew;		

?>