<!-- 

    	 \|||/							
         (o o)							
 ,~~~ooO~~(_)~~~~~~~~~,					//	File:		login.pr.inc.php
 |                    |					//	Ver :		v0.1
 |		   by	      |					//
 |    Flashfreezer    |					//	Functions:
 |       	          |					//				-	ShowTroops	-	Truppen werden in einer Tabelle angezeit
 '~~~~~~~~~~~~~~ooO~~~'					//				-	CalcTroop	-	Kampfalgorithmus
        |__|__|							//
         || ||							//
        ooO Ooo							//	License:	GNU GPL v3




 -->
 
<?php

if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");

include 'config/initMysql.pr.inc.php';

$accountID = $_SESSION['accountID'];

function ShowTroops()				//Truppenanzeige in einer Tabelle
{
	$sql = "SELECT * FROM Troops";
	$dbErg = mysql_query( $sql );
	
	if (!$dbErg)
	{
	  die('Konnte nicht abgefragt werden mit folgendem Fehler ' . mysql_error());
	}
	
	else {
		
		
	//---------------------------------------------Truppen in Tabelle anzeigen--------------------------------//	
				echo "<h1>Deine Truppen:</h1>";
				echo "<div class='troops'>";
				echo '<table border="1">';
				echo "<tr>
						<td>City</td>
						<td>MoveID</td>
						<td>TroopType</td>
						<td>Number</td>	
				     </tr>";
			
				while ($zeile = mysql_fetch_array( $dbErg, MYSQL_ASSOC))
				{
				  echo "<tr>";
				  echo "<td>". $zeile['cityID'] . "</td>";
				  echo "<td>". $zeile['moveID'] . "<td>" ;
				  echo "<td>". $zeile['troopType'] . "</td>"; 
				  echo "<td>". $zeile['number'] . "</td>";
				  echo "</tr>";
				}
				echo "</table>";
				echo "</div>";
				 
				mysql_free_result( $dbErg );
		}
}

function CalcTroops()				//Kampfalgorithmus
{
	
}

?>