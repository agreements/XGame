<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// GET: variables
$password = md5(sql_quote($_POST['password']));
$username = sql_quote($_POST['username']);
$ip= $_SERVER['REMOTE_ADDR'];
// CHECK: login
$userQ = mysql_query("SELECT * FROM users WHERE username = '$username' AND hashed_password = '$password' LIMIT 1", $connection);
if (mysql_num_rows($userQ) == 1) {
	$userR = mysql_fetch_array($userQ);
	$activated =  $userR['activated'];
	if ($activated == 'active') {
		$_SESSION['rights'] = $userR['rights'];
		$_SESSION['pid'] = $userR['pid'];
		// IP LOG
		$pid = $userR['pid'];
		$date = time();
		$iplogINS = mysql_query("INSERT INTO iplog (pid, ip, date) VALUES ('$pid', '$ip', '$date')", $connection);
		$lastactive = mysql_query("UPDATE users SET lastactive = '$date' WHERE pid = '$pid'", $connection);
		// SUCCER
		$error = 'Login successful !<br><br><meta http-equiv="refresh" content="2;URL=index.php?site=news">';
	} else {
		$error = 'Your account is not activated.<br><br><meta http-equiv="refresh" content="2;URL=index.php">';
	}
} else {
	$error = 'Wrong username or password.<br><br><a href="javascript:history.back()">Go back and try it again!</a>';
}
?>

<html>
<head>
<title>HR Esports</title>
<link href="_stylesheet.css" rel="stylesheet" type="text/css">
<style type="text/css">
a:link {
	color: #FFFF00;
	text-decoration: none;
}
a:visited {
	text-decoration: none;
	color: #FFFF00;
}
a:hover {
	color: #CCCCCC;
	text-decoration: none;
}
a:active {
	text-decoration: none;
	color: #FFFF00;
}
body {
	background-image: url(images/web/background.png);
}
body,td,th {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #FFFFFF;
}
</style>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" /></head>
<body bgcolor="000000">
<table width="100%" height="100%">
  <tr>
    <td align="center">
	  <table width="350" border="1" cellpadding="10" cellspacing="0" bordercolor="FFFFFF" bgcolor="555555">
	    <tr>
		  <td align="center"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td><span class="style1"><span class="style2">
                  <div align="center"></div>
                  <div align="center"><?php echo $error; ?></div></td>
              </tr>
            </table></td>
		</tr>
	  </table>
    </td>
  </tr>
</table>
</body>
</html>