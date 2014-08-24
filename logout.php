<?php // 
// ::: 1 ::: FIND A SESSION
session_name('user');
session_start();
require_once("includes/functions.php");
// ::: 2 ::: UNSET ALL SESSION VARIABLES
$_SESSION = array();
if (isset($_COOKIE['user'])) {
   setcookie('user', '', time()-42000, '/');
}
if (isset($_COOKIE['rights'])) {
   setcookie('rights', '', time()-42000, '/');
}
if (isset($_COOKIE['PHPSESSID'])) {
   setcookie('PHPSESSID', '', time()-42000, '/');
}
// ::: 3 ::: DESTROY SESSION
session_destroy();
// ::: 4 ::: REDIRECT
redirect_to("./index.php?site=news");
?>





