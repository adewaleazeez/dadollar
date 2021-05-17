<!DOCTYPE html>
<html lang="en" class="no-js">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title>Da-Dollar Global Resources Limited</title>
		<meta name="description" content="Da-Dollar Global Resources Limited" />
		<meta name="keywords" content="navigation, menu, responsive, border, overlay, css transition" />
		<meta name="author" content="Codrops" />
		<link rel="shortcut icon" href="../favicon.ico">
		<link rel="stylesheet" type="text/css" href="css/normalize.css" />
		<link rel="stylesheet" type="text/css" href="css/demo.css" />
		<link rel="stylesheet" type="text/css" href="css/icons.css" />
		<link rel="stylesheet" type="text/css" href="css/style5.css" />
		<script src="js/modernizr.custom.js"></script>
        <script type='text/javascript' src='js/setup.js'></script>

        <script type="text/javascript" src="js/jquery-latest.js"></script>
        <script type="text/javascript" src="js/jquery-ui-latest.js"></script>
        <script type="text/javascript" src="js/jquery.layout-latest.js"></script>
        <script type="text/javascript" src="js/utilities.js"></script>
        <script type='text/javascript' src='js/calendar.js'></script>
		<script language="javascript" src="js/jquery.marquee.js"></script>
        <script type="text/javascript">
            checkLogin();
        </script>
	</head>
	<body>
		<div class="container">
			<header class="codrops-header">
				<div align="center">
					<div style="font-size: 3.0em; color:#FFFFFF; ">DADOLLAR</div><BR>
					<div style="font-size: 3.0em; color:#FFFFFF; ">Accounts Management Systems</div><BR>
					<div style="font-size: 1.0em; color:#FFFFFF; ">&#169;&nbsp;Copyright 2014&nbsp;Immaculate High-Tech Systems Ltd.</div>
					<!--TEXTAREA ID="txt" ROWS="10" COLS="100"></TEXTAREA-->
				</div>
				<?php 
					$currentmenu = trim($_GET['pgid']);
					if($currentmenu == 1 && strstr($_COOKIE["access"], '.php')){
						include($_COOKIE["access"]);
					}else{
						include("mm.php");
					}
				?>
			</header>
			<nav id="bt-menu" class="bt-menu">
				<a href="#" class="bt-menu-trigger"><span>Menu</span></a>
				<ul>
					<li><a href="javascript: checkAccess('customersetup.php', 'Customers Setup');">Customers&nbsp;Setup</a></li>
					<li><a href="javascript: checkAccess('deposits.php', 'Deposits Posting');">Deposits&nbsp;Posting</a></li>
					<li><a href="#">Withdrawal&nbsp;Processing</a></li>
					<li><a href="#">Customers&nbsp;Statement</a></li>
					<li><a href="#">Transactions&nbsp;Listing</a></li>
					<li><a href="login.php">Exit</a></li>
				</ul>
				<ul>
					<li style="coloor: white;">&#169;&nbsp;Copyright 2014&nbsp;Immaculate High-Tech Systems Ltd.</li>
					<li><a href="http://www.twitter.com" class="bt-icon icon-twitter">Twitter</a></li>
					<li><a href="http://www.google.com" class="bt-icon icon-gplus">Google</a></li>
					<li><a href="http://www.facebook.com" class="bt-icon icon-facebook">Facebook</a></li>
					<li><a href="https://github.com" class="bt-icon icon-github">icon-github</a></li>
				</ul>
			</nav>
		</div><!-- /container -->
	</body>
	<script src="js/classie.js"></script>
	<script src="js/borderMenu.js"></script>
</html>
