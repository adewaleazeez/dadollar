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
                $(document).ready(function(){
                    $("#infolist").accordion({
                        autoHeight: false
                    });
                });

                $("#dialog").dialog("destroy");

                $("#transactioninfo").dialog({
                    autoOpen: true,
                    position:'center',
                    title: 'Loans Transactions Details - Items with RED colour labels are mandatory',
                    height: 600,
                    width: 1300,
                    modal: false,
                    buttons: {
                        Save: function() {
                            updateTransaction("addRecord", "transactions","loandeposit","loan");
                        },
                        Update: function() {
                            updateTransaction("updateRecord", "transactions","loandeposit","loan");
                        },
                        New: function() {
                            resetForm("loandeposits");
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

			function checkKeyPressed(e, id){ //e is event object passed from function invocation
				var characterCode; //literal character code will be stored in this variable

				if(e && e.which){ //if which property of event object is supported (NN4)
					e = e;
					characterCode = e.which //character code is contained in NN4's which property
				} else {
					e = event;
					characterCode = e.keyCode; //character code is contained in IE's keyCode property
				}

				if(id=="cardno"){
					populateCode(document.getElementById("cardno").value);
					if(characterCode==13 || characterCode==39 || characterCode==40){
						document.getElementById("transdate").focus();
					}
					if(characterCode==37 || characterCode==38){
					//	document.getElementById("cardno").focus();
					}
				}else if(id=="transdate"){
					if(characterCode==13 || characterCode==39 || characterCode==40){
						document.getElementById("amount").focus();
					}
					if(characterCode==37 || characterCode==38){
						document.getElementById("cardno").focus();
					}
					/*if(characterCode==121){
						document.getElementById("transdate").value="";
						getDate('transdate');
					}*/
				}else if(id=="amount"){
					if(characterCode==13 || characterCode==39 || characterCode==40){
						document.getElementById("narration").focus();
					}
					if(characterCode==37 || characterCode==38){
						document.getElementById("transdate").focus();
					}
				}else if(id=="narration"){
					if(characterCode==13){
						if(readCookie('savetype')=='add'){
							updateTransaction("addRecord", "transactions","withdrawal","loan");
						}
						if(readCookie('savetype')=='update'){
							updateTransaction("updateRecord", "transactions","withdrawal","loan");
						}
					}
					if(characterCode==39 || characterCode==40){
						//document.getElementById("othernames").focus();
						//updateCustomer("addRecord", "customers");
					}
					if(characterCode==37 || characterCode==38){
						document.getElementById("amount").focus();
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
										<input type="text" id="cardno" style="display:inline" size="20" onkeyup="getRecordlist(this.id, 'customers', 'recordlist');" onclick="this.value=''; document.getElementById('names').value=''; loadImage('silhouette.jpg'); getRecordlist(this.id,  'customers','recordlist');" onblur="this.value=capitalize(this.value); " onkeydown="checkKeyPressed(event,this.id);" />
										<input style="display:inline" type='text' id='names' disabled="true" size='45' />&nbsp;&nbsp;
									</td>
									<td rowspan="9">
										<div id="f1_uploaded_file"><img src="photo/silhouette.jpg" border="1" width="100" height="80" title="Picture" alt="Applicant's Passport"/><br/></div>
									</td>
								</tr>
								<tr>
									<td align='right'><b style=" color: red">Loan&nbsp;Deposit&nbsp;Date:&nbsp;</b></td>
									<td>
										<input type='text' id='transdate' name='transdate' size='11' onclick="this.value=''; clearLists('recordlist'); getDate('transdate')" style="display:inline; " onkeydown="checkKeyPressed(event,this.id);" />
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td align='right' style="color: red"><b>Loan&nbsp;Deposit&nbsp;Amount:&nbsp;</b></td>
									<td>
										<input type="text" id="amount" size="15" style="display:inline; text-align:right;" onblur="this.value=numberFormat(this.value); getNarration();" onfocus="this.selected();" onkeydown="checkKeyPressed(event,this.id);" />
									</td>
									<td>&nbsp;</td>
								</tr>
								<tr>
									<td align='right' style=" color: red"><b>Narations:&nbsp;</b><BR><BR><BR><BR><BR></td>
									<td>
										<textarea id="narration" rows="5" cols="67" onfocus="this.selected()" onblur="this.value=capAdd(this.value)" onkeydown="checkKeyPressed(event,this.id);" ></textarea>
									</td>
									<td>&nbsp;</td>
								</tr>
							</table>
						</div><BR>
						<div id='recordlist' style="overflow:auto;"></div>
						<INPUT TYPE="hidden" id="transtype">
						<INPUT TYPE="hidden" id="commission">
						<INPUT TYPE="hidden" id="description">
						<div style='border:5px solid #ddd;color:#000000;'>
							<div id="searchdiv">
								<b>From:&nbsp;</b><input type='text' id='fromdate' name='fromdate' size='11' onclick="this.value=''; clearLists('recordlist'); getDate('fromdate')" style="display:inline; " />&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
								<b>To:&nbsp;</b><input type='text' id='todate' name='todate' size='11' onclick="this.value=''; clearLists('recordlist'); getDate('todate')" style="display:inline; " />
							</div>
							<div id="listloandeposits"></div>
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
	createCookie('transtypes', 'loandeposits', false);
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
	
	getRecords("loandeposits");
</script>
