<!--
    //=========================================================================
    // File:        resources.ui.inc.php
    // Description: Handles the display of the resources.
    // Functions:   
    //            	- Displays maximum resources
    //            	- Displays actual resources and counts
    //
    // Created:     2012-05-06
    // Author:      Florian Polin (mail@florianonline.at)
    // Ver:         1.0
    // Ver Notes:  
    //              Should work like a charm! ;)
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
    // Copyright (C) 2012 Florian Polin
    //==========================================================================
-->

<!--
 *===========================================================================
 * CHANGELOG
 *===========================================================================
 * 0.1 - 2012-05-06 - FINISHED
 *      ***[ADDED]***
 *		- All main functions added and should work              ***added***
 *		***[CHANGED]***
 *		- 
 *		***[NOTE]***
 *      - File created								             ***note***
 *      ###AUTHOR### 'FLORIAN POLIN (mail@florianonline.at)'
 * 1.0 - 2012-05-28 - FINISHED
 *      ***[ADDED]***
 *		- 
 *		***[CHANGED]***
 *      - Fixed minor bug, when resources reach their maximum   ***changed***
 *		- Version to 1.0                                        ***ver change***
 *		***[NOTE]***
 *      -
 *      ###AUTHOR### 'FLORIAN POLIN (mail@florianonline.at)'
 *===========================================================================
-->

<?php
if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");

// Abfrage aller noetigen Variablen aus der DB

//SQL Abfrage nach den Aktuellen Resourcenstaenden
$cityID = $_GET["cityID"];
$query= "SELECT * FROM Resources WHERE cityID = '$cityID'";
$result=mysql_query($query);
$row=mysql_fetch_object($result);

//Auslesen der SQL Abfrage und uebernehmen der Variablen
$money=$row->money;
$iron=$row->iron;
$energy=$row->energy;
$area=$row->area;
$mineralOil=$row->mineralOil;

$maxIron=$row->maxIron;
$maxMineralOil=$row->maxMineralOil;
$maxArea=$row->maxArea;

$prodMoney=$row->prodMoney;
$prodIron=$row->prodIron;
$prodEnergy=$row->prodEnergy;
$prodMineralOil=$row->prodMineralOil;

?>

<!-- Die Resourcen entsprechend der aktuellen Produktion weiter zaehlen lassen -->
<script language="JavaScript">
var money = <?php echo $money ?>;
var iron = <?php echo $iron ?>;
var energy = <?php echo $energy ?>;
var area = <?php echo $area ?>;
var mineralOil = <?php echo $mineralOil ?>;

var maxIron = <?php echo $maxIron ?>;
var maxMineralOil = <?php  echo $maxMineralOil ?>;
var maxArea = <?php echo $maxArea ?>;

var prodMoney = <?php echo $prodMoney ?>;
var prodIron = <?php echo $prodIron ?>;
var prodEnergy = <?php echo $prodEnergy ?>;
var prodMineralOil = <?php echo $prodMineralOil ?>;

window.onload=ResourceCounter;

function ResourceCounter()
{
    var moneyRound = Math.round(money*10) / 10;
    var ironRound = Math.round(iron*10) / 10;
    var energyRound = Math.round(energy*10) / 10;
    var mineralOilRound = Math.round(mineralOil*10) / 10;
    
    var ResourceDisplay = "<span style='display:inline-block; width:150px;'>Geld:" + moneyRound + " " + "</span>\
    <span style='display:inline-block; width:150px;'>Energie: " + energy + "/" + prodEnergy + " " + "</span>\
    <span style='display:inline-block; width:150px;'>Fl&auml;che: " + area + "/" + maxArea + " " + "</span>\
    <span style='display:inline-block; width:200px;'>Eisen: " + ironRound + "/" + maxIron + " " + "</span>\
    <span style='display:inline-block; width:150px;'>Oil: " + mineralOilRound + "/" + maxMineralOil + "</span>";
    
    if(document.getElementById)
    {
        document.getElementById("Resources").innerHTML = ResourceDisplay;
    }
    else if(document.all)
    {
        Resources.innerHTML = ResourceDisplay;
    }
    
    
    money = money + prodMoney/3600;
    iron = iron + prodIron/3600;
    mineralOil = mineralOil + prodMineralOil/3600;
    
    if(iron > maxIron)
        iron = maxIron;
    
    if(mineralOil > maxMineralOil)
        mineralOil = maxMineralOil;
    
    setTimeout("ResourceCounter();", 1000);
}
</script>
<p id="Resources"></p>