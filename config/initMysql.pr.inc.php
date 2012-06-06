<?php

include 'config/config.php';

if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");

$mysqlConnection = mysql_connect($db_host,$db_user,$db_password) 
	or die ("Es ist ein Fehler aufgetreten  Fehlercode: 100");
mysql_select_db($db_name) 
	or die ("Es ist ein Fehler aufgetreten  Fehlercode: 101");

?>

