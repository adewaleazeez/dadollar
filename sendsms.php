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
            $(function () {
                // a workaround for a flaw in the demo system (http://dev.jqueryui.com/ticket/4375), ignore!

                $("#dialog").dialog("destroy");

                $("#balances").dialog({
                    autoOpen: true,
                    position: [280, 70],
                    title: 'Send Bulk SMS - Transaction',
                    height: 620,
                    width: 1350,
                    modal: false,
                    buttons: {
                        Send_SMS: function () {
							selectedIdsArray = [];
							processedIds = 0;
							firstdId = 0;
							sendSMS();
                        },
                        Close: function () {
                            $('#balances').dialog('close');
                            window.location = "home.php?pgid=0";
                        }
                    }
                });

                $("#transactioninfo").dialog({
                    autoOpen: false,
                    position: 'center',
                    title: 'Customer Statements - Items with RED colour labels are mandatory',
                    height: 600,
                    width: 1000,
                    modal: true,
                    buttons: {
                        Delete: function () {
                            updateTransaction("deleteRecord", "transactions", "", "contribution");
                        },
                        Update: function () {
                            updateTransaction("updateRecord", "transactions", "", "contribution");
                        },
                        New: function () {
                            document.getElementById('listwithdrawals').innerHTML = "";
                            resetForm("statements");
                        },
                        Close: function () {
                            $('#transactioninfo').dialog('close');
                            $('#balances').dialog('open');
                        }
                    }
                });

                $("#showPrompt").dialog({
                    autoOpen: false,
                    position: 'center',
                    title: 'Alert!!!',
                    height: 300,
                    width: 300,
                    modal: true,
                    buttons: {
                        Ok: function () {
                            $('#showPrompt').dialog('close');
                        }
                    }
                });

                $("#showAlert").dialog({
                    autoOpen: false,
                    position: 'center',
                    title: 'Alert!!!',
                    height: 280,
                    width: 350,
                    modal: true
                });

                $("#showError").dialog({
                    autoOpen: false,
                    position: 'center',
                    title: 'Error Message',
                    height: 300,
                    width: 300,
                    modal: true,
                    buttons: {
                        Ok: function () {
                            $('#showError').dialog('close');
                        }
                    }
                });

                $("#showRecord").dialog({
                    autoOpen: false,
                    position: [1100, 70],
                    title: 'Alert!!!',
                    height: 500,
                    width: 400,
                    modal: true,
                    buttons: {
                        Ok: function () {
                            $('#showPrompt').dialog('close');
                            $('#showRecord').dialog('close');
                            $('#showAlert').dialog('close');
                            $('#showError').dialog('close');
                        }
                    }
                });

            });




				/*var selectedids = "";
				selectedIdsArray = [];
				for (k = 1; k < checkcounter + 1; k++) {
					var selectid = "selectid" + k;
					var serialnoid = "serialnoid" + k;
					if (document.getElementById(selectid).checked === true &&  document.getElementById(serialnoid).value!=="") {
						selectedIdsArray.push(document.getElementById(serial_no).value);
						if (selectedids !== "") {
							selectedids += "_~_";
						}
						selectedids += document.getElementById(serialnoid).value;
					}
				}
				var lineno = document.getElementById('lineno').value;

				var transdate = document.getElementById('transdate').value;
				var transdate2 = transdate;
				transdate = transdate.substr(6, 4) + '-' + transdate.substr(3, 2) + '-' + transdate.substr(0, 2);

				var username = document.getElementById('username').value;

				var error = "";
				// if (lineno=="") error += "Line No must not be blank.<br><br>";
				if (transdate == ""){
					error += "Transaction Date must not be blank.<br><br>";
				}
				if (selectedids==""){
					error += "No Customer is selected for SMS message.<br><br>";
				}

				if (error.length > 0) {
					error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error;
					document.getElementById("showError").innerHTML = error;
					$('#showError').dialog('open');
					return true;
				}
				var url = "/dadollar/sendbulksms.php?option=smstranslist&selectedids="+selectedids+"&lineno="+lineno+"&transdate="+transdate+"&username="+username;
			//alert(url);
				document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Sending SMS to customers............";
				$('#showAlert').dialog('open');
				setTimeout(function () {
					AjaxFunctionSetupSMS(url);
				}, 3000);
				

			//                            var oWin = window.open("transreport.php?transdate=" + transdate + "&transdate2=" + transdate2 + "&username=" + username + "&lineno=" + lineno, "_blank", "directories=0,scrollbars=1,resizable=1,location=0,status=0,toolbar=0,menubar=0,width=800,height=500,left=100,top=100");
			//                            if (oWin == null || typeof (oWin) == "undefined") {
			//                                alert("Popup must be enabled on this browser to see the report");
			//                            }*/

			var selectedIdsArray = [];
            var processedIds = 0;
            var firstdId = 0;
            function sendSMS(){
				//myVar = setInterval(function(){ alert("Timeinerval"); }, 500); // showCurrentId()
				var selectedids = "";
				selectedIdsArray = [];
				if(totalrecords > 0){
					for (k = 1; k < totalrecords + 1; k++) {
						var selectid = "selectid" + k;
						var serialnoid = "serialnoid" + k;
						if (document.getElementById(selectid).checked === true &&  document.getElementById(serialnoid).value!=="") {
							//alert(document.getElementById(serialnoid).value);
							selectedIdsArray.push(document.getElementById(serialnoid).value);
							++processedIds;
							if(firstdId===0) {
								firstdId = processedIds;
							}
							//if((processedIds % 50)===0 && processedIds>0) {
							//	break;
							//}
							if (selectedids !== "") {
								selectedids += "_~_";
							}
							selectedids += document.getElementById(serialnoid).value;
							
						}
					}
				}
				var lineno = document.getElementById('lineno').value;

				var transdate = document.getElementById('transdate').value;
				var transdate2 = transdate;
				transdate = transdate.substr(6, 4) + '-' + transdate.substr(3, 2) + '-' + transdate.substr(0, 2);

				var username = document.getElementById('username').value;

				var error = "";
				// if (lineno=="") error += "Line No must not be blank.<br><br>";
				if (transdate === ""){
					error += "Transaction Date must not be blank.<br><br>";
				}
				if (selectedids==="" || totalrecords===0){
					error += "No Customer is selected for SMS message.<br><br>";
				}

				if (error.length > 0) {
					error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error;
					document.getElementById("showError").innerHTML = error;
					$('#showError').dialog('open');
					return true;
				}
				var url = "/dadollar/sendbulksms.php?option=smstranslist&selectedids="+selectedids+"&lineno="+lineno+"&transdate="+transdate+"&username="+username;
				//alert(url);
				document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Sending SMS to customers..........."+firstdId+" - "+processedIds+"/"+totalrecords;
				$('#showAlert').dialog('open');
				setTimeout(function () {
					AjaxFunctionSetupSMS(url);
				}, 3000);
			}


            createCookie('transtypes', 'withdrawals', false);
            function loadImage(imageID) {
                document.getElementById('f1_uploaded_file').innerHTML = "<img src='photo/" + imageID + "'  border='1' width='150' height='150' title='Picture' alt='Applicant`s Passport'/>";
            }

            function checkKeyPressed2(e, id) { //e is event object passed from function invocation
                clearLists('recordlist');
                var characterCode; //literal character code will be stored in this variable

                if (e && e.which) { //if which property of event object is supported (NN4)
                    e = e;
                    characterCode = e.which //character code is contained in NN4's which property
                } else {
                    e = event;
                    characterCode = e.keyCode; //character code is contained in IE's keyCode property
                }
                //if(characterCode==8 || characterCode==27){ // if backspace or Esc
                if (characterCode == 27) { // if backspace or Esc
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
                if (id == "cardno") {
                    if (characterCode == 13 || characterCode == 40) {
                        populateCardno(document.getElementById("cardno"));
                        document.getElementById("statementdate").focus();
                    }
                } else if (id == "statementdate") {
                    if (characterCode == 13 || characterCode == 40) {
                        document.getElementById("deposit").focus();
                    }
                    if (characterCode == 38) {
                        document.getElementById("cardno").focus();
                    }
                } else if (id == "deposit") {
                    if (characterCode == 13 || characterCode == 40) {
                        document.getElementById("withdrawal").focus();
                    }
                    if (characterCode == 38) {
                        document.getElementById("statementdate").focus();
                    }
                } else if (id == "withdrawal") {
                    if (characterCode == 13) {
                        //if(readCookie('savetype')=='add'){
                        //	updateTransaction("addRecord", "transactions","","contribution");
                        //}
                        //if(readCookie('savetype')=='update'){
                        updateTransaction("updateRecord", "transactions", "", "contribution");
                        //}
                    }
                    if (characterCode == 38) {
                        document.getElementById("deposit").focus();
                    }

                    /*if(characterCode==13 || characterCode==40){
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
                     }*/
                }
            }

            function checkKeyPressed(e, id) { //e is event object passed from function invocation
                clearLists('recordlist');
                var characterCode; //literal character code will be stored in this variable

                if (e && e.which) { //if which property of event object is supported (NN4)
                    e = e;
                    characterCode = e.which //character code is contained in NN4's which property
                } else {
                    e = event;
                    characterCode = e.keyCode; //character code is contained in IE's keyCode property
                }
                //if(characterCode==8 || characterCode==27){ // if backspace or Esc
                if (characterCode == 27) { // if backspace or Esc
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }
//alert(characterCode);				
//alert(id);				
                /*if(characterCode==121){
                 if(id=="transdate"){
                 document.getElementById("transdate").value="";
                 getDate('transdate');
                 }
                 }*/

                if (id == "lineno") {
                    if (characterCode == 37 || characterCode == 38) {
                        document.getElementById('lineno').focus();
                    }
                    if (characterCode == 39 || characterCode == 40 || characterCode == 13) {
                        document.getElementById('transdate').focus();
                    }
                }

                if (id == "transdate") {
                    if (characterCode == 37 || characterCode == 38) {
                        document.getElementById('lineno').focus();
                    }
                    if (characterCode == 39 || characterCode == 40 || characterCode == 13) {
                        document.getElementById('username').focus();
                    }
                }

                if (id == "username") {
                    if (characterCode == 37 || characterCode == 38) {
                        document.getElementById('transdate').focus();
                    }
                    if (characterCode == 39 || characterCode == 40 || characterCode == 13) {
                        document.getElementById('listtrans').focus();
                    }
                }

                if (id == "listtrans") {
                    if (characterCode == 37 || characterCode == 38) {
                        document.getElementById('lineno').focus();
                    }
                    if (characterCode == 39 || characterCode == 40) {
                        document.getElementById('lineno').focus();
                    }
                    if (characterCode == 13 || characterCode == 9) { // if enter ot tab
                        listSMSTransaction()
                    }
                }

                var old_id = id;
                id = id.split('id');
                var idcode = id[0] + 'id';
                if ((idcode == "saveid" || idcode == "updateid" || idcode == "clearid" || idcode == "deleteid") && characterCode == 8) { // if backspace
                    e.preventDefault();
                    e.stopPropagation();
                    return false;
                }

                var idno = parseInt(id[1]);
                if (characterCode == 38) { // if arrow up
                    if (idno > 1)
                        --idno;
                }
                if (characterCode == 40) { // if arrow down
                    if (idno < globalK)
                        ++idno;
                }
                if (characterCode == 13 || characterCode == 9) { // if enter ot tab
                    if (idcode == "cardnoid") {
                        idcode = "amountid";
                    } else if (idcode == "amountid") {
                        if (document.getElementById(old_id).value.trim().length == 0) {
                            document.getElementById(old_id).focus();
                            return true;
                        }
                        if (isNaN(document.getElementById(old_id).value.replace(/,/g, '') + "")) {
                            alert('The value you typed for Amount in not a number!!!');
                            document.getElementById(old_id).value = '';
                            document.getElementById(old_id).focus();
                            return true;
                        }
                        if (id[1].match("0000")) {
                            idcode = "saveid";
                        } else {
                            idcode = "updateid";
                        }
                    } else if (idcode == "updateid") {
                        document.getElementById(id).click();
                    } else if (idcode == "deleteid") {
                        document.getElementById(id).click();
                    } else if (idcode == "saveid") {
                        document.getElementById(id).click();
                    } else if (idcode == "clearid") {
                        document.getElementById(id).click();
                    }
                }
                id = idcode.trim() + idno;
                if (old_id.substr(0, 6) == "cardno" && characterCode == 13) {
                    if (document.getElementById(old_id).value != null && document.getElementById(old_id).value != "") {
                        /*if(!document.getElementById(old_id).value.trim().match(document.getElementById("lineno").value.trim()) && document.getElementById("lineno").value.trim()!='100'){
                         alert("Card No: "+document.getElementById(old_id).value+" does not belong to Line: "+document.getElementById("lineno").value);
                         document.getElementById(old_id).value="";
                         document.getElementById(old_id).focus();
                         return true;
                         }*/
                        populateCardno(document.getElementById(old_id));
                        var id2 = old_id.split('id');
                        if (document.getElementById("nameid" + id2[1]).value.trim().length == 0) {
                            setTimeout(function () {
                                populateCardno(document.getElementById(old_id));
                            }, 1000);
                        }
                        if (document.getElementById("nameid" + id2[1]).value.trim().length == 0) {
                            setTimeout(function () {
                                populateCardno(document.getElementById(old_id));
                            }, 1500);
                        }
                        if (document.getElementById("nameid" + id2[1]).value.trim().length == 0) {
                            setTimeout(function () {
                                if (document.getElementById("nameid" + id2[1]).value.trim().length == 0) {
                                    alert("Click ok to continue......");
                                    //alert("Card No: "+document.getElementById(old_id).value+" does not exist!!! ");
                                    //document.getElementById("amountid"+id2[1]).value="";
//									document.getElementById("balance_aid"+id2[1]).value="";
                                    //document.getElementById(old_id).value="";
                                    //document.getElementById(old_id).focus();
                                    //return true;
                                }
                            }, 2000);
                        } else {
                            clearLists('recordlist');
                        }
                    } else {
                        document.getElementById(old_id).focus();
                        clearLists('recordlist');
                        return true;
                    }
                    clearLists('recordlist');
                }
                clearLists('recordlist');
                document.getElementById(id).focus();
                /*var id2 = old_id.split('id');
                 setTimeout(function () {
                 if(document.getElementById("nameid"+id2[1]).value.trim().length==0){
                 alert("Card No: "+document.getElementById(old_id).value+" does not exist!!! ");
                 document.getElementById("amountid"+id2[1]).value="";
                 document.getElementById(old_id).value="";
                 document.getElementById(old_id).focus();
                 return true;
                 }
                 }, 1000);
                 }else{
                 document.getElementById(old_id).focus();
                 return true;
                 }
                 }
                 //setTimeout(function () { document.getElementById(id).focus(); }, 1000);
                 document.getElementById(id).focus();*/
            }
            createCookie('currentform', 'translist', false);

        </script>
    </head>
    <body>
        <div id="showError"></div>
        <div id="showAlert"></div>
        <div id="showPrompt"></div>
        <div id="showRecord"></div>
        <div id="balances">
            <div style='border:5px solid #ddd;color:#000000;'>
                <table style='font-size:14px;'>
                    <tr>
                        <td align='right' style="color: red"><b>Line&nbsp;No:&nbsp;</b></td>
                        <td>
                            <input type="text" id="lineno" size="10" onclick="this.value = '';
                                    clearLists('recordlist');" onkeydown="checkKeyPressed(event, this.id);" />
                        </td>
                        <td align='right' style="color: red"><b>Transaction&nbsp;Date:&nbsp;</b></td>
                        <td>
                            <input type='text' id='transdate' name='transdate' size='11' onclick="this.value = '';
                                    clearLists('recordlist');
                                    getDate('transdate')" style="display:inline; " onkeydown="checkKeyPressed(event, this.id);" />
                        </td>
                        <td align='right' style="color: red"><b>User&nbsp;Name:&nbsp;</b></td>
                        <td>
                            <input type="text" id="username" style="display:inline" size="20" onkeyup="getRecordlist(this.id, 'users', 'recordlist');
                                   " onclick="this.value = '';
                                           getRecordlist(this.id, 'users', 'recordlist');" onkeydown="checkKeyPressed(event, this.id);" />
                            &nbsp;&nbsp;
                            <input type="button" style="display:inline" value=" List Transactions " id="listtrans" onclick="listSMSTransaction();" onkeydown="checkKeyPressed(event, this.id);" />
                        </td>
                    </tr>
                    <tr>
                        <td colspan="6">
                            <div id="translist" style="width:1300px; height:460px; overflow:auto;"></div>
                        </td>
                    </tr>
                </table>
                <div id='recordlist'></div>
            </div>
        </div>
    </body>
</html>
<script>
    var d = new Date();

    var day = d.getDate() + "";
    day = ((day.length < 2) ? "0" : "") + day;
    var mon = (d.getMonth() + 1) + "";

    mon = ((mon.length < 2) ? "0" : "") + mon;

    var today = day + "/" + mon + "/" + d.getFullYear();

    document.getElementById("transdate").value = today;

    //var currentuser = readCookie('currentuser');
    //document.getElementById('username').value = currentuser;

    setTimeout(function () {
        document.getElementById("lineno").focus();
    }, 1000);






    function DaysInMonth(iMonth, iYear) {
        return 32 - new Date(iYear, iMonth, 32).getDate();
    }


    var d = new Date();
    var mon = (d.getMonth() + 1) + "";
    mon = ((mon.length < 2) ? "0" : "") + mon;
    var lastdate = DaysInMonth(d.getMonth(), d.getFullYear());

    var fromdate = "01/" + mon + "/" + d.getFullYear();
    var todate = lastdate + "/" + mon + "/" + d.getFullYear();
    document.getElementById("fromdate").value = fromdate;
    document.getElementById("todate").value = todate;

    //var day = d.getDate()+"";
    //var today = ((day.trim().length < 2) ? "0" : "") + d.getDate()+"/"+mon+"/"+d.getFullYear();
    //document.getElementById("statementdate").value=today;

    createCookie('date1', d.getFullYear() + "-" + mon + "-01", false);
    createCookie('date2', d.getFullYear() + "-" + mon + "-" + lastdate, false);

    //getRecords("withdrawals");
    function showStatements() {
        $('#transactioninfo').dialog('open');
        loadImage('silhouette.jpg');
        document.getElementById('cardno').focus();
        //setTimeout(function(){document.getElementById('cardno').focus();}, 500);
    }
</script>
