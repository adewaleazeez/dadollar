// Convert numbers to words
// copyright 25th July 2006, by Stephen Chapman http://javascript.about.com
// permission to use this Javascript on your web page is granted
// provided that all of the code (including this copyright notice) is
// used exactly as shown (you can change the numbering system if you wish)

//<script type="text/javascript" src="toword.js"></script>

//var words = toWords(num);

// American Numbering System
var th = ['', 'thousand', 'million', 'billion', 'trillion'];
// uncomment this line for English Number System
// var th = ['','thousand','million', 'milliard','billion'];

var dg = ['zero', 'one', 'two', 'three', 'four', 'five', 'six', 'seven', 'eight', 'nine'];
var tn = ['ten', 'eleven', 'twelve', 'thirteen', 'fourteen', 'fifteen', 'sixteen', 'seventeen', 'eighteen', 'nineteen'];
var tw = ['twenty', 'thirty', 'forty', 'fifty', 'sixty', 'seventy', 'eighty', 'ninety'];
function toWords(s) {
    s = s.toString();
    s = s.replace(/[\, ]/g, '');
    if (s !== String(parseFloat(s)))
        return 'not a number';
    var x = s.indexOf('.');
    if (x === -1)
        x = s.length;
    if (x > 15)
        return 'too big';
    var n = s.split('');
    var str = '';
    var sk = 0;
    for (var i = 0; i < x; i++) {
        if ((x - i) % 3 === 2) {
            if (n[i] === '1') {
                str += tn[Number(n[i + 1])] + ' ';
                i++;
                sk = 1;
            } else if (n[i] !== 0) {
                str += tw[n[i] - 2] + ' ';
                sk = 1;
            }
        } else if (n[i] !== 0) {
            str += dg[n[i]] + ' ';
            if ((x - i) % 3 === 0)
                str += 'hundred ';
            sk = 1;
        }
        if ((x - i) % 3 === 1) {
            if (sk)
                str += th[(x - i - 1) / 3] + ' ';
            sk = 0;
        }
    }

    if (x !== s.length) {
        var y = s.length;
        str += 'point ';
        for (i = x + 1; i < y; i++)
            str += dg[n[i]] + ' ';
    }

    return str.replace(/\s+/g, ' ');

}

var curr_obj = null;
var temp_table = null;
var list_obj = null;
var myCheckboxes = 0;
var duplicatecounter = 0;

function logoutUser() {
    var url = "/dadollar/userbackend.php?option=logoutUser";
    AjaxFunctionSetup(url);
}

function checkAccess(access, menuoption) {
    createCookie("access", access, false);
    //if(access==='changepassword.php'){
    //	window.location="home.php?pgid=1";
    //}else{
    var arg = "&currentuser=" + readCookie("currentuser") + "&menuoption=" + menuoption;
    var url = "/dadollar/setupbackend.php?option=checkAccess" + arg;
    AjaxFunctionSetup(url);
    //}
}

function populateTransaction() {
	var lineno = document.getElementById("lineno").value.replace(/&/g, '$');
    var transdate = document.getElementById("transdate").value.replace(/&/g, '$');
    transdate = transdate.substr(6, 4) + '-' + transdate.substr(3, 2) + '-' + transdate.substr(0, 2);
    var username = document.getElementById("username").value.replace(/&/g, '$');
    
    var error = "";
    if (lineno === "")
        error += "Line No must not be blank.<br><br>";
    if (transdate === "")
        error += "Transaction Date must not be blank.<br><br>";
    if (username === "")
        error += "Username must not be blank.<br><br>";

    if (error.length > 0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error;
        document.getElementById("showError").innerHTML = error;
        $('#showError').dialog('open');
        return true;
    }
    var a_param1 = "&table=transactionlist&access=" + lineno + "&serialno=" + transdate + "&currentuser=" + username;
    var url = "/dadollar/setupbackend.php?option=getAllRecs" + a_param1;
    AjaxFunctionSetup(url);
}

var globalK = 0;
function populateTransactions(arg) {
//document.getElementById("showError").innerHTML = arg;
//$('#showError').dialog('open');
    var row_split = arg.split('getAllRecs');
    var str = "<table style='border-color:#fff;border-style:solid;border-width:1px; height:10px;width:100%;background-color:#336699;margin-top:5px;'>";
    str += "<tr style='font-weight:bold; color:#000000'>";
    str += "<td>S/No</td><td>Card&nbsp;No</td><td>Customers'&nbsp;Names</td><td align='right'>Balance&nbsp;B/F</td><td align='right'>Amount</td><td align='right'>New&nbsp;Balance</td><td>Passports</td><td>Lock&nbsp;status</td></tr>";
    var flag = 0;
    var serialnoid = "";
    var serialnovalue = "";
    var linenoid = "";
    var linenovalue = "";
    var cardnoid = "";
    var cardnovalue = "";
    var nameid = "";
    var namevalue = "";
    var transdateid = "";
    var transdatevalue = "";
    var balance_bid = "";
    var balance_bvalue = "";
    var amountid = "";
    var amountvalue = "";
    var totalamount = 0;
    var balance_aid = "";
    var balance_avalue = "";
    var transtypeid = "";
    var transtypevalue = "";
    var transgroupid = "";
    var transgroupvalue = "";
    var passportpictureid = "";
    var passportpicturevalue = "";
    var usernameid = "";
    var usernamevalue = "";
    var lockid = "";
    var lockvalue = "";
    var postid = "";
    var postvalue = "";
    var col_split = "";
    var count = 0;
    var updateid = "";
    var deleteid = "";
    var saveid = "";
    var clearid = "";
    var k = 1;
    var table = 'customers';
    var list = 'recordlist';
    for (k = 1; k < row_split.length - 1; k++) {
        col_split = row_split[k].split('_~_');
        if (flag === 0) {
            str += "<tr style='font-weight:bold; color:#999999;background-color:#FFFFFF;'>";
            flag = 1;
        } else {
            str += "<tr style='font-weight:bold; color:#FFFFFF;background-color:#999999;'>";
            flag = 0;
        }

        serialnoid = "serialnoid" + k;
        serialnovalue = col_split[0];
        linenoid = "linenoid" + k;
        linenovalue = col_split[1];
        cardnoid = "cardnoid" + k;
        cardnovalue = col_split[2];
        nameid = "nameid" + k;
        namevalue = col_split[3];
        transdateid = "transdateid" + k;
        transdatevalue = col_split[4];
        balance_bid = "balance_bid" + k;
        balance_bvalue = col_split[5];
        amountid = "amountid" + k;
        amountvalue = col_split[6];
        totalamount += parseFloat(amountvalue);
        balance_aid = "balance_aid" + k;
        balance_avalue = col_split[7];
        transtypeid = "transtypeid" + k;
        transtypevalue = col_split[8];
        transgroupid = "transgroupid" + k;
        transgroupvalue = col_split[9];
        passportpictureid = "passportpictureid" + k;
        passportpicturevalue = col_split[10];
        usernameid = "usernameid" + k;
        usernamevalue = col_split[11];
        lockid = "lockid" + k;
        lockvalue = col_split[12];
        postid = "postid" + k;
        postvalue = col_split[13];
        updateid = "updateid" + k;
        deleteid = "deleteid" + k;

        /*serialnovalue=col_split[0];
         linenovalue=col_split[1];
         cardnovalue=col_split[2];
         namevalue=col_split[3];
         transdatevalue=col_split[4];
         balance_bvalue=numberFormat(col_split[5]);
         amountvalue=numberFormat(col_split[6]);
         balance_avalue=numberFormat(col_split[7]);
         transtypevalue=col_split[8];
         transgroupvalue=col_split[9];
         passportpicturevalue=col_split[10];
         usernamevalue=col_split[11];
         lockvalue=col_split[12];
         postvalue=col_split[13];*/

        str += "<td align='right'>" + (++count) + ".</td>";
        str += "<input type='hidden' value='" + serialnovalue + "' id='" + serialnoid + "' />";
        str += "<input type='hidden' value='" + linenovalue + "' id='" + linenoid + "' />";
        str += "<input type='hidden' value='" + transdatevalue + "' id='" + transdateid + "' />";
        str += "<input type='hidden' value='" + transtypevalue + "' id='" + transtypeid + "' />";
        str += "<input type='hidden' value='" + transgroupvalue + "' id='" + transgroupid + "' />";
        str += "<input type='hidden' value='" + usernamevalue + "' id='" + usernameid + "' />";
        str += "<input type='hidden' value='" + postvalue + "' id='" + postid + "' />";
        str += "<td><input  style='display:inline' type='text' readonly disabled value='" + cardnovalue + "' id='" + cardnoid + "'  onkeyup=getRecordlist('" + cardnoid + "','" + table + "','" + list + "'); onclick=\"this.value='';document.getElementById('" + nameid + "').value='';\" onblur='this.value=capitalize(this.value); clearLists('recordlist');' onkeydown='checkKeyPressed(event,this.id);' size='15' />";
        str += "<td><input type='text' readonly disabled='true' value='" + namevalue + "' id='" + nameid + "' onblur='this.value=capitalize(this.value)' size='20' /></td>";
        str += "<td><input type='text' style='text-align:right;' readonly disabled='true' value='" + numberFormat(balance_bvalue) + "' id='" + balance_bid + "' onblur='this.value=numberFormat(this.value)' size='10' /></td>";
        str += "<td><input type='text' style='text-align:right;' value='" + numberFormat(amountvalue) + "' id='" + amountid + "' onblur='this.value=numberFormat(this.value); getNewBal(this.id);' onkeydown='checkKeyPressed(event,this.id);' size='10' /></td>";
        str += "<td><input type='text' style='text-align:right;' readonly disabled='true' value='" + numberFormat(balance_avalue) + "' id='" + balance_aid + "' onblur='this.value=numberFormat(this.value)' size='10' /></td>";
        str += "<td><div id='" + passportpictureid + "' style='cursor: pointer;' onmouseout=clearPic() onmouseover=showPic('" + passportpictureid + "','" + passportpicturevalue + "','down')>" + passportpicturevalue + "</div></td>";
        if (lockvalue === null || lockvalue === "") {
            str += "<td align='center'><input type='checkbox' value='" + lockvalue + "' id='" + lockid + "' readonly disabled='true' >&nbsp;Open</td>";
        } else {
            str += "<td align='center'><input type='checkbox' value='" + lockvalue + "' checked id='" + lockid + "' readonly disabled='true' >&nbsp;Locked</td>";
        }
        str += "<td><a id='" + updateid + "' onfocus=document.getElementById('" + updateid + "').style.backgroundColor='lightblue' onblur=document.getElementById('" + updateid + "').style.backgroundColor='transparent' onkeydown='checkKeyPressed(event,this.id);' href=javascript:updateTrans('" + serialnoid + "','" + linenoid + "','" + cardnoid + "','" + nameid + "','" + transdateid + "','" + balance_bid + "','" + amountid + "','" + balance_aid + "','" + transtypeid + "','" + transgroupid + "','" + usernameid + "','" + lockid + "','" + postid + "','updateRecord')>Update</a>&nbsp;&nbsp;";
        str += "<a id='" + deleteid + "' onfocus=document.getElementById('" + deleteid + "').style.backgroundColor='lightblue' onblur=document.getElementById('" + deleteid + "').style.backgroundColor='transparent' onkeydown='checkKeyPressed(event,this.id);' href=javascript:updateTrans('" + serialnoid + "','" + linenoid + "','" + cardnoid + "','" + nameid + "','" + transdateid + "','" + balance_bid + "','" + amountid + "','" + balance_aid + "','" + transtypeid + "','" + transgroupid + "','" + usernameid + "','" + lockid + "','" + postid + "','deleteRecord')>Delete</a></td></tr>";
    }

    if (flag === 0) {
        str += "<tr style='font-weight:bold; color:#999999;background-color:#FFFFFF;'>";
        flag = 1;
    } else {
        str += "<tr style='font-weight:bold; color:#FFFFFF;background-color:#999999;'>";
        flag = 0;
    }

    serialnoid = "serialnoid" + k;
    linenoid = "linenoid" + k;
    cardnoid = "cardnoid" + k;
    nameid = "nameid" + k;
    transdateid = "transdateid" + k;
    balance_bid = "balance_bid" + k;
    amountid = "amountid" + k;
    balance_aid = "balance_aid" + k;
    transtypeid = "transtypeid" + k;
    transgroupid = "transgroupid" + k;
    passportpictureid = "passportpictureid" + k;
    usernameid = "usernameid" + k;
    lockid = "lockid" + k;
    postid = "lockid" + k;
    saveid = "saveid" + k;
    clearid = "clearid" + k;

    serialnovalue = "";
    linenovalue = "";
    cardnovalue = "";
    namevalue = "";
    transdatevalue = "";
    balance_bvalue = "";
    amountvalue = "";
    balance_avalue = "";
    transtypevalue = "";
    transgroupvalue = "";
    passportpicturevalue = "";
    usernamevalue = "";
    lockvalue = "";
    postvalue = "";

    str += "<td align='right'>" + (++count) + ".</td>";
    str += "<input type='hidden' value='" + serialnovalue + "' id='" + serialnoid + "' />";
    str += "<input type='hidden' value='" + linenovalue + "' id='" + linenoid + "' />";
    str += "<input type='hidden' value='" + transdatevalue + "' id='" + transdateid + "' />";
    str += "<input type='hidden' value='" + transtypevalue + "' id='" + transtypeid + "' />";
    str += "<input type='hidden' value='" + transgroupvalue + "' id='" + transgroupid + "' />";
    str += "<input type='hidden' value='" + usernamevalue + "' id='" + usernameid + "' />";
    str += "<input type='hidden' value='" + postvalue + "' id='" + postid + "' />";
    str += "<td><input  style='display:inline' type='text' value='" + cardnovalue + "' id='" + cardnoid + "' onkeyup=\"getRecordlist('" + cardnoid + "','" + table + "','" + list + "');\" onclick=\"this.value='';document.getElementById('" + nameid + "').value='';\"  onblur='this.value=capitalize(this.value); clearLists('recordlist');' onkeydown='checkKeyPressed(event,this.id);' size='15' />";
    str += "<td><input type='text' readonly disabled='true' value='" + namevalue + "' id='" + nameid + "' onblur='this.value=capitalize(this.value)' size='20' /></td>";
    str += "<td><input type='text' style='text-align:right;' readonly disabled='true' value='" + balance_bvalue + "' id='" + balance_bid + "' onblur='this.value=numberFormat(this.value)' size='10' /></td>";
    str += "<td><input type='text' style='text-align:right;' value='" + amountvalue + "' id='" + amountid + "' onblur='this.value=numberFormat(this.value); getNewBal(this.id);' onkeydown='checkKeyPressed(event,this.id);' size='10' /></td>";
    str += "<td><input type='text' style='text-align:right;' readonly disabled='true' value='" + balance_avalue + "' id='" + balance_aid + "' onblur='this.value=numberFormat(this.value)' size='10' /></td>";
    str += "<td><div id='" + passportpictureid + "' style='cursor: pointer;' onmouseout=clearPic() onmouseover=showPic('" + passportpictureid + "','" + passportpicturevalue + "','down')>" + passportpicturevalue + "</div></td>";
    if (lockvalue === null || lockvalue === "") {
        str += "<td align='center'><input type='checkbox' value='" + lockvalue + "' id='" + lockid + "' readonly disabled='true' >&nbsp;Open</td>";
    } else {
        str += "<td align='center'><input type='checkbox' value='" + lockvalue + "' checked id='" + lockid + "' readonly disabled='true' >&nbsp;Locked</td>";
    }
    str += "<td><a id='" + saveid + "' onfocus=document.getElementById('" + saveid + "').style.backgroundColor='lightblue' onblur=document.getElementById('" + saveid + "').style.backgroundColor='transparent' onkeydown='checkKeyPressed(event,this.id);' href=javascript:updateTrans('" + serialnoid + "','" + linenoid + "','" + cardnoid + "','" + nameid + "','" + transdateid + "','" + balance_bid + "','" + amountid + "','" + balance_aid + "','" + transtypeid + "','" + transgroupid + "','" + usernameid + "','" + lockid + "','" + postid + "','addRecord')>Save</a>&nbsp;&nbsp;";
    str += "<a id='" + clearid + "' onfocus=document.getElementById('" + clearid + "').style.backgroundColor='lightblue' onblur=document.getElementById('" + clearid + "').style.backgroundColor='transparent' onkeydown='checkKeyPressed(event,this.id);' href=javascript:clearTrans('" + cardnoid + "','" + nameid + "','" + balance_bid + "','" + amountid + "','" + balance_aid + "','" + passportpictureid + "')>Clear</a></td></tr>";
    str += "</table>";
    document.getElementById('translist').innerHTML = str;
    document.getElementById('totalamount').value = numberFormat(totalamount + "");
    document.getElementById(cardnoid).focus();
    globalK = k;
}

function listTransaction() {
    var lineno = document.getElementById("lineno").value.replace(/&/g, '$');
    var transdate = document.getElementById("transdate").value.replace(/&/g, '$');
    transdate = transdate.substr(6, 4) + '-' + transdate.substr(3, 2) + '-' + transdate.substr(0, 2);
    var username = document.getElementById("username").value.replace(/&/g, '$');

    var error = "";
    // if (lineno==="") error += "Line No must not be blank.<br><br>";
    if (transdate === "--")
        error += "Transaction Date must not be blank.<br><br>";
    //if (username==="") error += "Username must not be blank.<br><br>";

    if (error.length > 0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error;
        document.getElementById("showError").innerHTML = error;
        $('#showError').dialog('open');
        return true;
    }
    var a_param1 = "&table=transactionlist2&access=" + lineno + "&serialno=" + transdate + "&userName=" + username;
    var url = "/dadollar/setupbackend.php?option=getAllRecs" + a_param1;
    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Fetching your data...........";
    $('#showAlert').dialog('open');
//alert(url);	
    AjaxFunctionSetup(url);
}

function listSMSTransaction() {
    var lineno = document.getElementById("lineno").value.replace(/&/g, '$');
    var transdate = document.getElementById("transdate").value.replace(/&/g, '$');
    transdate = transdate.substr(6, 4) + '-' + transdate.substr(3, 2) + '-' + transdate.substr(0, 2);
    var username = document.getElementById("username").value.replace(/&/g, '$');

    var error = "";
    // if (lineno==="") error += "Line No must not be blank.<br><br>";
    if (transdate === "--") {
        error += "Transaction Date must not be blank.<br><br>";
    }
    //if (username==="") error += "Username must not be blank.<br><br>";

    if (error.length > 0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error;
        document.getElementById("showError").innerHTML = error;
        $('#showError').dialog('open');
        return true;
    }
    var a_param1 = "&table=smstransactionlist&access=" + lineno + "&serialno=" + transdate + "&userName=" + username;
    var url = "/dadollar/setupbackend.php?option=getAllRecs" + a_param1;
    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Fetching your data...........";
    $('#showAlert').dialog('open');
//alert(url);	
    AjaxFunctionSetup(url);
}

function listCustomers() {
    var lineno = document.getElementById("lineno").value.replace(/&/g, '$');

	if(lineno==""){
		alert("Invalid Line No\n\nYou must select a Line No");
		return true;
	}
    var a_param1 = "&table=listCustomers&access=" + lineno;
    var url = "/dadollar/setupbackend.php?option=getAllRecs" + a_param1;
    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Fetching your data...........";
    $('#showAlert').dialog('open');
//alert(url);	
    AjaxFunctionSetup(url);
}

function calculateBalances() {
    var cardno = document.getElementById("cardno").value.replace(/&/g, '$');
    var transdate = document.getElementById("fromdate").value.replace(/&/g, '$');
    transdate = transdate.substr(6, 4) + '-' + transdate.substr(3, 2) + '-' + transdate.substr(0, 2);

    var error = "";
    if (cardno === "")
        error += "Card No must not be blank.<br><br>";
    if (transdate === "--")
        error += "Transaction Date must not be blank.<br><br>";

    if (error.length > 0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error;
        document.getElementById("showError").innerHTML = error;
        $('#showError').dialog('open');
        return true;
    }
    var a_param1 = "&table=transactionlist2&access=" + cardno + "&serialno=" + transdate;
    var url = "/dadollar/setupbackend.php?option=calculateBalances" + a_param1;
	document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Recalculating balances...........";
    $('#showAlert').dialog('open');
    AjaxFunctionSetup(url);
}

function listTransactions(arg) {
//document.getElementById("showError").innerHTML = arg;
//$('#showError').dialog('open');
//alert(arg);
    if (arg === "transactionlist2getAllRecs") {
        document.getElementById('translist').innerHTML = "";
        alert("No data available for Specified date!!!");
        return true;
    }

    var row_split = arg.split('getAllRecs');
    col_split = row_split[1].split('_~_');
    lineno = col_split[3];
    transdate = col_split[6].substr(0, 10);
    username = col_split[1];
    transtype = col_split[2];
    var datecount = 1;
    var str = "<table style='border-color:#fff;border-style:solid;border-width:1px; height:10px;width:100%;background-color:#336699;margin-top:5px;'>";
    str += "<tr style='font-weight:bold; color:#FFFFFF'>";
    str += "<td>S/No</td><td>User&nbsp;Name</td><td>Transaction&nbsp;Type</td><td>Line&nbsp;No<input type='text' id='linefield" + datecount + "' onblur=checkLineValue(this.id); onclick=\"this.value=''\"; value='Change Line No' size='14' onkeyup=changeLineno(event,this.value,'" + lineno + "','" + transdate + "','" + username + "','" + transtype + "') /></td><td>Trans.&nbsp;Date<input type='text' id='datefield" + datecount + "' name='datefield" + datecount + "' onclick=\"this.value='';getDate(this.name)\" value='Change Date' size='11' onblur=checkDateValue(this.id); onkeyup=changeDate(event,this.value,'" + lineno + "','" + transdate + "','" + username + "','" + transtype + "') /></td><td>Card&nbsp;No</td><td>Customer&nbsp;Names</td><td align='right'>Balance&nbsp;B/F</td><td align='right'>Amount</td><td align='right'>New&nbsp;Balance</td><td><a href=javascript:deleteLine('" + lineno + "','" + transdate + "','" + username + "','" + transtype + "')>Delete&nbsp;Line&nbsp;" + lineno + "</a></td></tr>";
    var flag = 0;
    var serialnoid = "";
    var serialnovalue = "";
    var usernameid = "";
    var usernamevalue = "";
    var transtypeid = "";
    var transtypevalue = "";
    var linenoid = "";
    var linenovalue = "";
    var cardnoid = "";
    var cardnovalue = "";
    var nameid = "";
    var namevalue = "";
    var transdateid = "";
    var transdatevalue = "";
    var transdatevalue2 = "";
    var balance_bid = "";
    var balance_bvalue = "";
    var amountid = "";
    var amountvalue = "";
    var totalamount = 0;
    var balance_aid = "";
    var balance_avalue = "";
    var transgroupid = "";
    var transgroupvalue = "";
    var lockid = "";
    var lockvalue = "";
    var postid = "";
    var postvalue = "";
    var col_split = "";
    var count = 0;
    var editid = "";
    var k = 1;
    var table = 'customers';
    var list = 'recordlist';
    var dlineno = '';
    var duser = '';
    var dtranstype = '';
    var tdeposit = 0;
    var tcommission = 0;
    var twithdrawal = 0;
    var tttype = 0;
    for (k = 1; k < row_split.length - 1; k++) {
        col_split = row_split[k].split('_~_');
		if (dtranstype !== col_split[2] || dlineno !== col_split[3] || duser !== col_split[1]) {
            if ((dtranstype !== '' && dtranstype !== col_split[2]) || (dlineno !== '' && dlineno !== col_split[3]) || (duser !== '' && duser !== col_split[1])) {
                if (flag === 0) {
                    str += "<tr style='font-weight:bold; color:#999999;background-color:#FFFFFF;'>";
                    flag = 1;
                } else {
                    str += "<tr style='font-weight:bold; color:#FFFFFF;background-color:#999999;'>";
                    flag = 0;
                }
                var l = k * 10000;
                serialnoid = "serialnoid" + l;
                usernameid = "usernameid" + l;
                transtypeid = "transtypeid" + l;
                linenoid = "linenoid" + l;
                cardnoid = "cardnoid" + l;
                nameid = "nameid" + l;
                transdateid = "transdateid" + l;
                transdatevalue = newdate[2] + "/" + newdate[1] + "/" + newdate[0];
                transdatevalue2 = col_split[6].substr(0, 10);
                balance_bid = "balance_bid" + l;
                amountid = "amountid" + l;
                balance_aid = "balance_aid" + l;
                transgroupid = "transgroupid" + l;
                lockid = "lockid";
                postid = "postid";
                saveid = "saveid" + l;
                clearid = "clearid" + l;
                str += "<input type='hidden' value='' id='" + serialnoid + "' />";
                str += "<input type='hidden' value='" + dlineno + "' id='" + linenoid + "' />";
                str += "<input type='hidden' value='" + transdatevalue + "' id='" + transdateid + "' />";
                str += "<input type='hidden' value='" + dtranstype + "' id='" + transtypeid + "' />";
                str += "<input type='hidden' value='" + transgroupvalue + "' id='" + transgroupid + "' />";
                str += "<input type='hidden' value='" + duser + "' id='" + usernameid + "' />";
                str += "<input type='hidden' value='1' id='" + lockid + "' />";
                str += "<input type='hidden' value='1' id='" + postid + "' />";

                str += "<td align='right'>" + (++count) + ".</td>";
                str += "<td>" + duser + "</td>";
                str += "<td align='center'>" + dtranstype + "</td>";
                str += "<td align='center'>" + dlineno + "</td>";
                str += "<td align='center'>" + transdatevalue + "</td>";
                str += "<td align='center'><input  style='display:inline' type='text' id='" + cardnoid + "' onclick=\"this.value='';document.getElementById('" + nameid + "').value=''; onblur='this.value=capitalize(this.value);\" clearLists('recordlist');' onkeydown='checkKeyPressed(event,this.id);' size='15' />";
                str += "<td><input type='text' readonly disabled='true' id='" + nameid + "' onblur='this.value=capitalize(this.value)' size='20' /></td>";
                str += "<td><input type='text' style='text-align:right;' readonly disabled='true' id='" + balance_bid + "' onblur='this.value=numberFormat(this.value)' size='10' /></td>";
                str += "<td><input type='text' style='text-align:right;' id='" + amountid + "' onblur=getNewBalance(this.id,'" + dtranstype + "'); onkeydown='checkKeyPressed(event,this.id);' size='10' /></td>";
                str += "<td><input type='text' style='text-align:right;' readonly disabled='true' id='" + balance_aid + "' onblur='this.value=numberFormat(this.value)' size='10' /></td>";
                str += "<td><a id='" + saveid + "' onfocus=document.getElementById('" + saveid + "').style.backgroundColor='lightblue' onblur=document.getElementById('" + saveid + "').style.backgroundColor='transparent' onkeydown='checkKeyPressed(event,this.id);' href=javascript:updateTranslist('" + serialnoid + "','" + linenoid + "','" + cardnoid + "','" + nameid + "','" + transdateid + "','" + balance_bid + "','" + amountid + "','" + balance_aid + "','" + transtypeid + "','" + transgroupid + "','" + usernameid + "','" + lockid + "','" + postid + "','addRecord');>Save</a>&nbsp;&nbsp;";
                str += "<a id='" + clearid + "' onfocus=document.getElementById('" + clearid + "').style.backgroundColor='lightblue' onblur=document.getElementById('" + clearid + "').style.backgroundColor='transparent' onkeydown='checkKeyPressed(event,this.id);' href=javascript:clearTrans('" + cardnoid + "','" + nameid + "','" + balance_bid + "','" + amountid + "','" + balance_aid + "','" + balance_aid + "');>Clear</a></td></tr>";

                col_split = row_split[k].split('_~_');
                lineno = col_split[3];
                transdate = col_split[6].substr(0, 10);
                username = col_split[1];
                transtype = col_split[2];
                str += "<tr style='font-weight:bold; color:#FFFFFF;background-color:green;'>";
                str += "<td colspan='8' align='right'>Total " + capAdd(dtranstype) + " for Line No: " + dlineno + "</td>";
                str += "<td align='right'>" + numberFormat(tttype + "") + "</td>";
                str += "<td colspan='2'>&nbsp;</td></tr>";
                str += "<tr style='font-weight:bold; color:#FFFFFF;background-color:white;'><td colspan='11'>&nbsp;</td></tr>";
                str += "<tr style='font-weight:bold; color:#FFFFFF'>";
                datecount += 1;
                //str += "<td>S/No</td><td>User&nbsp;Name</td><td>Transaction&nbsp;Type</td><td>Line&nbsp;No<input type='text' id='linefield"+datecount+"' onblur=checkLineValue(this.id);  onclick=this.value=''; value='Change Line No' size='14' onkeyup=changeLineno(event,this.value,'"+lineno+"','"+transdate+"','"+username+"','"+transtype+"') /></td><td>Trans.&nbsp;Date<input type='text' id='datefield"+datecount+"' name='datefield"+datecount+"' onclick=this.value='';getDate(this.name) value='Change Date' size='11' onblur=checkDateValue(this.id); onkeyup=changeDate(event,this.value,'"+lineno+"','"+transdate+"','"+username+"','"+transtype+"') /></td><td>Card&nbsp;No</td><td>Customer&nbsp;Names</td><td align='right'>Balance&nbsp;B/F</td><td align='right'>Amount</td><td align='right'>New&nbsp;Balance</td><td><a href=javascript:deleteLine('"+lineno+"','"+transdate+"','"+username+"','"+transtype+"')>Delete&nbsp;Line&nbsp;"+lineno+"</a></td></tr>";
                str += "<td>S/No</td><td>User&nbsp;Name</td><td>Transaction&nbsp;Type</td><td>Line&nbsp;No<input type='text' id='linefield" + datecount + "' onblur=checkLineValue(this.id); onclick=\"this.value='';\" value='Change Line No' size='14' onkeyup=changeLineno(event,this.value,'" + lineno + "','" + transdate + "','" + username + "','" + transtype + "') /></td><td>Trans.&nbsp;Date<input type='text' id='datefield" + datecount + "' name='datefield" + datecount + "' onclick=\"this.value='';getDate(this.name);\" value='Change Date' size='11' onblur=checkDateValue(this.id); onkeyup=changeDate(event,this.value,'" + lineno + "','" + transdate + "','" + username + "','" + transtype + "') /></td><td>Card&nbsp;No</td><td>Customer&nbsp;Names</td><td align='right'>Balance&nbsp;B/F</td><td align='right'>Amount</td><td align='right'>New&nbsp;Balance</td><td><a href=javascript:deleteLine('" + lineno + "','" + transdate + "','" + username + "','" + transtype + "')>Delete&nbsp;Line&nbsp;" + lineno + "</a></td></tr>";
                tttype = 0;
                count = 0;
            }
            dtranstype = col_split[2];
            duser = col_split[1];
            dlineno = col_split[3];
        }
        if (flag === 0) {
            str += "<tr style='font-weight:bold; color:#999999;background-color:#FFFFFF;'>";
            flag = 1;
        } else {
            str += "<tr style='font-weight:bold; color:#FFFFFF;background-color:#999999;'>";
            flag = 0;
        }
        transdatevalue = col_split[6];
        usernamevalue = col_split[1];
        transtypevalue = col_split[2];

        serialnoid = "serialnoid" + k;
        serialnovalue = col_split[0];
        usernameid = "usernameid" + k;
        usernamevalue = col_split[1];
        transtypeid = "transtypeid" + k;
        transtypevalue = col_split[2];
        linenoid = "linenoid" + k;
        linenovalue = col_split[3];
        cardnoid = "cardnoid" + k;
        cardnovalue = col_split[4];
        nameid = "nameid" + k;
        namevalue = col_split[5];
        transdateid = "transdateid" + k;
        transdatevalue = col_split[6];
        var newdate = transdatevalue.substr(0, 10);
        newdate = newdate.split("-");
        transdatevalue = newdate[2] + "/" + newdate[1] + "/" + newdate[0];
        transdatevalue2 = col_split[6].substr(0, 10);
        balance_bid = "balance_bid" + k;
        balance_bvalue = col_split[7];
        amountid = "amountid" + k;
        amountvalue = col_split[8];
        totalamount += parseFloat(amountvalue);
        balance_aid = "balance_aid" + k;
        balance_avalue = col_split[9];
        transgroupid = "transgroupid" + k;
        transgroupvalue = col_split[10];
        lockid = "lockid";
        lockvalue = col_split[11];
        postid = "postid";
        postvalue = col_split[12];
        updateid = "updateid" + k;
        deleteid = "deleteid" + k;

        if (transtypevalue === 'deposit')
            tdeposit += parseFloat(amountvalue);
        if (transtypevalue === 'commission')
            tcommission += parseFloat(amountvalue);
        if (transtypevalue === 'withdrawal')
            twithdrawal += parseFloat(amountvalue);
        tttype += parseFloat(amountvalue);


        str += "<input type='hidden' value='" + serialnovalue + "' id='" + serialnoid + "' />";
        str += "<input type='hidden' value='" + linenovalue + "' id='" + linenoid + "' />";
        str += "<input type='hidden' value='" + transdatevalue + "' id='" + transdateid + "' />";
        str += "<input type='hidden' value='" + transtypevalue + "' id='" + transtypeid + "' />";
        str += "<input type='hidden' value='" + transgroupvalue + "' id='" + transgroupid + "' />";
        str += "<input type='hidden' value='" + usernamevalue + "' id='" + usernameid + "' />";
        str += "<input type='hidden' value='" + lockvalue + "' id='" + lockid + "' />";
        str += "<input type='hidden' value='" + postvalue + "' id='" + postid + "' />";

        str += "<td align='right'>" + (++count) + ".</td>";
        str += "<td>" + usernamevalue + "</td>";
        str += "<td align='center'>" + transtypevalue + "</td>";
        str += "<td align='center'>" + linenovalue + "</td>";
        str += "<td align='center'>" + transdatevalue + "</td>";
        str += "<td align='center'><input  style='display:inline' type='text' readonly disabled='true' value='" + cardnovalue + "' id='" + cardnoid + "' onkeyup=getRecordlist('" + cardnoid + "','" + table + "','" + list + "') onclick=\"this.value='';getRecordlist('" + cardnoid + "','" + table + "','" + list + "')\"  onblur='this.value=capitalize(this.value); clearLists('recordlist');' onkeydown='checkKeyPressed(event,this.id);' size='15' />";
        str += "<td><input type='text' readonly disabled='true' value='" + namevalue + "' id='" + nameid + "' onblur='this.value=capitalize(this.value)' size='20' /></td>";
        str += "<td><input type='text' style='text-align:right;' readonly disabled='true' value='" + numberFormat(balance_bvalue) + "' id='" + balance_bid + "' onblur='this.value=numberFormat(this.value)' size='10' /></td>";
        str += "<td><input type='text' style='text-align:right;' value='" + numberFormat(amountvalue) + "' id='" + amountid + "' onblur=getNewBalance(this.id,'" + transtypevalue + "'); onkeydown='checkKeyPressed(event,this.id);' size='10' /></td>";
        str += "<td><input type='text' style='text-align:right;' readonly disabled='true' value='" + numberFormat(balance_avalue) + "' id='" + balance_aid + "' onblur='this.value=numberFormat(this.value)' size='10' /></td>";
        str += "<td><a id='" + updateid + "' onfocus=document.getElementById('" + updateid + "').style.backgroundColor='lightblue' onblur=document.getElementById('" + updateid + "').style.backgroundColor='transparent' onkeydown='checkKeyPressed(event,this.id);' href=javascript:updateTranslist('" + serialnoid + "','" + linenoid + "','" + cardnoid + "','" + nameid + "','" + transdateid + "','" + balance_bid + "','" + amountid + "','" + balance_aid + "','" + transtypeid + "','" + transgroupid + "','" + usernameid + "','" + lockid + "','" + postid + "','updateRecord')>Update</a>&nbsp;&nbsp;";
        str += "<a id='" + deleteid + "' onfocus=document.getElementById('" + deleteid + "').style.backgroundColor='lightblue' onblur=document.getElementById('" + deleteid + "').style.backgroundColor='transparent' onkeydown='checkKeyPressed(event,this.id);' href=javascript:updateTranslist('" + serialnoid + "','" + linenoid + "','" + cardnoid + "','" + nameid + "','" + transdateid + "','" + balance_bid + "','" + amountid + "','" + balance_aid + "','" + transtypeid + "','" + transgroupid + "','" + usernameid + "','" + lockid + "','" + postid + "','deleteRecord')>Delete</a></td></tr>";
	}

    if (flag === 0) {
        str += "<tr style='font-weight:bold; color:#999999;background-color:#FFFFFF;'>";
        flag = 1;
    } else {
        str += "<tr style='font-weight:bold; color:#FFFFFF;background-color:#999999;'>";
        flag = 0;
    }
    var l = k * 10000
    serialnoid = "serialnoid" + l;
    usernameid = "usernameid" + l;
    transtypeid = "transtypeid" + l;
    linenoid = "linenoid" + l;
    cardnoid = "cardnoid" + l;
    nameid = "nameid" + l;
    transdateid = "transdateid" + l;
    transdatevalue = newdate[2] + "/" + newdate[1] + "/" + newdate[0];
    transdatevalue2 = col_split[6].substr(0, 10);
    balance_bid = "balance_bid" + l;
    amountid = "amountid" + l;
    balance_aid = "balance_aid" + l;
    transgroupid = "transgroupid" + l;
    lockid = "lockid";
    postid = "postid";
    saveid = "saveid" + l;
    clearid = "clearid" + l;
    str += "<input type='hidden' value='' id='" + serialnoid + "' />";
    str += "<input type='hidden' value='" + dlineno + "' id='" + linenoid + "' />";
    str += "<input type='hidden' value='" + transdatevalue + "' id='" + transdateid + "' />";
    str += "<input type='hidden' value='" + dtranstype + "' id='" + transtypeid + "' />";
    str += "<input type='hidden' value='" + transgroupvalue + "' id='" + transgroupid + "' />";
    str += "<input type='hidden' value='" + duser + "' id='" + usernameid + "' />";
    str += "<input type='hidden' value='1' id='" + lockid + "' />";
    str += "<input type='hidden' value='1' id='" + postid + "' />";

    str += "<td align='right'>" + (++count) + ".</td>";
    str += "<td>" + duser + "</td>";
    str += "<td align='center'>" + dtranstype + "</td>";
    str += "<td align='center'>" + dlineno + "</td>";
    str += "<td align='center'>" + transdatevalue + "</td>";
    str += "<td align='center'><input  style='display:inline' type='text'  id='" + cardnoid + "' onclick=\"this.value='';document.getElementById('" + nameid + "').value='';\" onblur='this.value=capitalize(this.value); clearLists('recordlist');' onkeydown='checkKeyPressed(event,this.id);' size='15' />";
    str += "<td><input type='text' readonly disabled='true' id='" + nameid + "' onblur='this.value=capitalize(this.value)' size='20' /></td>";
    str += "<td><input type='text' style='text-align:right;' readonly disabled='true' id='" + balance_bid + "' onblur='this.value=numberFormat(this.value)' size='10' /></td>";
    str += "<td><input type='text' style='text-align:right;' id='" + amountid + "' onblur=getNewBalance(this.id,'" + dtranstype + "'); onkeydown='checkKeyPressed(event,this.id);' size='10' /></td>";
    str += "<td><input type='text' style='text-align:right;' readonly disabled='true' id='" + balance_aid + "' onblur='this.value=numberFormat(this.value)' size='10' /></td>";
    str += "<td><a id='" + saveid + "' onfocus=document.getElementById('" + saveid + "').style.backgroundColor='lightblue' onblur=document.getElementById('" + saveid + "').style.backgroundColor='transparent' onkeydown='checkKeyPressed(event,this.id);' href=javascript:updateTranslist('" + serialnoid + "','" + linenoid + "','" + cardnoid + "','" + nameid + "','" + transdateid + "','" + balance_bid + "','" + amountid + "','" + balance_aid + "','" + transtypeid + "','" + transgroupid + "','" + usernameid + "','" + lockid + "','" + postid + "','addRecord');>Save</a>&nbsp;&nbsp;";
    str += "<a id='" + clearid + "' onfocus=document.getElementById('" + clearid + "').style.backgroundColor='lightblue' onblur=document.getElementById('" + clearid + "').style.backgroundColor='transparent' onkeydown='checkKeyPressed(event,this.id);' href=javascript:clearTrans('" + cardnoid + "','" + nameid + "','" + balance_bid + "','" + amountid + "','" + balance_aid + "','" + balance_aid + "');>Clear</a></td></tr>";


    str += "<tr style='font-weight:bold; color:#FFFFFF;background-color:green;'>";
    str += "<td colspan='8' align='right'>Total " + capAdd(dtranstype) + " for Line No: " + dlineno + "</td>";
    str += "<td align='right'>" + numberFormat(tttype + "") + "</td>";
    str += "<td colspan='2'>&nbsp;</td></tr>";
    str += "<tr style='font-weight:bold; color:#FFFFFF'>";

    str += "<tr style='font-weight:bold; color:#FFFFFF;background-color:red;'>";
    str += "<td colspan='8' align='right'>Total for Deposit:&nbsp;</td>";
    str += "<td align='right'>" + numberFormat(tdeposit + "") + "</td>";
    str += "<td colspan='2'>&nbsp;</td></tr>";
    str += "<tr style='font-weight:bold; color:#FFFFFF'>";

    str += "<tr style='font-weight:bold; color:#FFFFFF;background-color:red;'>";
    str += "<td colspan='8' align='right'>Total for Withdrawal:&nbsp;</td>";
    str += "<td align='right'>" + numberFormat(twithdrawal + "") + "</td>";
    str += "<td colspan='2'>&nbsp;</td></tr>";
    str += "<tr style='font-weight:bold; color:#FFFFFF'>";

    str += "<tr style='font-weight:bold; color:#FFFFFF;background-color:red;'>";
    str += "<td colspan='8' align='right'>Total for Commission:&nbsp;</td>";
    str += "<td align='right'>" + numberFormat(tcommission + "") + "</td>";
    str += "<td colspan='2'>&nbsp;</td></tr>";
    str += "<tr style='font-weight:bold; color:#FFFFFF'>";

    str += "</table>";
	//$("#translist").html("");
	replaceHtml('translist', str);
    //document.getElementById('translist').innerHTML = str;
	//$("#translist").html(str);
	//var allTheHTML = str;
	//var myTarget = document.getElementById('translist');
	//asyncInnerHTML(allTheHTML, function(fragment){
	//	myTarget.appendChild(fragment); // myTarget should be an element node.
	//});
}

function asyncInnerHTML(HTML, callback) {
    var temp = document.createElement('div'),
        frag = document.createDocumentFragment();
    temp.innerHTML = HTML;
    (function(){
        if(temp.firstChild){
            frag.appendChild(temp.firstChild);
            setTimeout(arguments.callee, 0);
        } else {
            callback(frag);
        }
    })();
}
function replaceHtml(el, html) {
	var oldEl = typeof el === "string" ? document.getElementById(el) : el;
	var newEl = oldEl.cloneNode(false);
	newEl.innerHTML = html;
	oldEl.parentNode.replaceChild(newEl, oldEl);
	return newEl;
};

//var checkcounter=null;
function doCheckAll(){
    for (k = 1; k < totalrecords + 1; k++) {
        var selectid = "selectid" + k;
        if(document.getElementById(selectid).checked === true){
            document.getElementById(selectid).checked = false;
        }else{
            document.getElementById(selectid).checked = true;
        }
    }
}
function smstransactionlist(arg) {
//document.getElementById("showError").innerHTML = arg;
//$('#showError').dialog('open');
//alert(arg);
    if (arg === "transactionlist2getAllRecs") {
        document.getElementById('translist').innerHTML = "";
        alert("No data available for Specified date!!!");
        return true;
    }

    var row_split = arg.split('getAllRecs');
    col_split = row_split[1].split('_~_');
    lineno = col_split[3];
    transdate = col_split[6].substr(0, 10);
    username = col_split[1];
    transtype = col_split[2];
    var datecount = 1;
    var str = "<table style='border-color:#fff;border-style:solid;border-width:1px; height:10px;width:100%;background-color:#336699;margin-top:5px;'>";
    str += "<tr style='font-weight:bold; color:#FFFFFF'>";
    str += "<td align='right'>S/No</td><td>User&nbsp;Name</td><td>Trans.&nbsp;Type</td><td>Line&nbsp;No</td><td>Trans.&nbsp;Date</td><td>Card&nbsp;No</td><td>Customer&nbsp;Names</td><td align='right'>Bal.&nbsp;B/F</td><td align='right'>Amt</td><td align='right'>New&nbsp;Bal.</td><td>Phone&nbsp;No</td><td>SMS&nbsp;Status</td><td>Select&nbsp;All<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' onclick='doCheckAll();' id='selectall'></td></tr>";
    var flag = 0;
    var serialnoid = "";
    var serialnovalue = "";
    var usernameid = "";
    var usernamevalue = "";
    var transtypeid = "";
    var transtypevalue = "";
    var linenoid = "";
    var linenovalue = "";
    var cardnoid = "";
    var cardnovalue = "";
    var nameid = "";
    var namevalue = "";
    var transdateid = "";
    var transdatevalue = "";
    var transdatevalue2 = "";
    var balance_bid = "";
    var balance_bvalue = "";
    var amountid = "";
    var amountvalue = "";
    var totalamount = 0;
    var balance_aid = "";
    var balance_avalue = "";
    var transgroupid = "";
    var transgroupvalue = "";
    var lockid = "";
    var lockvalue = "";
    var postid = "";
    var postvalue = "";
    var phoneid = "";
    var phonevalue = "";
    var smsstatusid = "";
    var smsstatusvalue = "";
    var selectid = "";
    var col_split = "";
    var count = 0;
    var editid = "";
    var k = 1;
    var table = 'customers';
    var list = 'recordlist';
    var dlineno = '';
    var duser = '';
    var dtranstype = '';
    var tdeposit = 0;
    var tcommission = 0;
    var twithdrawal = 0;
    var tttype = 0;
    for (k = 1; k < row_split.length - 1; k++) {
        col_split = row_split[k].split('_~_');
        if (flag === 0) {
            str += "<tr style='font-weight:bold; color:#999999;background-color:#FFFFFF;'>";
            flag = 1;
        } else {
            str += "<tr style='font-weight:bold; color:#FFFFFF;background-color:#999999;'>";
            flag = 0;
        }
        transdatevalue = col_split[6].trim();
        usernamevalue = col_split[1].trim();
        transtypevalue = col_split[2].trim();

        serialnoid = "serialnoid" + k;
        serialnovalue = col_split[0].trim();
        usernameid = "usernameid" + k;
        usernamevalue = col_split[1].trim();
        transtypeid = "transtypeid" + k;
        transtypevalue = col_split[2].trim();
        linenoid = "linenoid" + k;
        linenovalue = col_split[3].trim();
        cardnoid = "cardnoid" + k;
        cardnovalue = col_split[4].trim();
        nameid = "nameid" + k;
        namevalue = col_split[5].trim();
        transdateid = "transdateid" + k;
        transdatevalue = col_split[6].trim();
        var newdate = transdatevalue.substr(0, 10);
        newdate = newdate.split("-");
        transdatevalue = newdate[2] + "/" + newdate[1] + "/" + newdate[0];
        transdatevalue2 = col_split[6].substr(0, 10);
        balance_bid = "balance_bid" + k;
        balance_bvalue = col_split[7];
        amountid = "amountid" + k;
        amountvalue = col_split[8];
        totalamount += parseFloat(amountvalue);
        balance_aid = "balance_aid" + k;
        balance_avalue = col_split[9];
        transgroupid = "transgroupid" + k;
        transgroupvalue = col_split[10];
        lockid = "lockid";
        lockvalue = col_split[11];
        postid = "postid";
        postvalue = col_split[12];
        phoneid = "phoneid" + k;
        phonevalue = col_split[13];
        smsstatusid = "smsstatusid" + k;
        smsstatusvalue = col_split[14];
        selectid = "selectid" + k;
        
        if (transtypevalue === 'deposit')
            tdeposit += parseFloat(amountvalue);
        if (transtypevalue === 'commission')
            tcommission += parseFloat(amountvalue);
        if (transtypevalue === 'withdrawal')
            twithdrawal += parseFloat(amountvalue);
        tttype += parseFloat(amountvalue);


        str += "<input type='hidden' value='" + serialnovalue + "' id='" + serialnoid + "' />";
        str += "<input type='hidden' value='" + cardnovalue + "' id='" + cardnoid + "' />";
        str += "<input type='hidden' value='" + linenovalue + "' id='" + linenoid + "' />";
        str += "<input type='hidden' value='" + transdatevalue + "' id='" + transdateid + "' />";
        str += "<input type='hidden' value='" + transtypevalue + "' id='" + transtypeid + "' />";
        str += "<input type='hidden' value='" + transgroupvalue + "' id='" + transgroupid + "' />";
        str += "<input type='hidden' value='" + usernamevalue + "' id='" + usernameid + "' />";
        str += "<input type='hidden' value='" + lockvalue + "' id='" + lockid + "' />";
        str += "<input type='hidden' value='" + postvalue + "' id='" + postid + "' />";

        str += "<td align='right'>" + (++count) + ".</td>";
        str += "<td>" + usernamevalue + "</td>";
        str += "<td align='left'>" + transtypevalue + "</td>";
        str += "<td align='left'>" + linenovalue + "</td>";
        str += "<td align='left'>" + transdatevalue + "</td>";
        str += "<td align='left'>" + cardnovalue + "</td>";
        str += "<td align='left'>" + namevalue + "</td>";
        str += "<td align='right'>" + numberFormat(balance_bvalue) + "</td>";
        str += "<td align='right'>" + numberFormat(amountvalue) + "</td>";
        str += "<td align='right'>" + numberFormat(balance_avalue) + "</td>";
        str += "<td align='left'>" + phonevalue + "</td>";
        str += "<td align='left'>" + smsstatusvalue + "</td>";
        str += "<td align='center'><input type='checkbox' id='"+selectid+"'></td></tr>";
        totalrecords = k;
    }
    str += "</table>";
    document.getElementById('translist').innerHTML = str;
}

function checkLineValue(id) {
    if (document.getElementById(id).value === '')
        document.getElementById(id).value = 'Change Line No';
}

function checkDateValue(id) {
    if (document.getElementById(id).value === '')
        document.getElementById(id).value = 'Change Date';
}

function changeDate(e, date, lineno, transdate, username, transtype) {
    var characterCode; //literal character code will be stored in this variable

    if (e && e.which) { //if which property of event object is supported (NN4)
        e = e;
        characterCode = e.which //character code is contained in NN4's which property
    } else {
        e = event;
        characterCode = e.keyCode; //character code is contained in IE's keyCode property
    }
    if (characterCode === 13) {
        date = date.substr(6, 4) + '-' + date.substr(3, 2) + '-' + date.substr(0, 2);
        var a_param1 = "a_param1=" + date + "][" + lineno + "][" + transdate + "][" + username + "][" + transtype;
        var url = "/dadollar/setupbackend.php?option=changeDate&" + a_param1;
//alert(url);
        var ans = confirm("Are you sure you want to change the date for line No " + lineno);
        if (!ans)
            return true;
        document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Changing date for Line No " + lineno + ".";
        $('#showAlert').dialog('open');
        createCookie("currentoperation", "changeDate", false);
        AjaxFunctionSetup(url);
    }
}

function changeLineno(e, line, lineno, transdate, username, transtype) {
    var characterCode; //literal character code will be stored in this variable

    if (e && e.which) { //if which property of event object is supported (NN4)
        e = e;
        characterCode = e.which //character code is contained in NN4's which property
    } else {
        e = event;
        characterCode = e.keyCode; //character code is contained in IE's keyCode property
    }
    if (characterCode === 13) {
        var a_param1 = "a_param1=" + line + "][" + lineno + "][" + transdate + "][" + username + "][" + transtype;
        var url = "/dadollar/setupbackend.php?option=changeLineno&" + a_param1;
        document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Changing Line No" + lineno + ".";
        $('#showAlert').dialog('open');
        var ans = confirm("Are you sure you want to change line No " + lineno);
        if (!ans)
            return true;
        AjaxFunctionSetup(url);
    }
}

function deleteLine(lineno, transdate, username, transtype) {
    var a_param1 = "a_param1=" + lineno + "][" + transdate + "][" + username + "][" + transtype;
    var url = "/dadollar/setupbackend.php?option=deleteLine&" + a_param1;
    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Deleting Line" + lineno + ".";
    $('#showAlert').dialog('open');
    var ans = confirm("Are you sure you want to delete all for Line No " + lineno);
    if (!ans)
        return true;
    AjaxFunctionSetup(url);
}

function clearTrans(cardnoid, nameid, balance_bid, amountid, balance_aid, passportpictureid) {
    document.getElementById(cardnoid).value = "";
    document.getElementById(nameid).value = "";
    document.getElementById(balance_bid).value = "";
    document.getElementById(amountid).value = "";
    document.getElementById(balance_aid).value = "";
    if (document.getElementById(passportpictureid).substr(0, 8) === "passport") {
        document.getElementById(passportpictureid).innerHTML = "";
    }
    document.getElementById(cardnoid).focus();
}

function getNewBal(id) {
    var amount = document.getElementById(id).value.replace(/&/g, '$').replace(/,/g, '');
    var balance_bid = "balance_bid" + id.substr(8, id.length);
    var balance_aid = "balance_aid" + id.substr(8, id.length);
    if (readCookie('currentform') === "deposit") {
        document.getElementById(balance_aid).value = numberFormat((parseFloat(document.getElementById(balance_bid).value.replace(/&/g, '$').replace(/,/g, '')) + parseFloat(amount)) + "");
    }
    if (readCookie('currentform') === "commission" || readCookie('currentform') === "withdrawal") {
        document.getElementById(balance_aid).value = numberFormat((parseFloat(document.getElementById(balance_bid).value.replace(/&/g, '$').replace(/,/g, '')) - parseFloat(amount)) + "");
    }
    if (document.getElementById(balance_aid).value === 'NaN.00')
        document.getElementById(balance_aid).value = "";
}

function getNewBalance(id, transtype) {
    var amount = document.getElementById(id).value.replace(/&/g, '$').replace(/,/g, '');
    var balance_bid = "balance_bid" + id.substr(8, id.length);
    var balance_aid = "balance_aid" + id.substr(8, id.length);
    if (transtype === "deposit") {
        document.getElementById(balance_aid).value = numberFormat((parseFloat(document.getElementById(balance_bid).value.replace(/&/g, '$').replace(/,/g, '')) + parseFloat(amount)) + "");
    }
    if (transtype === "commission" || transtype === "withdrawal") {
        document.getElementById(balance_aid).value = numberFormat((parseFloat(document.getElementById(balance_bid).value.replace(/&/g, '$').replace(/,/g, '')) - parseFloat(amount)) + "");
    }
    if (document.getElementById(balance_aid).value.match('NaN'))
        document.getElementById(balance_aid).value = "";
    document.getElementById(id).value = numberFormat(document.getElementById(id).value.replace(/&/g, '$'));
}

function updateTrans(serialnoid, linenoid, cardnoid, nameid, transdateid, balance_bid, amountid, balance_aid, transtypeid, transgroupid, usernameid, lockid, postid, option) {
    var transtype = "";
    var transgroup = "";
    if (readCookie('currentform') === "deposit") {
        transtype = "deposit";
        transgroup = "contribution";
    }

    if (readCookie('currentform') === "withdrawal") {
        transtype = "withdrawal";
        transgroup = "contribution";
    }

    if (readCookie('currentform') === "commission") {
        transtype = "commission";
        transgroup = "contribution";
    }
    var serialno = document.getElementById(serialnoid).value.replace(/&/g, '$');
    var lineno = document.getElementById(linenoid).value.replace(/&/g, '$');
    if (option === "addRecord"){
        lineno = document.getElementById('lineno').value.replace(/&/g, '$');
	}
    var cardno = document.getElementById(cardnoid).value.replace(/&/g, '$');
    var name = document.getElementById(nameid).value.replace(/&/g, '$');
    var transdate = document.getElementById(transdateid).value.replace(/&/g, '$');
    if (option === "addRecord") {
        transdate = document.getElementById('transdate').value.replace(/&/g, '$');
        transdate = transdate.substr(6, 4) + '-' + transdate.substr(3, 2) + '-' + transdate.substr(0, 2);
    }
    var balance_b = document.getElementById(balance_bid).value.replace(/&/g, '$').replace(/,/g, '');
    var amount = document.getElementById(amountid).value.replace(/&/g, '$').replace(/,/g, '');
    var balance_a = document.getElementById(balance_aid).value.replace(/&/g, '$').replace(/,/g, '');
    //var transtype = document.getElementById(transtypeid).value.replace(/&/g,'$');
    //var transgroup = document.getElementById(transgroupid).value.replace(/&/g,'$');
    var username = document.getElementById('username').value.replace(/&/g, '$');
    //var username = readCookie('currentuser');
    var lock = document.getElementById(lockid).value.replace(/&/g, '$');
    var post = document.getElementById(postid).value.replace(/&/g, '$');

	if(isNaN(balance_b) || balance_b=="") balance_b="0.0";
    if(isNaN(balance_a) || balance_a=="") balance_a="0.0";
    var error = "";
    if (lineno === "")
        error += "Line No must not be blank.<br><br>";
    if (transdate === "--")
        error += "Transaction Date must not be blank.<br><br>";
    if (username === "")
        error += "User Name must not be blank.<br><br>";
    if (cardno === "")
        error += "Card No must not be blank.<br><br>";
    if (name === "" && cardno !== "" && option !== "deleteRecord")
        error += "Wrong Card No typed or selected.<br><br>";
    if (amount === "")
        error += "Transaction Amount must not be blank.<br><br>";
    if (lock === "1" && readCookie('currentuser') !== "Admin")
        error += "Locked record can not be updated.<br><br>";
    if (error.length > 0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error
        document.getElementById("showError").innerHTML = error;
        $('#showError').dialog('open');
    } else {
        var a_param1 = "&a_param1=" + serialno + "][" + lineno + "][" + cardno + "][" + transdate + "][" + balance_b + "][" + amount + "][" + balance_a + "][" + transtype + "][" + transgroup + "][" + username + "][" + lock + "][" + post;
        var url = "/dadollar/setupbackend.php?option=" + option + "&table=transactionlist" + a_param1;
        document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Updating your data.";
        $('#showAlert').dialog('open');
//alert(url);
        AjaxFunctionSetup(url);
    }
}

function updateTranslist(serialnoid, linenoid, cardnoid, nameid, transdateid, balance_bid, amountid, balance_aid, transtypeid, transgroupid, usernameid, lockid, postid, option) {
//alert(serialnoid+" | "+linenoid+" | "+cardnoid+" | "+nameid+" | "+transdateid+" | "+balance_bid+" | "+amountid+" | "+balance_aid+" | "+transtypeid+" | "+usernameid+" | "+option)
    var serialno = document.getElementById(serialnoid).value.replace(/&/g, '$');
    var lineno = document.getElementById(linenoid).value.replace(/&/g, '$');
    var cardno = document.getElementById(cardnoid).value.replace(/&/g, '$');
    var name = document.getElementById(nameid).value.replace(/&/g, '$');
    var transdate = document.getElementById(transdateid).value.replace(/&/g, '$');
    transdate = transdate.substr(6, 4) + '-' + transdate.substr(3, 2) + '-' + transdate.substr(0, 2);
    var balance_b = document.getElementById(balance_bid).value.replace(/&/g, '$').replace(/,/g, '');
    var amount = document.getElementById(amountid).value.replace(/&/g, '$').replace(/,/g, '');
    var balance_a = document.getElementById(balance_aid).value.replace(/&/g, '$').replace(/,/g, '');
    var transtype = document.getElementById(transtypeid).value.replace(/&/g, '$');
    var transgroup = document.getElementById(transgroupid).value.replace(/&/g, '$');
    var username = document.getElementById(usernameid).value.replace(/&/g, '$');
    var lock = document.getElementById(lockid).value.replace(/&/g, '$');
    var post = document.getElementById(postid).value.replace(/&/g, '$');
    //var username = readCookie('currentuser');
	if(isNaN(balance_b) || balance_b=="") balance_b="0.0";
    if(isNaN(balance_a) || balance_a=="") balance_a="0.0";

    var error = "";
    if(lineno==="") error += "Line No must not be blank.<br><br>";
     if(transdate==="--") error += "Transaction Date must not be blank.<br><br>";
     if(username==="") error += "User Name must not be blank.<br><br>";
    if (cardno === "")
        error += "Card No must not be blank.<br><br>";
    if (name === "" && cardno !== "")
        error += "Wrong Card No typed or selected.<br><br>";
    if (amount === "")
        error += "Transaction Amount must not be blank.<br><br>";
    if (error.length > 0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error
        document.getElementById("showError").innerHTML = error;
        $('#showError').dialog('open');
    } else {
        var a_param1 = "&a_param1=" + serialno + "][" + lineno + "][" + cardno + "][" + transdate + "][" + balance_b + "][" + amount + "][" + balance_a + "][" + transtype + "][" + transgroup + "][" + username + "][" + lock + "][" + post;
        var url = "/dadollar/setupbackend.php?option=" + option + "&table=transactionlist2" + a_param1;
		//alert(url);
        document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Updating your data.";
        $('#showAlert').dialog('open');
        AjaxFunctionSetup(url);
    }
}

function postTransaction() {
    var transtype = "";
    var transgroup = "";
    if (readCookie('currentform') === "deposit") {
        transtype = "deposit";
        transgroup = "contribution";
    }

    if (readCookie('currentform') === "withdrawal") {
        transtype = "withdrawal";
        transgroup = "contribution";
    }

    if (readCookie('currentform') === "commission") {
        transtype = "commission";
        transgroup = "contribution";
    }
    var lineno = document.getElementById('lineno').value.replace(/&/g, '$');
    var transdate = document.getElementById('transdate').value.replace(/&/g, '$');
    transdate = transdate.substr(6, 4) + '-' + transdate.substr(3, 2) + '-' + transdate.substr(0, 2);
    var username = document.getElementById("username").value.replace(/&/g, '$');

    var error = "";
    if (lineno === "")
        error += "Line No must not be blank.<br><br>";
    if (transdate === "--")
        error += "Transaction Date must not be blank.<br><br>";
    if (username === "")
        error += "User Name must not be blank.<br><br>";
    if (error.length > 0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error
        document.getElementById("showError").innerHTML = error;
        $('#showError').dialog('open');
    } else {
        myVar = setInterval(function () {
            showCurrentCardno();
        }, 1000);
        var a_param1 = "&a_param1=" + lineno + "][" + transdate + "][" + username + "][" + transtype + "][" + transgroup;
        var url = "/dadollar/setupbackend.php?option=postTransaction" + a_param1;
        document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Posting Transactions.";
        $('#showAlert').dialog('open');
//alert(url);
        AjaxFunctionSetup(url);
        
    }
}

function deleteTransaction(){
    var transtype = "";
    var transgroup = "";
    if (readCookie('currentform') === "deposit") {
        transtype = "deposit";
        transgroup = "contribution";
    }

    if (readCookie('currentform') === "withdrawal") {
        transtype = "withdrawal";
        transgroup = "contribution";
    }

    if (readCookie('currentform') === "commission") {
        transtype = "commission";
        transgroup = "contribution";
    }
    var lineno = document.getElementById('lineno').value.replace(/&/g, '$');
    var transdate = document.getElementById('transdate').value.replace(/&/g, '$');
    transdate = transdate.substr(6, 4) + '-' + transdate.substr(3, 2) + '-' + transdate.substr(0, 2);
    var username = document.getElementById("username").value.replace(/&/g, '$');

    var error = "";
    if (lineno === "")
        error += "Line No must not be blank.<br><br>";
    if (transdate === "--")
        error += "Transaction Date must not be blank.<br><br>";
    if (username === "")
        error += "User Name must not be blank.<br><br>";
    if (error.length > 0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error
        document.getElementById("showError").innerHTML = error;
        $('#showError').dialog('open');
    } else {
        myVar = setInterval(function () {
            showCurrentCardno();
        }, 1000);
        var a_param1 = "&a_param1=" + lineno + "][" + transdate + "][" + username + "][" + transtype + "][" + transgroup;
        var url = "/dadollar/setupbackend.php?option=deleteTransaction" + a_param1;
//document.getElementById("showError").innerHTML = url;
//$('#showError').dialog('open');
//alert(url);
        document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Posting Transactions.";
        $('#showAlert').dialog('open');
        //AjaxFunctionSetup(url);
	}
}

function showUpdateCard() {
    document.getElementById("oldcardNumber").value = document.getElementById("cardno").value.replace(/&/g, '$');
    if (document.getElementById("oldcardNumber").value === null || document.getElementById("oldcardNumber").value === "") {
        var error = "Please correct the following error:\n\nSelect a Customer with Card No";
        alert(error);
        //document.getElementById("showError").innerHTML = error;
        //$('#showError').dialog('open');
        return true;
    }
    $('#updatecardno').dialog('open');
}

function showCurrentCardno() {
    var url = "/dadollar/setupbackend.php?option=showCurrentCardno";
    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Calculating Commissions.";
	$('#showAlert').dialog('open');
	AjaxFunctionSetup(url);
}

var myVar = null;
function calculateCommission() {
    var username = document.getElementById("username").value.replace(/&/g, '$');
    var transdate = document.getElementById('transdate').value.replace(/&/g, '$');
    transdate = transdate.substr(6, 4) + '-' + transdate.substr(3, 2) + '-' + transdate.substr(0, 2);

    var error = "";
    if (transdate === "--")
        error += "Transaction Date must not be blank.<br><br>";
    if (error.length > 0) {
        error = "Please correct the following errors:\n\n\n" + error;
        alert(error);
        return true;
    }
	//showCurrentCardno();
    myVar = setInterval(function () {
        showCurrentCardno();
    }, 1000);
	var a_param1 = "&a_param1=" + username + "][" + transdate;
	var url = "/dadollar/setupbackend.php?option=calculateCommission" + a_param1;
//alert(url);
	document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Calculating Commissions.";
	$('#showAlert').dialog('open');
	AjaxFunctionSetup(url);
}

function updateCardNo() {
    var oldcardnumber = document.getElementById("oldcardNumber").value.replace(/&/g, '$');
    var newcardnumber = document.getElementById("newcardNumber").value.replace(/&/g, '$');
    var error = "";
    if (newcardnumber === "")
        error += "New Card No must not be blank.<br><br>";
    if (error.length > 0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error
        document.getElementById("showError").innerHTML = error;
        $('#showError').dialog('open');
        return true;
    }
    var a_param1 = "&a_param1=" + oldcardnumber + "][" + newcardnumber;
    var url = "/dadollar/setupbackend.php?option=updateCardNo" + a_param1;
    AjaxFunctionSetup(url);
}

function getDate(datefield) {
    //alert(datefield);
    displayDatePicker(datefield, false, 'dmy', '/');
}

function updateCustomer(option, table) {
//alert(option+"   "+table);	
	var passportpicture = readCookie("theImage");
    var cardno = document.getElementById("cardno").value.replace(/&/g, '$');
    var lineno = document.getElementById("lineno").value.replace(/&/g, '$');
    var cardserial = document.getElementById("cardserial").value.replace(/&/g, '$');
    var lastname = document.getElementById("lastname").value.replace(/&/g, '$');
    var othernames = document.getElementById("othernames").value.replace(/&/g, '$');
    var sex = document.getElementById("sex").value.replace(/&/g, '$');
    var telephone = document.getElementById("telephone").value.replace(/&/g, '$');
    var openingbalance = document.getElementById("openingbalance").value.replace(/&/g, '$').replace(/,/g, '');
    if (openingbalance === "")
        openingbalance = "0";
    var recordlock = document.getElementById("recordlock").value.replace(/&/g, '$');
    var address = document.getElementById("address").value.replace(/&/g, '$');
    var commission = document.getElementById("commission").value.replace(/&/g, '$').replace(/,/g, '');
    if (commission === "")
        commission = "0";

    var datedisbursed = document.getElementById("datedisbursed").value.replace(/&/g, '$');
    var datedisbursed2 = datedisbursed;
    datedisbursed = datedisbursed.substr(6, 4) + '-' + datedisbursed.substr(3, 2) + '-' + datedisbursed.substr(0, 2);
    var loanamount = document.getElementById("loanamount").value.replace(/&/g, '$').replace(/,/g, '');
    if (loanamount === "")
        loanamount = "0";
    var loaninterest = document.getElementById("loaninterest").value.replace(/&/g, '$').replace(/,/g, '');
    if (loaninterest === "")
        loaninterest = "0";
    var loanstartdate = document.getElementById("loanstartdate").value.replace(/&/g, '$');
    loanstartdate = loanstartdate.substr(6, 4) + '-' + loanstartdate.substr(3, 2) + '-' + loanstartdate.substr(0, 2);
    var loanenddate = document.getElementById("loanenddate").value.replace(/&/g, '$');
    loanenddate = loanenddate.substr(6, 4) + '-' + loanenddate.substr(3, 2) + '-' + loanenddate.substr(0, 2);
    var repayoption = document.getElementById("repayoption").value.replace(/&/g, '$');
    var amountperrepay = document.getElementById("amountperrepay").value.replace(/&/g, '$').replace(/,/g, '');
    if (amountperrepay === "")
        amountperrepay = "0";
    var loan_amount_in_words = "";
    if (loanamount !== null && loanamount !== "") {
        var naira = "";
        var kobo = "";
        if (loanamount.match(".")) {
            var num_split = loanamount.split('.');
            naira = capAdd(toWords(num_split[0])) + " Naira";
            if (parseInt(num_split[1]) > 0) {
                kobo = capAdd(toWords(num_split[1])) + " Kobo";
            }
        } else {
            naira = capAdd(toWords(loanamount)) + " Naira";
        }
        loan_amount_in_words = naira + " " + kobo + " Only";
    }

    var interest_amount_in_words = "";
    if (loaninterest !== null && loaninterest !== "") {
        var naira = "";
        var kobo = "";
        if (loaninterest.match(".")) {
            var num_split = loaninterest.split('.');
            naira = capAdd(toWords(num_split[0])) + " Naira";
            if (parseInt(num_split[1]) > 0) {
                kobo = capAdd(toWords(num_split[1])) + " Kobo";
            }
        } else {
            naira = capAdd(toWords(loaninterest)) + " Naira";
        }
        interest_amount_in_words = naira + " " + kobo + " Only";
    }

    var error = "";
    if (cardno === "")
        error += "Card No must not be blank.<br><br>";
    if (cardserial === "")
        error += "Card Serial No must not be blank.<br><br>";
    if (lastname === "")
        error += "Last Name must not be blank.<br><br>";
    //if (othernames==="") error += "Other Names must not be blank.<br><br>";
    if (lineno==="") error += "Line No must not be blank.<br><br>";
    if (telephone==="") error += "Phone No must not be blank.<br><br>";
    if (telephone.length!==11) error += "Phone No must be 11 digits.<br><br>";
	//alert(cardno.substr(0,3)+"    "+lineno);
    if (cardno.substr(0,3)==="100"){
		if (cardno.substr(0,3)!==lineno){
			error += "Card No does not belong to the Line No.<br><br>";
		}
	}else{
		if (cardno.substr(0,2)!==lineno){
			error += "Card No does not belong to the Line No.<br><br>";
		}
	}
    if (recordlock === "1")
        error += "Record is locked for this customer, it can not be updated.<br><br>";

    if (error.length > 0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error
        document.getElementById("showError").innerHTML = error;
        $('#showError').dialog('open');
        return true;
    }

    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Processing your record.";
    $('#showAlert').dialog('open');
    var serialno = readCookie("serialno");
    if (serialno === null)
        serialno = "";
    var a_param1 = "&a_param1=" + serialno + "][" + cardno + "][" + lastname + "][" + othernames + "][" + sex + "][" + telephone + "][" + address + "][" + passportpicture + "][" + openingbalance + "][1][][" + commission + "][" + lineno + "][" + cardserial;
    var a_param2 = "&a_param2=][" + cardno + "][" + datedisbursed + "][" + loanamount + "][" + loaninterest + "][" + loanstartdate + "][" + loanenddate + "][" + repayoption + "][" + amountperrepay + "][" + datedisbursed2 + "][" + loan_amount_in_words + "][" + interest_amount_in_words;
    var url = "/dadollar/setupbackend.php?option=" + option + "&table=" + table + a_param1 + a_param2 + "&currentuser=" + readCookie("currentuser") + "&serialno=" + serialno;
//alert(url);	
    AjaxFunctionSetup(url);
}

function lockWithdrawal(id) {
    var cardno = document.getElementById("cardno").value.replace(/&/g, '$');
    var lock = "";
    if (document.getElementById(id).checked === true) {
        lock = "1";
    }
    var url = "/dadollar/setupbackend.php?option=lockWithdrawal&access=" + cardno + "&a_param1=" + lock;
    AjaxFunctionSetup(url);
}

function checkCalcomm() {
    createCookie('access', 'Calculate Commission', false);
    var arg = "&currentuser=" + readCookie("currentuser") + "&menuoption=Calculate Commission";
    var url = "/dadollar/setupbackend.php?option=checkAccess" + arg;
    AjaxFunctionSetup(url);
}

function checkUpdCard() {
    createCookie('access', 'Update Card No', false);
    var arg = "&currentuser=" + readCookie("currentuser") + "&menuoption=Update Card No";
    var url = "/dadollar/setupbackend.php?option=checkAccess" + arg;
    AjaxFunctionSetup(url);
}

function updateTransaction(option, table, transtypes, transgroup) {
    /*if(transtypes==="withdrawal" && document.getElementById("lockwitdrawal").value==="1"){
     document.getElementById("showAlert").innerHTML = "<b>Withdrawal Locked!!!</b><br><br>Withdrawal lock has been placed on this customer.";
     $('#showAlert').dialog('open');
     return true;
     }*/
    var cardno = document.getElementById("cardno").value.replace(/&/g, '$');
    var names = document.getElementById("names").value.replace(/&/g, '$');
    var transdate = document.getElementById("statementdate").value.replace(/&/g, '$');
    var deposit = document.getElementById("deposit").value.replace(/&/g, '$').replace(/,/g, '');
    var withdrawal = document.getElementById("withdrawal").value.replace(/&/g, '$').replace(/,/g, '');
    var transtype = document.getElementById("transtype").value.replace(/&/g, '$');
    var username = document.getElementById("userid").value.replace(/&/g, '$');
    var lineno = document.getElementById("lineid").value.replace(/&/g, '$');
    var narration = document.getElementById("narration").value.replace(/&/g, '$');
    var transno = document.getElementById("transno").value.replace(/&/g, '$');
    //var transtype="deposit";
    //if(withdrawal>"0") transtype="withdrawal";
    var error = "";
    if (cardno.trim() === "")
        error += "Card No name must not be blank.<br><br>";
    if (names.trim() === "")
        error += "You selected a wrong customer.<br><br>";
    if (transdate.trim() === "")
        error += "Date must not be blank.<br><br>";
    if (isNaN(parseFloat(deposit)))
        error += "Deposit must be numeric.<br><br>";
    if (isNaN(parseFloat(withdrawal)))
        error += "Withdrawal must be numeric.<br><br>";
    if (deposit.trim() === "" && withdrawal.trim() === "")
        error += "Deposit and Withdrawal Amount must not be blank.<br><br>";
    if (deposit.trim() > "0" && withdrawal.trim() > "0")
        error += "One of Deposit or Withdrawal Amount must be blank.<br><br>";
    //if (commission.trim()==="" && document.getElementById("commission").disabled===false && readCookie('transtypes')==='deposits') error += "Commission must not be blank.<br><br>";
    //if (narration.trim()==="") error += "Narrations must not be blank.<br><br>";


    /*var date = new Date(readCookie('serverdate'));
     var minimalpostdate = new Date(date);
     minimalpostdate.setDate(minimalpostdate.getDate() - 2);
     
     var dd = minimalpostdate.getDate();
     var mm = minimalpostdate.getMonth() + 1;
     var yy = minimalpostdate.getFullYear();
     
     var minimalpostdate = yy + '-' + ((mm <= 9) ? "0" : "") + mm + '-' + ((dd <= 9) ? "0" : "") + dd;
     
     if (transdate < minimalpostdate && option==="addRecord") error += "Deposit Date must not be less than 2 days before today.<br><br>";
     if (transdate > readCookie('serverdate')) error += "Deposit Date must not be greater than today.<br><br>";*/

    transdate = transdate.substr(6, 4) + '-' + transdate.substr(3, 2) + '-' + transdate.substr(0, 2);

    if (error.length > 0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error
        document.getElementById("showError").innerHTML = error;
        $('#showError').dialog('open');
        return true;
    }

    var fromdate = document.getElementById("fromdate").value.replace(/&/g, '$');
    fromdate = fromdate.substr(6, 4) + '-' + fromdate.substr(3, 2) + '-' + fromdate.substr(0, 2);
    var todate = document.getElementById("todate").value.replace(/&/g, '$');
    todate = todate.substr(6, 4) + '-' + todate.substr(3, 2) + '-' + todate.substr(0, 2);

    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Processing your record.";
    $('#showAlert').dialog('open');
    var serialno = readCookie("serialno");
    if (serialno === null)
        serialno = "";
    var credit = deposit;
    var debit = withdrawal;
    var balance = "0";

    /*var transtype = document.getElementById("transtype").value.replace(/&/g,'$');
     if(transtype===null || transtype===""){
     transtype = transtypes;
     }
     if(transtype==="deposit" || transtype==="loandeposits"){
     credit=amount;
     }else{
     debit=amount;
     }*/
    //var username = readCookie("currentuser");
    var amount = deposit;
    if (withdrawal > "0")
        amount = withdrawal;
    if (narration === "")
        narration = "The sum of " + amount + " being " + transtype + " by " + cardno;
    var a_param1 = "&a_param1=" + serialno + "][" + cardno + "][" + transdate + "][" + narration + "][" + credit + "][" + debit + "][" + balance + "][" + transtype + "][" + username + "][" + transgroup + "][1][" + lineno + "][" + transno;
    //var a_param2 = "&a_param2="+commission+"]["+fromdate+"]["+todate+"]["+description;
    var url = "/dadollar/setupbackend.php?option=" + option + "&table=" + table + a_param1 + "&currentuser=" + readCookie("currentuser") + "&serialno=" + serialno;//+a_param2;
    var msg = "Save Record ?";
    if (option === "updateRecord")
        msg = "Update Record ?";
    if (option === "deleteRecord")
        msg = "Delete Record ?";
    var ans = confirm(msg);
    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Updating your data.";
    $('#showAlert').dialog('open');
    if (ans)
        AjaxFunctionSetup(url);
}

function checkCommission() {
    var cardno = document.getElementById("cardno").value.replace(/&/g, '$');
    var fromdate = document.getElementById("fromdate").value.replace(/&/g, '$');
    fromdate = fromdate.substr(6, 4) + '-' + fromdate.substr(3, 2) + '-' + fromdate.substr(0, 2);
    var todate = document.getElementById("todate").value.replace(/&/g, '$');
    todate = todate.substr(6, 4) + '-' + todate.substr(3, 2) + '-' + todate.substr(0, 2);

    var a_param1 = "&a_param1=" + cardno + "][" + fromdate + "][" + todate;
    var url = "/dadollar/setupbackend.php?option=checkCommission" + a_param1;
    AjaxFunctionSetup(url);
}

function getNames(cardno, names) {
    createCookie('names', names, false);
	//alert(cardno+"    "+names);
    var url = "/dadollar/setupbackend.php?option=getARecord" + "&table=customers&access=" + cardno.replace(/#/g, ' ');
//alert(url);
    AjaxFunctionSetup(url);
}

function getNarration() {
    var amount = document.getElementById("amount").value.replace(/&/g, '$');

    if (isNaN(parseFloat(amount)))
        return true;
    var naira = "";
    var kobo = "";
    if (amount.match(".")) {
        var num_split = amount.split('.');
        naira = capAdd(toWords(num_split[0])) + " Naira";
        if (parseInt(num_split[1]) > 0) {
            kobo = capAdd(toWords(num_split[1])) + " Kobo";
        }
    } else {
        naira = capAdd(toWords(amount)) + " Naira";
    }
    var amount_in_words = naira + " " + kobo + " Only";

    var cardno = document.getElementById("cardno").value.replace(/&/g, '$');
    var names = document.getElementById("names").value.replace(/&/g, '$');
    var transdate = document.getElementById("transdate").value.replace(/&/g, '$');
    var narrations = "";
    if (readCookie('transtypes') === 'deposits') {
        narrations = "The sum of " + amount_in_words + " being deposit made by (" + cardno + ") - " + names + " on " + transdate;
    } else if (readCookie('transtypes') === 'loandeposits') {
        narrations = "The sum of " + amount_in_words + " being loan deposit made by (" + cardno + ") - " + names + " on " + transdate;
    } else if (readCookie('transtypes') === 'withdrawals') {
        narrations = "The sum of " + amount_in_words + " being withdrawal from (" + cardno + ") - " + names + " on " + transdate;
    }
    document.getElementById("narration").value = narrations;
}

function getDescription() {
    var commission = document.getElementById("commission").value.replace(/&/g, '$').trim();
    if (isNaN(parseFloat(commission)))
        return true;

    var naira = "";
    var kobo = "";
    if (commission.match(".")) {
        var num_split = commission.split('.');
        naira = capAdd(toWords(num_split[0])) + " Naira";
        if (parseInt(num_split[1]) > 0) {
            kobo = capAdd(toWords(num_split[1])) + " Kobo";
        }
    } else {
        naira = capAdd(toWords(commission)) + " Naira";
    }
    var amount_in_words = naira + " " + kobo + " Only";

    var cardno = document.getElementById("cardno").value.replace(/&/g, '$');
    var names = document.getElementById("names").value.replace(/&/g, '$');
    var transdate = document.getElementById("transdate").value.replace(/&/g, '$');
    var description = "The sum of " + amount_in_words + " being commision charged to (" + cardno + ") - " + names + " on " + transdate;
    document.getElementById("description").value = description;
    //document.getElementById("narration").value = description;
}

function lockRecords(table, id, lock) {
    createCookie("checkid", id);
    /*var a_param2="";
     for(var i=0; i < myCheckboxes; i++){
     var checkboxid="box"+(i);
     if(document.getElementById(checkboxid).checked===true){
     document.getElementById(checkboxid).checked=false;
     }else{
     document.getElementById(checkboxid).checked=true;
     }
     }
     var k=0;
     for(var i=0; i < myCheckboxes; i++){
     var checkboxid="box"+(i);
     var hiddenid="hidden"+(i);
     k=i+1;
     serialnoid="serialnoid"+k;
     serialnovalue=document.getElementById(serialnoid).value.replace(/&/g,'$');
     if(document.getElementById(checkboxid).checked===true){
     lockvalue="1";
     }else{
     lockvalue="";
     }
     a_param2 +=serialnovalue+"!!!"+lockvalue+"_~_";
     }
     if(a_param2.length===0) {
     error = "<br><b>Please correct the following errors:</b> <br><br><br>Please select some Records to lock: ";
     document.getElementById("showError").innerHTML = error;
     $('#showError').dialog('open');
     return true;
     }
     a_param2 = "&a_param2="+a_param2;*/
    var url = "/dadollar/setupbackend.php?option=lockrecords&table=" + table + "&access=" + lock;
    if (table === "customers") {
        createCookie("searchid", document.getElementById("search").value.replace(/&/g, '$'), false);
        createCookie("sexid", document.getElementById("sexid").value.replace(/&/g, '$'), false);
    }
    if (lock === "1") {
        document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Locking your records.";
    }
    if (lock === "") {
        document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Unlocking your records.";
    }
    $('#showAlert').dialog('open');
//alert(url);
    AjaxFunctionSetup(url);
}

function populateLockRecords(arg) {
    myCheckboxes = 0;
    var row_split = arg.split('getAllRecs');
    var str = "<table style='border-color:#fff;border-style:solid;border-width:1px; height:10px;width:100%;background-color:#336699;margin-top:5px;'>";
    str += "<tr style='font-weight:bold; color:#ffffff'>";
    str += "<td>S/No</td><td>Card No</td><td>Names</td><td>Trans.&nbsp;Date</td><td>Narrations</td><td align='right'>Credit</td><td align='right'>Debit</td><td align='right'>Balance</td>";
    str += "<td><input type='checkbox' id='selectall' onclick=lockRecords('transactions',this.id)>&nbsp;Lock/Unlock</td></tr>";
    var flag = 0;
    var serialnoid = "";
    var serialnovalue = "";
    var cardno = "";
    var names = "";
    var transdate = "";
    var narration = "";
    var credit = "";
    var debit = "";
    var balance = "";
    var checkboxid = "";
    var hiddenid = "";
    var col_split = "";
    var lockid = "";
    var lockvalue = "";
    var count = 0;
    var k = 1;
    for (k = 1; k < row_split.length - 1; k++) {
        col_split = row_split[k].split('_~_');
        if (flag === 0) {
            str += "<tr style='font-weight:bold; color:#999999;background-color:#FFFFFF;'>";
            flag = 1;
        } else {
            str += "<tr style='font-weight:bold; color:#FFFFFF;background-color:#999999;'>";
            flag = 0;
        }
        serialnoid = "serialnoid" + k;
        serialnovalue = col_split[0];
        cardno = col_split[1];
        names = col_split[2];
        transdate = col_split[3];
        var newdate = transdate.substr(0, 10);
        newdate = newdate.split("-");
        transdate = newdate[2] + "/" + newdate[1] + "/" + newdate[0];
        narration = col_split[4];
        credit = numberFormat(col_split[5]);
        debit = numberFormat(col_split[6]);
        balance = numberFormat(col_split[7]);
        lockid = "lockidB" + k;
        lockvalue = col_split[8];
        checkboxid = "box" + (k - 1);
        hiddenid = "hidden" + (k - 1);

        str += "<input type='hidden' value='" + serialnovalue + "' id='" + serialnoid + "' /></td>";

        str += "<td align='right'>" + (++count) + ".</td>";
        str += "<td>" + cardno + "</td>";
        str += "<td>" + names + "</td>";
        str += "<td>" + transdate + "</td>";
        str += "<td>" + narration + "</td>";
        str += "<td align='right'>" + credit + "</td>";
        str += "<td align='right'>" + debit + "</td>";
        str += "<td align='right'>" + balance + "</td>";
        myCheckboxes++;
        serialnovalue = serialnovalue.replace(/ /g, '_');
        if (lockvalue === "1") {
            str += "<td><input type='checkbox' id='" + checkboxid + "' checked onclick=updateLocks('" + serialnovalue + "','" + lockvalue + "','transactions'); >&nbsp;[Locked]<input type='hidden' id='" + hiddenid + "' value='" + serialnovalue + "'></td></tr>";
        } else {
            str += "<td><input type='checkbox' id='" + checkboxid + "' onclick=updateLocks('" + serialnovalue + "','" + lockvalue + "','transactions'); >&nbsp;[Open]<input type='hidden' id='" + hiddenid + "' value='" + serialnovalue + "'></td></tr>";
        }
    }
    str += "</table>";
    document.getElementById('listrecords').innerHTML = str;
}

function updateLocks(serialno, lock, table, id) {
    createCookie("checkid", id);
    if (lock === "1") {
        lock = "";
    } else {
        lock = "1";
    }
    var a_param1 = "&a_param1=" + serialno + "][" + lock;
    var url = "/dadollar/setupbackend.php?option=updateLocks&table=" + table + a_param1;
    if (table === "customers") {
        createCookie("searchid", document.getElementById("search").value.replace(/&/g, '$'), false);
        createCookie("sexid", document.getElementById("sexid").value.replace(/&/g, '$'), false);
    }
    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Locking/Unlocking your data.";
    $('#showAlert').dialog('open');
    AjaxFunctionSetup(url);
}

function getRecords(table) {
//alert(table);
    createCookie('tabletype', null, false);
	var cardnumber = document.getElementById("cardno").value;
    if (readCookie('transtypes') === 'withdrawals')
        createCookie('cardnumber', document.getElementById("cardno").value.replace(/&/g, '$'), false);
    if (readCookie('transtypes') === 'loandeposits')
        createCookie('cardnumber', document.getElementById("cardno").value.replace(/&/g, '$'), false);
    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Fetching your data.";
    $('#showAlert').dialog('open');
	var url = "/dadollar/setupbackend.php?option=getAllRecs" + "&table=" + table; // + "&cardnumber=" + cardnumber;
//alert(url);
    AjaxFunctionSetup(url);
}

function getAllBySex(id) {
    createCookie('tabletype', 'customers3', false);
    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Fetching your data.";
    $('#showAlert').dialog('open');
    var sex = document.getElementById(id).value.replace(/&/g, '$');
    var url = "/dadollar/setupbackend.php?option=getAllRecs&table=customers3&serialno=" + sex;
    createCookie('sexvalue', sex, false);
    AjaxFunctionSetup(url);
}

function populateRecords(serialno, table) {
    if (list_obj !== null)
        clearLists(list_obj);
    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Fetching your data.";
    $('#showAlert').dialog('open');
    var url = "/dadollar/setupbackend.php?option=getARecord" + "&table=" + table + "&serialno=" + serialno;
//alert(url);    
	AjaxFunctionSetup(url);
}

function searchCustomer(id, table) {
    createCookie('tabletype', table, false);
    createCookie('lineno', document.getElementById('lineno').value.replace(/&/g, '$'), false);
    createCookie('owner', document.getElementById('username').value.replace(/&/g, '$'), false);
    var transdate = document.getElementById('transdate').value.replace(/&/g, '$');
    transdate = transdate.substr(6, 4) + '-' + transdate.substr(3, 2) + '-' + transdate.substr(0, 2);
    //var fromdate = document.getElementById('fromdate').value.replace(/&/g,'$');
    //fromdate = fromdate.substr(6,4)+'-'+fromdate.substr(3,2)+'-'+fromdate.substr(0,2);
    //var todate = document.getElementById('todate').value.replace(/&/g,'$');
    //todate = todate.substr(6,4)+'-'+todate.substr(3,2)+'-'+todate.substr(0,2);
    createCookie('transdate', transdate, false);
    //createCookie('date1', fromdate, false);
    //createCookie('date2', todate, false);
    var searchstr = document.getElementById(id).value.replace(/&/g, '$');
    createCookie('searchstr', searchstr, false);
    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>searching your data.";
    $('#showAlert').dialog('open');
    var url = "/dadollar/setupbackend.php?option=getAllRecs&table=" + table + "&serialno=" + searchstr;
    AjaxFunctionSetup(url);
}

function searchCustomer2(id, table) {
    createCookie('tabletype', table, false);
    var searchstr = document.getElementById(id).value.replace(/&/g, '$');
    createCookie('searchstr', searchstr, false);
    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>searching your data.";
    $('#showAlert').dialog('open');
    var url = "/dadollar/setupbackend.php?option=getAllRecs&table=" + table + "&serialno=" + searchstr;
    AjaxFunctionSetup(url);
}

function getRecordlist(arg2, arg3, arg4) {
	curr_obj = document.getElementById(arg2);
	    
    if ((curr_obj.value === null || curr_obj.value === "" || curr_obj.value.length < 4) && curr_obj.id.substr(0, 6) === "cardno"){
        return true;
	}
	//alert(document.getElementById("lineno").value);
	if(document.getElementById("lineno")){
		var lineno = document.getElementById("lineno").value.replace(/&/g, '$');
		var cardno = curr_obj.value;
		if(lineno!=='WT'){
			/*if (cardno.substr(0,3)==="100"){
				if (cardno.substr(0,3)!==lineno){
					alert("The Card No [ "+cardno+" ] does not belong to the Line No [ "+lineno+" ].");
					curr_obj.value = '';
					return true;
				}
			}else{*/
				if (cardno.substr(0,2)!==lineno && lineno!=='100' && cardno.substr(0,2)!=="" && arg2 != "linenoid"){
					alert("The Card No [ "+cardno+" ] does not belong to the Line No [ "+lineno+" ].");
					curr_obj.value = '';
					return true;
				}
			//}
		}
	}
    //alert(curr_obj.value);
    temp_table = arg3;
    list_obj = arg4;
    var url = "/dadollar/setupbackend.php?option=getRecordlist&table=" + arg3 + "&currentobject=" + arg2 + "&serialno=" + curr_obj.value;
//alert(url);
    AjaxFunctionSetup(url);
}

function populateCardno(curr_obj) {
    if (curr_obj.id === "cardno") {
        getNames(curr_obj.value, "names");
    } else if (curr_obj.id.substr(0, 8) === "cardnoid") {
        var name = "nameid" + curr_obj.id.substr(8, curr_obj.id.length);
		//alert(curr_obj.value+", "+name);
        getNames(curr_obj.value, name);
        //setTimeout(function () {						
        //	populateCode(curr_obj.value);
        //}, 2000);

    }
}

function populateCode(code) {
//alert(code);
    curr_obj.value = code.replace(/#/g, ' ');
	//alert(curr_obj.id);
    clearLists(list_obj);
    if (curr_obj.id === "cardno") {
        getNames(code, "names");
    }
    if (curr_obj.id === "cardno1") {
        getNames(code, "names1");
    }
    if (curr_obj.id === "cardno2") {
        getNames(code, "names2");
    }
    if (curr_obj.id.substr(0, 8) === "cardnoid") {
        var name = "nameid" + curr_obj.id.substr(8, curr_obj.id.length);
        getNames(code, name);
    }
}

/*function populateCode(code){
 curr_obj.value = code.replace(/#/g,' ');
 clearLists(list_obj);
 }*/

function clearLists(arg) {
    if (arg === null ||arg === undefined)
        arg = list_obj;
    var codeslist = document.getElementById(arg);
    codeslist.style.border = "0px";
    document.getElementById(arg).innerHTML = "";
}

function resetForm(table) {
    if (table === "customers") {
//alert("customers");
        document.getElementById("cardserial").value = "";
        document.getElementById("cardno").value = "";
        document.getElementById("lineno").value = "";
        document.getElementById("cardno").disabled = false;
        //document.getElementById("cardserial").disabled = false;
        document.getElementById("lastname").value = "";
        document.getElementById("othernames").value = "";
        document.getElementById("sex").value = "";
        document.getElementById("telephone").value = "";
        document.getElementById("openingbalance").value = "";
        document.getElementById("lockwitdrawal").checked = false;
        document.getElementById("recordlock").value = "";
        document.getElementById("address").value = "";
        document.getElementById("datedisbursed").value = "";
        document.getElementById("loanamount").value = "";
        document.getElementById("loaninterest").value = "";
        document.getElementById("loanstartdate").value = "";
        document.getElementById("loanenddate").value = "";
        document.getElementById("repayoption").value = "";
        document.getElementById("amountperrepay").value = "";
        loadImage('silhouette.jpg');
        createCookie("theImage", 'silhouette.jpg', false);
    }
    if (table === "deposits") {
        document.getElementById("cardno").value = "";
        document.getElementById("cardno").disabled = false;
        document.getElementById("cardserial").value = "";
        //document.getElementById("cardserial").disabled = false;
        document.getElementById("names").value = "";
        //document.getElementById("transdate").value="";
        document.getElementById("amount").value = "";
        document.getElementById("narration").value = "";
        document.getElementById("transtype").value = "";
        document.getElementById("commission").value = "";
        document.getElementById("description").value = "";
        loadImage('silhouette.jpg');
        createCookie("theImage", 'silhouette.jpg', false);
        document.getElementById("cardno").focus();
    }
    if (table === "withdrawals") {
        document.getElementById("cardno").value = "";
        document.getElementById("cardno").disabled = false;
        document.getElementById("cardserial").value = "";
        //document.getElementById("cardserial").disabled = false;
        document.getElementById("names").value = "";
        //document.getElementById("transdate").value="";
        document.getElementById("amount").value = "";
        document.getElementById("narration").value = "";
        document.getElementById("transtype").value = "";
        document.getElementById("commission").value = "";
        document.getElementById("description").value = "";
        loadImage('silhouette.jpg');
        createCookie("theImage", 'silhouette.jpg', false);
        document.getElementById("cardno").focus();
    }
    if (table === "statements") {
        document.getElementById("cardno").value = "";
        document.getElementById("cardno").disabled = false;
        //document.getElementById("cardserial").value = "";
        //document.getElementById("cardserial").disabled = false;
        document.getElementById("names").value = "";
        document.getElementById("statementdate").value = "";
        document.getElementById("deposit").value = "";
        document.getElementById("balance").value = "";
        document.getElementById("withdrawal").value = "";
        document.getElementById("transtype").value = "";
        document.getElementById("userid").value = "";
        document.getElementById("openbalances").innerHTML = "";
        loadImage('silhouette.jpg');
        createCookie("theImage", 'silhouette.jpg', false);
        document.getElementById("cardno").focus();
    }
    if (table === "loandeposits") {
        document.getElementById("cardno").value = "";
        document.getElementById("cardno").disabled = false;
        //document.getElementById("cardserial").disabled = false;
        document.getElementById("names").value = "";
        //document.getElementById("transdate").value="";
        document.getElementById("amount").value = "";
        document.getElementById("narration").value = "";
        document.getElementById("transtype").value = "";
        document.getElementById("commission").value = "";
        document.getElementById("description").value = "";
        loadImage('silhouette.jpg');
        createCookie("theImage", 'silhouette.jpg', false);
        document.getElementById("cardno").focus();
    }
}

function showPic(obj, pic, pos) {
    var codeslist = document.getElementById('mypic');
    codeslist.style.zIndex = 100;
    codeslist.style.position = "absolute";
    codeslist.innerHTML = "<img src='photo/" + pic + "'  border='1' width='150' height='150' title='Picture' alt='Applicant`s Passport'/>";

    if (pos === 'down') {
        codeslist.style.top = ($(document.getElementById(obj)).position().top + 25) + 'px';
    } else {
        codeslist.style.top = ($(document.getElementById(obj)).position().top - 155) + 'px';
    }
    codeslist.style.left = ($(document.getElementById(obj)).position().left) + 'px';
}

function clearPic() {
    document.getElementById('mypic').innerHTML = "";
}

function clearCurrentRecord() {
    var url = "/dadollar/dataentrybackend.php?option=clearCurrentRecord";
    AjaxFunctionSetup(url);
}

function updateCheckBox(){
	document.getElementById("showPrompt").innerHTML = "Please wait..................";
	$('#showPrompt').dialog('open');
	for(var i=0; i < totalrecords; i++){
		var checkbox = 'box' + i;
		if(document.getElementById(checkbox).checked){
			document.getElementById(checkbox).checked = false;
		}else{
			document.getElementById(checkbox).checked = true;
		}
	}
	$('#showPrompt').dialog('close');
}

function getCharNo() {
	var charlen = document.getElementById("textmsg").value.length;
	document.getElementById("char_used").innerHTML = charlen;
	if(charlen <= 160){
		sms_used = 1;
	}else{
		var num = Math.floor(charlen / 160);
		var den = (charlen % 160);
		console.log("num  "+num);
		console.log("den  "+den);
		if(den > 0.0){
			den = 1;
		}
		sms_used = num + den;
	}
	document.getElementById("sms_used").innerHTML = sms_used;
}

function showCurrentId() {
	$.ajax({
		type: "GET",
		url: "/dadollar/setupbackend.php",
		data: {option: "showCurrentId" },
		dataType: "text",
		cache: false,
		async: false,
		success: function (resp) {
			var break_resp = resp.split("showCurrentId");
            document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Sending SMS to customers............</b><br><br>Processing: " + '<?php echo $_SESSION["currentid"] ?>';
            $('#showAlert').dialog('open');
		},
		error: function (a, b, c) {
			alert("a: " + a + " b: " + b + " c: " + c);
		}
	});
}

function AjaxFunctionSetupSMS(arg) {
	var arg = arg.split("?");
	datas = {};
	var arg2 = arg[1].split("&");
	for(var count=0; count < arg2.length; count++){
		var arg3 = arg2[count].split("=");
		//alert(arg3[0]+"="+arg3[1]);
		datas[arg3[0]] = arg3[1];
	}
    datas['selectedIdsArray'] = selectedIdsArray;
	$.ajax({
		type: "POST",
		url: arg[0],
		data: datas,
		dataType: "text",
		cache: false,
		async: false,
		success: function stateChangedSetupSMS(resp) {
			if (resp.match("listSMSTransaction")) {
				$('#showAlert').dialog('close');
				listSMSTransaction();
				return true;
			}
			if (resp.trim().match("show_excel")) {
				oWin = window.open("excelreport.php?resp=" + resp, "_blank", "directories=0,scrollbars=1,resizable=1,location=0,status=0,toolbar=0,menubar=0,width=800,height=500,left=100,top=100");
				$('#showAlert').dialog('close');
				if(processedIds < totalrecords && processedIds !== firstdId && lastId < totalrecords){
					firstdId=0;
					sendSMS();
				}else{
					alert("Messages successfully sent for...........1 - "+processedIds+"/"+totalrecords);
					$('#showAlert').dialog('close');
				}
				return true;
			} else if (resp.trim().match("fail")) {
				alert("Invalid Phone Numbers!!!\n\nSome phone numbers are not valid");
				$('#showAlert').dialog('close');
				return true;
			} else{
				alert("Error!!!\n\n"+resp);
				$('#showAlert').dialog('close');
			}
		

			/*if (resp.trim().match("Insufficient")) {
				alert("Insufficient Credits!!!\n\nYou do not have sufficient credits to send messages");
				$('#showAlert').dialog('close');
				return true;
			}
			
			if (resp.trim().match("Invalid")) {
				alert("No Internet Connection!!!\n\nYour computer is not connected to the internet");
				$('#showAlert').dialog('close');
				return true;
			
			
			if (resp.trim().match("nointernet")) {
				$('#showAlert').dialog('close');
				alert("No Internet Connection!!!\n\nYour computer is not connected to the internet"+resp);
				return true;
			}
			
			if (resp.match("myresponse")) {
				alert("Message Failed\n\nSMS Server not accessible!!!");
				$('#showAlert').dialog('close');
				return true;
			}

			if (resp.trim().match("smsmsg")) {
//alert("totalrecord: "+totalrecords+"      processedIds: "+processedIds+"      firstdId: "+firstdId+"      lastId: "+lastId);
				oWin = window.open("excelreport.php?resp=" + resp, "_blank", "directories=0,scrollbars=1,resizable=1,location=0,status=0,toolbar=0,menubar=0,width=800,height=500,left=100,top=100");
				$('#showAlert').dialog('close');
				if(processedIds < totalrecords && processedIds !== firstdId && lastId < totalrecords){
					firstdId=0;
					sendSMS();
				}else{
					alert("Messages successfully sent for...........1 - "+processedIds+"/"+totalrecords);
					$('#showAlert').dialog('close');
				}
			}}*/
			
		},
		error: function (a, b, c) {
			alert("a: " + a + " b: " + b + " c: " + c);
		}
	});
}

var xmlhttp
function AjaxFunctionSetup(arg) {
//alert(arg);
    xmlhttp = GetXmlHttpObject();
    if (xmlhttp === null) {
        alert("Your browser does not support XMLHTTP!");
        return true;
    }

    var timestamp = new Date().getTime();
    var url = arg + "&timestamp=" + timestamp;
//alert(url);
//document.getElementById('txt').value=url;
//document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Processing Card No: " + url;
//$('#showAlert').dialog('open');
    xmlhttp.onreadystatechange = stateChangedSetup;
    xmlhttp.open("GET", url, true);
    xmlhttp.send(null);
}
var totalrecords = 0;
var temp_resp = "";
function stateChangedSetup() {
    if (xmlhttp.readyState === 4) {
        var resp = xmlhttp.responseText;
        var break_resp = "";

		//alert(resp);
        //document.getElementById("showPrompt").innerHTML = resp;
        //$('#showPrompt').dialog('open');
		if (resp === "") {
            resp = readCookie("currentoperation");
        } else {
			$('#showAlert').dialog('close');
			$('#showAlerts').dialog('close');
            if(resp==='archived'){
				alert("Data archiving completed!!!");
				return true;
			}
        }

        //if(resp.match("myquery")){
        //	document.getElementById("showPrompt").innerHTML = resp;
        //	$('#showPrompt').dialog('open');
        //}

        //if (resp.match("myquery")) {
		//	alert("resp   "+resp);
		//}
        if (resp.match("checkAccess")) {
            if (resp.match("checkAccessSuccess")) {
//alert(readCookie('access'));
                if (readCookie('access').match('divcodechangeid')) {
                    document.getElementById(readCookie('access')).innerHTML = "<input style='display:inline' type='text' id='newcodeid' onblur=updateCodeChange(this.id) size='10' />";
                    document.getElementById('newcodeid').focus();
                } else if (readCookie('access').match('Calculate Commission')) {
                    calculateCommission();
                } else if (readCookie('access').match('Update Card No')) {
                    showUpdateCard();
                } else {
                    window.location = "home.php?pgid=1";
                }
            } else {
                break_resp = resp.split("checkAccessFailed");
                resp = "Access Denied!!!\n\nMenu [" + break_resp[1] + "] not accessible by " + readCookie("currentuser");
                if (break_resp[1] === "Lock Withdrawal") {
                    if (document.getElementById("lockwitdrawal").checked === false) {
                        document.getElementById("lockwitdrawal").checked = true;
                    } else {
                        document.getElementById("lockwitdrawal").checked = false;
                    }
                }
                alert(resp);
                //document.getElementById("showPrompt").innerHTML = resp;
                //$('#showPrompt').dialog('open');
            }
        }
        if (resp.match("editCourseCode")) {
            editCourseCode();
        }

        if (resp.match("readCookies")) {
            var currentrecord = resp.split("readCookies");
            var break_resp = currentrecord[1].split("_-_");
            var percentage = parseInt((parseInt(break_resp[2]) / parseInt(break_resp[1])) * 100);
            if (isNaN(percentage))
                percentage = 0;
            document.getElementById(break_resp[0]).innerHTML = break_resp[2] + "/" + break_resp[1] + "&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;" + percentage + " % "; //+break_resp[4];
            if (break_resp[3] === 'Y') {
                clearCurrentRecord();
            }
        }

        if (resp.match("unposted")){
			var break_resp = resp.split("unposted");
			document.getElementById("showPrompt").innerHTML = "<b>"+break_resp[1]+"</b>";
            $('#showPrompt').dialog('open');
			return true;
		}
            
        if (resp.match("showCurrentCardno")) {
			//alert(resp);
			if (resp=="showCurrentCardno") {
				clearInterval(myVar);
                $('#showAlert').dialog('close');
				alert("Transaction posting stopped!!!");
				return true;
			}
			var break_resp = resp.split("showCurrentCardno");
            document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Processing Card No: " + break_resp[1];
            $('#showAlert').dialog('open');
            if (break_resp[1] === readCookie("currentCardno")) {
                duplicatecounter++;
                if (duplicatecounter >= 100) {
                    //document.getElementById("showAlert").innerHTML = "Commission calculation completed!!!";
                    //$('#showAlert').dialog('open');
                    alert("Transaction posting stopped!!!");
                    clearInterval(myVar);
                    $('#showAlert').dialog('close');
                }
            } else {
                duplicatecounter = 0;
                createCookie("currentCardno", break_resp[1], false);
            }
        }

        if (resp.match("commissioncompleted")) {
            clearInterval(myVar);
            alert("Commission calculation completed!!!");
        }

        if (resp.match("myStopFunction")) {
            myStopFunction();
        }

//alert(resp);
//alert(resp.length);

		if (resp.match("listTransaction") || resp.match("changeDate") || resp.match("deleteLine") || resp.match("changeLineno")) {
/*document.getElementById("showPrompt").innerHTML = resp;
$('#showPrompt').dialog('open');
alert(resp);*/
			createCookie("currentoperation", null, false);
			listTransaction();
			return true;
		}
//console.log(resp);
//alert(resp);        
        if (resp.match("getAllRecs")) {
//document.getElementById("showPrompt").innerHTML = resp;
//$('#showPrompt').dialog('open');

            eraseCookie("ordersort", null, false);
            break_resp = resp.split("getAllRecs");
            var allrecords = "<table border='1' style='border-color:#fff;border-style:solid;border-width:1px; height:10px;width:100%;background-color:#006699;margin-top:5px;'>";
            allrecords += "<tr style='font-weight:bold; color:white'>";
            if (break_resp[0] === "customers") {
 //alert(resp);
               allrecords += "<td>S/No</td><td>Card No</td><td>Last Name</td><td>Other Names</td><td>Sex&nbsp;<select id='sexid' onchange='getAllBySex(this.id)'><option></option><option>Male</option><option>Female</option></select></td><td>Phone No</td><td>Address</td><td>Passport</td><td width='10%'><input type='checkbox' id='selectall1' onclick=lockRecords('customers',this.id,'1')>&nbsp;Lock&nbsp;All<br><input type='checkbox' id='selectall2' onclick=lockRecords('customers',this.id,'')>&nbsp;Unlock&nbsp;All</td></tr>";
            } else if (break_resp[0] === "deposits") {
                allrecords += "<td>S/No</td><td>Card No</td><td>Names</td><td>Trans.&nbsp;Date</td><td>Narrations</td><td align='right'>Deposit</td></tr>";
            } else if (break_resp[0] === "withdrawals") {
                allrecords += "<td>S/No</td><td>Card No</td><td>Names</td><td align='center'>Trans.&nbsp;Date</td><td align='right'>Credit</td><td align='right'>Debit</td><td align='right'>Balance</td><td>Trans.&nbsp;Type</td><td>Card&nbsp;Serial&nbsp;No</td></tr>";
            } else if (break_resp[0] === "loandeposits") {
                allrecords += "<td>S/No</td><td>Card No</td><td>Names</td><td>Trans.&nbsp;Date</td><td>Narrations</td><td align='right'>Deposit</td><td align='right'>Withdrawal</td><td align='right'>Balance</td></tr>";
            } else if (break_resp[0] === "listCustomers") {
                allrecords += "<td>S/No</td><td>Line&nbsp;No</td><td>Card&nbsp;No</td><td>Names</td><td>Phone&nbsp;No</td><td>Select&nbsp;All&nbsp;<input type='checkbox' id='selectall1' onclick='updateCheckBox();' ></td></tr>";
            } else if (break_resp[0] === "lockrecords") {
                populateLockRecords(resp);
                return true;
            } else if (break_resp[0] === "transactionlist") {
                populateTransactions(resp);
                return true;
            } else if (break_resp[0] === "transactionlist2") {
				//alert(resp);
				listTransactions(resp);
                return true;
            } else if (break_resp[0] === "smstransactionlist") {
                smstransactionlist(resp);
                return true;
            }
            var recordlist = null;
            if (break_resp[0] === "customers")
                recordlist = document.getElementById('listcustomers');
            if (break_resp[0] === "deposits")
                recordlist = document.getElementById('listdeposits');
            if (break_resp[0] === "withdrawals")
                recordlist = document.getElementById('listwithdrawals');
            if (break_resp[0] === "loandeposits")
                recordlist = document.getElementById('listloandeposits');
            if (break_resp[0] === "listCustomers"){
                recordlist = document.getElementById('custlist');
					
			}
            var counter = 0;
            var rsp = "";
            var flg = 0;
            var break_row = "";
            var compare1 = "customers deposits withdrawals loandeposits listCustomers ";
            var compare2 = "customers deposits withdrawals loandeposits listCustomers ";
            var compare3 = "customers deposits withdrawals loandeposits ";
            var compare4 = "customers withdrawals loandeposits listCustomers ";
            var compare5 = "customers withdrawals loandeposits ";
            var compare6 = "customers withdrawals ";
            myCheckboxes = 0;
            var serialnovalue = "";
            var serialnoid = "";
            var checkboxid = "";
            var hiddenid = "";
            var lockvalue = "";
            var serialnoid = "";
            var totaldeposit = 0;
            var totalwithdrawals = 0;
			
            for (var i = 1; i < (break_resp.length - 1); i++) {
				totalrecords = i;
                break_row = break_resp[i].split("_~_");

                if (flg === 1) {
                    flg = 0;
                    rsp += "<tr style='font-weight:bold; color:#999999;background-color:#FFFFFF;'>";
                } else {
                    flg = 1;
                    rsp += "<tr style='font-weight:bold; color:#FFFFFF;background-color:#999999;'>";
                }

                rsp += "<td align='right'>" + (++counter) + ".</td>";
                if (break_resp[0] === "withdrawals" || break_resp[0] === "loandeposits") {
                    //if(break_row[8]==='deposit'){
                    //	rsp += "<td>" + break_row[1]+"<br>"+((break_row[9]==="1") ? "[Locked]" : "[Open]")+"</td>";
                    //}else{ "+"<br>"+((break_row[9]==="1") ? "[Locked]" : "[Open]")+"
                    rsp += "<td><a href=javascript:populateRecords('" + break_row[0] + "','" + break_resp[0] + "')>" + break_row[1] + "</a></td>";
                    //}
                } else if (break_resp[0] === "deposits") {
                    if (break_row[8] === "withdrawal" && break_row[6] === 'commission') {
                        rsp += "<td>" + break_row[1] + "<br>" + ((break_row[9] === "1") ? "[Locked]" : "[Open]") + "</td>";
                    } else {
                        rsp += "<td><a href=javascript:populateRecords('" + break_row[0] + "','" + break_resp[0] + "')>" + break_row[1] + "</a>" + "<br>" + ((break_row[9] === "1") ? "[Locked]" : "[Open]") + "</td>";
                    }
                } else {
                    rsp += "<td><a href=javascript:populateRecords('" + break_row[0] + "','" + break_resp[0] + "')>" + break_row[1] + "</a></td>";
                }
                rsp += "<td>" + break_row[2] + "</td>";
                if (compare1.match(break_resp[0])) {
                    if (break_resp[0] === "withdrawals" || break_resp[0] === "deposits" || break_resp[0] === "loandeposits") {
                        var newdate = break_row[3].substr(0, 10);
                        newdate = newdate.split("-");
                        break_row[3] = newdate[2] + "/" + newdate[1] + "/" + newdate[0];
                        rsp += "<td align='center'>" + break_row[3] + "</td>";
                    } else {
                        rsp += "<td>" + break_row[3] + "</td>";
                    }
                }
                if (compare2.match(break_resp[0])) {
                    if (break_resp[0] === "withdrawals" || break_resp[0] === "loandeposits") {
                        rsp += "<td style='text-align:right;'>" + numberFormat(break_row[4]) + "</td>";
                        totaldeposit += parseFloat(break_row[4]);
                    } else {
                        rsp += "<td>" + break_row[4] + "</td>";
                    }
                }
                if (compare3.match(break_resp[0])) {
                    if (break_resp[0] === "deposits") {
                        rsp += "<td style='text-align:right;'>" + numberFormat(break_row[5]) + "</td>";
                        totaldeposit += parseFloat(break_row[5]);
                    } else if (break_resp[0] === "withdrawals" || break_resp[0] === "loandeposits") {
                        rsp += "<td style='text-align:right;'>" + numberFormat(break_row[5]) + "</td>";
                        totalwithdrawals += parseFloat(break_row[5]);
                    } else {
                        rsp += "<td>" + break_row[5] + "</td>";
                    }
                }
                if (compare4.match(break_resp[0])) {
                    if (break_resp[0] === "deposits") {
                    } else if (break_resp[0] === "withdrawals" || break_resp[0] === "loandeposits") {
                        if (parseFloat(break_row[6]) < 0) {
                            rsp += "<td style='text-align:right; color: #FF0033;'>" + numberFormat(break_row[6]) + "</td>";
                        } else {
                            rsp += "<td style='text-align:right;'>" + numberFormat(break_row[6]) + "</td>";
                        }
                    } else if (break_resp[0] === "listCustomers") {
						checkboxid = "box" + (i - 1);
						hiddenid = "hidden" + (i - 1);
						serialnoid = "serialnoid" + i;
                        serialnovalue = break_row[0];
						//console.log("break_row[0] "+break_row[0]+"   "+"break_row[1] "+break_row[1]+"   "+"break_row[2] "+break_row[2]+"   "+"break_row[3] "+break_row[3]+"   "+"break_row[4] "+break_row[4]+"   "+"break_row[5] "+break_row[5]+"   ");
                        rsp += "<td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type='checkbox' id='" + checkboxid + "'><input type='hidden' id='" + hiddenid + "' value='" + serialnovalue + "'></td></tr>";
                    } else {
                        rsp += "<td>" + break_row[6] + "</td>";
                    }
                }
                if (compare5.match(break_resp[0])) {
                    if (break_resp[0] === "customers") {
                        rsp += "<td><div id='" + counter + "' style='cursor: pointer;' onmouseout=clearPic() onmouseover=showPic('" + counter + "','" + break_row[7] + "','up')>" + break_row[7] + "</div></td>";
                    } else if (break_resp[0] === "deposits") {
                    } else {
                        rsp += "<td>" + break_row[7] + "</td>";
                    }
                }
                if (compare6.match(break_resp[0])) {
                    if (break_resp[0] === "customers") {
                        lockvalue = break_row[8];
                        checkboxid = "box" + (i - 1);
                        hiddenid = "hidden" + (i - 1);
                        serialnoid = "serialnoid" + i;
                        serialnovalue = break_row[0];

                        rsp += "<input type='hidden' value='" + serialnovalue + "' id='" + serialnoid + "' />";
                        myCheckboxes++;
                        if (lockvalue !== null && lockvalue === "1") {
                            rsp += "<td><input type='checkbox' id='" + checkboxid + "' checked onclick=updateLocks('" + serialnovalue + "','" + lockvalue + "','customers','" + checkboxid + "'); >&nbsp;[Locked]<input type='hidden' id='" + hiddenid + "' value='" + serialnovalue + "'></td></tr>";
                        } else {
                            rsp += "<td><input type='checkbox' id='" + checkboxid + "' onclick=updateLocks('" + serialnovalue + "','" + lockvalue + "','customers','" + checkboxid + "'); >&nbsp;[Open]<input type='hidden' id='" + hiddenid + "' value='" + serialnovalue + "'></td></tr>";
                        }
                    } else if (break_resp[0] === "withdrawals"){
                        rsp += "<td>" + break_row[9] + "</td>";
                    } else {
                        rsp += "<td>" + break_row[8] + "</td>";
                    }
                }
                rsp += "</tr>";
            }

            if (break_resp[0] === "deposits") {
                //if (flg === 1) {
                //    rsp += "<tr style='font-weight:bold; color:#999999;background-color:#FF8080;'>";
                //} else {
                rsp += "<tr style='font-weight:bold; color:#FFFFFF;background-color:#FF8080;'>";
                //}
                //rsp += "<td colspan='8'>&nbsp;</td></tr>";
                rsp += "<td colspan='5'  style='text-align:right;'>Total:</td><td  style='text-align:right;'>" + numberFormat(totaldeposit + "") + "</td></tr>";
            }

            if (break_resp[0] === "withdrawals") {
                var bals = readCookie('thebalances').split("k");
                rsp += "<tr style='font-weight:bold; color:#FFFFFF;background-color:#FF8080;'>";
                rsp += "<td colspan='4'  style='text-align:right;'>Total:</td><td  style='text-align:right;'>" + numberFormat(totaldeposit + "") + "</td><td  style='text-align:right;'>" + numberFormat(totalwithdrawals + "") + "</td><td colspan='3'>&nbsp;</td></tr>";
                rsp += "<tr style='font-weight:bold; color:#FFFFFF;background-color:#FF8080;'>";
                rsp += "<td colspan='6'  style='text-align:right;'>Closing Balance:</td><td  style='text-align:right;'><input type='text' readonly  disabled='true' value='" + numberFormat(bals[1] + "") + "' style='display:inline; text-align:right;' size='15' /></td><td>&nbsp;</td><td>&nbsp;</td></tr>";
            }

            recordlist.innerHTML = allrecords + rsp + "</table>";
            createCookie('cardnumber', null, false);
            if (break_resp[0] === "customers") {
                document.getElementById("sexid").value = readCookie("sexvalue");
                document.getElementById("cardno").focus();
            }

            if (break_resp[0] === "deposits") {
                //getRecordlist("cardno",  'customers','recordlist');
                document.getElementById("cardno").focus();
            }
            if (break_resp[0] === "withdrawals" || break_resp[0] === "loandeposits") {
                var bals = readCookie('thebalances').split("k");
                document.getElementById("openbalances").innerHTML = "<b>__________Opening Balance: <input type='text' readonly  disabled='true' value='" + numberFormat(bals[0]) + "' style='display:inline; text-align:right;' size='15' /></b>";
                //getRecordlist("cardno",  'customers','recordlist');
                clearLists('recordlist');
                document.getElementById("cardno").focus();
            }
			if (break_resp[0] === "listCustomers") {
				document.getElementById('msgbox').innerHTML="<textarea id='textmsg' rows='4' cols='175' onkeyup='getCharNo()' placeholder='Type your message here................'></textarea>";
			}
        }

        if (resp.match("getRecordlist")) {
//alert(resp);
            var keyword = curr_obj.value;
            var allCodes = resp.split("getRecordlist");
            var inner_codeslist = "";
            if (navigator.appName === "Microsoft Internet Explorer") {
                inner_codeslist = "<table border='1' style='border-color:#fff;border-style:solid;border-width:1px; height:10px;width:60%;background-color:#336699;margin-top:5px;'>";
            } else {
                inner_codeslist = "<table border='1' style='border-color:#fff;border-style:solid;border-width:1px; height:10px;width:100%;background-color:#336699;margin-top:5px;'>";
            }
            inner_codeslist += "<tr style='font-weight:bold; color:white'>";
            inner_codeslist += "<td colspan='3' align='right'><a title='Close' style='font-weight:bold; font-size:20px; color:white;background-color:red;' href='javascript:clearLists()'>X</a></td></tr>";

            var codeslist = document.getElementById(list_obj);
            codeslist.style.backgroundColor = "#006699";
            codeslist.style.border = "2px solid #006699";
            codeslist.style.zIndex = 100;
            codeslist.style.position = "absolute";

            codeslist.style.top = ($(curr_obj).position().top + 23) + 'px';
            codeslist.style.left = ($(curr_obj).position().left) + 'px';

            var token = "";
            var tokensent = "";
            counter = 1;
            var colorflag = 0;
            var k = 0;

            if (keyword.trim().length === 0) {
                for (k = 0; k < allCodes.length; k++) {
                    if (allCodes[k].trim().length > 0) {
						token = allCodes[k].split("_~_");
                        tokensent = token[1].replace(/ /g, '#');
                        if (colorflag === 0) {
                            colorflag = 1;
                            inner_codeslist += "<tr style='background-color:#CCCCCC;color:#FFFFFF'>"

                        } else {
                            colorflag = 0;
                            inner_codeslist += "<tr style='background-color:#FFFFFF;color:#CCCCCC'>"
                        }
                        inner_codeslist += "<td align='right'>" + counter++ + ".</td><td><a href=javascript:populateCode('" + tokensent + "')>" + token[1] + "</a></td>";
                        if (token[3] !== null && token[3] !== "")
                            token[2] += " " + token[3];
                        if (token[2] !== null && token[2] !== "")
                            inner_codeslist += "<td>" + token[2] + "</td>";
                        inner_codeslist += "</tr>";
                    }
                }
            } else {
                for (k = 0; k < allCodes.length; k++) {
                    if (allCodes[k].trim().length > 0 && (allCodes[k].toUpperCase().match(keyword.toUpperCase()))) {
                        token = allCodes[k].split("_~_");
                        tokensent = token[1].replace(/ /g, '#');
                        if (colorflag === 0) {
                            colorflag = 1;
                            inner_codeslist += "<tr style='background-color:#CCCCCC;color:#FFFFFF'>"

                        } else {
                            colorflag = 0;
                            inner_codeslist += "<tr style='background-color:#FFFFFF;color:#CCCCCC'>"
                        }
                        inner_codeslist += "<td align='right'>" + counter++ + ".</td><td><a href=javascript:populateCode('" + tokensent + "')>" + token[1] + "</a></td>";
                        if (token[3] !== null && token[3] !== "")
                            token[2] += " " + token[3];
                        if (token[2] !== null && token[2] !== "")
                            inner_codeslist += "<td>" + token[2] + "</td>";
                        inner_codeslist += "</tr>";
                    }
                }
            }
            inner_codeslist += "</table>";
            codeslist.style.zIndex = 100;
            codeslist.innerHTML = "";
            codeslist.innerHTML = inner_codeslist;
			
            return true;
        }

//alert(resp+"   outside");
        if (resp.match("getARecord")) {
//alert(resp);
//alert(readCookie("names"));
//document.getElementById("showPrompt").innerHTML = resp;
//$('#showPrompt').dialog('open');
            break_resp = resp.split("getARecord");
            createCookie("serialno", break_resp[1], false);
            if (break_resp[0] === "customers") {
				if (readCookie("names").match(null)) {
                    if (break_resp[9] === '1' && readCookie('currentuser') !== 'Admin') {
                        document.getElementById("showPrompt").innerHTML = "<b>Record Locked!!!</b><br><br>The record you selected is locked.<br>It can not be edited. Unlock it to edit it. ";
                        $('#showPrompt').dialog('open');
                        return true;
                    }
                    resetForm("customers");
                    document.getElementById("cardno").value = break_resp[2];
                    //document.getElementById("cardserial").disabled = true;
                    document.getElementById("cardno").disabled = true;
                    document.getElementById("lastname").value = break_resp[3];
                    document.getElementById("othernames").value = break_resp[4];
                    document.getElementById("sex").value = break_resp[5];
                    document.getElementById("telephone").value = break_resp[6];
                    document.getElementById("address").value = break_resp[7];
                    createCookie("theImage", break_resp[8], false);
                    loadImage(break_resp[8]);
                    document.getElementById("openingbalance").value = numberFormat(break_resp[9]);
                    document.getElementById("recordlock").value = break_resp[10];
                    if (break_resp[11] === "1") {
                        document.getElementById("lockwitdrawal").checked = true;
                    } else {
                        document.getElementById("lockwitdrawal").checked = false;
                    }
                    document.getElementById("commission").value = numberFormat(break_resp[12]);
                    document.getElementById("lineno").value = break_resp[13];
                    document.getElementById("cardserial").value = break_resp[14];
                    if (break_resp[15] !== null && break_resp[15] !== "") {
                        var newdate = break_resp[14].substr(0, 10);
                        newdate = newdate.split("-");
                        break_resp[15] = newdate[2] + "/" + newdate[1] + "/" + newdate[0];
                        document.getElementById("datedisbursed").value = break_resp[15];
                        document.getElementById("loanamount").value = numberFormat(break_resp[16]);
                        document.getElementById("loaninterest").value = numberFormat(break_resp[17]);
                        var newdate = break_resp[8].substr(0, 10);
                        newdate = newdate.split("-");
                        break_resp[18] = newdate[2] + "/" + newdate[1] + "/" + newdate[0];
                        //document.getElementById("loanstartdate").value = break_resp[17];
                        var newdate = break_resp[19].substr(0, 10);
                        newdate = newdate.split("-");
                        break_resp[20] = newdate[2] + "/" + newdate[1] + "/" + newdate[0];
                        document.getElementById("loanenddate").value = break_resp[19];
                        document.getElementById("repayoption").value = break_resp[20];
                        document.getElementById("amountperrepay").value = numberFormat(break_resp[21]);
                    }
                    createCookie('savetype', 'update', false);
                    document.getElementById("lastname").focus();
                } else {
//alert(resp);
//alert(readCookie('names'));
//alert(readCookie('transtypes'));
                    if (readCookie('names') === 'names') {
                        if (readCookie('currentform') !== "deposit" && readCookie('currentform') !== "commission" && readCookie('currentform') !== "withdrawal" && break_resp[11] === "1" && readCookie('currentuser') !== 'Admin') {
                            alert("Customer: " + break_resp[3] + " " + break_resp[4] + " is locked for withdrawal!!!");
                            return true;
                        }
                        createCookie("serialno", null, false);
                        loadImage(break_resp[8]);
                        document.getElementById(readCookie('names')).value = break_resp[3] + " " + break_resp[4];
						//document.getElementById("cardserial").disabled = true;
						document.getElementById("cardno").disabled = true;
                        //document.getElementById("lockwitdrawal").value=break_resp[11];
                        //if(readCookie('transtypes')==='deposits') checkCommission();
                        if (readCookie('transtypes') === 'withdrawals')
                            getRecords("withdrawals");
                        if (readCookie('transtypes') === 'loandeposits')
                            getRecords("loandeposits");
                        //document.getElementById("cardno").disabled = true;
                        document.getElementById("statementdate").focus();
                    } else if (readCookie('names').substr(0, 6) === 'nameid') {
                        if (readCookie('currentform') !== "deposit" && readCookie('currentform') !== "commission" && readCookie('currentform') !== "withdrawal" && break_resp[11] === "1" && readCookie('currentuser') !== 'Admin') {
                            alert("Customer: " + break_resp[3] + " " + break_resp[4] + " is locked for withdrawal!!!");
                            return true;
                        }
                        var passportpictureid = "passportpictureid" + readCookie('names').substr(6, readCookie('names').length);
                        var balance_bid = "balance_bid" + readCookie('names').substr(6, readCookie('names').length);
                        document.getElementById(readCookie('names')).value = break_resp[3] + " " + break_resp[4];
                        if (document.getElementById(passportpictureid) !== null)
                            document.getElementById(passportpictureid).innerHTML = break_resp[8];
                        if (break_resp[12] === null || break_resp[12] === ''){
                            break_resp[12] = '0';
						}
						document.getElementById(balance_bid).value = numberFormat(break_resp[15]);
                        var amountid = "amountid" + readCookie('names').substr(6, readCookie('names').length);
                        document.getElementById(amountid).focus();
                    } else {
                        if (readCookie('currentform') !== "deposit" && readCookie('currentform') !== "commission" && readCookie('currentform') !== "withdrawal" && break_resp[11] === "1" && readCookie('currentuser') !== 'Admin') {
                            alert("Customer: " + break_resp[3] + " " + break_resp[4] + " is locked for withdrawal!!!");
                            return true;
                        }
                        document.getElementById(readCookie('names')).value = break_resp[3] + " " + break_resp[4];
                    }
                    createCookie('names', null, false);
                }
            }
            if (break_resp[0] === "withdrawals") {
                if (break_resp[11] === '1' && readCookie('currentuser') !== 'Admin') {
                    document.getElementById("showPrompt").innerHTML = "<b>Record Locked!!!</b><br><br>The record you selected is locked.<br>It can not be edited. Unlock it to edit it. ";
                    $('#showPrompt').dialog('open');
                    return true;
                }
                document.getElementById("cardno").value = break_resp[2];
				//document.getElementById("cardserial").disabled = true;
                document.getElementById("cardno").disabled = true;
                var newdate = break_resp[3].substr(0, 10);
                newdate = newdate.split("-");
                break_resp[3] = newdate[2] + "/" + newdate[1] + "/" + newdate[0];
                document.getElementById("statementdate").value = break_resp[3];
                document.getElementById("narration").value = break_resp[4];
                document.getElementById("deposit").value = numberFormat(break_resp[5]);
                document.getElementById("withdrawal").value = numberFormat(break_resp[6]);
                document.getElementById("balance").value = numberFormat(break_resp[7]);
                document.getElementById("balance").disabled = true;
                document.getElementById("transtype").value = break_resp[8];
                document.getElementById("transtype").disabled = true;
                document.getElementById("userid").value = break_resp[9];
                document.getElementById("lineid").value = break_resp[12];
                document.getElementById("transno").value = break_resp[13];
                document.getElementById("userid").disabled = true;
                createCookie('savetype', 'update', false);
                document.getElementById("statementdate").focus();
            }
            if (break_resp[0] === "loandeposits") {
                if (break_resp[11] === '1' && readCookie('currentuser') !== 'Admin') {
                    document.getElementById("showPrompt").innerHTML = "<b>Record Locked!!!</b><br><br>The record you selected is locked.<br>It can not be edited. Unlock it to edit it. ";
                    $('#showPrompt').dialog('open');
                    return true;
                }
                document.getElementById("cardno").value = break_resp[2];
				//document.getElementById("cardserial").disabled = true;
                document.getElementById("cardno").disabled = true;
                var newdate = break_resp[3].substr(0, 10);
                newdate = newdate.split("-");
                break_resp[3] = newdate[2] + "/" + newdate[1] + "/" + newdate[0];
                document.getElementById("transdate").value = break_resp[3];
                document.getElementById("amount").value = numberFormat(break_resp[6]);
                document.getElementById("narration").value = break_resp[4];
                document.getElementById("transtype").value = break_resp[8];
                document.getElementById("commission").value = document.getElementById("amount").value.replace(/&/g, '$');
                document.getElementById("description").value = document.getElementById("narration").value.replace(/&/g, '$');
                createCookie('savetype', 'update', false);
                document.getElementById("transdate").focus();
            }
            if (break_resp[0] === "deposits") {
                if (break_resp[11] === '1' && readCookie('currentuser') !== 'Admin') {
                    document.getElementById("showPrompt").innerHTML = "<b>Record Locked!!!</b><br><br>The record you selected is locked.<br>It can not be edited. Unlock it to edit it. ";
                    $('#showPrompt').dialog('open');
                    return true;
                }
                document.getElementById("cardno").value = break_resp[2];
				//document.getElementById("cardserial").disabled = true;
                document.getElementById("cardno").disabled = true;
                var newdate = break_resp[3].substr(0, 10);
                newdate = newdate.split("-");
                break_resp[3] = newdate[2] + "/" + newdate[1] + "/" + newdate[0];
                document.getElementById("transdate").value = break_resp[3];
                document.getElementById("amount").value = numberFormat(break_resp[5]);
                document.getElementById("commission").value = numberFormat(break_resp[6]);
                document.getElementById("narration").value = break_resp[4];
                document.getElementById("transtype").value = break_resp[8];
                document.getElementById('names').value = break_resp[12];
                loadImage(break_resp[13]);
                //document.getElementById("commission").value=document.getElementById("amount").value;
                //document.getElementById("description").value=document.getElementById("narration").value;
                //getNames(break_resp[2],"names");
                createCookie('savetype', 'update', false);
                document.getElementById("transdate").focus();
            }
            return true;
        }

        if (resp.match("inserted")) {
//document.getElementById("showPrompt").innerHTML = resp+readCookie("azeez");
//$('#showPrompt').dialog('open');
            break_resp = resp.split("inserted");
            if (break_resp[0] === "transactionlist") {
                populateTransaction();
                return true;
            } else if (break_resp[0] === "transactionlist2") {
                listTransaction();
                return true;
            } else if (readCookie('transtypes') === "deposits") {
                resetForm("deposits");
                getRecords("deposits");
            } else if (readCookie('transtypes') === "withdrawals") {
                createCookie('cardnumber', document.getElementById("cardno").value.replace(/&/g, '$'), false);
                createCookie('cardname', document.getElementById("names").value.replace(/&/g, '$'), false);
                resetForm("withdrawals");
                document.getElementById("cardno").value = readCookie('cardnumber');
                document.getElementById("names").value = readCookie('cardname');
                getRecords("withdrawals");
                document.getElementById("cardno").disabled = true;
				//document.getElementById("cardserial").disabled = true;
                document.getElementById("transdate").focus();
            } else if (readCookie('transtypes') === "loandeposits") {
                createCookie('cardnumber', document.getElementById("cardno").value.replace(/&/g, '$'), false);
                createCookie('cardname', document.getElementById("names").value.replace(/&/g, '$'), false);
                resetForm("loandeposits");
                document.getElementById("cardno").value = readCookie('cardnumber');
                document.getElementById("names").value = readCookie('cardname');
                getRecords("loandeposits");
                document.getElementById("cardno").disabled = true;
				//document.getElementById("cardserial").disabled = true;
                document.getElementById("transdate").focus();
            } else if (break_resp[0] === "customers") {

                resetForm("customers");
                getRecords("customers");
            } else {
                resetForm(break_resp[0]);
                getRecords(break_resp[0]);
            }
            //document.getElementById("showPrompt").innerHTML = "<b>Record Added!!!</b><br><br>Your record was successfully added.";
            //$('#showPrompt').dialog('open');
        }

        if (resp.match("calculateBalances")) {
            getRecords("withdrawals");
        }

        if (resp.match("updated")) {
//alert(resp);
//document.getElementById("showPrompt").innerHTML = readCookie("myrecord"); //resp;
//$('#showPrompt').dialog('open');
            break_resp = resp.split("updated");
            if (break_resp[0] === "transactionlist") {
                populateTransaction();
                return true;
            } else if (break_resp[0] === "transactionlist2") {
                listTransaction();
                return true;
            } else if (break_resp[0] === "transactions") {
                getRecords("withdrawals");
                document.getElementById("statementdate").value = "";
                document.getElementById("deposit").value = "";
                document.getElementById("balance").value = "";
                document.getElementById("withdrawal").value = "";
                document.getElementById("transtype").value = "";
                document.getElementById("userid").value = "";
                loadImage('silhouette.jpg');
                createCookie("theImage", 'silhouette.jpg', false);
            } else if (readCookie('transtypes') === "deposits") {
                resetForm("deposits");
                getRecords("deposits");
            } else if (readCookie('transtypes') === "withdrawals") {
                createCookie('cardnumber', document.getElementById("cardno").value.replace(/&/g, '$'), false);
                createCookie('cardname', document.getElementById("names").value.replace(/&/g, '$'), false);
                resetForm("withdrawals");
                document.getElementById("cardno").value = readCookie('cardnumber');
                document.getElementById("names").value = readCookie('cardname');
                getRecords("withdrawals");
                document.getElementById("cardno").disabled = true;
				//document.getElementById("cardserial").disabled = true;
                document.getElementById("transdate").focus();
            } else if (readCookie('transtypes') === "loandeposits") {
                createCookie('cardnumber', document.getElementById("cardno").value.replace(/&/g, '$'), false);
                createCookie('cardname', document.getElementById("names").value.replace(/&/g, '$'), false);
                resetForm("loandeposits");
                document.getElementById("cardno").value = readCookie('cardnumber');
                document.getElementById("names").value = readCookie('cardname');
                getRecords("loandeposits");
                document.getElementById("cardno").disabled = true;
				//document.getElementById("cardserial").disabled = true;
                document.getElementById("transdate").focus();
            } else if (break_resp[0] === "customers") {
                resetForm("customers");
				searchCustomer2('search','customers2')
				//searchCustomer2(document.getElementById('linenoid').id,'customers5');
                //getRecords("customers");
            } else {
                resetForm(break_resp[0]);
                getRecords(break_resp[0]);
            }
            //document.getElementById("showPrompt").innerHTML = "<b>Record Updated!!!</b><br><br>Your record was successfully updated.";
            //$('#showPrompt').dialog('open');
        }

        if (resp.match("deleted")) {
//document.getElementById("showPrompt").innerHTML = resp;
//$('#showPrompt').dialog('open');
//return true;
            break_resp = resp.split("deleted");
            if (break_resp[0] === "transactionlist") {
                populateTransaction();
                //alert("Record Deleted");
                return true;
            } else if (break_resp[0] === "transactionlist2") {
                listTransaction();
                return true;
            } else if (break_resp[0] === "transactions") {
                getRecords("withdrawals");
                document.getElementById("statementdate").value = "";
                document.getElementById("deposit").value = "";
                document.getElementById("balance").value = "";
                document.getElementById("withdrawal").value = "";
                document.getElementById("transtype").value = "";
                document.getElementById("userid").value = "";
                loadImage('silhouette.jpg');
                createCookie("theImage", 'silhouette.jpg', false);
            } else if (break_resp[0] === "customers") {
                resetForm("customers");
                getRecords("customers");
            } else {
                resetForm(break_resp[0]);
                getRecords(break_resp[0], "1");
            }
            document.getElementById("showPrompt").innerHTML = "<b>Record Deleted!!!</b><br><br>Your record was successfully deleted.";
            $('#showPrompt').dialog('open');
        }

        if (resp.match("postedTransactions") || resp.match("deltedTransaction")) {
//alert("Transactions Posted");
			clearInterval(myVar);
			if(resp.match("deltedTransaction")){
				alert('Your records were successfully deleted.');
			}else{
				alert('All Transactions were successfully posted.');
				populateTransaction();
			}
            return true;
        }

        if (resp.match("deletePost")) {
//alert("Transactions Posted");
			clearInterval(myVar);
            alert("This transaction can not be posted again\n\nYou must delete it!!!");
            return true;
        }

        if (resp.match("runningPost")) {
			clearInterval(myVar);
            alert("This user is posting this transaction at the moment in another session\n\nYou can start a new posting after 10 minutes!!!");
            return true;
        }

        if (resp.match("enablecommission"))
            document.getElementById("commission").disabled = false;
        if (resp.match("disablecommission"))
            document.getElementById("commission").disabled = true;

        //if(resp.match("enablecommission") || resp.match("disablecommission")){ 
        //document.getElementById("showPrompt").innerHTML = resp;
        //$('#showPrompt').dialog('open');
        //}

        if (resp.match("minimaldate")) {
            break_resp = resp.split("minimaldate");
            var newdate = break_resp[1].substr(0, 10);
            newdate = newdate.split("-");
            break_resp[1] = newdate[2] + "/" + newdate[1] + "/" + newdate[0];
            document.getElementById("showPrompt").innerHTML = "<b>Posting Below Minimum Date!!!</b><br><br>you can not post to a date below minimum date of " + break_resp[1];
            $('#showPrompt').dialog('open');
        }

        if (resp.match("recordlocked")) {
            break_resp = resp.split("recordlocked");
            document.getElementById("showPrompt").innerHTML = "<b>Records Locked!!!</b><br><br>The record your are trying to change is locked " + break_resp[1];
            $('#showPrompt').dialog('open');
        }

        if (resp.match("recordlocked2")) {
            break_resp = resp.split("recordlocked");
            document.getElementById("showPrompt").innerHTML = "<b>Records Locked!!!</b><br><br>The record your are trying to delete is locked ";
            $('#showPrompt').dialog('open');
        }

        if (resp.match("locknotallowed")) {
            var id = readCookie("checkid");
            if (document.getElementById(id).checked === true) {
                document.getElementById(id).checked = false;
            } else {
                document.getElementById(id).checked = true;
            }
            break_resp = resp.split("recordlocked");
            //document.getElementById("showPrompt").innerHTML = "<b>Record Lock Not Allowed!!!</b><br><br>"+readCookie('currentuser')+" does not have permission to lock Customer records";
            //$('#showPrompt').dialog('open');
            alert("Record Lock Not Allowed!!!\n\n" + readCookie('currentuser') + " does not have permission to lock Customer records");
        }

        if (resp.match("lockWithdrawal")) {
            break_resp = resp.split("lockWithdrawal");
            if (break_resp[1] === '1') {
                document.getElementById("showPrompt").innerHTML = "<b>Withdrawal Locked!!!</b><br><br>" + readCookie('currentuser') + " locked Withdrawal for " + document.getElementById("cardno").value;
            } else {
                document.getElementById("showPrompt").innerHTML = "<b>Withdrawal Unlocked!!!</b><br><br>" + readCookie('currentuser') + " unlocked Withdrawal for " + document.getElementById("cardno").value;
            }
            $('#showPrompt').dialog('open');
        }

        if (resp.match("withdrawallocked")) {
            break_resp = resp.split("withdrawallocked");
            document.getElementById("showPrompt").innerHTML = "<b>Withdrawal Locked!!!</b><br><br>Withdrawal is locked for " + break_resp[1];
            $('#showPrompt').dialog('open');
            setTimeout(function () {
                $('#showPrompt').dialog('close');
                populateTransaction();
            }, 3000);
            //populateTransaction();
        }

        if (resp.match("cardupdated")) {
            var oldcardnumber = document.getElementById("oldcardNumber").value.replace(/&/g, '$');
            var newcardnumber = document.getElementById("newcardNumber").value.replace(/&/g, '$');
            document.getElementById("cardno").value = newcardnumber;
            document.getElementById("showPrompt").innerHTML = "<b>Card No Updated!!!</b><br><br>All " + oldcardnumber + " have been successfully replaced with " + newcardnumber + " in the entire database.";
            $('#showPrompt').dialog('open');
        }

        if (resp.match("cardexists")) {
            var newcardnumber = document.getElementById("newcardNumber").value.replace(/&/g, '$');
            document.getElementById("showPrompt").innerHTML = "<b>Card No Exists!!!</b><br><br>The new Card No " + newcardnumber + " already exists for a customer in the database.";
            $('#showPrompt').dialog('open');
        }

        if (resp.match("cardblank")) {
            var newcardnumber = document.getElementById("newcardNumber").value.replace(/&/g, '$');
            document.getElementById("showPrompt").innerHTML = "<b>Blank Card No!!!</b><br><br>The new Card No " + newcardnumber + " is blank and can not be updated in the database.";
            $('#showPrompt').dialog('open');
        }

        if (resp.match("showMaxUnit")) {
            break_resp = resp.split("showMaxUnit");
            document.getElementById("maximumunit").value = break_resp[1];
        }

        if (resp.match("coursecode_exists")) {
            resp = resp.replace(/_/g, ' ');
            document.getElementById("showError").innerHTML = "<b>Course Code Used!!!</b><br><br>The new course code have been used, try another one.";
            $('#showError').dialog('open');
            return true;
        }

        if (resp.match("exists_in")) {
            resp = resp.replace(/_/g, ' ');
            document.getElementById("showError").innerHTML = "<b>Record Exists!!!</b><br><br>The " + resp + ".";
            $('#showError').dialog('open');
            return true;
        }

        if (resp.match("recordexists")) {
            document.getElementById("showError").innerHTML = "<b>Record Exists!!!</b><br><br>The record already exists.";
            $('#showError').dialog('open');
            return true;
        }

        if (resp.match("recordused")) {
            document.getElementById("showError").innerHTML = "<b>Record Used!!!</b><br><br>The record has been used in another table.";
            $('#showError').dialog('open');
            return true;
        }

        if (resp.match("recordnotexist")) {
            document.getElementById("showError").innerHTML = "<b>Record Not Existing!!!</b><br><br>The record does not exist.";
            $('#showError').dialog('open');
            return true;
        }
        if (resp.match("logoutUser")) {
            window.location = "login.php";
        }
    }
}

function GetXmlHttpObject() {
    if (window.XMLHttpRequest) {
        // code for IE7+, Firefox, Chrome, Opera, Safari
        return new XMLHttpRequest();
    }

    if (window.ActiveXObject) {
        // code for IE6, IE5
        return new ActiveXObject("Microsoft.XMLHTTP");
    }
    return null;
}
