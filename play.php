<!DOCTYPE html>

<!--
    //=========================================================================
    // File:        play.php
    // Description: Open-War Game
    // Functions:   
    //                  - Main file of the game
    //						- Includes all functions
    //
    // Created:     2012-05-01
    // Author:      Andreas Klingler (aklingler94@gmail.com)
    // Ver:         0.2 RC
    // Ver Notes:  
    //              Should be complete
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
    // Copyright (C) 2012 Andreas Klingler
    //==========================================================================
-->
  
<?php
@session_start();

$OWEXEC = true;

$accountID = $_SESSION['accountID'];

include 'config/initMysql.pr.inc.php';

//verantwortlich fuer das Adminmen-Menu
$id = $_SESSION['accountID'];
$sql = "SELECT role FROM Account WHERE accountID = '$accountID' LIMIT 1"; 
$sqlResult = mysql_query($sql); 
$row = mysql_fetch_object($sqlResult);  

// wenn der user gebannt ist....
if($row->role == "banned")
{
	die('You are banned!');
}

include "game/update.pr.inc.php";

if(isset($_GET['front']))
    $front = $_GET['front'];
else
	$front = "city";


?>
<html>
	<head>
	<?php 
	if(!isset($_SESSION['accountID']))
    {
    	?> 
		<meta http-equiv="refresh" content="1; URL=index.php">
    	<?php
    }
    else
    {
    	
	?>
		<title>Open War</title>
		<link rel="stylesheet" type="text/css" href="style/theme/play.css">
		<link rel="stylesheet" type="text/css" href="style/theme/input.css">
		<link rel="stylesheet" type="text/css" href="style/theme/city.css">
	</head>
	<body>
	   <div id="header">
    		<div id="menu">
    			<a href="play.php" id="link1">&Uuml;bersicht</a>
    			<a href="play.php?front=messages">Nachrichten</a>
    			<!--<a href="play.php?front=notifications">Statusmeldungen</a>-->
    			<a href="play.php?front=settings">Einstellungen</a>
    			<a href="play.php?front=memberlist">Mitglieder</a>
    			<?php 
    			//verantwortlich fuer das Adminmen-Menu
                $sql = "SELECT role FROM Account WHERE accountID = '$accountID' LIMIT 1"; 
                $sqlResult = mysql_query($sql); 
                $row = mysql_fetch_object($sqlResult);  
				
    			if($row->role == "admin")
				{
					?>	<a href="play.php?front=admin">Admin-Tools</a> <?php 
				} 
				
    			?>
    			<a id="link5" href="play.php?front=logout" >Logout</a>
    		
    			
    			
    			<?
	            $id = $_SESSION['accountID'];
				$sqlResult = mysql_query("SELECT role FROM Account WHERE accountID = '$id'"); 
				$row = mysql_fetch_object($sqlResult);  
				
    			if($row->role == "admin" || $row->role == "tempNoAdmin")
				{
				    include "account/userview.ui.inc.php"; 
				}
				?> 
			</div>
    	</div>
		
		<div id="content">
		    <div id="time">
                <?php
                    include "game/time.ui.inc.php";
                ?>
            </div>
		    
			<?php
				if($front == "messages")
				{
					include "interactions/messages.ui.inc.php";
				}
				else if($front == "notifications")
				{
					include "interactions/notifications.ui.inc.php";
				}
				else if($front == "settings")
				{
					include "account/settings.ui.inc.php";
				}
				else if($front == "logout")
				{
					include "actions/logout.pr.inc.php";
				}
				else if($front == "memberlist")
				{
					include "account/memberlist.ui.inc.php";
				}
				else if($front == "city")
				{
					include "game/city.ui.inc.php";
				}
				else if($front == "profile")
				{
					include "account/profile.ui.inc.php";
				}
				else if($front == "admin")
				{
					include "includes/admin.ui.inc.php";
				}
			?>
		</div>

	
	</body>
</html>
<?php } ?>