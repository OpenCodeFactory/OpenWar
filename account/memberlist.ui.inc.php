<!-- 

    	 \|||/							
         (o o)							
 ,~~~ooO~~(_)~~~~~~~~~,					//	File:		memberlist.ui.inc.php
 |                    |					//	Ver :		v1.0
 |		   by	      |					//
 |    Flashfreezer    |					//	Functions:
 |       	          |					//				-	shows the members in a list
 '~~~~~~~~~~~~~~ooO~~~'					//
        |__|__|							//
         || ||							//
        ooO Ooo							//	License:	GNU GPL v3




 -->



<?php

if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");

include 'config/initMysql.pr.inc.php';

if(!isset($_SESSION['accountID']))
{
	header("Location:index.php");
}

?>
	<style type="text/css">
		.adminButton input[type="submit"]
		{
			width:100%;
		}
	</style>
<?

$accountID = $_SESSION['accountID'];

if(isset($_GET["action"]) && $_GET["action"] == "true" && isset($_POST["action"]) && isset($_POST["userID"]))
{
	//echo "UserID = " . $_POST["userID"] . "<br/>";
	//echo "Action = " . $_POST["action"] . "<br/>";
	
	$userID = $_POST["userID"];
	$row = mysql_fetch_object(mysql_query("SELECT * FROM Account WHERE accountID='$userID'"));
	$success = true;

	if($_POST["action"] == "ban")
	{
		$success = mysql_query("UPDATE Account SET role='banned' WHERE accountID='$userID'");
	}
	else if($_POST["action"] == "unban")
	{
		$success = mysql_query("UPDATE Account SET role='user' WHERE accountID='$userID'");
	}	
	else if($_POST["action"] == "makeAdmin")
	{
		$success = mysql_query("UPDATE Account SET role='admin' WHERE accountID='$userID'");
	}
	else if($_POST["action"] == "makeUser")
	{
		$success = mysql_query("UPDATE Account SET role='user' WHERE accountID='$userID'");
	}
	else if($_POST["action"] == "delete")
	{
		//DeleteUser($row->name);
		$success = false;
	}	
	
	
	if($success == true)
	{
		//echo "&Auml;nderung erfolgreich!";
	?>
		<meta http-equiv="refresh" content="0; URL=play.php?front=memberlist">
	<?
	}
	else
	{
		echo "UserID = " . $_POST["userID"] . "<br/>";
		echo "Action = " . $_POST["action"] . "<br/>";
		echo "Ein Fehler ist aufgetreten!";	
	}
}

$sql = "SELECT * FROM Account";
$dbErg = mysql_query( $sql );

if (!$dbErg)
{
  die('Ungueltige Abfrage: ' . mysql_error());
}


// User loeschen
function DeleteUser($username)
{
	function DeleteAllLines($username, $table)
	{
		//holt sich die jeweilige AccountID des Users
		$row = mysql_fetch_object(mysql_query("SELECT accountID FROM Account Where name = '$username'"));
		$accountID = $row->accountID;
		
		//Loescht alles von der jeweiligen ID
		$del = "DELETE FROM " . $table . " Where accountID='$accountID'";
		echo $del . " ...";
		$sqlDeleteFromAccount = mysql_query($del);
		
		if($sqlDeleteFromAccount == true)
		{
			echo "done";	
		}
		else
		{
			echo "Fehler!";
		}
		echo "<br/>";
	}
	
	DeleteAllLines($username, "buildingsinprocess");
	DeleteAllLines($username, "troopsinprocess");
	DeleteAllLines($username, "troopmove");
	DeleteAllLines($username, "troops");
	DeleteAllLines($username, "activation");
	DeleteAllLines($username, "buildings");
	DeleteAllLines($username, "city");
	DeleteAllLines($username, "messages");
	DeleteAllLines($username, "notifications");
	DeleteAllLines($username, "resources");
	DeleteAllLines($username, "account");
}
///
 
echo "<h1>Mitglieder</h1>";
echo "<div class='memberlist'>";
echo '<table border="1">';
echo "<tr>
    <td>ID</td>
    <td>Benutzername</td>
    <td>E-Mail</td>
    <td>Land</td>	
    ";

//verantwortlich für die Anzeige ob Admin oder User, ist nur für Admins sichtbar
$id = $_SESSION['accountID'];
$sql = "SELECT role FROM Account WHERE accountID = '$id' LIMIT 1"; 
$sqlResult = mysql_query($sql); 
$row = mysql_fetch_object($sqlResult);  

if($row->role == "admin")
{
    echo "<td>Aktiviert</td>";
    echo "<td>Role</td>";
    echo "<td>Ban</td>";
    echo "<td>Admin</td>";
    echo "<td>Delete</td>";
}
				
    					
		
while ($zeile = mysql_fetch_array( $dbErg, MYSQL_ASSOC))
{
    echo "<tr>";
    echo "<td>". $zeile['accountID'] . "</td>";
    echo "<td><a href=play.php?front=profile&userID=". $zeile['accountID'] . ">" . $zeile['name'] . "</a></td>";
    echo "<td>". $zeile['emailAdress'] . "</td>"; 
    echo "<td>". $zeile['country'] . "</td>";
       
    //verantwortlich für die Anzeige ob Admin oder User, ist nur für Admins sichtbar
    $id = $_SESSION['accountID'];
    $sql = "SELECT role FROM Account WHERE accountID = '$id' LIMIT 1"; 
    $sqlResult = mysql_query($sql); 
    $row = mysql_fetch_object($sqlResult);  
    
    if($row->role == "admin")
    {
        echo "<td>";
    if($zeile["activated"] == 1)
        echo "ja";
    else
        echo "nein";
    
    echo "</td>";
    
    echo "<td>". $zeile['role'] . "</td>";
    
    // Ban
    if($zeile['role'] != "banned")
    {
        echo "<td><div class='adminButton'><form action='play.php?front=memberlist&action=true' method='POST'>";
        echo "<input type='hidden' name='userID' value='" . $zeile['accountID'] . "' />";
        echo "<input type='hidden' name='action' value='ban' />";
        echo "<input type='submit' value='Ban' />";
        echo "</form></div></td>";
    }
    else if($zeile['role'] == "banned")
    {
        echo "<td><div class='adminButton'><form action='play.php?front=memberlist&action=true' method='POST'>";
        echo "<input type='hidden' name='userID' value='" . $zeile['accountID'] . "' />";
        echo "<input type='hidden' name='action' value='unban' />";
        echo "<input type='submit' value='Unban' />";
        echo "</form></div></td>";
    }
    
    // Admin
    if($zeile['role'] == "user")
    {
        echo "<td><div class='adminButton'><form action='play.php?front=memberlist&action=true' method='POST'>";
        echo "<input type='hidden' name='userID' value='" . $zeile['accountID'] . "' />";
        echo "<input type='hidden' name='action' value='makeAdmin' />";
        echo "<input type='submit' value='Admin' />";
        echo "</form></div></td>";
    }
    else 
    {
        echo "<td><div class='adminButton'><form action='play.php?front=memberlist&action=true' method='POST'>";
        echo "<input type='hidden' name='userID' value='" . $zeile['accountID'] . "' />";
        echo "<input type='hidden' name='action' value='makeUser' />";
        echo "<input type='submit' value='User' />";
        echo "</form></div></td>";
    }
    	
    // Delete
    echo "<td><div class='adminButton'><form action='play.php?front=memberlist&action=true' method='POST'>";
    echo "<input type='hidden' name='userID' value='" . $zeile['accountID'] . "' />";
    echo "<input type='hidden' name='action' value='delete' />";
    echo "<input type='submit' value='X' />";
    echo "</form></div></td>";
    				}
        			
        			
    echo "</tr>";
}
echo "</table>";
echo "</div>";
 
mysql_free_result( $dbErg );
?>