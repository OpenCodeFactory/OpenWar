<!--
    //=========================================================================
    // File:        buildings.pr.inc.php
    // Description: Open-War Game
    // Functions:   
    //                  - tätigt die Datenbankabfragen
    //                  - upgrade Gebäude
    //
    // Created:     2012-05-29
    // Author:      Jonas Juffinger jonasjuffinger@gmail.com
    // Ver:         0
    // Ver Notes:  
    //              Doesn't work
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
$buildingQuery = mysql_query("SELECT * FROM Buildings WHERE buildingID = '$buildingID'");
$building = mysql_fetch_object($buildingQuery);

$buildingTypeQuery = mysql_query("SELECT * FROM BuildingTypes WHERE buildingType = '$building->buildingType' AND level = '$building->level'");
$buildingType = mysql_fetch_object($buildingTypeQuery);

$buildingNextLevelTypeQuery = mysql_query("SELECT * FROM BuildingTypes WHERE buildingType = '$building->buildingType' AND level = '".($building->level+1)."'");
$buildingNextLevelType = mysql_fetch_object($buildingNextLevelTypeQuery);

$cityQuery = mysql_query("SELECT * FROM City WHERE cityID = '$building->cityID'");
$city = mysql_fetch_object($cityQuery);


if(isset($_GET["upgrade"]))
{
    $upgrade = $_GET["upgrade"];
    
    upgradeBuilding($upgrade);
}

if(isset($_GET["build"]))
{
    $build = $_GET["build"];
    
    $newBuildingQuery = mysql_query("INSERT INTO Buildings (accountID, cityID, buildingType, level) VALUES ('$accountID', '$building->cityID', '$build', '0')");
    
    $newBuildingIDQuery = mysql_query("SELECT buildingID FROM Buildings ORDER BY buildingID DESC LIMIT 1");
    
    $upgrade = mysql_fetch_object($newBuildingIDQuery)->buildingID;
    
    upgradeBuilding($upgrade);
}


function upgradeBuilding($upgrade)
{
    global $building;
    global $buildingType;
    global $buildingNextLevelType;
    global $city;
    global $accountID;
        
    $now = time();
    
    $upgradeBuildingQuery = mysql_query("SELECT * FROM Buildings WHERE buildingID = '$upgrade' AND accountID = '$accountID'");
    $upgradeBuilding = mysql_fetch_object($upgradeBuildingQuery);
    
    if(isset($upgradeBuilding))
    {
        //Prüfen ob schon 2 Gebäude in der Bauschleife sind, prüfen wann das letzte Gebäude in der Bauschleife fertig ist
        $lastBuildFinished = $now;
        $nextLevel = $upgradeBuilding->level+1;
        
        $buildingEventsQuery = mysql_query("SELECT endTime, buildingID FROM BuildingEvents WHERE cityID = '$upgradeBuilding->cityID' ORDER BY endTime");
        for($i=0; $buildingEvent = mysql_fetch_object($buildingEventsQuery); $i++)
        {
            $lastBuildFinished = $buildingEvent->endTime;
            
            if($buildingEvent->buildingID == $upgradeBuilding->buildingID)
                $nextLevel++;
        }
        
        if($i >= 2)
        {
            echo "Es haben nur 2 Geb&auml;ude in der Bauschleife platz!<br><br>";
        }
        else
        {
            $upgradeBuildingTypeQuery = mysql_query("SELECT * FROM BuildingTypes WHERE buildingType = '$upgradeBuilding->buildingType' AND level = '$nextLevel'");
            $upgradeBuildingType = mysql_fetch_object($upgradeBuildingTypeQuery);
            
            //Prüfen ob genügend Resourcen vorhanden sind
            $resourcesQuery = mysql_query("SELECT * FROM Resources WHERE cityID = '$upgradeBuilding->cityID'");
            $resources = mysql_fetch_object($resourcesQuery);
            
            $notEnoughResources = false;
            if($upgradeBuildingType->cost > $resources->money)
            {
                echo "zu wenig Geld!<br>";
                $notEnoughResources = true;
            }
            if($upgradeBuildingType->area + $resources->area > $resources->maxArea && $nextLevel < 1)
            {
                echo "zu wenig Baufl&auml;che!<br>";
                $notEnoughResources = true;
            }
            if($upgradeBuildingType->ironNeed > $resources->iron)
            {
                echo "zu wenig Eisen!<br>";
                $notEnoughResources = true;
            }
            if($upgradeBuildingType->mineralOilNeed > $resources->mineralOil)
            {
                echo "zu wenig Erdöl!<br>";
                $notEnoughResources = true;
            }
            if($upgradeBuildingType->energyNeed + $resources->energy > $resources->prodEnergy && $upgradeBuilding->buildingType != 2)
            {
                echo "nicht genug Energie!<br>";
                $notEnoughResources = true;
            }
            
            if($notEnoughResources == false)
            {
                $buildFinished = $lastBuildFinished + $upgradeBuildingType->buildingTime*60;
                
                mysql_query("INSERT INTO BuildingEvents (accountID, buildingID, cityID, buildingEventType, nextLevel, startTime, endTime) VALUES ('$accountID', '$upgradeBuilding->buildingID', '$upgradeBuilding->cityID', '1', '$nextLevel', '$lastBuildFinished', '$buildFinished')");
                
                $eventID = mysql_fetch_object(mysql_query("SELECT buildingEventID FROM BuildingEvents WHERE accountID = '$accountID' ORDER BY buildingEventID DESC LIMIT 1"))->buildingEventID;
                
                mysql_query("INSERT INTO Events (eventType, eventLink, accountID, endTime) VALUES ('0', '$eventID', '$accountID', '$buildFinished')");
                
                
                $area = $resources->area;
                if($nextLevel < 1)
                    $area = $upgradeBuildingType->area + $resources->area;
                
                $money = $resources->money-$upgradeBuildingType->cost;
                $iron = $resources->iron-$upgradeBuildingType->ironNeed;
                $energy = $upgradeBuildingType->energyNeed + $resources->energy;
                $mineralOil = $resources->mineralOil-$upgradeBuildingType->mineralOilNeed;
                
                mysql_query("UPDATE Resources Set area = '$area', money = '$money', iron = '$iron', energy = '$energy', mineralOil = '$mineralOil' WHERE cityID = '$city->cityID'");
            
                echo mysql_error();
            }
        }
    }
}
?>