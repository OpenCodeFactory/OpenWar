<?
	if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");
    
	$accountID = $_SESSION['accountID'];
	$sqlResult = mysql_query("SELECT * FROM Account WHERE accountID='$accountID'");
	$row = mysql_fetch_object($sqlResult);  
	
	if(isset($_GET['view']))
	{
	    include "account/userview.pr.inc.php";
	}
	
    if($row->role == "admin")
	{
	    ?>
	    <form action="play.php?view=0" method="POST">
	  	 	<input type="hidden" name="view" value="tempNoAdmin" />
	  	 	<input type="hidden" name="page" value="<? echo (isset($_SERVER['HTTPS'])?'https':'http').'://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];   ?>" />
    		<input type="submit" value=">UserView" />
    	</form>
    	<?
	}
	else if($row->role == "tempNoAdmin")
	{
	    ?>
	    <form action="play.php?view=1" method="POST">
	   		<input type="hidden" name="view" value="admin" />
	   		<input type="hidden" name="page" value="<? echo (isset($_SERVER['HTTPS'])?'https':'http').'://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];   ?>" />
    		<input type="submit" value=">AdminView" />
    	</form>
    	<?
	}
?>
