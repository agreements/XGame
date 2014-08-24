<?php // 
// INCLUDES
require_once("../includes/session.php");
require_once("../includes/connect.php");
require_once("../includes/functions.php");
// SETTINGS: page
$settingsQ = mysql_query("SELECT * FROM settings WHERE settingsID = '1'", $connection); $settingsR = mysql_fetch_array($settingsQ); 
$pagetitle = $settingsR["pagetitle"];
$errorreporting = $settingsR["errorreporting"]; error_reporting($errorreporting);
// CHECK: validation AND auth
$pid = $_SESSION['pid'];
$rights = $_SESSION['rights'];
//CHECK: auth
if(!isset($pid)) {echo "<h4>&#8226; Please LOG IN!</h4>"; die;}
if($rights != 3) {echo "<h4>&#8226; You dont have rights to see this page!</h4>"; return;}
// CONTROL: page
if (isset($_GET['site'])) {$site = sql_quote($_GET['site']);} else {$site = 'news';}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title><?php echo $pagetitle; ?></title>
<link href="../css/stylesheet.css" rel="stylesheet" type="text/css">
<link href="../css/menu.css" media="screen" rel="stylesheet" type="text/css" />
</head>

<body>
<table width="1020" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td height="150" background="../images/web/logo.png"></td>
  </tr>
  <tr>
    <td height="15"><?php require_once("menu.php"); ?></td>
  </tr>
  <tr>
    <td background="../images/web/pagebg_middle.jpg"><table width="100%" border="0" cellpadding="0" cellspacing="0">
        <tr>
          <td width="810" valign="top"><div id="webmain"><?php @include($site.'.php'); ?></div></td>
          <td width="210" valign="top"><div id="webside">
            <?php require_once("statistic.php"); ?>
          </div></td>
        </tr>
    </table>
    </td>
  </tr>
</table>
</body>
</html>
