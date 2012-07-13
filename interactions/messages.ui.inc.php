<!--
    //=========================================================================
    // File:        messages.ui.inc.php
    // Description: Open-War Game
    // Functions:   
    //                  - Messages
    //
    // Created:     2012-05-01
    // Author:      Jonas Juffinger jonasjuffinger@gmail.com
    // Ver:         1.0 RC
    // Ver Notes:  
    //              great
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

$accountID = $_SESSION['accountID'];

if(!isset($_SESSION['accountID']))
{
	header("Location:index.php");
}
else
{
    if(isset($_GET['action']))
    {
        if($_GET['action'] == "send")
        {
            $success = false;
            include 'interactions/messages.pr.inc.php';
        }
    }
?>

<link rel="stylesheet" type="text/css" href="styles/messages.css">
<?php
   if(isset($success))
   {
       if($success == true)
       {
           ?>
           <p>Nachricht erfolgreich gesendet</p>
           <?php
       }
       else
       {
           ?>
           <p>Fehler beim Senden der Nachricht!</p>
           <?php
       }
   }
?>
<div id="messages">
<?php
	
	echo "<div id='submenu'>";
	echo "<a href='play.php?front=messages&type=newMessage'>Neue Nachricht</a>";
	echo "<a href='play.php?front=messages&type=sentMessages'>Gesendet</a>";
	echo "<a href='play.php?front=messages&type=receivedMessages'>Empfangen</a>";
	echo "</div>";
	
	if(isset($_GET["type"]))
	{
		if($_GET["type"] == "reply")
		{
			if(isset($_GET["show"]))
			{
				$show = $_GET["show"];
				
				$message = mysql_query("SELECT * FROM Messages WHERE messageID = '$show'");
				
				if($message == false)
					die("Nachricht existiert nicht");
					
				
				$row = mysql_fetch_object($message);
				
				$sender = mysql_fetch_object( mysql_query("SELECT name FROM Account WHERE accountID = '$row->sender'") )->name;
				
				//if($row->sender != $accountID)
				//{
					//als gelesen markieren
					$update = mysql_query("UPDATE Messages Set viewed = '1' WHERE messageID = '$show'");
					
					if($update == false)
						die(mysql_error());
				//}
				
				echo "<br/>";
				?>
				
				<div class="message">
					<div class="header">
						<span class="sender"><?php echo $sender; ?></span> <span class="subject"><?php echo $row->subject; ?></span> <span class="date"><?php echo $row->sendDate; ?></span>
					</div>
					<div class="text">
						<?php echo $row->message ?>
					</div>
				</div>
				
				<?php
			}
		}
		else if($_GET["type"] == "receivedMessages")
		{
			echo "<h3>Empfangene Nachrichten:</h3>";			
			$messages = mysql_query("SELECT * FROM Messages WHERE receiver = '$accountID' ORDER BY sendDate DESC");
			while($row = mysql_fetch_object($messages))
			{
				if($row->viewed == 0)
					$read = "unread";
				else
					$read = "read";
				
				$sender = mysql_fetch_object( mysql_query("SELECT name FROM Account WHERE accountID = '$row->sender'") )->name;
				?>
				
				<a href="play.php?front=messages&type=reply&show=<?php echo $row->messageID; ?>" class="head <?php echo $read; ?>">
						<span class="sender"><?php echo $sender; ?></span> <span class="subject"><?php echo $row->subject; ?></span> <span class="date"><?php echo $row->sendDate; ?></span>
				</a><br />
				
				<?php
			}
		}
		else if($_GET["type"] == "sentMessages")
		{
			echo "<h3>Gesendete Nachrichten:</h3>";
			$messages = mysql_query("SELECT * FROM Messages WHERE sender = '$accountID' ORDER BY sendDate DESC");
			while($row = mysql_fetch_object($messages))
			{		
				$receiver = mysql_fetch_object( mysql_query("SELECT name FROM Account WHERE accountID = '$row->receiver'") )->name;
				?>
				
				<a href="play.php?front=messages&type=reply&show=<?php echo $row->messageID; ?>" class="headread">
						<span class="sender"><?php echo $receiver; ?></span> <span class="subject"><?php echo $row->subject; ?></span> <span class="date"><?php echo $row->sendDate; ?></span>
				</a><br />
				
				<?php
			}
		}
	}
?>			
</div>
<?php 
	if(isset($_GET["show"]) && isset($_GET["type"]) && $_GET["type"] == "reply")
	{
		?><h3>Antworten:</h3><?php
	}
	else if(!isset($_GET["type"]) || $_GET["type"] == "newMessage")
	{
		?><h3>Neue Nachricht:</h3><?php
	}
	
	if(!isset($_GET["type"]) || $_GET["type"] == "reply" || $_GET["type"] == "newMessage")
	{

	
	$receiver="";
	
	if(isset($_GET["receiver"]))
	{
		$receiver = $_GET["receiver"];
	}

?>

<form action="play.php?front=messages&action=send" method="POST">
	<?php 
		if(isset($_GET["show"]))
		{
			?>
			<input type="hidden" name="reply" value="<?php echo $show; ?>" />
			<input type="hidden" name="receiver" value="<?php echo $sender; ?>" />
			<?php
		}
	?>
	<table>
		<?php 
			if(!isset($_GET["show"]))
			{
				?>
				<tr>
					<td>An:</td><td><input type="text" name="receiver" value="<? echo $receiver; ?>"/></td>
				</tr>
				<?php
			}
		?>
		<tr>
			<td>Betreff:</td><td><input type="text" name="subject" /></td>
		</tr>
		<tr>
			<td>Text:</td><td><textarea name="message" cols="50" rows="10"></textarea></td>
		</tr>
		<tr>
			<td><input type="submit" value="senden"/></td>
		</tr>
	</table>
</form>
<?php
	}
}
?>
