<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.1//EN" "http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="language" content="en" />
        <!-- uncomment 'base' to view this page without external files
		<base href="http://jquery-border-layout.googlecode.com/svn/trunk/" />
		-->

        <title>Da-Dollar Global Resources Limited</title>

        <link rel="stylesheet" type="text/css" href="css/complex.css" />
        <!--[if lte IE 7]>
			<style type="text/css"> body { font-size: 85%; } </style>
		<![endif]-->

        <script type="text/javascript" src="js/jquery-latest.js"></script>
        <script type="text/javascript" src="js/jquery-ui-latest.js"></script>
        <script type="text/javascript" src="js/jquery.layout-latest.js"></script>
        <script type="text/javascript" src="js/complex.js"></script>
        <script type="text/javascript" src="js/utilities.js"></script>
        <script type='text/javascript' src='js/setup.js'></script>
        <script type='text/javascript' src='js/calendar.js'></script>
		<script language="javascript" src="js/jquery.marquee.js"></script>

        <link href="css/mycss.css" rel="stylesheet" type="text/css"/>
        <link href="css/emsportal.css" rel="stylesheet" type="text/css"/>
        <!--[if IE]> <style type="text/css">@import "css/IE-override.css";</style> <![endif]-->
        <script type="text/javascript">
            checkLogin();
			//var myVar2=setInterval(function(){updateUserTime()},60000);
			//AjaxFunctionSetup("/laspotechportal/userbackend.php?option=updateUserTime");

			/*function updateUserTime(){
				var url = "/dadollar/userbackend.php?option=updateUserTime";
				AjaxFunctionSetup(url);
			}*/

        </script>
    </head>
    <body onload="javascript: checkLogin()">
        <div class="ui-layout-north">
            <div align="center">
                <img src="images/SCHOOBanner.png" width="100%" height="60" title="Da-Dollar Logo" alt="Da-Dollar Portal Logo"/>
            </div>
        </div>


        <div class="ui-layout-west">
			<div class="header"><b>The Menu</b></div>
			<div class="content">
				<?php include("menupane.php"); ?> 
			</div>

			<div class="footer"><b>*** Click Menu to Show Sub Menu ***</b></div>

		</div>


		<div class="ui-layout-east">

			<div class="header"><b>News Ticker</b></div>

			<div id="subhead" class="subhead" align="center"><b>*** Mouseover pauses scrolling ***</b></div>

			
			<div id="content" class="content">
				<?php include("newsticker.php"); ?>
			</div>
			<div id="footer" class="footer" align="center"><b>*** Mouseout continues scrolling ***</b></div>
		</div>

		<div id="mainContent">
			<div class="ui-layout-center">
				<?php 
					$currentmenu = trim($_GET['pgid']);
					if($currentmenu == 1 && strstr($_COOKIE["access"], '.php')){
						include($_COOKIE["access"]);
					}else{
						include("mm.php");
					}
				?>
			</div>
		</div>


        <div class="ui-layout-south">
            <div class="footer_container" id="footer_container">
				<div class="footer_left">
					<table width="100%">
						<tr>
							<td width="30%" align="left">
								<div style="display:inline;text-align:left">
									Full Name:&nbsp;<b><?php echo $_COOKIE["currentuserfullname"]; ?></b>
								</div>
							</td>
							<td width="50%">
								<div style="display:inline;text-align:center">
									&#169;&nbsp;Copyright 2009&nbsp;<a href="" target="_blank">Immaculate High-Tech Systems Ltd.</a>
								</div>
							</td>
							<td width="20%" align="right">
								<div style="display:inline;text-align:right">
									User-Id:&nbsp;<b><?php echo $_COOKIE["currentuser"]; ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</b>
								</div>
							</td>
						</tr>
					</table>
				</div>
            </div>
        </div>

    </body>

</html>
