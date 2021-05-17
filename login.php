<!-- 
    Document   : login
    Created on : 28-Feb-2011
    Author     : Adewale Azeez
-->

<!--@page contentType="text/html" pageEncoding="UTF-8"-->
<?php	setcookie("currentuser", null, false);	?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN"
    "http://www.w3.org/TR/html4/loose.dtd">

<html>
    <head>
        <title>Da-Dollar Global Resources Limited</title>
		<meta charset="UTF-8" />
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<meta name="description" content="Blueprint: Slide and Push Menus" />
		<meta name="keywords" content="sliding menu, pushing menu, navigation, responsive, menu, css, jquery" />
		<meta name="author" content="Codrops" />
		<link rel="shortcut icon" href="../favicon.ico">
		<!--link rel="stylesheet" type="text/css" href="css/default.css" />
		<link rel="stylesheet" type="text/css" href="css/component.css" /-->

        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />
        <script type='text/javascript' src='js/utilities.js'></script>
        <script type='text/javascript' src='js/users.js'></script>
        <script type="text/javascript" src="js/jquery-1.4.2.min.js"></script>
        <script type="text/javascript" src="js/jquery-ui-1.8.4.custom.min.js"></script>

		<!--link href="css/emsportal.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript" src="js/complex.js"></script>
		<script src="js/modernizr.custom.js"></script>
        <link href="css/login.css" rel="stylesheet" type="text/css"/>
        <style type="text/css">
            body { font-size: 60.5%; }
            label, input { display:block; }
            input.text { margin-bottom:12px; width:95%; padding: .4em; }
            fieldset { padding:0; border:0; margin-top:25px; }
            h1 { font-size: 1.2em; margin: .6em 0; }
            div#users-contain { width: 350px; margin: 20px 0; }
            div#users-contain table { margin: 1em 0; border-collapse: collapse; width: 100%; }
            div#users-contain table td, div#users-contain table th { border: 1px solid #eee; padding: .6em 10px; text-align: left; }
            .ui-dialog .ui-state-error { padding: .3em; }
            .validateTips { border: 1px solid transparent; padding: 0.3em; }

        </style-->
        <script type="text/javascript">
            $(function() {
                // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
                $("#dialog").dialog("destroy");

                $("#loginFormPanel").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Login',
                    height: 250,
                    width: 450,
                    modal: false,
                    buttons: {
                        Login: function() {
                            loginForm();
                        },
                        Clear: function() {
                            clearLoginForm();
                        },
                        Reset_Password: function() {
                            resetPassword();
                        }
                    }
                });

                $("#showPrompt").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Alert!!!',
                    height: 400,
                    width: 400,
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $('#showPrompt').dialog('close');
                        }
                    }
                });

                $("#showAlert").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Alert!!!',
                    height: 400,
                    width: 400,
                    modal: true
                });

                $("#showError").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Error Message',
                    height: 400,
                    width: 400,
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $('#showError').dialog('close');
                        }
                    }
                });

				$('#loginFormPanel').dialog('open');

				if(navigator.appName == "Microsoft Internet Explorer"){
					$('#loginFormPanel').dialog({ modal: false, height: 380, width: 450 });
				}				

            });

			createCookie('progid', 'loginForm', false);
        </script>
    </head>
    <body class="cbp-spmenu-push" style="background-color: #6633FF; font-color: #FFFFFF;"><!--#1abc9c;-->
		<div id="showError"></div>
		<div id="showAlert"></div>
		<div id="showPrompt"></div>
		<div class="container">
			<header class="clearfix" style="font-color: #47a3da;">
				<div style="font-size: 90px; text-align: center;"><font color="#FFFFFF">DADOLLAR</font></div>
				<div style="font-size: 70px; text-align: center;"><font color="#FFFFFF">Accounts Management Systems</font></div>
			</header>
		</div>
		<div style="height:335px; width:415px; top:5px; left:10px" id="loginFormPanel">
			<table style="width:380px">
				<tr class="formLabel">
					<td><b>User&nbsp;Name:</b></td>
					<!-- UserName -->
					<td class="input">
						<input type="text" id="username" size="30" tabindex="1" />
					</td>
				</tr>
				</tr><td>&nbsp;</td></tr>
				<tr class="formLabel">
					<td><b>Password:</b></td>
					<!-- Password -->
					<td class="input">
						<input type="password" id="password" onkeypress="checkEnter(event)" name="password" size="20" maxlength=20  tabindex="2" />
					</td>
				</tr>
			</table>
		</div>
    </body>
</html>
