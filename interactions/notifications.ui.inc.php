<?php
if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");
include "interactions/notifications.pr.inc.php";
$accountID = $_SESSION['accountID'];
?>
<table class="memberlist" border="1">
	<?php
		$sql="SELECT notificationsID, actionTime, aUserID, pUserID, pUserCity ".
			"FROM Notifications WHERE aUserID='$accountID' or pUserID='$accountID'";
		$attacks=mysql_query($sql);
		if(mysql_num_rows($attacks) == 0)
			echo "<h2>Keine Statusmeldungen vorhanden</h2>";
		while($row = mysql_fetch_object($attacks))
    	{
    		echo "<tr>";
        	if($row->pUserID == $accountID)
        	{
        		$sqlUser="SELECT name from Account WHERE accountID='$row->aUserID'".
        		" UNION SELECT name from City WHERE cityID='$row->pUserCity'";
				$user=mysql_query($sqlUser);
				while($rowNames = mysql_fetch_object($user))
    			{
					$nameArray[] = $rowNames->name;
				}
				echo "<td>Angriff auf dein Dorf $nameArray[1] von $nameArray[0] </td>";
				unset($nameArray);
			}
			else
			{
				$sqlUser="SELECT name from Account WHERE accountID='$row->pUserID'".
        		" UNION SELECT name from City WHERE cityID='$row->pUserCity'";
				$user=mysql_query($sqlUser);
				while($rowNames = mysql_fetch_object($user))
    			{
					$nameArray[] = $rowNames->name;
				}
				echo "<td>Angriff auf $nameArray[0]'s Dorf $nameArray[1] </td>";
			}
			$date = strtotime($row->actionTime);
			echo "<td>Am ".date( 'd.m.Y', $date )." um ".date( 'H:i', $date )."</td>";
			echo "</tr>";
    	}
	?>
</table>