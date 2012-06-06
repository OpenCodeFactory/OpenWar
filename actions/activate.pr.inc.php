<?php 
if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");
    
if(isset($_GET['key']))
    $activationKey = $_GET['key'];
if(isset($_GET['id']))
    $activationID = $_GET['id'];

$query= "SELECT * FROM Activation WHERE ID = '$activationID'";
$result=mysql_query($query);
$row=mysql_fetch_object($result);

$activationKeyCompare = $row->activationKey;
$userName = $row->userName;

if($activationKeyCompare == $activationKey)
{
    $dbUpdate = "UPDATE Account SET  activated =  '1' WHERE  name ='$userName';";
    $dbDeleteActivation = "DELETE FROM Activation WHERE userName = '$userName';";
   
    $dbUpdateNow = mysql_query($dbUpdate) OR die("SQL FEHLER:<br>" . 
				   mysql_error() . "<br><br>SQL Fehler:<br>" . $dbUpdate);
	$dbUpdateNow = mysql_query($dbDeleteActivation) OR die("SQL FEHLER:<br>" . 
				   mysql_error() . "<br><br>SQL Fehler:<br>" . $dbDeleteActivation);
				   			   
	GenerateCity($userName);		//ruft die Funktion auf mit der die Stadt generiert wird				   
				   
	$notification = "Aktivierung erfolgreich!";
				   
} 


function GenerateCity($userName)	//generiert bei der Aktivierung eine eigene Stadt	-	by Matthias Lexer und Jonas Juffinger weil Matthias die Rohstoffe vergessen hat
{
		//	Komandozentrale - BuildingType 0 
		//  Macht einfach neue Zeile in der City mit der jeweiligen AccountID
		
	$sql = mysql_fetch_object(mysql_query("SELECT accountID FROM Account Where name = '$userName'"));
	$accountID = $sql->accountID;
	$cityName = "NewCity";
	$locationX = "1";
	$locationY = "1";
    $now = time();
		
	//generiert die erste Stadt mit den Werten von oben
	$dbGenerateCity = mysql_query("INSERT INTO City (accountID, name, locationX, locationY) 
						VALUES ('$accountID', '$cityName', '$locationX', '$locationY')");
	
	//holt sich die CityID von der Datenbank
	$sqlCity = mysql_fetch_object(mysql_query("SELECT cityID FROM City WHERE accountID LIKE '$accountID'")); 
	$cityID = $sqlCity->cityID;
	
    $dbGenerateResources = mysql_query("INSERT INTO Resources (cityID, lastUpdated, area, money, iron, energy, mineralOil, maxArea, maxIron, maxMineralOil, prodIron, prodMoney, prodEnergy, prodMineralOil)
                                VALUES ('$cityID', '$now', '0', '100', '100', '0', '50', '25', '100', '100', '5', '5', '100', '5')");
    
	//Baut die Kommandozentrale standartmaßig
	$dbGenerateBuilding = mysql_query("INSERT INTO Buildings (cityID, buildingType, locationX, locationY, level)
							    VALUES ('$cityID','0','$locationX','$locationY', 1)");
}

?>
