<!--
    //=========================================================================
    // File:        settings.ui.inc.php
    // Description: Change User Password
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

if(!isset($_SESSION['accountID']))
{
	/// Weiterleitung auf Startseite wenn nicht eingeloggt
	header("Location:index.php");
}
else
{
	
	$accountID = $_SESSION["accountID"];
	$query = mysql_query("SELECT * FROM Account WHERE accountID='$accountID'");
	$accRow = mysql_fetch_object($query);	
	
	// Admin kann alles sehen 
	if(isset($accRow->role) && $accRow->role == "admin")
		$adminView = true;
	else 
		$adminView = false;
	
	
	// admin kann andere user bearbeiten, 
	// der normale user hat bei settings keine id in der adresszeile
	if(isset($_GET["userID"]))
	{
		if($adminView == false)
		{ 
			$ID = $_SESSION["accountID"];
			header("Location:play.php?front=settings&show=password");
		}
		else
		{
			$ID = $_GET["userID"];
			
			// sollte die ID keine zahl sein wird sie auf NULL gesetzt
			if(!is_numeric($ID))
				$ID = 0;
		}
	}
	else 
	{
		$ID = $_SESSION["accountID"];	
	}
	
	
	// wenn einstellungen geändert wurden...
	if(isset($_GET["type"]))
		include "settings.pr.inc.php";
	
	/// ID ist die userID - kann auch ID von anderen Usern sein - accountID ist deine ID
	$result = mysql_query("SELECT * FROM Account WHERE accountID=$ID");
	$row = mysql_fetch_object($result);
	
	
	/// existiert der Benutzer werden die Daten gelesen, usw.
	if($row == true)
	{
		/// Ausgabe der Fehlermeldungen bzw. erfolgreiche Aenderungen
		if(isset($code))
		{
			echo "<br />";
			if($success == true)
			{?>
				<div style="color: #00ff00;">
				<?php
				if(isset($_GET["type"]) && $_GET["type"] == "change")
				{
					if($_GET["show"] == "password")
						echo "Ihr Passwort wurde erfolgreich ge&auml;ndert!";
					else if($_GET["show"] == "email")
						echo "Ihre E-Mail Adresse wurde erfolgreich ge&auml;ndert!";
					else if($_GET["show"] == "profile" || $_GET["show"] == "role")
						echo "&Auml;nderungen gespeichert!";
				}
				?>
				</div>
				<?php
			}
			else
			{
				?>
				<div style="color: #ff0000;">
				<?php
				if($code == 1)
					echo "Das alte Passwort war leer oder falsch!";
				else if($code == 2)
					echo "Das neue Passwort war leer oder die Passw&ouml;rter stimmen nicht &uuml;berein!";
				else if($code == 4)
					echo "Sie haben keine E-Mail Adresse eingegeben!";
				else if($code == 5)
					echo "Sie haben keine g&uuml;ltige E-Mail Adresse eingegeben!";
				else if($code == 6)
					echo "Keine &Auml;nderung notwendig!";
				else if($code == 3 || $code == 7 || $code == 8)
					echo "Fehler beim Updaten der Datenbank!<br/>Die Datenbank ist m&ouml;glicherwei&szlig;e besch&auml;digt oder nicht aktuell!";
				else if($code == 9)
					echo "Falsche Altersangabe!";
				else
				{
					echo "Unbekannter Fehler!";
					
					echo "<br />";
					echo "Code: ";
					echo $code;
				}
				
				?>
				</div> 
				<?php
			}
		}
		
		
		$oldPassword = $row->password;
		$emailDb = $row->emailAdress;
		
	?>
	<link rel="stylesheet" type="text/css" href="styles/settings.css" />	
	<script type="text/javascript" src="includes/md5.js"></script>
	<script type="text/javascript">
		
		function checkPw()
		{
			/// Passwort wird verschluesselt uebertragen
			document.Password.newPassword.value = MD5(document.Password.newPassword.value);
			document.Password.newPasswordRepeat.value = MD5(document.Password.newPasswordRepeat.value);
			document.Password.oldPassword.value = MD5(document.Password.oldPassword.value);
			
			// checkvalue um zu ueberpruefen ob das passwort verschluesselt ist oder nicht -> ansonsten wird es per php verschluesselt
			document.Password.check.value = "true";
			return true;
		}
		
	</script>
	
	<?
	
	echo "<h1>Benutzer: " . $row->name . "</h1>";

	/// Einstellungsmenue 
	// admin kann natuerlich mehr
		?>
	<div id="submenu">
		<? if($adminView == false) { ?>
		<a href="play.php?front=settings&show=password">Passwort</a>
		<a href="play.php?front=settings&show=email">E-Mail</a>
		<a href="play.php?front=settings&show=profile">Profil</a>
		<? 
		} 
		else if($adminView == true) 
		{
			echo "<a href='play.php?front=settings&show=password&userID=" . $ID . "'>Passwort</a>";
			echo "<a href='play.php?front=settings&show=email&userID=" . $ID . "'>E-Mail</a>";
			echo "<a href='play.php?front=settings&show=profile&userID=" . $ID . "'>Profil</a>";
			echo "<a href='play.php?front=settings&show=role&userID=" . $ID . "'>Rechte</a>";
		}?>
	</div>
	<br />
	
	<?php
	
	if(!isset($_GET["show"]) || $_GET["show"] == "password")
	{
	?>
	<h2><u>Passwort</u></h2>
	Hier kannst du dein Passwort &auml;ndern <br/><br/><br/>
	<form name="Password" action="play.php?front=settings&show=password<? if($adminView==true) echo"&userID=".$ID; ?>&type=change" method="POST" onsubmit="return checkPw();" >
		<table>
			<tr>
				<td>Altes Passwort:</td><td><input type="password" name="oldPassword" /></td>
			</tr>
			<tr>
				<td>Neues Passwort:</td><td><input type="password" name="newPassword" /></td>
			</tr>
			<tr>
				<td>Neues Passwort:</td><td><input type="password" name="newPasswordRepeat" /></td>
			</tr>
			<tr>
				<td><input type="submit" value="Passwort &auml;ndern"/></td>
			</tr>
			<tr>
				<td><input type="hidden" name="check"/></td>
			</tr>
		</table>
	</form> 
	
	<noscript><br />HINWEIS: Sie haben Javascript deaktiviert! Ihr Passwort wird im Klartext &uuml;bertragen!<br /></noscript>

	<?php
	}
	else if($_GET["show"] == "email")
	{
	?>
	<h2><u>E-Mail</u></h2>
	Hier kannst du deine E-Mail Adresse &auml;ndern<br/><br/><br/>
	<form name="Email" action="play.php?front=settings&show=email<? if($adminView==true) echo"&userID=".$ID; ?>&type=change" method="POST">
		<table>
			<tr>
				<td>E-Mail:</td> <td><input type="text" size="25" name="newEmail" value="<? if(isset($newEmail)) echo $newEmail; else echo $emailDb; ?>"/></td>
			</tr>
			<tr>
				<td><input type="submit" value="E-Mail &auml;ndern" /></td>
			</tr>
		</table>		
	</form>
	<?php
	}
	else if($_GET["show"] == "profile")
	{
		$showMailDB = $row->showMail;
		$showAgeDB = $row->showAge;
		$selectedCountry = $row->country;
		
	?>
	<h2><u>Profil</u></h2>
	
	Hier kannst du deine Profileinstellungen &auml;ndern<br/>
	
	<? if($adminView == false) { ?>
		<a href="play.php?front=profile">Mein Profil anzeigen</a>
	<? } else if($adminView == true) 
		echo "<a href='play.php?front=profile&userID=" . $ID ."'>Profil anzeigen</a>";
		?>
		
		
		<br/><br/><br/>
	
	<div id="settingsProfile">
		<form name="profile" action="play.php?front=settings&show=profile<? if($adminView==true) echo"&userID=".$ID; ?>&type=change" method="POST" enctype="multipart/form-data">
			<table>
				<tr>
					<td>Name:</td><td><input type="text" name="name" value="<? echo $row->profileName; ?>"/></td>
					</tr>
					<tr><td></td><td>Hier kannst du deinen richtigen Namen eingeben</td></tr>
				
				<tr><td><br/></td></tr>
				<tr>
					<td>Wohnort:</td><td><input type="text" name="residence" value="<? echo $row->residence; ?>"/></td></tr>
					<tr><td></td><td>Hier kannst du deinen Wohnort eingeben</td></tr>
				
				<tr><td><br/></td></tr>
				<tr>
					<td>Website:</td><td><input type="text" name="website" value="<? echo $row->website; ?>" /></td></tr>
					<tr><td></td><td>Hier kannst du deine eigene Website eintragen (ohne http://)</td></tr>
				
				<tr><td><br/></td></tr>
				<tr>
					<td>Geburtsdatum:</td><td><input type="text" name="birthDate" maxlength="10" value="<? echo $row->birthDate; ?>"/>&nbsp;(dd.mm.yyyy)</td></tr>
					<tr><td></td><td>Hier kannst du dein Alter eingeben</td></tr>
				
				<tr><td><br/></td></tr>
				<tr>
					<td>Land:</td><td><select name="country"><? include "includes/countries.php"; ?></select></td></tr>
					<tr><td></td><td>Hier kannst du Land ausw&auml;hlen</td></tr>
				
				<tr><td><br/></td></tr>
				<tr>
					<td>&Uuml;ber dich:</td><td><textarea name="about"><? echo $row->about; ?></textarea></td></tr>
					<tr><td></td><td>Hier kannst einen Text &uuml;ber dich schreiben</td></tr>
				
				<tr><td><br/></td></tr>
				<tr>		
			   		<td><input type="checkbox" <? if($showMailDB == 1) echo "checked"; ?> name="showMail"> E-Mail in Profil anzeigen</td>
			  	</tr>
			  	<tr><td><br/></td></tr>
				<tr>		
			   		<td><input type="checkbox" <? if($showAgeDB == 1) echo "checked"; ?> name="showAge"> Alter in Profil anzeigen</td>
			  	</tr>
			  	<tr><td><br/></td></tr>
			   	<tr>
			   		<td><input type="submit" value="&Auml;nderungen speichern" /></td>
		   		</tr>
		   </table>
		</form>
	</div>
	<? 
	} 
	else if($_GET["show"] == "role")
	{
	 	if($adminView == false)
			echo "You don't have Permission to view this page!";
		else
		{?>
			<h2><u>Rechte</u></h2>
		
			Hier kannst du Rechte &auml;ndern <br/>
			<form name="role" action="play.php?front=settings&show=role&userID=<? echo $ID; ?>&type=change" method="POST" enctype="multipart/form-data">
				<table>
					<tr>		
				   		<td><input type="checkbox" <? if($row->role == "admin") echo "checked"; ?> name="admin"> Benutzer ist Admin</td>
				  	</tr>
					<tr>		
				   		<td><input type="checkbox" <? if($row->role == "banned") echo "checked"; ?> name="banned"> Benutzer bannen</td>
		 	        </tr>
		 	        <tr>		
				   		<td><input type="checkbox" <? if($row->activated == 1) echo "checked"; ?> name="activated">Aktiviert</td>
		 	        </tr>
				   	<tr>
				   		<td><input type="submit" value="&Auml;nderungen speichern" /></td>
			   		</tr>
			   </table>
			</form>
		<?
		}
	}
	
	
	}
else 
{
	echo "<h1>User existiert nicht!</h1>";	
}
	?>

<?php 
}
?>