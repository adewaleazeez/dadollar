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
        <script type='text/javascript' src='js/setup.js'></script>
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

                $("#balances").dialog({
                    autoOpen: true,
                    position:[280,70],
                    title: 'Loans Transaction Listing Report',
                    height: 420,
                    width: 910,
                    modal: false,
                    buttons: {
                        Print: function() {
							var cardno1 = document.getElementById('cardno1').value;
							var cardno2 = document.getElementById('cardno2').value;
							
							var startdate = document.getElementById('startdate').value;
							var startdate2 = startdate;
							startdate = startdate.substr(6,4)+'-'+startdate.substr(3,2)+'-'+startdate.substr(0,2);
			
							var enddate = document.getElementById('enddate').value;
							var enddate2 = enddate;
							enddate = enddate.substr(6,4)+'-'+enddate.substr(3,2)+'-'+enddate.substr(0,2);

							var oWin = window.open("loansreport.php?startdate="+startdate+"&startdate2="+startdate2+"&enddate="+enddate+"&enddate2="+enddate2+"&cardno1="+cardno1+"&cardno2="+cardno2, "_blank", "directories=0,scrollbars=1,resizable=1,location=0,status=0,toolbar=0,menubar=0,width=800,height=500,left=100,top=100");
							if (oWin==null || typeof(oWin)=="undefined"){
								alert("Popup must be enabled on this browser to see the report");
							}
                        },
						Close: function() {
                            $('#balances').dialog('close');
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

                $("#showRecord").dialog({
                    autoOpen: false,
                    position:[1100,70],
                    title: 'Alert!!!',
                    height: 500,
                    width: 400,
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $('#showPrompt').dialog('close');
							$('#showRecord').dialog('close');
							$('#showAlert').dialog('close');
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
            <div id="showRecord"></div>
            <tr>
                <td>
					<div id="balances">
						<table width='100%' style='font-size:14px;'>
							<tr>
								<td align='right'><b>First&nbsp;customer&nbsp;Card&nbsp;No:&nbsp;</b></td>
								<td>
									<input type="text" id="cardno1" style="display:inline" size="20" onkeyup="getRecordlist(this.id, 'customers', 'recordlist');" onclick="this.value=''; document.getElementById('names1').value=''; getRecordlist(this.id,  'customers','recordlist');" />
									<input style="display:inline" type='text' id='names1' disabled="true" size='45' />&nbsp;&nbsp;
								</td>
							</tr>
							<tr>
								<td align='right'><b>Lastst&nbsp;customer&nbsp;Card&nbsp;No:&nbsp;</b></td>
								<td>
									<input type="text" id="cardno2" style="display:inline" size="20" onkeyup="getRecordlist(this.id, 'customers', 'recordlist');" onclick="this.value=''; document.getElementById('names2').value=''; getRecordlist(this.id,  'customers','recordlist');" />
									<input style="display:inline" type='text' id='names2' disabled="true" size='45' />&nbsp;&nbsp;
								</td>
							</tr>
							<tr>
								<td align='right'><b>Start&nbsp;Date:&nbsp;</b></td>
								<td>
									<input type='text' id='startdate' name='startdate' size='11' onclick="this.value='';  getDate('startdate')" />
								</td>
							</tr>
							<tr>
								<td align='right'><b>End&nbsp;Date:&nbsp;</b></td>
								<td>
									<input type='text' id='enddate' name='enddate' size='11' onclick="this.value='';  getDate('enddate')" />
								</td>
							</tr>
						</table>
						<div id='recordlist'></div>
					</div>
                </td>
            </tr>
        </table>
    </body>
</html>
