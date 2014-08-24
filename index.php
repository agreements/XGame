<?php // 
// INCLUDES
require_once("includes/session.php");
require_once("includes/connect.php");
require_once("includes/functions.php");
if (is_dir("install")) {echo "Please remove installation folder after you finish with installation."; die;}
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// CONTROL: page
if (isset($_GET['site'])) {$site = sql_quote($_GET['site']);} else {$site = 'home';}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<link rel="icon" href="favicon.ico" type="image/x-icon">
<link rel="shortcut icon" href="favicon.ico" type="image/x-icon"> 
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $pagetitle; ?></title>
<link href="css/stylesheet.css" rel="stylesheet" type="text/css">
<link href="css/menu.css" media="screen" rel="stylesheet" type="text/css" />
</head>
<body>
<?php include_once("analyticstracking.php"); ?>
<table width="1020" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="150" background="images/web/logo.png"></td>
  </tr>
  <tr>
    <td height="15"><?php require_once("includes/menu.php"); ?></td>
  </tr>
  <tr>
    <td>
      <div id="webpage">
        <table width="100%" border="0" cellpadding="0" cellspacing="0">
          <tr>
            <td width="810" valign="top"><div id="webmain"><?php @include($site.'.php'); ?></div></td>
            <td width="210" valign="top"><div id="webside"><?php if ($site != "login") {include('login.php');} ?></div></td>
          </tr>
        </table>
      </div>
    </td>
  </tr>
</table>
</body>
</html>
<?php $lastactiveDATE = time(); $activity = mysql_query("UPDATE users SET lastactive = '$lastactiveDATE' WHERE pid = '$pid'", $connection); ?>