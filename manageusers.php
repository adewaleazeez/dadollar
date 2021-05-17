<!-- 
    Document   : login
    Created on : 28-Feb-2011
    Author     : Adewale Azeez
-->

<!--@page contentType="text/html" pageEncoding="UTF-8"-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
        <title>Da-Dollar Global Resources Limited</title>
		<!-- DEMO styles - specific to this page -->
		<link rel="stylesheet" type="text/css" href="css/complex.css" />
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />
        <link href="css/calendar.css" rel="stylesheet" type="text/css"/>
		<!--[if lte IE 7]>
			<style type="text/css"> body { font-size: 85%; } </style>
		<![endif]-->

        <script type="text/javascript" src="js/calendar.js"></script>
		<script type="text/javascript" src="js/jquery-ui-latest.js"></script>
		<script type="text/javascript" src="js/jquery.layout-latest.js"></script>
		<script type="text/javascript" src="js/complex.js"></script>
		<script type="text/javascript" src="js/users.js"></script>
		<script type="text/javascript" src="js/utilities.js"></script>

		<script language="javascript" src="js/jquery.marquee.js"></script>

		<link href="css/mycss.css" rel="stylesheet" type="text/css"/>
		<link href="css/westmart.css" rel="stylesheet" type="text/css"/>
        <script type="text/javascript">
            checkLogin();
        </script>
        <script type="text/javascript">
            $(function() {
                // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
                $("#dialog").dialog("destroy");

                $("#register").dialog({
                    autoOpen: true,
                    position:[280,70],
                    title: 'Manage Users',
                    height: 450,
                    width: 710,
                    modal: false,
                    buttons: {
                        Save: function() {
                            var reg = registerForm('insertUser');
                            if(reg.length>0){
                                $('#showAlert').dialog('close');
                                document.getElementById("showError").innerHTML = reg;
                                $('#showError').dialog('open');
                            }
                        },
                        Update: function() {
                            var reg = registerForm('updateUser');
                            if(reg.length>0){
                                $('#showAlert').dialog('close');
                                document.getElementById("showError").innerHTML = reg;
                                $('#showError').dialog('open');
                            }
                        },
                        New: function() {
                            document.getElementById("username").disabled = false;
                            clearRegisterForm();
                        },
                        Close: function() {
                            $('#register').dialog('close');
							window.location="home.php?pgid=0";
                        }
                    }
                });

                $("#showPrompt").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Alert!!!',
                    height: 300,
                    width: 300,
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
                    height: 280,
                    width: 350,
                    modal: true
                });

                $("#showError").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Error Message',
                    height: 300,
                    width: 300,
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $('#showError').dialog('close');
                        }
                    }
                });

            });
        </script>
    </head>
    <body>
        <table width="100%">
            <div id="showError"></div>
            <div id="showAlert"></div>
            <div id="showPrompt"></div>
            <tr>
                <td>
                    <div style="font-size:12px; height:235px; width:415px; top:5px; left:10px" id="register">
						<table style="width:380px">
							<tr class="formLabel">
								<td><b>User Name:</b></td>
								<!-- UserName -->
								<td class="input">
									<input type="text" id="username" name="username" size="30" />
								</td>
							</tr>

							<tr class="formLabel">
								<td><b>First Name:</b></td>
								<!-- FirstName -->
								<td class="input">
									<input type="text" id="firstname" name="firstname" onblur="this.value=capAdd(this.value);" size="20" />
								</td>
							</tr>

							<tr class="formLabel">
								<td><b>Last Name:</b></td>
								<!-- LastName -->
								<td class="input">
									<input type="text" id="lastname" name="lastname" onblur="this.value=capAdd(this.value);" size="20" />
								</td>
							</tr>

							<tr class="formLabel">
								<td><b>Login Status:</b></td>
								<td class="input">
									<select id="selectlogin" name="selectlogin" class="textField" >
										<option></option>
										<option>Yes</option>
										<option>No</option>
									</select>
								</td>
							</tr>

							<tr class="formLabel">
								<td><b>Active:</b></td>
								<td class="input">
									<select id="selectactive" name="selectactive" class="textField" >
										<option></option>
										<option>Yes</option>
										<option>No</option>
									</select>
								</td>
							</tr>

						</table>
						<div id="userlist" style="height:230px; max-width:795px; overflow:auto;"></div>
					</div>
                </td>
            </tr>
        </table>
    </body>
</html>
<script>
	getRegister();
</script>