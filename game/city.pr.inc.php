<!--
    //=========================================================================
    // File:        buildings.pr.inc.php
    // Description: Open-War Game
    // Functions:   
    //                  - Name der Stadt ändern
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


if($show == "city")
{
    if(isset($_GET["cityID"]))
    {
        if(isset($_GET["changeName"]))
        {
            $newName = mysql_real_escape_string(htmlentities(htmlspecialchars($_GET["changeName"])));
            $cityID = $_GET["cityID"];
            
            $update = mysql_query("UPDATE City Set name = '$newName' WHERE cityID = '$cityID'");
            
            echo mysql_error();
        }
    }
}

?>