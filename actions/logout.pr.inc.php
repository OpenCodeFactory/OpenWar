<!-- 

    	 \|||/							
         (o o)							
 ,~~~ooO~~(_)~~~~~~~~~,					//	File:		logout.pr.inc.php
 |                    |					//	Ver :		v1.0
 |		   by	      |					//
 |    Flashfreezer    |					//	Functions:
 |       	          |					//				-	Logs you out from the system
 '~~~~~~~~~~~~~~ooO~~~'					//
        |__|__|							//
         || ||							//
        ooO Ooo							//	License:	GNU GPL v3




 -->


<?php 

if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");

	//simples session_destroy() als logout 

	@session_start();
	session_destroy();
	header("Location:index.php")

?>
