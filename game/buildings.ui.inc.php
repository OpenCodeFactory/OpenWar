<!--
    //=========================================================================
    // File:        buildings.ui.inc.php
    // Description: Open-War Game
    // Functions:   
    //                  - Zeigt Gebäude an
    //
    // Created:     2012-05-01
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
if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");


if(!isset($_SESSION['accountID']))
{
	header("Location:index.php");
}
else
{
    if(!isset($_GET["buildingID"]))
    {
        header("Location:play.php");
    }
    
    $buildingID = $_GET["buildingID"];
    
    include "game/buildings.pr.inc.php";
    
    ?>
    Current City: <a href="play.php?show=city&cityID=<?php echo $city->cityID; ?>"><?php echo $city->name; ?></a>

    <h2><?php echo $buildingType->buildingName ?></h2>
    
    <p>
        Level: <?php echo $building->level; ?>
    </p>
    
    <?php
    
    switch($building->buildingType)
    {
        case 0:
            showBuilding0();    //Kommandozentrale
            break;
            
        case 1:
            showBuilding1();    //Bohrturm
            break;
            
        case 2:
            showBuilding2();    //usw...
            break;
            
        case 3:
            showBuilding3();
            break;
            
        case 4:
            showBuilding4();
            break;
            
        case 5:
            showBuilding5();
            break;
            
        case 6:
            showBuilding6();
            break;
            
        case 7:
            showBuilding7();
            break;
            
        case 8:
            showBuilding8();
            break;
            
        case 9:
            showBuilding9();
            break;
    }
}

/*
 *  Kommandozentrale
 */
function showBuilding0()
{
    global $accountID;
    global $building;
    global $buildingType;
    global $buildingNextLevelType;
    
    ?>
    <h3>Bauschleife</h3>
    <table>
        <?php
        $buildingEventsQuery = mysql_query("SELECT * FROM BuildingEvents WHERE accountID = '$accountID' AND cityID = '$building->cityID'");
        while($buildingEvent = mysql_fetch_object($buildingEventsQuery))
        {
            $buildingEventBuilding = mysql_fetch_object(mysql_query("SELECT * FROM Buildings WHERE buildingID = '$buildingEvent->buildingID'"));
            $buildingName = mysql_fetch_object(mysql_query("SELECT buildingName FROM BuildingTypes WHERE buildingType = '$buildingEventBuilding->buildingType'"))->buildingName;
        
            ?>
            <tr>
                <td><?php echo $buildingName; ?></td>
                <td>Stufe: <?php echo $buildingEvent->nextLevel; ?></td>
                <td><?php echo date("d.m.Y - H:i:s", $buildingEvent->endTime); ?></td>
            </tr>
            <?php
        }
        ?>
    </table>
    <h3>Geb&auml;ube&uuml;bersicht</h3>
    
        <?php     
        for($i=0; $i<10; $i++)
        {
            $buildingTypeQuery = mysql_query("SELECT * FROM BuildingTypes WHERE buildingType = '$i'");
            $buildingType = mysql_fetch_object($buildingTypeQuery);
            
            $buildingName = $buildingType->buildingName;
            
            echo "<h4>$buildingName</h4>";
            
            echo "<img src='images/$buildingName.png' class='buildingSymbolSmall'>";
            
            ?><table><?php 
            
            $buildingsQuery = mysql_query("SELECT * FROM Buildings WHERE cityID = '$building->cityID' AND buildingType = '$i' ORDER BY level DESC");
            while($buildings = mysql_fetch_object($buildingsQuery))
            {
                $buildingTypeQuery = mysql_query("SELECT * FROM BuildingTypes WHERE buildingType = '$i' AND level = '$buildings->level'");
                $buildingType = mysql_fetch_object($buildingTypeQuery);
                ?>
                
                <tr>
                    <td><?php echo $buildingName; ?></td>
                    <td>
                         <?php
                         if($buildings->level == 0)
                            echo "wird gebaut";
                         else
                            echo "Stufe: $buildings->level"; 
                         ?>
                    </td>
                    <td><a href="?show=building&buildingID=<?php echo $building->buildingID; ?>&upgrade=<?php echo $buildings->buildingID ?>">Upgrade</a></td>
                    <td>Kosten: <?php echo $buildingType->cost; ?></td>
                    <td>Eisen: <?php echo $buildingType->ironNeed; ?></td>
                    <td>Energie: <?php echo $buildingType->energyNeed; ?></td>
                    <td>Er&ouml;l: <?php echo $buildingType->mineralOilNeed; ?></td>
                    <td>Zeit: <?php echo $buildingType->buildingTime; ?>m</td>
                </tr>
                
                <?php
            }
            
            if($buildingType->multiple == 1)
            {
            ?>
                <tr>
                    <td><?php echo $buildingName; ?></td>
                    <td></td>
                    <td><a href="?show=building&buildingID=<?php echo $building->buildingID; ?>&build=<?php echo $i ?>">Bauen</a></td>
                </tr>
            <?php
            }
            ?>
            </table>
            
            <div class='clearFloatLeft'></div>
            <?php
        }
        ?>
    
    
    <!--<h3>Truppen&uuml;bersicht</h3>-->
    
    <?php
}


/*
 *  Bohrturm
 */
function showBuilding1()
{
    global $building;
    global $buildingType;
    global $buildingNextLevelType;
    
    ?>
    
    <?php
    
    echo "V&ouml;rdermenge: $buildingType->specificValue <br>";
    if($building->level < 10)
        echo "V&ouml;rdermenge Level ".($building->level+1).": $buildingNextLevelType->specificValue";
}


/*
 *  Kraftwerk
 */
function showBuilding2()
{
    global $building;
    global $buildingType;
    global $buildingNextLevelType;
    
    ?>
    
    <?php
    
    echo "Energieproduktion: $buildingType->specificValue <br>";
    if($building->level < 10)
        echo "Energieproduktion Level ".($building->level+1).": $buildingNextLevelType->specificValue";
}


/*
 *  Miene
 */
function showBuilding3()
{
    global $building;
    global $buildingType;
    global $buildingNextLevelType;
    
    ?>
    
    <?php
    
    echo "V&ouml;rdermenge: $buildingType->specificValue <br>";
    if($building->level < 10)
        echo "V&ouml;rdermenge Level ".($building->level+1).": $buildingNextLevelType->specificValue";
}


/*
 *  Öltank
 */
function showBuilding4()
{
    global $building;
    global $buildingType;
    global $buildingNextLevelType;
    
    ?>
    
    <?php
    
    echo "F&uuml;llmenge: $buildingType->specificValue <br>";
    if($building->level < 10)
        echo "F&uuml;llmenge ".($building->level+1).": $buildingNextLevelType->specificValue";
}


/*
 *  Wohnhaus
 */
function showBuilding5()
{
    global $building;
    global $buildingType;
    global $buildingNextLevelType;
    
    ?>
    
    <?php
    
    echo "Einnahmen: $buildingType->specificValue <br>";
    if($building->level < 10)
        echo "Einnahmen Level ".($building->level+1).": $buildingNextLevelType->specificValue";
}


/*
 *  Stahllager
 */
function showBuilding6()
{
    global $building;
    global $buildingType;
    global $buildingNextLevelType;
    
    ?>
    
    <?php
    
    echo "Speichermenge: $buildingType->specificValue <br>";
    if($building->level < 10)
        echo "Speichermenge Level ".($building->level+1).": $buildingNextLevelType->specificValue";
}


/*
 *  Kaserne
 */
function showBuilding7()
{
    global $building;
    global $buildingType;
    global $buildingNextLevelType;
    
    echo "Baugeschwindigkeitsmultiplikator: ".($buildingType->specificValue/100)." <br>";
    if($building->level < 10)
        echo "Baugeschwindigkeitsmultiplikator Level ".($building->level+1).": ".($buildingNextLevelType->specificValue/100)."";
    
    ?>
    <br><br>
    <form>
        <table>
            <tr>
                <th>Soldat:</th><td><input type="text" /></td>
            </tr>
            <tr>
                <th>RPG Soldat:</th><td><input type="text" /></td>
            </tr>
            <tr>
                <th>Scharfsch&uuml;tze:</th><td><input type="text" /></td>
            </tr>
        </table>
        <input type="submit" value="Bauen">
    </form>
    <?php
    
    
}

/*
 *  Fabrik
 */
function showBuilding8()
{
    global $building;
    global $buildingType;
    global $buildingNextLevelType;
    
    echo "Baugeschwindigkeitsmultiplikator: ".($buildingType->specificValue/100)." <br>";
    if($building->level < 10)
        echo "Baugeschwindigkeitsmultiplikator Level ".($building->level+1).": ".($buildingNextLevelType->specificValue/100)."";
    
    ?>
    <br><br>
    <form>
        <table>
            <tr>
                <th>Panzer:</th><td><input type="text" /></td>
            </tr>
            <tr>
                <th>Mobile FLAK:</th><td><input type="text" /></td>
            </tr>
            <tr>
                <th>Humvee:</th><td><input type="text" /></td>
            </tr>
        </table>
        <input type="submit" value="Bauen">
    </form>
    <?php
}


/*
 *  Flughafen
 */
function showBuilding9()
{
    global $building;
    global $buildingType;
    global $buildingNextLevelType;
    
    echo "Baugeschwindigkeitsmultiplikator: ".($buildingType->specificValue/100)." <br>";
    if($building->level < 10)
        echo "Baugeschwindigkeitsmultiplikator Level ".($building->level+1).": ".($buildingNextLevelType->specificValue/100)."";
    
    ?>
    <br><br>
    <form>
        <table>
            <tr>
                <th>Bomber:</th><td><input type="text" /></td>
            </tr>
            <tr>
                <th>Abfangj&auml;ger:</th><td><input type="text" /></td>
            </tr>
            <tr>
                <th>Hubschrauber:</th><td><input type="text" /></td>
            </tr>
        </table>
        <input type="submit" value="Bauen">
    </form>
    <?php
}


