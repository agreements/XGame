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
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; return;}
// POST: reply
if (isset($_POST['reply'])) {
	$messageID = sql_quote($_POST['messageID']);
	$content = sql_quote(br2nl($_POST['content']));
	$date = time();
	$messagesQ = mysql_query("SELECT * FROM messages WHERE messageID = '$messageID' AND (sender = '$pid' OR reciever = '$pid')", $connection);
	if (mysql_num_rows($messagesQ) != '0') {
		$replyINS = mysql_query("INSERT INTO messagereply (messageID, memberID, content, date) VALUES ('$messageID', '$pid', '$content', '$date')", $connection);
		// QUERY: messages
		$messagesQ = mysql_query("SELECT * FROM messages WHERE messageID = '$messageID'", $connection); $messagesR = mysql_fetch_array($messagesQ); 
		$sender = $messagesR["sender"];
		$reciever = $messagesR["reciever"];
		if ($sender == $pid) {$viewS = '0'; $viewR = '1';}
		if ($reciever == $pid) {$viewS = '1'; $viewR = '0';}
		$messagesUPD = mysql_query("UPDATE messages SET viewS = '$viewS', viewR = '$viewR' WHERE messageID = '$messageID'", $connection);
		echo "<meta http-equiv='refresh' content='0;URL=index.php?site=messages'>"; exit;
	}
}
// POST: view
if (isset($_POST['view'])) {
	$messageID = sql_quote($_POST['messageID']);	
	// QUERY: message
	$messagesQ = mysql_query("SELECT * FROM messages WHERE messageID = '$messageID' AND (sender = '$pid' OR reciever = '$pid')", $connection);
	if (mysql_num_rows($messagesQ) == '0') {echo "<h4>&#8226; You cant see this mesaages</h4>"; return;}
	$messagesR = mysql_fetch_array($messagesQ); 
	$title = $messagesR["title"];
	$sender = $messagesR["sender"];
	$reciever = $messagesR["reciever"];
	if ($sender == $pid) {$messagesUPD = mysql_query("UPDATE messages SET viewS = '0' WHERE messageID = '$messageID'", $connection);}
	if ($reciever == $pid) {$messagesUPD = mysql_query("UPDATE messages SET viewR = '0' WHERE messageID = '$messageID'", $connection);}
	// OUTPUT: message
	eval ("\$message = \"".gettemplate("message")."\";");
	echo $message;
	// QUERY: reply
	$replyQ = mysql_query("SELECT * FROM messagereply WHERE messageID = '$messageID'", $connection);
	while ($replyR = mysql_fetch_array($replyQ)) {
		$memberID = $replyR["memberID"];
		$content = showBBcodes($replyR["content"]);
		$date = date("d.m.Y H:i", $replyR[date]);
		// QUERY: users
		$memberQ = mysql_query("SELECT * FROM users WHERE pid = '$memberID'", $connection); $memberR = mysql_fetch_array($memberQ); $nickname = $memberR["nickname"];
		// LIST: matches
		eval ("\$messagereply = \"".getlist("messagereply")."\";"); echo $messagereply;
	}
	// OUTPUT: message
	eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
	eval ("\$messagereply = \"".gettemplate("messagereply")."\";");
	echo $messagereply;	
}
?>