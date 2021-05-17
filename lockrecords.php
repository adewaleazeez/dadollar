<!-- 
    Document   : login
    Created on : 28-Feb-2011
    Author     : Adewale Azeez
-->

<!--@page contentType="text/html" pageEncoding="UTF-8"-->
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">

<html>
    <head>
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
        <script type='text/javascript' src='js/toword.js'></script>
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

                $("#lockrecords").dialog({
                    autoOpen: true,
                    position:'center',
                    title: 'Lock Records',
                    height: 600,
                    width: 1300,
                    modal: false,
                    buttons: {
                        Close: function() {
                            $('#lockrecords').dialog('close');
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
                    <div style="font-size:11px" id="lockrecords">
						<div id="searchdiv">
							<b>Line&nbsp;No:&nbsp;</b><input type="text" id="lineno" size="10" onclick="this.value=''; clearLists('recordlist');" style="display:inline; " />&nbsp;&nbsp;&nbsp;&nbsp;
							<b>User&nbsp;Name:&nbsp;</b><input type="text" id="username" style="display:inline" size="15" onkeyup="getRecordlist(this.id, 'users', 'recordlist'); " onclick="this.value=''; getRecordlist(this.id, 'users', 'recordlist');" />&nbsp;&nbsp;&nbsp;&nbsp;
							<b>Trans.&nbsp;Date:&nbsp;</b><input type='text' id='transdate' name='transdate' size='11' onclick="this.value=''; getDate('transdate')" style="display:inline; " />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<!--b>From:&nbsp;</b><input type='text' id='fromdate' name='fromdate' size='11' onclick="this.value=''; getDate('fromdate')" style="display:inline; " />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
							<b>To:&nbsp;</b><input type='text' id='todate' name='todate' size='11' onclick="this.value=''; getDate('todate')" style="display:inline; " /&nbsp;&nbsp;&nbsp;&nbsp;-->
							<b>Search:&nbsp;</b>
							<input type="text" style="display:inline" id="search" name="search" size="30" />
							<input type="button" style="display:inline" value=" Search " onclick="searchCustomer('search','lockrecords')" />&nbsp;&nbsp;&nbsp;&nbsp;
							<input type="button" style="display:inline" value=" List All " onclick="document.getElementById('search').value='';   searchCustomer('search','lockrecords');" /><BR><BR>
						</div>
						<div id="listrecords"></div>
						<div id="recordlist"></div>
					</div>
                </td>
            </tr>
        </table>
    </body>
</html>
<script type="text/javascript">
	createCookie('transtypes', 'lockrecords', false);
	
	function DaysInMonth(iMonth, iYear) { 
		return 32 - new Date(iYear, iMonth, 32).getDate();
	}
	var d = new Date();

	var day = d.getDate()+"";
	day = ((day.length < 2) ? "0" : "") + day;
	var mon = (d.getMonth()+1)+"";

	mon = ((mon.length < 2) ? "0" : "") + mon;
	
	var transdate = day+"/"+mon+"/"+d.getFullYear();
	
	document.getElementById("transdate").value=transdate;
	

	//var d = new Date();
	//var mon = (d.getMonth()+1)+"";
	//mon = ((mon.length < 2) ? "0" : "") + mon;
	//var lastdate = DaysInMonth(d.getMonth(),d.getFullYear());

	//var fromdate = "01/"+mon+"/"+d.getFullYear();
	//var todate = lastdate+"/"+mon+"/"+d.getFullYear();
	//var transdate = lastdate+"/"+mon+"/"+d.getFullYear();

	//document.getElementById("fromdate").value=fromdate;
	//document.getElementById("todate").value=todate;
	//document.getElementById("transdate").value=todate;

	//createCookie('date1', d.getFullYear()+"-"+mon+"-01", false);
	//createCookie('date2', d.getFullYear()+"-"+mon+"-"+lastdate, false);
	createCookie('transdate', d.getFullYear()+"-"+mon+"-"+lastdate, false);

	createCookie('searchstr', '', false);

	//getRecords("lockrecords");

	showTransForm();
	function showTransForm(){
		var currentuser = readCookie('currentuser');
		//document.getElementById('username').value = currentuser;
		if(currentuser=="Admin"){
			document.getElementById('username').disabled = false;
		}
	}
	setTimeout(function () { document.getElementById("lineno").focus(); }, 1000);
</script>
