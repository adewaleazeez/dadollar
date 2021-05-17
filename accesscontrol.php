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

                $("#menuaccess").dialog({
                    autoOpen: true,
                    position:[280,70],
                    title: 'User Access Menu',
                    height: 450,
                    width: 710,
                    modal: false,
                    buttons: {
                        Close: function() {
                            $('#menuaccess').dialog('close');
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
                    <div style="font-size:12px; height:235px; width:415px; top:5px; left:10px" id="menuaccess">
						<table width="100%">
							<tr>
								<td>
									<div id="menulistheader">
										<table border='0' style='border-color:#fff;border-style:solid;border-width:1px; height:10px;width:100%;background-color:#336699;margin-top:5px;'>
											<tr style='font-weight:bold; color:white'>
												<td colspan='2'>
													User Name:&nbsp;<input type="text" id="currentuser" style="display:inline" name="currentuser" size="25" onkeyup="getRecordlist(this.id,'userlist2')" onclick="this.value=''; getRecordlist(this.id,'userlist2')">
												<!--/td>
												<td-->
													<input type='button' id="filterbutton" style="display:inline" onclick='getUsersMenu(this.id)' value=' List Menus ' />
												</td>
											</tr>
										</table>
										<div id="userlist2"></div>
									</div>
									<div id="menulistheader2">
										<table border='0' style='border-color:#fff;border-style:solid; height:10px;width:100%;background-color:#336699;margin-top:5px;'>
											<tr style='font-weight:bold; color:white'>
												<td width='6%' align='right'><b>S/No</b></td>
												<td width='25%'><b>Menu Item</b></td>
												<td width='5%'><b>Accessible</b></td></tr>
										</table>
									</div>
									<div id="menulist2" style="height:260px; max-width:700px; overflow:auto; border-top:3px solid #336699;border-left:3px solid #336699;border-bottom:3px solid #336699;border-right:3px solid #336699;background-color:#ddd;"></div>
								</td>
							</tr>
						</table>
					</div>
                </td>
            </tr>
        </table>
    </body>
</html>
<script>
	doUsersMenu();
</script>