<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// PAGES
$rowsPerPage = $settingsR["newsPP"];
$pageNum = 1; // by default we show first page
if(isset($_GET['page'])) {$pageNum = $_GET['page'];} // if $_GET['page'] defined, use it as page number
$offset = ($pageNum - 1) * $rowsPerPage; // counting the offset
$result = mysql_query("SELECT COUNT(newsID) AS numrows FROM news", $connection);
$row = mysql_fetch_array($result, MYSQL_ASSOC);
$numrows = $row['numrows']; // how many rows we have in database
// QUERY: news
$newsQ = mysql_query("SELECT * FROM news ORDER BY newsID DESC LIMIT $offset, $rowsPerPage", $connection);
if (mysql_num_rows($newsQ) == '0') {echo "<h4>&#8226; There is no news to display!</h4>"; return;}
while ($newsR = mysql_fetch_array($newsQ)) {
	$newsID = $newsR["newsID"];
	$title = $newsR["title"];
	$date = date("d.m.Y - H:i", $newsR[date]);
	$authorID = $newsR["author"];
	$content = showBBcodes($newsR["content"]);
	// QUERY: numCOMMENTS
	$numCOMMENTSq = mysql_query("SELECT * FROM comments WHERE section = 'news' AND id = '$newsID'", $connection);
	$numCOMMENTS = mysql_num_rows($numCOMMENTSq);
	// QUERY: member
	$memberQ = mysql_query("SELECT * FROM users WHERE pid = '$authorID'", $connection);
	$memberR = mysql_fetch_array($memberQ);
	$authorNAME = $memberR["nickname"];
	// OUTPUT: news
	eval ("\$news = \"".gettemplate("news")."\";");
	echo $news;
}
// INCLUDE PAGES
include("pages/newspages.php");
?>