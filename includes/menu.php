<?php
// CHECK: validation AND auth
$pid = $_SESSION['pid'];
$gamesadminQ = mysql_query("SELECT * FROM gameadmins WHERE pid = '$pid'", $connection);
if (!empty($pid)) {
	if (mysql_num_rows($gamesadminQ) == '0') {$leagueADMIN = "";} 
	else {
	$leagueADMIN = "
		<li><a href='index.php?site=admin'>Admin panel</a>
			<ul>
				<li><a href='index.php?site=conflicts'>Conflicts</a></li>
				<li><a href='index.php?site=editmatch'>Edit match</a></li>
				<li><a href='index.php?site=acceptteams'>Accept teams</a></li>
				<li><a href='index.php?site=bansadd'>New ban</a></li>
				<li><a href='index.php?site=tournaments'>Tournaments</a></li>
			</ul>
		</li>"
	;}
}
$messagesQ = mysql_query("SELECT * FROM messages WHERE (sender = '$pid' AND viewS = '1') OR (reciever = '$pid' AND viewR = '1')", $connection);
$messagesNUM = mysql_num_rows($messagesQ);
if (isset($pid)) {$messages = "<li><a href='index.php?site=messages'>Messages (".$messagesNUM.")</a><ul><li><a href='index.php?site=messagenew'>New message</a></li></ul></li>";} 
else {$messages = "";}
?>
<div id='cssmenu'>
    <ul>
        <li><a href='index.php?site=home'>Home</a></li>
        <li><a href='index.php?site=news'>News</a></li>
        <li><a href='index.php?site=ladders'>Ladders</a></li>
        <li><a href='index.php?site=tournaments'>Tournaments</a></li>
        <li><a href='index.php?site=search'>Search</a></li>
        <li><a href='index.php?site=bans'>Bans</a></li>
        <li><a href='index.php?site=siteadmins'>Staff</a></li>
        <?php echo $messages; ?>
        <?php echo $leagueADMIN; ?>
    </ul>
</div>