<!--
    //=========================================================================
    // File:        profile.ui.inc.php
    // Description: 
    // Functions:   
    //           
    //
    // Created:     2012-05-25
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

if(!isset($_SESSION['accountID']))
{
	?> 
		<meta http-equiv="refresh" content="0; URL=index.php">
	<?php
}
else
{
	if(isset($_GET["userID"]))
		$ID = $_GET["userID"];
	else 
		$ID = $_SESSION["accountID"];		
	
	if(!is_numeric($ID))
		$ID = 0;

	$result = mysql_query("SELECT * FROM Account WHERE accountID='$ID'");
	$row = mysql_fetch_object($result);

	if($row)
		$status = true;
	else
		$status = false;
	
	$accountID = $_SESSION["accountID"];
	$query = mysql_query("SELECT * FROM Account WHERE accountID='$accountID'");
	$accRow = mysql_fetch_object($query);	
	
	if(isset($accRow->role) && $accRow->role == "admin")
		$adminView = true;
	else 
		$adminView = false;
	
?>

<link rel="stylesheet" type="text/css" href="style/theme/profile.css" />
<div id="profile">
<? if($status == true){?>
<h1><u>Benutzer: <? echo $row->name; ?></u></h1>
<?


if($adminView)
	echo "<a href='play.php?front=settings&show=profile&userID=" . $ID ."'>(bearbeiten)</a><br/><br/>";
else if($ID == $_SESSION["accountID"])
	echo "<a href='play.php?front=settings&show=profile'>(bearbeiten)</a><br/><br/>";


if($ID != $_SESSION["accountID"])
{
?>

<a href="play.php?front=messages&type=newMessage&receiver=<? echo $row->name; ?>">Nachricht schicken</a>
<br/><br/>

<?php 
} 
?>


<table>
	<? if(!empty($row->profileName)) { ?>
	<tr>
		<td>Name:</td><td><? echo $row->profileName; ?></td>
	</tr>
	<?php } ?>
	<tr>
        <td>Punkte:</td><td><? echo $row->points; ?></td>
    </tr>
	<? if(!empty($row->residence)) { ?>
	<tr>
		<td>Wohnort:</td><td><? echo $row->residence; ?></td>
	</tr>
	<? } if(!empty($row->website)) { ?>
	<tr>
		<td>Website:</td><td><? echo "<a href='http://" . $row->website . "'>". $row->website . "</a>"; ?></td>
	</tr>
	<? } if(!empty($row->birthDate) && ($row->showAge == 1 || $adminView == true)) { ?>
	<tr>
		<td>Geburtsdatum:</td><td><? echo $row->birthDate; ?></td>
	</tr>
	<? }  if(!empty($row->about)) { ?>
	<tr>
		<td>&Uuml;ber mich:</td><td><? echo $row->about; ?></td>
	</tr>
	<? } if($row->showMail == 1 || $adminView == true) { ?>
	<tr>
		<td>E-Mail: </td> <td><? echo "<a href='mailto:" . $row->emailAdress . "'>" . $row->emailAdress . "</a>"; ?></td>
	</tr>
	<? } ?>
	<tr>
		<td>Land:</td>	<td><? echo $row->country; ?></td>
	</tr>
	<? if($adminView == true) { ?>
	<tr>
		<td>Aktiviert:</td>	  <td><? if($row->activated == 2) echo "Nein"; else echo "Ja"; ?></td>
	</tr>
	<tr>
		<td>Administrator:</td>	  <td><? if(isset($row->role) && $row->role == "admin") echo "Ja"; else echo "Nein"; ?></td>
	</tr>
	<? } ?>
</table>
<br />

<h1>St&auml;dte:</h1>
<ul>
	<?php
    echo "<table>";   
    
    $cityQuery = mysql_query("SELECT * FROM City WHERE accountID = '$ID'");
    while($city = mysql_fetch_object($cityQuery))
    {
        echo "<tr><td><a href='?show=city&cityID=$city->cityID'>$city->name</a></td><td>$city->points Punkte</td>";
    }
    
    echo "</table>";
}
else
	echo "<br/><h1>User existiert nicht!</h1>";
?>
</ul>
</div>

<?php		
}
?>

	