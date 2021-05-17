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
        <!--[if lte IE 7]>
			<style type="text/css"> body { font-size: 85%; } </style>
		<![endif]-->

        <script type="text/javascript" src="js/jquery-latest.js"></script>
        <script type="text/javascript" src="js/jquery-ui-latest.js"></script>
        <script type="text/javascript" src="js/jquery.layout-latest.js"></script>
        <script type="text/javascript" src="js/utilities.js"></script>
        <script type="text/javascript" src="js/setup.js"></script>
        <script type="text/javascript" src="js/calendar.js"></script>
        <script type="text/javascript' src='js/toword.js"></script>

        <link href="css/mycss.css" rel="stylesheet" type="text/css"/>
        <link href="css/emsportal.css" rel="stylesheet" type="text/css"/>
        <!--[if IE]> <style type="text/css">@import "css/IE-override.css";</style> <![endif]-->
		<!-- DEMO styles - specific to this page -->
		<link rel="stylesheet" type="text/css" href="css/complex.css" />
        <link type="text/css" href="css/ui-lightness/jquery-ui-1.8.4.custom.css" rel="stylesheet" />
        <link href="css/calendar.css" rel="stylesheet" type="text/css"/>
		<!--[if lte IE 7]>
			<style type="text/css"> body { font-size: 85%; } </style>
		<![endif]-->



		<link href="css/mycss.css" rel="stylesheet" type="text/css"/>
		<link href="css/westmart.css" rel="stylesheet" type="text/css"/>

        <script type="text/javascript">
            checkLogin();
        </script>
        <script type="text/javascript">
            $(function() {
                // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!
                $(document).ready(function(){
                    $("#infolist").accordion({
                        autoHeight: false
                    });
                });

                $("#dialog").dialog("destroy");

                $("#transactioninfo").dialog({
                    autoOpen: true,
                    position:'center',
                    title: 'Withdrawal Transactions Details - Items with RED colour labels are mandatory',
                    height: 600,
                    width: 1300,
                    modal: false,
                    buttons: {
                        Save: function() {
                            updateTransaction("addRecord", "transactions","","contribution");
                        },
                        Update: function() {
                            updateTransaction("updateRecord", "transactions","","contribution");
                        },
                        New: function() {
							//document.getElementById('listwithdrawals').innerHTML = "";
                            resetForm("withdrawals2");
                        },
                        Close: function() {
                            $('#transactioninfo').dialog('close');
							window.location="home.php?pgid=0";
                        }
                    }
                });

                $("#showPrompt").dialog({
                    autoOpen: false,
                    position:'center',
                    title: 'Alert!!!',
                    height: 300,
                    width: 600,
                    modal: true,
                    buttons: {
                        Ok: function() {
                            $('#showPrompt').dialog('close');
							$('#showAlert').dialog('close');
							$('#showError').dialog('close');
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
                    width: 500,
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

			function loadImage(imageID){
				document.getElementById('f1_uploaded_file').innerHTML = "<img src='photo/"+imageID+"'  border='1' width='150' height='150' title='Picture' alt='Applicant`s Passport'/>";
			}

			function checkKeyPressed2(e, id){ //e is event object passed from function invocation
				clearLists('recordlist');
				var characterCode; //literal character code will be stored in this variable

				if(e && e.which){ //if which property of event object is supported (NN4)
					e = e;
					characterCode = e.which //character code is contained in NN4's which property
				} else {
					e = event;
					characterCode = e.keyCode; //character code is contained in IE's keyCode property
				}
				if(characterCode==8 || characterCode==27){ // if backspace or Esc
					e.preventDefault();
					e.stopPropagation();
					return false;
				}
				if(id=="cardno"){
					if(characterCode==13 || characterCode==40){
						populateCardno(document.getElementById("cardno"));
						document.getElementById("transdate").focus();
					}
				}else if(id=="transdate"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("deposit").focus();
					}
					if(characterCode==38){
						document.getElementById("cardno").focus();
					}
				}else if(id=="deposit"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("withdrawal").focus();
					}
					if(characterCode==38){
						document.getElementById("transdate").focus();
					}
				}else if(id=="withdrawal"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("narration").focus();
					}
					if(characterCode==38){
						document.getElementById("deposit").focus();
					}
				}else if(id=="narration"){
					if(characterCode==13 || characterCode==40){
						document.getElementById("username").focus();
					}
					if(characterCode==38){
						document.getElementById("withdrawal").focus();
					}
				}else if(id=="username"){
					if(characterCode==13){
						if(readCookie('savetype')=='add'){
							updateTransaction("addRecord", "transactions","","contribution");
						}
						if(readCookie('savetype')=='update'){
							updateTransaction("updateRecord", "transactions","","contribution");
						}
					}
					if(characterCode==38){
						document.getElementById("narration").focus();
					}
				}
			}
        </script>
    </head>
    <body>
		<div id="showError"></div>
		<div id="showAlert"></div>
		<div id="showPrompt"></div>
		<div id="showRecord"></div>
        <table width="100%">
            <tr>
                <td><!--serialno, cardno, transdate, narration, credit, debit, balance, username-->
					<div id="transactioninfo">
						<div style='border:5px solid #ddd;color:#000000;'>
							<table style='font-size:14px;'>
								<tr>
									<td align='right' style="color: red"><b>Card No:&nbsp;</b></td>
									<td>
										<!--onkeyup="getRecordlist(this.id, 'customers', 'recordlist');" loadImage('silhouette.jpg'); getRecordlist(this.id,  'customers','recordlist');  checkDescription();-->
										<input type="text" id="cardno" style="display:inline" onclick="this.value=''; document.getElementById('names').value=''; " size="20" onblur="this.value=capitalize(this.value); " onkeydown="checkKeyPressed2(event,this.id);" />
										<input style="display:inline" type='text' id='names' disabled="true" size='30' />&nbsp;&nbsp;
									</td>
									<td rowspan="6">
										<div id="f1_uploaded_file"><img src="photo/silhouette.jpg" border="1" width="50" height="50" title="Picture" alt="Customer's Passport"/><br/></div>
									</td>
								</tr>
								<tr>
									<td align='right'><b style=" color: red">Date:&nbsp;</b></td>
									<td>
										<input type='text' id='transdate' name='transdate' size='11' onclick="this.value=''; clearLists('recordlist'); getDate('transdate')" style="display:inline; " onkeydown="checkKeyPressed2(event,this.id);" />
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td align='right' style="color: red"><b>Deposit:&nbsp;</b></td>
									<td>
										<input type="text" id="deposit" size="15" style="display:inline; text-align:right;" onblur="this.value=numberFormat(this.value);" onfocus="this.selected();" onkeydown="checkKeyPressed2(event,this.id);" />
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td align='right' style="color: red"><b>Withdrawal:&nbsp;</b></td>
									<td>
										<input type="text" id="withdrawal" size="15" style="display:inline; text-align:right;" onblur="this.value=numberFormat(this.value);" onfocus="this.selected();" onkeydown="checkKeyPressed2(event,this.id);" />
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td align='right' style=" color: red"><b>Narations:&nbsp;</b></td>
									<td>
										<input style="display:inline" type='text' id='narration' size='50' onkeydown="checkKeyPressed2(event,this.id);" />
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td align='right' style=" color: red"><b>User Name:&nbsp;</b></td>
									<td>
										<input style="display:inline" type='text' id='username' size='30' onkeydown="checkKeyPressed2(event,this.id);" />
									</td>
									<td>&nbsp;</td>
								</tr>
							</table>
						</div><BR>
						<div id='recordlist' style="overflow:auto;"></div>
						<INPUT TYPE="hidden" id="transtype">
						<INPUT TYPE="hidden" id="commission">
						<INPUT TYPE="hidden" id="description">
						<input type="hidden" id="lockwitdrawal">
						<div style='border:5px solid #ddd;color:#000000;'>
							<div id="searchdiv">
								<b>From:&nbsp;</b><input type='text' id='fromdate' name='fromdate' size='11' onclick="this.value=''; clearLists('recordlist'); getDate('fromdate')" style="display:inline; " />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<b>To:&nbsp;</b><input type='text' id='todate' name='todate' size='11' onclick="this.value=''; clearLists('recordlist'); getDate('todate')" style="display:inline; " />
							</div>
							<div id="listwithdrawals" style="overflow:auto; height: 250px"></div>
						</div>
						<div id="mypic"></div>
						</div>
					</div>
                </td>
            </tr>
        </table>
    </body>
</html>
<script>
	function checkDescription(){
		if(document.getElementById("transtype").value=='commission'){
			document.getElementById("commission").value=document.getElementById("amount").value;
			document.getElementById("description").value=document.getElementById("narration").value;
			getDescription();
		}else{
			getNarration();
		}
	}

	//createCookie('transtypes', 'withdrawals', false);
	loadImage("silhouette.jpg");
	
	function DaysInMonth(iMonth, iYear) { 
		return 32 - new Date(iYear, iMonth, 32).getDate();
	}


	var d = new Date();
	var mon = (d.getMonth()+1)+"";
	mon = ((mon.length < 2) ? "0" : "") + mon;
	var lastdate = DaysInMonth(d.getMonth(),d.getFullYear());

	var fromdate = "01/"+mon+"/"+d.getFullYear();
	var todate = lastdate+"/"+mon+"/"+d.getFullYear();
	document.getElementById("fromdate").value=fromdate;
	document.getElementById("todate").value=todate;

	var day = d.getDate()+"";
	var today = ((day.trim().length < 2) ? "0" : "") + d.getDate()+"/"+mon+"/"+d.getFullYear();
	document.getElementById("transdate").value=today;

	createCookie('date1', d.getFullYear()+"-"+mon+"-01", false);
	createCookie('date2', d.getFullYear()+"-"+mon+"-"+lastdate, false);
	
	//getRecords("withdrawals");
	setTimeout(function(){document.getElementById("cardno").focus();}, 1000);
</script>
