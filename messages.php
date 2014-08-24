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
// QUERY: match ENTER RESULT
$messagesQ = mysql_query("SELECT * FROM messages WHERE (sender = '$pid' OR reciever = '$pid') ORDER BY lastdate DESC", $connection);
if (mysql_num_rows($messagesQ) == '0') {echo "<h4>&#8226; There is no messages to display!</h4>"; return;}
// OUTPUT: matches
eval ("\$messages = \"".gettemplate("messages")."\";");
echo $messages;
while ($messagesR = mysql_fetch_array($messagesQ)) {
	$messageID = $messagesR["messageID"];
	$title = $messagesR["title"];
	$sender = $messagesR["sender"];
	$reciever = $messagesR["reciever"];
	$lastdate = date("d.m.Y H:i", $messagesR[lastdate]);
	$viewS = $messagesR["viewS"];
	$viewR = $messagesR["viewR"];
	// RULES:
	if ($sender == $pid) {$status = "To: "; $memberID = $reciever;}
	if ($reciever == $pid) {$status = "From: "; $memberID = $sender;}
	// READ STATUS
	$read = "h5";
	if ($sender == $pid && $viewS == '1') {$read = "h4";}
	if ($reciever == $pid && $viewR == '1') {$read = "h4";}
	// QUERY: users
	$memberQ = mysql_query("SELECT * FROM users WHERE pid = '$memberID'", $connection); $memberR = mysql_fetch_array($memberQ); $nickname = $memberR["nickname"];
	// LIST: matches
	eval ("\$messages = \"".getlist("messages")."\";"); echo $messages;
}	

?>