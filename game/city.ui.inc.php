<!--
    //=========================================================================
    // File:        city.ui.inc.php
    // Description: Open-War Game
    // Functions:   
    //                  - Zeigt eine Übersicht aller Städte und einzelne Städte an
    //
    // Created:     2012-05-01
    // Author:      Jonas Juffinger jonasjuffinger@gmail.com
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
    // Copyright (C) 2012 Jonas Juffinger
    //==========================================================================
-->

<?php
if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");

$accountID = $_SESSION['accountID'];

if(!isset($_GET["show"]))
{
    $show = "overview";
}
else
{
    $show = $_GET["show"];
}

include "game/city.pr.inc.php";

if($show == "overview")
{
    showOverview();
}
else if($show == "city")
{
    ?>  <?php
    
    if(!isset($_GET["cityID"]))
        showOverview();
    else
        showCity($OWEXEC);
}
else if($show == "building")
{
    include "game/buildings.ui.inc.php";
}

/*
 *  function showOverview()
 * 
 *  zeigt alle Städte an die man besitzt.
 */
function showOverview()
{
    global $accountID;
    ?>
    
    <h2>Deine St&auml;dte:</h2>
    
    <table>   
    <?php
    $cityQuery = mysql_query("SELECT * FROM City WHERE accountID = '$accountID'");
    while($city = mysql_fetch_object($cityQuery))
    {
        echo "<tr><td><a href='?show=city&cityID=$city->cityID'>$city->name</a></td><td>$city->points Punkte</td>";
    }
    
    echo "</table>";
}


/*
 *  function showCity()
 * 
 *  Zeigt eine Stadt an.
 *  Entscheidet ob die Stadt einem gehört oder ob es eine fremde Stadt ist.
 *      Je nachdem werden dann die Funktionen showOwnCity und showForeignCity aufgerufen
 */
function showCity($OWEXEC)
{
    $cityID = $_GET["cityID"];
    global $accountID;
    
    $cityQuery = mysql_query("SELECT * FROM City WHERE cityID = '$cityID'");
    $city = mysql_fetch_object($cityQuery);

    
    echo "Current City:";  echo "$city->name &emsp;&emsp; $city->points";  echo "Punkte";

    
    
    if($city->accountID == $accountID)
    {
        showOwnCity($city->accountID, $cityID, $OWEXEC);
    }
    else
    {
        showForeignCity($city->accountID, $cityID);
    }
}


/*
 *  function showOwnCity($cityOwner, $cityID)
 * 
 *  Zeigt alle Gebäude in einer Stadt an
 */
function showOwnCity($cityOwner, $cityID, $OWEXEC)
{
    ?>
    <br><br>
    <a href="?front=city">&larr; &Uuml;bersicht</a>
    <br>
    <br>
    <?php 
    include "resources.ui.inc.php";
    $buildings = mysql_query("SELECT * FROM Buildings WHERE cityID = '$cityID'");
        
    for($i=0; $i<10; $i++)
    {
        $buildingTypeQuery = mysql_query("SELECT * FROM BuildingTypes WHERE buildingType = '$i'");
        $buildingType = mysql_fetch_object($buildingTypeQuery);
        
        $buildingName = $buildingType->buildingName;
        
        echo "<h4>$buildingName</h4>";
        
        echo "<img src='images/$buildingName.png' class='buildingSymbolMedium'>";
        
        echo "<table>";
        
        $buildingsQuery = mysql_query("SELECT * FROM Buildings WHERE cityID = '$cityID' AND buildingType = '$i' ORDER BY level DESC");
        while($buildings = mysql_fetch_object($buildingsQuery))
        {
            
            ?>
            <tr>
                <td><a href="?show=building&buildingID=<?php echo $buildings->buildingID; ?>"><?php echo "$buildingName"; ?></a></td>
                <td>
                     <?php
                     if($buildings->level == 0)
                        echo "wird gebaut";
                     else
                        echo "Stufe: $buildings->level"; 
                     ?>
                </td>
            </tr>
            <?php
        }
        echo "</table>";
        
        echo "<div class='clearFloatLeft'></div>";
    }
    ?>
    <br>
    <br>
    <form action="play.php" method="GET">
        <input type="hidden" name="show" value="city">
        <input type="hidden" name="cityID" value="<?php echo $cityID; ?>">
        Stadt umbenennen: <input type="text" name="changeName"><input type="submit" value="umbennenen">
    </form>
    <?php 
}


/*
 *  showForeignCity($cityOwner)
 * 
 *  Zeigt Informationen zu einer fremden Stadt an
 */
function showForeignCity($cityOwner)
{
    $cityOwnerQuery = mysql_query("SELECT * FROM Account WHERE accountID = '$cityOwner'");
    $cityOwner = mysql_fetch_object($cityOwnerQuery)

    ?>
    <br><br>Stadtbesitzer: <a href="play.php?front=profile&userID=<?php echo $cityOwner->accountID; ?>"><?php echo $cityOwner->name; ?></a>
    <?php
}
?>