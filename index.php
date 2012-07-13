<!DOCTYPE html>

<!--
 
 #######  ########  ######## ##    ## 
##     ## ##     ## ##       ###   ## 
##     ## ##     ## ##       ####  ## 
##     ## ########  ######   ## ## ## 
##     ## ##        ##       ##  #### 
##     ## ##        ##       ##   ### 
 #######  ##        ######## ##    ## 
       
##      ##    ###    ########  
##  ##  ##   ## ##   ##     ## 
##  ##  ##  ##   ##  ##     ## 
##  ##  ## ##     ## ########  
##  ##  ## ######### ##   ##   
##  ##  ## ##     ## ##    ##  
 ###  ###  ##     ## ##     ## 
 
########  ########   #######        ## ########  ######  ######## 
##     ## ##     ## ##     ##       ## ##       ##    ##    ##    
##     ## ##     ## ##     ##       ## ##       ##          ##    
########  ########  ##     ##       ## ######   ##          ##    
##        ##   ##   ##     ## ##    ## ##       ##          ##    
##        ##    ##  ##     ## ##    ## ##       ##    ##    ##    
##        ##     ##  #######   ######  ########  ######     ##    

    //=========================================================================
    // FOR MORE INFO AND SOURCE VISIT:
    // http://code.google.com/p/open-war-project/
    //=========================================================================
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
    // Copyright (C) 2012 THE OPEN WAR PROJECT
    //==========================================================================

 -->
 
<?php
@session_start();

$OWEXEC = true;

include 'config/initMysql.pr.inc.php';
?>
 
<html>
	<head>
		<title>Open War</title>
		
		
		<?php 
        // - - - - - - - - - - - - index.php?action=......- - - - - - - - - - - - //
        if(isset($_GET['action']))
		{
	        if($_GET['action'] == "register")
	        {
	            include "actions/register.pr.inc.php";
	        }
	        
	         if($_GET['action'] == "activate")
	        {
	            include "actions/activate.pr.inc.php";
	        }
	                        
	        else if($_GET['action'] == "login")
	        {
	            include "actions/login.pr.inc.php";
	        }
		}
                            
        //wird die login.pr.inc.php verwendet ist der obige code 
        //dazu da um es als index.php?action=login darzustellen
		?>
		<meta http-equiv="content-type" content="text/html; charset=ISO-8859-1">
		<link rel="stylesheet" type="text/css" href="styles/index.css">
		<link rel="stylesheet" type="text/css" href="styles/input.css">
		<link href='http://fonts.googleapis.com/css?family=Ubuntu:300,400,500' rel='stylesheet' type='text/css'>
		<script type="text/javascript" src="includes/index.js"></script>
	</head>
    <body>
    <!-- - - - - - - - - - - - Registrierungsformular- - - - - - - - - - - - - -->
    	<img src="images/logo.png" id="title" alt="logo" />
    	<div id="content">
    		<div id="register" class="box">
    			<h1>Registrieren</h1>
    			<form action="?action=register" method="POST">
    				<table>
    					<tr>
    						  <td>Username*:</td>
    						  <td><input type='text' name='username' /></td>
    					 </tr>
    					<tr>
    						  <td>E-Mail*:</td>
    						  <td><input type='text' name='emailAdress' /></td>
    					</tr>
    					<tr>
    						<td>Password*:</td>
    						<td><input type='password' name='password' /></td>
    					</tr>
    					<tr>
    						 <td>Repeat*:</td>
    						 <td><input type='password' name='passwordForChecking' /></td>
    					 </tr>
    					 <tr>
    						  <td>Country:</td>
    						  <td><select name="country">
								<? include "includes/countries.php"; ?>
							</select></td>
    					</tr>
    				</table>
    				<div style="text-align:right;"><input type="submit" value="Registrieren"></div>
    			</form>
    		</div>
    		
    		<!-- - - - - - - - - - - - - -Einzelnen Boxen- - - - - - - - - - - - -->
    							
    		
    		<div id="about" class="box unvisible">
    			<h1>Was ist Open War?</h1>
    			<p>Open War ist ein kleines Browserspiel das als Projekt im Rahmen des INI Unterrichts an unserer Schule entstanden ist.</p>
    			<h3>Mitarbeiter:</h3>
    			<ul>
    				<li><a href= "http://florianonline.at">Florian Polin</a></li>
    				<li>Andreas Kecht</li>
    				<li><a href= "http://flashfreezer.ath.cx">Matthias Lexer</a></li>
    				<li><a href="http://www.jonasjuffinger.com">Jonas Juffinger</a></li>
    				<li>Andreas Klingler</li>
    			</ul>
    		</div>
    		
    		<div id="impressum" class="box unvisible">
    			<h1>Impressum:</h1>
    			<p>OpenCodeFactory</p>
    			<p>Zust&auml;ndig:</p>
    			<p>Florian Polin</p>
    			<p>Buchenweg 4</p>
    			<p>A-6067 Absam</p>
    			<p>Tel. +43605046282</p>
    			<p>ow@florianonline.at</p>
    			
    		</div>
    	</div>
    	
    	<?php
    	if(isset($notification))
        {
            ?>
        	<div id="notificationBox">
            	<div id="notification">
                    <?php echo $notification; ?>
                </div>
            </div>
            <?php
        }
        ?>
    							<!-- - - - - - - - - - - - - Fusszeile- - - - - - - - - - - - -->
    							
    	<div id="footer">
    		<div id="menu">
    			<a href="#" class="button" onclick="showBox('register')">Registrieren</a>
    			<a href="#" class="button" onclick="showBox('about')">Was ist Open War?</a>
    			<a href="#" class="button" onclick="showBox('impressum')">Impressum</a>
    		</div>
    		<form action="?action=login" method="POST" id="login">
    			Login:
    			<input type='text' name='username' id="loginUsernameTextInput">
    			<script type="text/javascript">document.getElementById("loginUsernameTextInput").focus();</script>
    			<input type='password' name='password'>
    			<input type="submit" value="Anmelden">
    		</form>
    	</div>
    </body>
</html>
