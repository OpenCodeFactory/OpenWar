<!--
    //=========================================================================
    // File:        update.pr.inc.php
    // Description: Open-War Game
    // Functions:   
    //                  - updates the whole game
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
    // Copyright (C) 2012 ow team
    //==========================================================================
-->

<?php

if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");


function update($accountID)
{
    $eventsQuery = mysql_query("SELECT * FROM Events WHERE accountID = '$accountID' ORDER BY endTime");
    
    while($event = mysql_fetch_object($eventsQuery))
    {
        if($event->endTime > time())
            break;
        
        if($event->eventType == 0)
            updateBuilding($event);
        
        updateResources($accountID, $event->endTime);
    }
    
    updateResources($accountID, time());
    
    updatePoints($accountID);
}

function updateResources($accountID, $endTime)
{
    $citysQuery = mysql_query("SELECT * FROM City WHERE accountID = '$accountID'");
    
    while($city = mysql_fetch_object($citysQuery))
    {
        $resourceValues = array();
        
        for($i=1; $i<=6; $i++)
        {
            $resourceValues[$i-1] = 0;
            
            $buildingQuery = mysql_query("SELECT level FROM Buildings WHERE cityID = '$city->cityID' AND buildingType = '$i'");
            while($building = mysql_fetch_object($buildingQuery))
            {
                if($building->level == 0)
                    continue;
                
                $buildingTypeQuery = mysql_query("SELECT specificValue FROM BuildingTypes WHERE buildingType = '$i' AND level = '$building->level'");
                $specificValue = mysql_fetch_object($buildingTypeQuery)->specificValue;
                
                $resourceValues[$i-1] += $specificValue;
            }
        }
        
        if($resourceValues[0] == 0)     //Mineral Oil
            $resourceValues[0] = 5;
        
        if($resourceValues[1] == 0)     //Prod Energy
            $resourceValues[1] = 100;
        
        if($resourceValues[2] == 0)     //Prod Iron
            $resourceValues[2] = 5;
        
        if($resourceValues[3] == 0)     //max Mineral Oil
            $resourceValues[3] = 100;
        
        if($resourceValues[4] == 0)     //Prod Money
            $resourceValues[4] = 5;
        
        if($resourceValues[5] == 0)     //max Iron
            $resourceValues[5] = 100;
        
        $resources = mysql_fetch_object(mysql_query("SELECT * FROM Resources WHERE cityID = '$city->cityID'"));
    
        $lastUpdated = $resources->lastUpdated;
        $dtime = ($endTime-$lastUpdated)/3600;
        
        $money = $resources->money + $dtime * $resources->prodMoney;
        $iron = $resources->iron + $dtime * $resources->prodIron;
        $mineralOil = $resources->mineralOil + $dtime * $resources->prodMineralOil;
        
        if($iron > $resources->maxIron)
            $iron = $resources->maxIron;
        
        if($mineralOil > $resources->maxMineralOil)
            $mineralOil = $resources->maxMineralOil;
        
        $aendern = mysql_query("UPDATE Resources Set 
                                    money = '$money',
                                    iron = '$iron',
                                    mineralOil = '$mineralOil',
                                    prodMineralOil = '".$resourceValues[0]."',
                                    prodEnergy = '".$resourceValues[1]."',
                                    prodIron = '".$resourceValues[2]."',
                                    maxMineralOil = '".$resourceValues[3]."',
                                    prodMoney = '".$resourceValues[4]."',
                                    maxIron = '".$resourceValues[5]."',
                                    lastUpdated = '$endTime'
                                    WHERE cityID = '$city->cityID'"
                              );
    }
}

function updatePoints($accountID)
{
    $totalPoints = 0;
    
    $citysQuery = mysql_query("SELECT * FROM City WHERE accountID = '$accountID'");
    while($city = mysql_fetch_object($citysQuery))
    {
        $points = 0;
        
        $buildingQuery = mysql_query("SELECT buildingType, level FROM Buildings WHERE cityID = '$city->cityID'");
        while($building = mysql_fetch_object($buildingQuery))
        {
            if($building->level == 0)
                continue;
            
            $buildingTypeQuery = mysql_query("SELECT points FROM BuildingTypes WHERE buildingType = '$building->buildingType' AND level = '$building->level'");
            $points += mysql_fetch_object($buildingTypeQuery)->points;
        }
        mysql_query("UPDATE City SET points = '$points' WHERE cityID = '$city->cityID'");
        
        $totalPoints += $points;
    }
    mysql_query("UPDATE Account SET points = '$totalPoints' WHERE accountID = '$accountID'");
}

function updateBuilding($event)
{
    $buildingEventQuery = mysql_query("SELECT * FROM BuildingEvents WHERE buildingEventID = '$event->eventLink'");
    $buildingEvent = mysql_fetch_object($buildingEventQuery);
    
    $updatedBuildingQuery = mysql_query("SELECT * FROM Buildings WHERE buildingID = '$buildingEvent->buildingID'");
    $updatedBuilding = mysql_fetch_object($updatedBuildingQuery);
    
    $nextLevel = $updatedBuilding->level+1;
    
    if($buildingEvent->buildingEventType == 1)
        $aendern = mysql_query("UPDATE Buildings Set level = '". $nextLevel ."' WHERE buildingID = '$buildingEvent->buildingID'");
    
    mysql_query("DELETE FROM Events WHERE eventID = '$event->eventID'");
    mysql_query("DELETE FROM BuildingEvents WHERE buildingEventID = '$event->eventLink'");
}

update($accountID);

?>