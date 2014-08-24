<?php // 
error_reporting(0);
// GET VALUES: admin info
$username = $_POST['username'];
$password = $_POST['password'];
$password2 = $_POST['password2'];
$email = $_POST['email'];
$url = $_POST['url'];
// GET VALUES: datebase
$db_name = $_POST['db_name'];
$db_user = $_POST['db_user'];
$db_pass = $_POST['db_pass'];
$db_server = $_POST['db_server'];
// CONNECTION
mysql_connect($db_server,$db_user,$db_pass) or die( "Unable to connect to database!");
mysql_select_db($db_name) or die( "Unable to select database");

// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// TABLES :::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
// ::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::::
//mysql_query("DROP TABLE IF EXISTS `bans`");
$query= "CREATE TABLE bans (
	banID int(10) NOT NULL auto_increment,
	memberID int(10) NOT NULL,
	adminID int(10) NOT NULL,
	reason varchar(100) NOT NULL,
	date int(15) NOT NULL,
	expire int(15) NOT NULL,
	PRIMARY KEY (banID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"bans\" created...";} else {$error[]= "error creating table \"bans\"...";}

//mysql_query("DROP TABLE IF EXISTS `comments`");
$query= "CREATE TABLE comments (
	commentID int(10) NOT NULL auto_increment,
	section varchar(20) NOT NULL,
	id int(10) NOT NULL,
	pid int(10) NOT NULL,
	content text NOT NULL,
	date int(15) NOT NULL,
	PRIMARY KEY (commentID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"comments\" created...";} else {$error[]= "error creating table \"comments\"...";}

//mysql_query("DROP TABLE IF EXISTS `conflict`");
$query= "CREATE TABLE conflict (
	conflictID int(10) NOT NULL auto_increment,
	matchID int(10) NOT NULL,
	ladderID int(10) NOT NULL,
	status int(1) NOT NULL,
	opponent1 int(10) NOT NULL,
	opponent2 int(10) NOT NULL,
	claim1 varchar(30) NOT NULL,
	claim2 varchar(30) NOT NULL,
	verdict1 varchar(30) NOT NULL,
	verdict2 varchar(30) NOT NULL,
	adminverdict text NOT NULL,
	content text NOT NULL,
	startedby int(10) NOT NULL,
	adminID int(10) NOT NULL,
	date int(15) NOT NULL,
	solveddate int(15) NOT NULL,
	PRIMARY KEY (conflictID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"conflict\" created...";} else {$error[]= "error creating table \"conflict\"...";}

//mysql_query("DROP TABLE IF EXISTS `gameadmins`");
$query= "CREATE TABLE gameadmins (
	gameadminID int(1) NOT NULL auto_increment,
	pid int(10) NOT NULL,
	gameID int(10) NOT NULL,
	date int(15) NOT NULL,
	PRIMARY KEY (gameadminID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"gameadmins\" created...";} else {$error[]= "error creating table \"gameadmins\"...";}

//mysql_query("DROP TABLE IF EXISTS `games`");
$query= "CREATE TABLE games (
	gameID int(10) NOT NULL auto_increment,
	title varchar(60) NOT NULL,
	pic varchar(255) NOT NULL default 'game.jpg',
	PRIMARY KEY (gameID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"games\" created...";} else {$error[]= "error creating table \"games\"...";}

//mysql_query("DROP TABLE IF EXISTS `guids`");
$query= "CREATE TABLE guids (
	guidID int(10) NOT NULL auto_increment,
	title varchar(60) NOT NULL,
	PRIMARY KEY (guidID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"guids\" created...";} else {$error[]= "error creating table \"guids\"...";}

//mysql_query("DROP TABLE IF EXISTS `iplog`");
$query= "CREATE TABLE iplog (
	id int(10) NOT NULL auto_increment,
	pid int(10) NOT NULL,
	ip varchar(40) NOT NULL,
	date int(15) NOT NULL,
	PRIMARY KEY (id)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"iplog\" created...";} else {$error[]= "error creating table \"iplog\"...";}

//mysql_query("DROP TABLE IF EXISTS `ladders`");
$query= "CREATE TABLE ladders (
	ladderID int(10) NOT NULL auto_increment,
	title varchar(60) NOT NULL,
	guid int(10) NOT NULL,
	gamemode varchar(30) NOT NULL,
	date int(15) NOT NULL,
	game varchar(60) NOT NULL,
	active int(1) NOT NULL default '0',
	ratingsystem varchar(60) NOT NULL,
	rules text NOT NULL,
	PRIMARY KEY (ladderID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"ladders\" created...";} else {$error[]= "error creating table \"ladders\"...";}

//mysql_query("DROP TABLE IF EXISTS `ladderteams`");
$query= "CREATE TABLE ladderteams (
	id int(10) NOT NULL auto_increment,
	ladderID int(10) NOT NULL,
	teamID int(10) NOT NULL,
	joined varchar(30) NOT NULL,
	accepted int(15) NOT NULL,
	points int(15) NOT NULL,
	PRIMARY KEY (id)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"ladderteams\" created...";} else {$error[]= "error creating table \"ladderteams\"...";}

//mysql_query("DROP TABLE IF EXISTS `maps`");
$query= "CREATE TABLE maps (
	mapID int(10) NOT NULL auto_increment,
	title varchar(60) NOT NULL,
	ladderID int(10) NOT NULL,
	PRIMARY KEY (mapID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"maps\" created...";} else {$error[]= "error creating table \"maps\"...";}

//mysql_query("DROP TABLE IF EXISTS `matches`");
$query= "CREATE TABLE matches (
matchID int(15) NOT NULL auto_increment,
opponent1 int(10) NOT NULL,
opponent2 int(10) NOT NULL,
accepted1 int(1) NOT NULL,
accepted2 int(1) NOT NULL,
score1 varchar(15) NOT NULL,
score2 varchar(15) NOT NULL,
confirmed1 int(1) default '0',
confirmed2 int(1) default '0',
points1 int(10) NOT NULL,
points2 int(10) NOT NULL,
map1 varchar(30) NOT NULL,
map2 varchar(30) NOT NULL,
ladderID int(10) NOT NULL,
date int(15) NOT NULL,
challengedate int(15) NOT NULL,
acceptdate int(15) NOT NULL,
confirmdate int(15) NOT NULL,
lastedit int(15) NOT NULL,
serverIP varchar(30) NOT NULL,
serverPASS varchar(30) NOT NULL,
PRIMARY KEY (matchID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"matches\" created...";} else {$error[]= "error creating table \"matches\"...";}

//mysql_query("DROP TABLE IF EXISTS `messagereply`");
$query= "CREATE TABLE messagereply (
	messagereplyID int(10) NOT NULL auto_increment,
	messageID int(10) NOT NULL,
	memberID int(10) NOT NULL,
	content text NOT NULL,
	date int(15) NOT NULL,
	PRIMARY KEY (messagereplyID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"messagereply\" created...";} else {$error[]= "error creating table \"messagereply\"...";}

//mysql_query("DROP TABLE IF EXISTS `messages`");
$query= "CREATE TABLE messages (
	messageID int(10) NOT NULL auto_increment,
	title varchar(60) NOT NULL,
	sender int(10) NOT NULL,
	reciever int(10) NOT NULL,
	lastdate int(15) NOT NULL,
	viewS int(1) NOT NULL,
	viewR int(1) NOT NULL,
	PRIMARY KEY (messageID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"messages\" created...";} else {$error[]= "error creating table \"messages\"...";}

//mysql_query("DROP TABLE IF EXISTS `news`");
$query= "CREATE TABLE news (
	newsID int(10) NOT NULL auto_increment,
	title varchar(60) NOT NULL,
	date int(15) NOT NULL,
	author int(11) NOT NULL,
	visible int(1) NOT NULL default '0',
	content text NOT NULL,
	PRIMARY KEY (newsID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"news\" created...";} else {$error[]= "error creating table \"news\"...";};

//mysql_query("DROP TABLE IF EXISTS `playersguids`");
$query= "CREATE TABLE playerguids (
	id int(10) NOT NULL auto_increment,
	pid int(10) NOT NULL,
	guidID int(10) NOT NULL,
	value varchar(60) NOT NULL,
	note varchar(60) NOT NULL,
	date int(15) NOT NULL,
	active int(1) NOT NULL,
	PRIMARY KEY (id)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"playersguids\" created...";} else {$error[]= "error creating table \"playersguids\"...";}

//mysql_query("DROP TABLE IF EXISTS `settings`");
$query= "CREATE TABLE settings (
	settingsID int(1) NOT NULL auto_increment,
	activation int(20) NOT NULL default '1',
	url varchar(255) NOT NULL,
	pagetitle varchar(255) NOT NULL,
	adminmail varchar(255) NOT NULL,
	errorreporting varchar(255) NOT NULL,
	newsPP int(10) NOT NULL default '10',
	newsPPlist int(10) NOT NULL default '10',
	rankingPP int(10) NOT NULL default '10',
	PRIMARY KEY (settingsID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"settings\" created...";} else {$error[]= "error creating table \"settings\"...";}

//mysql_query("DROP TABLE IF EXISTS `teammembers`");
$query= "CREATE TABLE teammembers (
	id int(10) NOT NULL auto_increment,
	teamID varchar(60) NOT NULL,
	pid int(10) NOT NULL,
	joined varchar(30) NOT NULL,
	accepted int(15) NOT NULL,
	rights int(1) NOT NULL,
	notes varchar(255) NOT NULL,
	PRIMARY KEY (id)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"teammembers\" created...";} else {$error[]= "error creating table \"teammembers\"...";}

//mysql_query("DROP TABLE IF EXISTS `teamrating`");
$query= "CREATE TABLE teamrating (
	ratingID int(10) NOT NULL auto_increment,
	matchID int(10) NOT NULL,
	teamID int(10) NOT NULL,
	opponentID int(10) NOT NULL,
	pid int(10) NOT NULL,
	content text NOT NULL,
	date int(15) NOT NULL,
	rating int(10) NOT NULL,
	PRIMARY KEY (ratingID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"teamrating\" created...";} else {$error[]= "error creating table \"teamrating\"...";}

//mysql_query("DROP TABLE IF EXISTS `teams`");
$query= "CREATE TABLE teams (
	teamID int(10) NOT NULL auto_increment,
	name varchar(50) NOT NULL,
	tag varchar(10) NOT NULL,
	date int(15) NOT NULL,
	web varchar(50) NOT NULL,
	adminID int(15) NOT NULL,
	PRIMARY KEY (teamID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"teams\" created...";} else {$error[]= "error creating table \"teams\"...";}

//mysql_query("DROP TABLE IF EXISTS `tournamentmatches`");
$query= "CREATE TABLE tournamentmatches (
matchID int(15) NOT NULL auto_increment,
tournamentID int(15) NOT NULL,
bracketID varchar(15) NOT NULL,
opponent1score1 varchar(15) NOT NULL,
opponent1score2 varchar(15) NOT NULL,
opponent2score1 varchar(15) NOT NULL,
opponent2score2 varchar(15) NOT NULL,
confirmed int(1) default '0',
date int(15) NOT NULL,
lastedit int(15) NOT NULL,
PRIMARY KEY (matchID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"tournamentmatches\" created...";} else {$error[]= "error creating table \"tournamentmatches\"...";}

//mysql_query("DROP TABLE IF EXISTS `tournaments`");
$query= "CREATE TABLE tournaments (
	tournamentsID int(10) NOT NULL auto_increment,
	title varchar(60) NOT NULL,
	guid int(10) NOT NULL,
	gamemode varchar(30) NOT NULL,
	game varchar(60) NOT NULL,
	rules text NOT NULL,
	prize1 varchar(60) NOT NULL,
	prize2 varchar(60) NOT NULL,
	prize3 varchar(60) NOT NULL,
	size int(3) NOT NULL,
	signupOPEN int(15) NOT NULL,
	signupCLOSED int(15) NOT NULL,
	start int(15) NOT NULL,
	r6s1 int(15) NOT NULL,
	r6s2 int(15) NOT NULL,
	r6s3 int(15) NOT NULL,
	r6s4 int(15) NOT NULL,
	r6s5 int(15) NOT NULL,
	r6s6 int(15) NOT NULL,
	r6s7 int(15) NOT NULL,
	r6s8 int(15) NOT NULL,
	r6s9 int(15) NOT NULL,
	r6s10 int(15) NOT NULL,
	r6s11 int(15) NOT NULL,
	r6s12 int(15) NOT NULL,
	r6s13 int(15) NOT NULL,
	r6s14 int(15) NOT NULL,
	r6s15 int(15) NOT NULL,
	r6s16 int(15) NOT NULL,
	r6s17 int(15) NOT NULL,
	r6s18 int(15) NOT NULL,
	r6s19 int(15) NOT NULL,
	r6s20 int(15) NOT NULL,
	r6s21 int(15) NOT NULL,
	r6s22 int(15) NOT NULL,
	r6s23 int(15) NOT NULL,
	r6s24 int(15) NOT NULL,
	r6s25 int(15) NOT NULL,
	r6s26 int(15) NOT NULL,
	r6s27 int(15) NOT NULL,
	r6s28 int(15) NOT NULL,
	r6s29 int(15) NOT NULL,
	r6s30 int(15) NOT NULL,
	r6s31 int(15) NOT NULL,
	r6s32 int(15) NOT NULL,
	r5s1 int(15) NOT NULL,
	r5s2 int(15) NOT NULL,
	r5s3 int(15) NOT NULL,
	r5s4 int(15) NOT NULL,
	r5s5 int(15) NOT NULL,
	r5s6 int(15) NOT NULL,
	r5s7 int(15) NOT NULL,
	r5s8 int(15) NOT NULL,
	r5s9 int(15) NOT NULL,
	r5s10 int(15) NOT NULL,
	r5s11 int(15) NOT NULL,
	r5s12 int(15) NOT NULL,
	r5s13 int(15) NOT NULL,
	r5s14 int(15) NOT NULL,
	r5s15 int(15) NOT NULL,
	r5s16 int(15) NOT NULL,
	r4s1 int(15) NOT NULL,
	r4s2 int(15) NOT NULL,
	r4s3 int(15) NOT NULL,
	r4s4 int(15) NOT NULL,
	r4s5 int(15) NOT NULL,
	r4s6 int(15) NOT NULL,
	r4s7 int(15) NOT NULL,
	r4s8 int(15) NOT NULL,
	r3s1 int(15) NOT NULL,
	r3s2 int(15) NOT NULL,
	r3s3 int(15) NOT NULL,
	r3s4 int(15) NOT NULL,
	r2s1 int(15) NOT NULL,
	r2s2 int(15) NOT NULL,
	r1s1 int(15) NOT NULL,
	r6s1s varchar(60) NOT NULL,
	r6s2s varchar(60) NOT NULL,
	r6s3s varchar(60) NOT NULL,
	r6s4s varchar(60) NOT NULL,
	r6s5s varchar(60) NOT NULL,
	r6s6s varchar(60) NOT NULL,
	r6s7s varchar(60) NOT NULL,
	r6s8s varchar(60) NOT NULL,
	r6s9s varchar(60) NOT NULL,
	r6s10s varchar(60) NOT NULL,
	r6s11s varchar(60) NOT NULL,
	r6s12s varchar(60) NOT NULL,
	r6s13s varchar(60) NOT NULL,
	r6s14s varchar(60) NOT NULL,
	r6s15s varchar(60) NOT NULL,
	r6s16s varchar(60) NOT NULL,
	r6s17s varchar(60) NOT NULL,
	r6s18s varchar(60) NOT NULL,
	r6s19s varchar(60) NOT NULL,
	r6s20s varchar(60) NOT NULL,
	r6s21s varchar(60) NOT NULL,
	r6s22s varchar(60) NOT NULL,
	r6s23s varchar(60) NOT NULL,
	r6s24s varchar(60) NOT NULL,
	r6s25s varchar(60) NOT NULL,
	r6s26s varchar(60) NOT NULL,
	r6s27s varchar(60) NOT NULL,
	r6s28s varchar(60) NOT NULL,
	r6s29s varchar(60) NOT NULL,
	r6s30s varchar(60) NOT NULL,
	r6s31s varchar(60) NOT NULL,
	r6s32s varchar(60) NOT NULL,
	r5s1s varchar(60) NOT NULL,
	r5s2s varchar(60) NOT NULL,
	r5s3s varchar(60) NOT NULL,
	r5s4s varchar(60) NOT NULL,
	r5s5s varchar(60) NOT NULL,
	r5s6s varchar(60) NOT NULL,
	r5s7s varchar(60) NOT NULL,
	r5s8s varchar(60) NOT NULL,
	r5s9s varchar(60) NOT NULL,
	r5s10s varchar(60) NOT NULL,
	r5s11s varchar(60) NOT NULL,
	r5s12s varchar(60) NOT NULL,
	r5s13s varchar(60) NOT NULL,
	r5s14s varchar(60) NOT NULL,
	r5s15s varchar(60) NOT NULL,
	r5s16s varchar(60) NOT NULL,
	r4s1s varchar(60) NOT NULL,
	r4s2s varchar(60) NOT NULL,
	r4s3s varchar(60) NOT NULL,
	r4s4s varchar(60) NOT NULL,
	r4s5s varchar(60) NOT NULL,
	r4s6s varchar(60) NOT NULL,
	r4s7s varchar(60) NOT NULL,
	r4s8s varchar(60) NOT NULL,
	r3s1s varchar(60) NOT NULL,
	r3s2s varchar(60) NOT NULL,
	r3s3s varchar(60) NOT NULL,
	r3s4s varchar(60) NOT NULL,
	r2s1s varchar(60) NOT NULL,
	r2s2s varchar(60) NOT NULL,
	PRIMARY KEY (tournamentsID)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"tournaments\" created...";} else {$error[]= "error creating table \"tournaments\"...";}

//mysql_query("DROP TABLE IF EXISTS `tournamentteams`");
$query= "CREATE TABLE tournamentteams (
	id int(10) NOT NULL auto_increment,
	tournamentID int(10) NOT NULL,
	teamID int(10) NOT NULL,
	joined varchar(30) NOT NULL,
	accepted int(15) NOT NULL,
	PRIMARY KEY (id)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"tournamentteams\" created...";} else {$error[]= "error creating table \"tournamentteams\"...";}

//mysql_query("DROP TABLE IF EXISTS `users`");
$query= "CREATE TABLE users (
	pid int(10) NOT NULL auto_increment,
	username varchar(15) NOT NULL,
	hashed_password varchar(32) NOT NULL,
	nickname varchar(15) NOT NULL,
	email varchar(25) NOT NULL,
	country varchar(50) NOT NULL,
	gender varchar(10) NOT NULL,
	pic varchar(255) NOT NULL default 'user.jpg',
	joined int(15) NOT NULL,
	ip varchar(40) NOT NULL,
	rights int(1) NOT NULL,
	activated varchar(20) NOT NULL,
	lastactive int(15) NOT NULL,
	PRIMARY KEY (pid)
)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "table \"users\" created...";} else {$error[]= "error creating table \"users\"...";}

// SETTINGS DEFAULT
$query= "INSERT INTO settings (activation, url, pagetitle, adminmail, errorreporting, newsPP, newsPPlist, rankingPP)
	VALUES ('1', '$url', 'Xleague', '$email', '0', 10, 10, 10)";
$querystatus = mysql_query($query);
if ($querystatus) {$succes[]= "inserting into settings succes...";} else {$error[]= "error inserting into settings...";}
// USER ENTER
if ($password ==  $password2) {
	$pass = md5($password);
	$ip=@$REMOTE_ADDR;
	$date = time();
	$query= "INSERT INTO users (username, hashed_password, nickname, email, country, gender, joined, ip, rights, activated)
		VALUES ('$username', '$pass', '$username', '$email', '', 'male', '$date', '$ip', '3', 'active')";
	$querystatus = mysql_query($query);
	if ($querystatus) {$succes[]= "inserting into users succes...";} else {$error[]= "error inserting user...";}
} 
else {$error[]= "Passwords don't match.";}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Welcome to XGame</title>
<link href="../css/stylesheet.css" rel="stylesheet" type="text/css">
</head>

<body>
<form id="install" name="install" method="post" action="installation.php">
<table width="100%" border="0" cellpadding="0" cellspacing="0">
  <tr>
    <td>&nbsp;</td>
    <td width="600">
    <div id="webmain">
<?php // NOTE
	if(is_array($error)) {foreach($error as $err) {$message .="<h4>&#8226; ".$err."</h4></br>";} echo $message;}
	if(is_array($succes) && !is_array($error)) {foreach($succes as $suc) {$message .="<h4>&#8226; ".$suc."</h4></br>";} echo $message;}
	if(!is_array($error)) {echo "<h2>Instalation is complete!</h2>";} 
	else {
		$sql="DROP DATABASE $db_name"; mysql_query($sql);
		$sql="CREATE DATABASE $db_name"; mysql_query($sql);
		echo "<h2>Instalation error!</h2>";
	}
	// SQL CLOSE
	mysql_close();
?>
</div>
    </td>
    <td>&nbsp;</td>
  </tr>
</table>
</form>
</body>
</html>