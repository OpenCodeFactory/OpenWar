<!-- 

    	 \|||/							
         (o o)							
 ,~~~ooO~~(_)~~~~~~~~~,					//	File:		login.pr.inc.php
 |                    |					//	Ver :		v1.0
 |		   by	      |					//
 |    Flashfreezer    |					//	Functions:
 |       	          |					//				-	Login
 '~~~~~~~~~~~~~~ooO~~~'					//
        |__|__|							//
         || ||							//
        ooO Ooo							//	License:	GNU GPL v3




 -->



<?php 

if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");

//nimmt die Variablen von der index.php und wandelt das pw in md5 um
$username = mysql_real_escape_string($_POST["username"]); 
$password = mysql_real_escape_string(md5($_POST["password"])); 

$status = true;

// abfrage ob username oder passwort leer ist
//datenbankabfrage ob user vorhanden, falls ja dann wird die ID als Session_ID gesetzt
if(!empty($username) && !empty($_POST["password"]))
{
	$sql = "SELECT * FROM Account WHERE name = '$username' LIMIT 1"; 
	$sqlResult = mysql_query($sql); 
	$row = mysql_fetch_object($sqlResult);  

	if($row->password == $password) 
    { 
    	
    	if($row->activated == 2)
		{
			$notification = "Du bist noch nicht aktiviert";
		}
		else 
		{
		    $_SESSION["username"] = $username; 
		    $_SESSION["accountID"] = $row->accountID;
		    $notification = "Login erfolgreich!";
			?>	<meta http-equiv="refresh" content="1; URL=play.php">  <?php
		}
    } 
	else
		$status = false;
}
else 
	$status = false;


	if($status == false)
	{
	    $notification = "Benutzername oder Passwort falsch";
	} 

?>
