
<?
	if( !isset($OWEXEC) )
  	  die("You don't have permissions to access this file");
    
	if(isset($_POST["view"]) && isset($_SESSION["accountID"]) && isset($_POST["page"]))
	{
		$role = $_POST['view'];
		$id = $_SESSION['accountID'];
	    
        $success = mysql_query("UPDATE Account SET role='$role' WHERE accountID='$id'");
	
		?> 
		<meta http-equiv="refresh" content="0; URL=<? echo $_POST["page"]; ?>"> 
		<? 
	}
?>