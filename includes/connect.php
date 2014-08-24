<?php // www.xantrus.com | contact mail: mbelavic@inet.hr | all rights reserved
// mySQL SETTINGS
$db_server = ""; // db server
$db_user = ""; // db username
$db_pass = ""; //db password
$db_name = ""; //db name
// CONNECTION
$connection = mysql_connect($db_server,$db_user,$db_pass) or die( "Unable to connect to database!");
$db_selection = mysql_select_db($db_name) or die( "Unable to select database");
?>