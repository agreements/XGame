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
$newsID = sql_quote($_GET['newsID']);
// QUERY: news
$newsQ = mysql_query("SELECT * FROM news WHERE newsID = '$newsID'", $connection);
if (mysql_num_rows($newsQ) == '0') {echo "<h4>&#8226; There is no news with this ID!</h4>"; return;}
$newsR = mysql_fetch_array($newsQ);
$title = $newsR["title"];
$date = date("d.m.Y - H:i", $newsR[date]);
$authorID = $newsR["author"];
$content = showBBcodes($newsR["content"]);
// QUERY: member
$memberQ = mysql_query("SELECT * FROM users WHERE pid = '$authorID'", $connection); $memberR = mysql_fetch_array($memberQ); $authorNAME = $memberR["nickname"];
// OUTPUT: news
eval ("\$newsview = \"".gettemplate("newsview")."\";");
echo $newsview;
// OUTPUT: commentnew
$section = "news"; 
if(isset($pid)) {
	$id = $newsID;
	eval ("\$addbbcode = \"".gettemplate("addbbcode")."\";");
	eval ("\$commentnew = \"".gettemplate("commentnew")."\";");
	echo $commentnew;
}
$commentsQ = mysql_query("SELECT * FROM comments WHERE section = '$section' AND id = '$newsID' ORDER BY commentID ASC", $connection);
if (mysql_num_rows($commentsQ) != '0') {
	// OUTPUT: comments
	eval ("\$comments = \"".gettemplate("comments")."\";");
	echo $comments;
}
// QUERY: comments
while ($commentsR = mysql_fetch_array($commentsQ)) {
	$commentID = $commentsR["commentID"];
	$content = showBBcodes($commentsR["content"]);
	$memberID = $commentsR["pid"];
	$date = date("d.m.Y H:i", $commentsR[date]);
	// DELETE BUTTON
	if ($memberID == $pid) {$deleteB = "<input type='submit' name='delete' id='delete' value='delete'>";} else {$deleteB = "";}
	// QUERY: users
	$memberQ = mysql_query("SELECT * FROM users WHERE pid = '$memberID'", $connection);  $memberR = mysql_fetch_array($memberQ); $memberNAME = $memberR["nickname"];
	// LIST:
	eval ("\$newscomments = \"".getlist("newscomments")."\";"); echo $newscomments;
}
?>