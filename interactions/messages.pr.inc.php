<!--
    //=========================================================================
    // File:        messages.ui.inc.php
    // Description: Open-War Game
    // Functions:   
    //                  - Messages
    //
    // Created:     2012-05-01
    // Author:      Jonas Juffinger jonasjuffinger@gmail.com
    // Ver:         0.9 alpha
    // Ver Notes:  
    //              Doesn't work
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
    // Copyright (C) 2012 Jonas Juffinger
    //==========================================================================
-->

<?php

if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");
 
$receiver = mysql_real_escape_string(htmlentities(htmlspecialchars($_POST["receiver"])));
$subject = mysql_real_escape_string(htmlentities(htmlspecialchars($_POST["subject"])));
$message = mysql_real_escape_string(htmlentities(htmlspecialchars($_POST["message"])));

if(isset($_POST["reply"]))
	$reply = $_POST["reply"];


if(!empty($receiver) && !empty($subject) && !empty($message))
{
	$receiverIDQueryResult = mysql_query("SELECT accountID FROM Account WHERE name = '$receiver'") or die ("User existiert nicht");
	$receiverID = mysql_fetch_object($receiverIDQueryResult)->accountID;
	
	if(!isset($reply))
		$messageEntry = "INSERT INTO Messages (sender, receiver, subject, message) VALUES ('$accountID', '$receiverID', '$subject', '$message')";
		
	else
		$messageEntry = "INSERT INTO Messages (sender, receiver, subject, message, reply) VALUES ('$accountID', '$receiverID', '$subject', '$message', '$reply')";
	
	$success = mysql_query($messageEntry);
}
?>