<?

if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");
$id = $_SESSION['accountID'];
$sql = "SELECT role FROM Account WHERE accountID = '$id' LIMIT 1"; 
$sqlResult = mysql_query($sql); 
$row = mysql_fetch_object($sqlResult);
	
if(($row->role)!="admin")
{
  	?> <meta http-equiv="refresh" content="1; URL=play.php"> <?
}
else
{
    if(isset($_GET['action']))
	{
	    if($_GET['action'] == "pull")
	    {
	        include "config/pull.ui.inc.php";
	    }
	}
	
  	?>	<form action="?front=admin&action=pull" method="POST">
    	<input type="submit" value="Pull Changes">
    	</form> 
   	<?
}
    
    
?>
