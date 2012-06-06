<!--
    //=========================================================================
    // File:        settings.pr.inc.php
    // Description: Saves the new password into the database
    // Functions:   
    //           
    //
    // Created:     2012-05-17
    // Author:     	Andreas Kecht (andreas.kecht@gmail.com)
    // Ver:         0.1
    // Ver Notes:  
    //              
    //
    // License:     
    //              This program is free software: you can redistribute it and/or modify
    //              it under the terms of the GNU General Public License as published by
    //              the Free Software Foundation, either version 3 of the License, or
    //              (at your option) any later version.
    //
    //              This program is distributed in the hope that it will be useful,
    //              but WITHOUT ANY WARRANTY; without even the implied warranty of
    //              MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    //              GNU General Public License for more details.
    //
    //              You should have received a copy of the GNU General Public License
    //              along with this program.  If not, see <http://www.gnu.org/licenses/>.
    //      
    // Copyright (C) 2012 Andreas Kecht
    //==========================================================================
-->

<?php
if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");

/// hier ist accountID die ID vom user dessen Daten geaendert werden sollen
$accountID = $ID; //$_SESSION['accountID'];

$success = false;

/// wenn daten geaendert werden sollen
if(isset($_GET["type"]) && $_GET["type"] == "change" && is_numeric($accountID))
{
	if($_GET["show"] == "password")
	{
		$result = mysql_query("SELECT * FROM Account WHERE accountID=$accountID");
		$oldPasswordDb = mysql_fetch_object($result)->password;
		
		$oldPassword = $_POST["oldPassword"];
		$newPasswordRepeat = $_POST["newPasswordRepeat"];
		$newPassword = $_POST["newPassword"];
		
		if(md5($oldPassword) != $oldPasswordDb && $oldPassword != $oldPasswordDb)
			$code = 1;
		else if($newPassword != $newPasswordRepeat || empty($newPassword))
			$code = 2;
		else
		{
			if($_POST["check"] != "true")
				$newPassword = md5($newPassword); // verschluesselung des passwortes, wenn dies noch nicht per JS gemacht wurde
			
			$success = mysql_query("UPDATE Account SET password='$newPassword' WHERE accountID='$accountID'");
			$code = 3;
		}
	}
	else if($_GET["show"] == "email")
	{
		$newEmail = mysql_real_escape_string(htmlentities(htmlspecialchars($_POST["newEmail"])));
		
		
		$query = mysql_query("SELECT * FROM Account WHERE accountID=$accountID");
		$oldEmail = mysql_fetch_object($query)->emailAdress;
		
		
		if(empty($newEmail))
			$code = 4;
		else if(strpos($newEmail,"@") != true || strpos($newEmail,".") != true) 
			$code = 5;
		else if($newEmail == $oldEmail)
			$code = 6;
		else
		{
			$success = mysql_query("UPDATE Account SET emailAdress='$newEmail' WHERE accountID='$accountID'");
			$code = 7;
			
			// Bestätigungsmail an neue Adresse schicken!?
		}
	}
	else if($_GET["show"] == "profile")
	{
		$code = 8;
		
		if(isset($_POST["showMail"]))
			$showMail = 1;
		else
			$showMail = 0;
		
		if(isset($_POST["showAge"]))
			$showAge = 1;
		else
			$showAge = 0;
		
		$name = mysql_real_escape_string(htmlentities(htmlspecialchars($_POST["name"])));
		$residence = mysql_real_escape_string(htmlentities(htmlspecialchars($_POST["residence"])));
		$website = mysql_real_escape_string(htmlentities(htmlspecialchars($_POST["website"])));
		$about = mysql_real_escape_string(htmlentities(htmlspecialchars($_POST["about"])));
		$county = mysql_real_escape_string(htmlentities(htmlspecialchars($_POST["country"])));
		$birthDate = mysql_real_escape_string(htmlentities(htmlspecialchars($_POST["birthDate"])));
		
		$website = str_replace("http://", "", $website);
		
		if(!empty($birthDate))
		{
			$p1 = strpos($birthDate, ".");
			$p2 = strpos($birthDate, ".", $p1+1);
			$p3 = $p2 - $p1-1;
			
			$day = substr($birthDate, 0, $p1);
			$month = substr($birthDate, $p1+1, $p3);
			$year = substr($birthDate, $p2+1, 4);
			
			if($day > 31 || $day < 1)
				$code = 9;
			if($month > 12 || $month < 1)
				$code = 9;
			if($year > 2012 || $year < 1900)
				$code = 9;
			
			$age = $day . "." . $month . "." . $year;
		}
		
		if($code != 9)
			$success = mysql_query("UPDATE Account SET profileName='$name', residence='$residence', website='$website', about='$about', showMail='$showMail', showAge='$showAge', country='$county', birthDate='$birthDate' WHERE accountID=$accountID");
	}
	else if($_GET["show"] == "role")
	{
		$code = 10;
		
		if(isset($_POST["admin"]))
			$role = "admin";
		else if(isset($_POST["banned"]))
			$role = "banned";
		else
			$role = "user";  // = standardwert 
			
		if(isset($_POST["activated"]))
		{
			mysql_query("UPDATE Account SET activated='1' WHERE accountID=$accountID");
		}
		else
		{
			mysql_query("UPDATE Account SET activated='2' WHERE accountID=$accountID");
		}
		
		$success = mysql_query("UPDATE Account SET role='$role' WHERE accountID=$accountID");
	}
}
?>
