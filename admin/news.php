<?php // 
// INCLUDES
require_once("../includes/session.php");
require_once("../includes/connect.php");
require_once("../includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// PAGES
$rowsPerPage = $settingsR["newsPPlist"];
$pageNum = 1; // by default we show first page
if(isset($_GET['page'])) {$pageNum = $_GET['page'];} // if $_GET['page'] defined, use it as page number
$offset = ($pageNum - 1) * $rowsPerPage; // counting the offset
$result = mysql_query("SELECT COUNT(newsID) AS numrows FROM news", $connection);
$row     = mysql_fetch_array($result, MYSQL_ASSOC);
$numrows = $row['numrows']; // how many rows we have in database
// OUTPUT: title
eval ("\$news = \"".gettemplate("news")."\";");
echo $news;
// QUERY: news
$newsQ = mysql_query("SELECT * FROM news ORDER BY newsID DESC LIMIT $offset, $rowsPerPage", $connection);
while ($newsR = mysql_fetch_array($newsQ)) {
	$newsID = $newsR["newsID"];
	$title = $newsR["title"];
	$date = date("d.m.Y", $newsR[date]);
	$authorID = $newsR["author"];
	$content = xml_entity_decode($newsR["content"]);
	//USER QUERY
	$userQ = mysql_query("SELECT * FROM users WHERE pid = '$authorID'", $connection); $userR = mysql_fetch_array($userQ); $newsauthor = $userR["nickname"];
	// OUTPUT: news
	eval ("\$newsLIST = \"".getlist("newsLIST")."\";"); echo $newsLIST;
}
echo "<div align='left'><INPUT type=\"button\" value=\"add news\" name=\"button6\" onClick=\"window.location='./admincp.php?site=newsedit&action=add'\"></div>";
// INCLUDE PAGES
include("./pages/newspages.php");
?>