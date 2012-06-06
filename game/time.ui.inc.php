<!--
    //=========================================================================
    // File:        time.ui.inc.php
    // Description: Displays the server time.
    // Functions:   
    //            	- Displays server Time in UTC
    //
    // Created:     2012-05-01
    // Author:      Florian Polin (mail@florianonline.at)
    // Ver:         1.0
    // Ver Notes:  
    //              Should be complete
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
 * 0.1 - 2012-05-03
 *      ***[ADDED]***
 *      - Everything should be working as it should             ***working***
 *      ###AUTHOR### 'FLORIAN POLIN (mail@florianonline.at)'
 * 1.0 - 2012-05-27
 *      ***[CHANGED]***
 *      - Version Number now 1.0 because everything seems OK    ***working***
 *      ###AUTHOR### 'FLORIAN POLIN (mail@florianonline.at)'
 *===========================================================================
-->

<!-- May Garfield be with you... 
              __ __
            ,;::\::\
          ,'/' `/'`/
      _\,: '.,-'.-':.
     -./"'  :    :  :\/,
      ::.  ,:____;__; :-
      :"  ( .`-*'o*',);
       \.. ` `---'`' /
        `:._..-   _.'
        ,;  .     `.
       /"'| |       \
      ::. ) :        :
      |" (   \       |
      :.(_,  :       ;
       \'`-'_/      /
        `...   , _,'
         |,|  : |
         |`|  | |
         |,|  | |
     ,--.;`|  | '..--.
    /;' "' ;  '..--. ))
    \:.___(___   ) ))'
           SSt`-'-'' 
-->

<!-- Zeit vom Server holen -->
<?php
if( !isset($OWEXEC) )
    die("You don't have permissions to access this file");

date_default_timezone_set('Europe/Berlin'); 
$oTimeFromServer = date("Y, n, j, G, i, s");
?>

<!-- Script bekommt die Zeit und zaehlt jede Sekunde um eines weiter. -->
<script language="JavaScript">
var oTimeServer = new Date(<?php echo $oTimeFromServer ?>);

window.onload=oTimeCounter;

function oTimeCounter()
{
    if(!document.all && !document.getElementById)
    {
        return;
    }

    var iHours = oTimeServer.getHours();
    var iMinutes = oTimeServer.getMinutes();
    var iSeconds = oTimeServer.getSeconds();
    oTimeServer.setSeconds(iSeconds+1);

    if(iHours <= 9)
    {
        iHours = "0" + iHours;
    }

    if(iMinutes <= 9)
    {
        iMinutes = "0" + iMinutes;
    }

    if(iSeconds <= 9)
    {
        iSeconds = "0" + iSeconds;
    }
    
    oDisplayTime = iHours + ":" + iMinutes + ":" + iSeconds ;

    if(document.getElementById)
    {
        document.getElementById("Time").innerHTML = oDisplayTime;
    }
    else if(document.all)
    {
        Time.innerHTML = oDisplayTime;
    }

    setTimeout("oTimeCounter();", 1000);
}
</script>
<p id="Time"></p>