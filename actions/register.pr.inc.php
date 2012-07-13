<!-- 

    	 \|||/							
         (o o)							
 ,~~~ooO~~(_)~~~~~~~~~,					//	File:		register.pr.inc.php
 |                    |					//	Ver :		v1.0
 |		   by	      |					//
 |    Flashfreezer    |					//	Functions:
 |       	          |					//				-	Registration
 '~~~~~~~~~~~~~~ooO~~~'					//
        |__|__|							//
         || ||							//
        ooO Ooo							//	License:	GNU GPL v3




 -->

<?php 

	if(!isset($OWEXEC))		die("You don't have permissions to access this file");
	
	
	//einige Funktionen f�r diese Datei
	function userInDatabase($username)		//prueft ob User schon vorhanden
	{
		$sql = "SELECT accountID FROM Account WHERE name LIKE '$username'"; 
		$sqlResult = mysql_query($sql); 
		$row = mysql_fetch_object($sqlResult); 
		
		if(isset($row->accountID)) return true;
	    else return false;
	}
	function emailInDatabase($email)		//prueft ob E-Mail schon vorhanden
	{
		$sql = "SELECT accountID FROM Account WHERE emailAdress LIKE '$email'"; 
		$sqlResult = mysql_query($sql); 
		$row = mysql_fetch_object($sqlResult); 
		
		if(isset($row->accountID)) return true;
	    else return false;
	}
	function check_email($email) 			//prueft ob E-Mail auch wirkliche eine gueltige Adresse ist
	{        
			if (preg_match('/^[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)*\@[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+(?:\.[^\x00-\x20()<>@,;:\\".[\]\x7f-\xff]+)+$/i', $email)) 
				return true;
		    	return false;
		}
	function GenerateString($length) 		//erstellt einen zufaelligen String mit der Laenge $length
	{
		srand ((double)microtime()*1000000); 
	    $zufall = rand(); 
	    $zufallsstring = substr( md5($zufall) , 0 , $length ); 
	    return $zufallsstring;
	}
	
	$username = mysql_real_escape_string($_POST['username']);
	$password = mysql_real_escape_string($_POST['password']);
	$passwordForChecking = mysql_real_escape_string($_POST['passwordForChecking']);			
	$country = mysql_real_escape_string(htmlentities(htmlspecialchars($_POST['country'])));
	$emailAdress = mysql_real_escape_string($_POST['emailAdress']);
	
//--------------------------------Ueberpruefung der $_POST Variablen-------------------------------------//
	
	if($username=='' || $emailAdress=='' || $password=='' || $passwordForChecking=='')
	{
		$notification = 'Bitte gib die erforderlichen Felder mit dem Sternchen ein.';
	}
	
	elseif(strlen($password)<5)	//kontrolliert Passwort auf Laenge
	{
		$notification = 'Achtung das Passwort muss mindesten 5 Zeichen lang sein';
	}
	
	elseif(check_email($emailAdress)==FALSE)
	{
		$notification = 'Keine g&uuml;ltige E-Mail eingeben';
	}
	
	
	elseif($password!=$passwordForChecking)		//schauen ob bei beiden feldern das passwort das gleiche ist
	{
		$notification = 'Bitte 2 mal das gleiche Passwort eingeben';
	}
	
	elseif(userInDatabase($username)==TRUE)
	{
		$notification = 'Der Benutzer ist schon in der Datenbank';
	}
	
	elseif(emailInDatabase($emailAdress)==TRUE)
	{
		$notification = 'Mit dieser E-Mail hat sich schon ein Nutzer registriert';
	}
	
//-------------------------------Verarbeitung der Variablen und Senden an die Datenbank------------------//
	else 
	{
		/// MD$ = UNSICHER!!
		/// UMSTELLUNG AUF SHA256 oder höher folg!
		
		$passwordMD5 = md5($password);
		//$passwordHASH = hash("sha512", $password); 
		$activationKey = md5($username.GenerateString(15));		//ist immer username + zufallstring und das ganze in MD5
		$activated = 2; 	// 1 - aktiviert
							// 2-  nicht aktiviert (standartmaeßig)
		$idForActivation = GenerateString(5);
		
		//daten an die db senden
		$dbUpdate = "INSERT INTO Account (name, password, emailAdress, country, activated, role) 
					 VALUES ('".$username."', '".$passwordMD5."', '".$emailAdress."', '".$country."', '".$activated."', 'user')";
		$dbActivation= "INSERT INTO Activation (ActivationKey, ID, userName) 
						VALUES ('".$activationKey."', '".$idForActivation."', '".$username."')";
					
		//Standartwerte die an die DB gesendet werden:
	
					//activated = 2
					//role = user
		
		$dbUpdateNow = mysql_query($dbUpdate) OR die("SQL FEHLER:<br>" . 
					   mysql_error() . "<br><br>SQL Fehler:<br>" . $dbUpdate);
					   
		$dbUpdateNow = mysql_query($dbActivation) OR die("SQL FEHLER:<br>" . 
					   mysql_error() . "<br><br>SQL Fehler:<br>" . $dbActivation);
		
		//Registrierungsmail versenden
		
		$headers   = array();
	    $headers[] = "MIME-Version: 1.0";
	    $headers[] = "Content-type: text/plain; charset=iso-8859-1";
	    $headers[] = "From: The Open War Project <ow@florianonline.at>";
	    $headers[] = "X-Mailer: PHP/".phpversion();
		
	    $message = "http://florianonline.at/open-war-project/index.php?action=activate&key=".$activationKey."&id=".$idForActivation."";
		$subject = "Deine Registrierung beim open-war-project!";
	    mail($emailAdress, $subject, $message, implode("\r\n", $headers));
	
	
		$notification = "Du wurdest erfolgreich registriert!<br>Wir haben dir eine E-Mail mit einem Aktivierungslink zugesandt";
	}
?>