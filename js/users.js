/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

var curr_obj = null;
var curr_table = null;
var temp_table = "";
var temp_serialno = 0;
var curr_obj=null;
var curr_object=null;
var list_obj=null;
var duplicatecounter = 0;
		
function doUsersMenu(){
    $('#menuaccess').dialog('open');
}

function resetPassword(){
	var username = document.getElementById("username").value.replace(/&/g,'$');
    var error = "";
    if (username.length == 0) error += "User Name must not be blank<br><br>";
    if(error.length >0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error;
        $('#showAlert').dialog('close');
        document.getElementById("showError").innerHTML = error;
        $('#showError').dialog('open');
		return true;
    }
    var arg = "userName="+username;
    var url = "/dadollar/userbackend.php?option=resetPassword&"+arg;
	AjaxFunctionUser(url);
}

function logoutUser(){
    var url = "/dadollar/userbackend.php?option=logoutUser";
	AjaxFunctionUser(url);
}

function getUsersMenu(arg){
    checkLogin();
    document.getElementById("showAlert").innerHTML = "<br><br><b>Please wait...</b><br><br>fetching your record.";
    $('#showAlert').dialog('open');
    var currentuser = "";
    if(arg == "filterbutton"){
        currentuser = document.getElementById("currentuser").value.replace(/&/g,'$');
        if(currentuser == null || currentuser == ""){
            document.getElementById("showPrompt").innerHTML = "<b>No User is selected...</b><br><br>Please select a User and click again!!!";
            $('#showPrompt').dialog('open');
            //return true;
        }
    }
    arg = "&userName="+currentuser;
    var url = "/dadollar/setupbackend.php?option=getAllMenus"+arg;
    AjaxFunctionUser(url);
}

function getRecordlist(code,list){
	curr_object = code;
	curr_obj = document.getElementById(code);
    list_obj = 'recordlist';
    if(list!=null) list_obj = list;
    clearLists(list_obj);
	var table = "";
    if(code=='currentuser') table = 'users';
    curr_table = table;
    var url = "/dadollar/userbackend.php?option=getRecordlist&table="+table;
    AjaxFunctionUser(url);
}

function clearLists(arg){
    if(arg==null) arg=list_obj;
    document.getElementById(arg).innerHTML = "";
}

function checkAccess(access, menuoption){
	createCookie("access",access,false);
    var arg = "&currentuser="+readCookie("currentuser")+"&menuoption="+menuoption;
    var url = "/dadollar/setupbackend.php?option=checkAccess"+arg;
    AjaxFunctionUser(url);
}

function chgAccess(serialno){
    var selectoption = document.getElementById("access"+serialno);
    var access = selectoption.options[selectoption.selectedIndex].text;
    var arg = "&serialno="+serialno+"&access="+access;
    var url = "/dadollar/userbackend.php?option=changeAccess"+arg;
    AjaxFunctionUser(url);
}

function getUser(username){
	if(username==null || username=="") return true;
    document.getElementById("showAlert").innerHTML = "<br><br><b>Please wait...</b><br><br>Authenticating your username.";
    $('#showAlert').dialog('open');
    var url = "/dadollar/userbackend.php?option=getUser&userName="+username;
    AjaxFunctionUser(url);
}

function loginForm(){
    document.getElementById("showAlert").innerHTML = "<br><br><b>Please wait...</b><br><br>Checking your login.";
    $('#showAlert').dialog('open');
    var username = document.getElementById("username").value.replace(/&/g,'$');
    var password = document.getElementById("password").value.replace(/&/g,'$');
    var error = "";
    if (username.length == 0) error += "User Name must not be blank<br><br>";
    if (password.length == 0) error += "Password must not be blank<br><br>";
    if(error.length >0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error;
        $('#showAlert').dialog('close');
        document.getElementById("showError").innerHTML = error;
        $('#showError').dialog('open');
		return true;
    }
    var arg = "userName="+username+"&userPassword="+password;
    var url = "/dadollar/userbackend.php?option=loginUser&"+arg;
	AjaxFunctionUser(url);
}

function getUsers(arg,obj){
    createCookie("obj",obj,false);
    var url = "/dadollar/userbackend.php?option=getAllUsers"+"&active="+arg;
    AjaxFunctionUser(url);
}

function registerForm(option){
    document.getElementById("showAlert").innerHTML = "<br><br><b>Please wait...</b><br><br>processing your record.";
    $('#showAlert').dialog('open');
    var username = document.getElementById("username").value.replace(/&/g,'$');
    var firstname = document.getElementById("firstname").value.replace(/&/g,'$');
    var lastname = document.getElementById("lastname").value.replace(/&/g,'$');
    var login = document.getElementById("selectlogin").value.replace(/&/g,'$');
    var active = document.getElementById("selectactive").value.replace(/&/g,'$');
    var error = "";
    if (username.length == 0) error += "User Name must not be blank<br><br>";
    if (firstname.length == 0) error += "First Name must not be blank<br><br>";
    if (lastname.length == 0) error += "Last Name must not be blank<br><br>";
    if (login.length == 0) error += "Login Status must not be blank<br><br>";
    if (active.length == 0) error += "Active must not be blank<br><br>";
    if(error.length >0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error;
        return error;
    }
    var arg = "&firstName="+firstname+"&lastName="+lastname+"&userName="+username+"&active="+active+"&login="+login;
    var url = "/dadollar/userbackend.php?option="+option+arg;
//alert(url);
    AjaxFunctionUser(url);
}

function clearPassForm(){
    //document.getElementById("username2").value = "";
    //document.getElementById("firstname2").value = "";
    //document.getElementById("lastname2").value = "";
    document.getElementById("password").value = "";
    document.getElementById("newpassword").value = "";
    document.getElementById("rptpassword").value = "";
}

function getRegister(){
    $('#register').dialog('open');
    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Fetching your records.";
    $('#showAlert').dialog('open');

    var url = "/dadollar/userbackend.php?option=getAllUsers";
    AjaxFunctionUser(url);
    
}

function getPassword(){
    document.getElementById("password").value = "";
    document.getElementById("newpassword").value = "";
    document.getElementById("rptpassword").value = "";
    $('#changepass').dialog('open');
    document.getElementById("showAlert").innerHTML = "<b>Please wait...</b><br><br>Fetching your records.";
    $('#showAlert').dialog('open');

    var username = readCookie("currentuser");
    var url = "/dadollar/userbackend.php?option=getPassword&userName="+username;
    AjaxFunctionUser(url);
    
}

function changePass(){
    document.getElementById("showAlert").innerHTML = "<br><br><b>Please wait...</b><br><br>processing your record.";
    $('#showAlert').dialog('open');
    var username2 = document.getElementById("username2").value.replace(/&/g,'$');
    var password = document.getElementById("password").value.replace(/&/g,'$');
    var newpassword = document.getElementById("newpassword").value.replace(/&/g,'$');
    var rptpassword = document.getElementById("rptpassword").value.replace(/&/g,'$');
    var error = "";
    if (password.length == 0) error += "Old Password must not be blank<br><br>";
    if (newpassword.length < 6) error += "New Password must be greater than 5 characters<br><br>";
    if (rptpassword != newpassword) error += "New Password does not match Repeat New Password<br><br>";
    if(error.length > 0) {
        error = "<br><b>Please correct the following errors:</b> <br><br><br>" + error;
        //alert("Please correct the following: \n\n" + error);
		if(error.length>0){
			$('#showAlert').dialog('close');
			document.getElementById("showError").innerHTML = error;
			$('#showError').dialog('open');
			return true;
		}
    }
    var arg = "userName="+username2+"&userPassword="+password+"]["+newpassword;
    var url = "/dadollar/userbackend.php?option=changePass&"+arg;
    AjaxFunctionUser(url);
    
}

function populateUsers(a,b,c,d,e){
    document.getElementById(readCookie("obj")).value=a;
    if(readCookie("obj")=="email"){
        document.getElementById("firstname").value=b;
        document.getElementById("lastname").value=c;
    }
    //clearUsersList();
}

function populateRecords(a,b,c,d,e){
    document.getElementById("username").value=a;
    document.getElementById("firstname").value=b;
    document.getElementById("lastname").value=c;
    document.getElementById("selectlogin").value=d;
    document.getElementById("selectactive").value=e;
    /*selectoption = document.getElementById("selectactive");
    for(k=0; selectoption.options[k].text != null; k++){
        if(selectoption.options[k].text == d){
            selectoption.selectedIndex = k;
            break;
        }
    }*/
    //document.getElementsByName("selectactive").value=d;
    document.getElementById("username").disabled = true;
    //clearUsersList();
}

function populateCode(code){
    curr_obj.value = code;
    clearLists(list_obj);
	if(list_obj=="codelist"){
		getCode(curr_object);
	}
}

function clearUsersList(){
    document.getElementById("userlist").innerHTML = "";
}

function clearLoginForm(){
    document.getElementById("username").value = "";
    document.getElementById("password").value = "";
}

function clearRegisterForm(){
    document.getElementById("firstname").value = "";
    document.getElementById("lastname").value = "";
    document.getElementById("username").value = "";
    document.getElementById("selectlogin").value = "";
    document.getElementById("selectactive").value = "";
	//document.getElementById("selectactive").selectedIndex=0;
    //clearUsersList();
}

function showCurrentCardno() {
    var url = "/dadollar/setupbackend.php?option=showCurrentCardno";
    document.getElementById("showAlert").innerHTML = "<br><b>Balance Brought Forward!!!</b><br><br>The system is processing balance brought forward for: ";
	$('#showAlert').dialog('open');
	AjaxFunctionUser(url);
}

var myVar = null;
function checkBalanceBF(resp){
	myVar = setInterval(function () {
            showCurrentCardno();
        }, 750);
	break_resp = resp.split("Yes");
	resp = "<br><b>Balance Brought Forward!!!</b><br><br>The system is processing balance brought forward for: <br><br>"+break_resp[1];
	document.getElementById("showAlert").innerHTML = resp;
	$('#showAlert').dialog('open');
	var url = "/dadollar/userbackend.php?option=checkBalanceBF";
    AjaxFunctionUser(url);
}

var xmlhttp

function AjaxFunctionUser(arg){

    xmlhttp=GetXmlHttpObject();
    if(xmlhttp == null){
        alert ("Your browser does not support XMLHTTP!");
        return true;
    }

    var timestamp = new Date().getTime();
    var url = window.location+"";
    var break_url = url.split("/dadollar");
    url = break_url[0] + arg+"&timestamp="+timestamp; //+"&url="+break_url[0];

    xmlhttp.onreadystatechange=stateChangedLogin;
    xmlhttp.open("GET",url,true);
    xmlhttp.send(null);
    
}

function stateChangedLogin(){
    if (xmlhttp.readyState==4){
        var resp = xmlhttp.responseText;
        var break_resp = "";
        $('#showAlert').dialog('close');
        if(resp.match("checkAccess")){
            if(resp.match("checkAccessSuccess")){
				if(readCookie('currentuser'==null)) window.location = "login.php";
				window.location="home.php?pgid=1";
            }else{
                break_resp = resp.split("checkAccessFailed");
                resp = "<b>Access Denied!!!</b><br><br>Menu ["+break_resp[1]+"] not accessible by "+readCookie("currentuser");
                document.getElementById("showPrompt").innerHTML = resp;
                $('#showPrompt').dialog('open');
            }
        }
        if(resp.match("getRecordlist")){
            var keyword = curr_obj.value;
            var allCodes = resp.split("getRecordlist");
            var inner_codeslist = "";
            if(navigator.appName == "Microsoft Internet Explorer"){
                inner_codeslist = "<table border='1' style='border-color:#fff;border-style:solid;border-width:1px; height:10px;width:30%;background-color:#336699;margin-top:5px;'>";
            }else{
                inner_codeslist = "<table border='1' style='border-color:#fff;border-style:solid;border-width:1px; height:10px;width:100%;background-color:#336699;margin-top:5px;'>";
            }
            inner_codeslist += "<tr style='font-weight:bold; color:white'>";
            inner_codeslist += "<td>S/No</td><td>Codes</td><td>Name&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td><td align='right'><a title='Close' style='font-weight:bold; font-size:20px; color:white;background-color:red;' href=javascript:clearLists()>X</a></td></tr>";

            var codeslist = document.getElementById(list_obj);
            codeslist.style.zIndex = 100;
            codeslist.style.position = "absolute";

            if(navigator.appName=="Microsoft Internet Explorer"){
                codeslist.style.top = '200px';
                codeslist.style.left ='200px';
                codeslist.style.top = (findPosY(curr_obj) - 52 + curr_obj.clientHeight) + 'px';
                codeslist.style.left = (findPosX(curr_obj) - 340) + 'px';
            }else{
                codeslist.style.top = ($(curr_obj).position().top + 23) + 'px';
                codeslist.style.left = ($(curr_obj).position().left) + 'px';
            }

            var token = "";
            var colorflag = 0;
			var count=0;
            var k=0;
            if(keyword.trim().length==0){
                for(k=0; k<allCodes.length; k++){
                    if(allCodes[k].trim().length>0){
                        token = allCodes[k].split("~_~");
                        if(colorflag == 0){
                            colorflag = 1;
                            inner_codeslist += "<tr style='background-color:#CCCCCC;'><td align='right'>"+(++count)+".</td><td><a href=javascript:populateCode('"+token[0]+"')>"+token[0]+"</a></td><td>"+token[1]+"</td><td>&nbsp;</td></tr>";
                        } else {
                            colorflag = 0;
                            inner_codeslist += "<tr style='background-color:#FFFFFF;color:#CCCCCC'><td align='right'>"+(++count)+".</td><td><a href=javascript:populateCode('"+token[0]+"')>"+token[0]+"</a></td><td>"+token[1]+"</td><td>&nbsp;</td></tr>";
                        }
                    }
                }
            } else {
                for(k=0; k<allCodes.length; k++){
                    if(allCodes[k].trim().length>0 && (allCodes[k].toUpperCase().match(keyword.toUpperCase()))){
                        token = allCodes[k].split("~_~");
                        if(colorflag == 0){
                            colorflag = 1;
                            inner_codeslist += "<tr style='background-color:#CCCCCC;'><td align='right'>"+(++count)+".</td><td><a href=javascript:populateCode('"+token[0]+"')>"+token[0]+"</a></td><td>"+token[1]+"</td><td>&nbsp;</td></tr>";
                        } else {
                            colorflag = 0;
                            inner_codeslist += "<tr style='background-color:#FFFFFF;color:#CCCCCC'><td align='right'>"+(++count)+".</td><td><a href=javascript:populateCode('"+token[0]+"')>"+token[0]+"</a></td><td>"+token[1]+"</td><td>&nbsp;</td></tr>";
                        }
                    }
                }
            }
            inner_codeslist += "</table>";
            codeslist.style.zIndex = 100;
            codeslist.innerHTML = "";
            codeslist.innerHTML = inner_codeslist;
            //return true;
        }

		/*if(resp.match("getMyUsers")){
            var recordlist = document.getElementById('userlist');
            recordlist.innerHTML = "";
            if(resp == "getMyUsers"){
                //return true;
            }
            break_resp = resp.split("getMyUsers");
            var allrecords = "<table border='1' style='border-color:#fff;border-style:solid;border-width:1px; height:10px;wi0dth:550px;background-color:#336699;margin-top:5px;'>";
            allrecords += "<tr style='font-weight:bold; color:white'>";
            allrecords += "<td width='5%'>S/No</td><td width='20%'>Username</td><td width='20%'>User Email</td><td width='20%'>Firstname</td><td width='20%'>Lastname</td><td width='10%'>User Type</td><td width='10%'>Active</td></tr>";
            var counter = 0;
            var rsp = "";
            var flg = 0;
            var break_row = "";
            for(var i=1; i < (break_resp.length-1); i++){
                break_row = break_resp[i].split("~_~");
                if (flg == 1) {
                    flg = 0;
                    rsp += "<tr style='font-weight:bold; color:#999999;background-color:#FFFFFF;'>";
                    rsp += "<td width='5%' align='right'>" + (++counter) + ".</td>";
                    rsp += "<td width='20%'><a href=javascript:populateRecords('" + break_row[0] + "','" + break_row[1] + "','" + break_row[2] + "','" + break_row[3] + "','" + break_row[4] + "','" + break_row[5] + "','" + break_row[6] + "')>" + break_row[5] + "</a></td>";
                    rsp += "<td width='20%'>" + break_row[0] + "</td>";
                    rsp += "<td width='20%'>" + break_row[1] + "</td>";
                    rsp += "<td width='20%'>" + break_row[2] + "</td>";
                    rsp += "<td width='20%'>" + break_row[3] + "</td>";
                    rsp += "<td width='20%'>" + break_row[4] + "</td></tr>";
                } else {
                    flg = 1;
                    rsp += "<tr style='font-weight:bold; color:#FFFFFF;background-color:#999999;'>";
                    rsp += "<td width='5%' align='right'>" + (++counter) + ".</td>";
                    rsp += "<td width='20%'><a href=javascript:populateRecords('" + break_row[0] + "','" + break_row[1] + "','" + break_row[2] + "','" + break_row[3] + "','" + break_row[4] + "','" + break_row[5] + "','" + break_row[6] + "')>" + break_row[5] + "</a></td>";
                    rsp += "<td width='20%'>" + break_row[0] + "</td>";
                    rsp += "<td width='20%'>" + break_row[1] + "</td>";
                    rsp += "<td width='20%'>" + break_row[2] + "</td>";
                    rsp += "<td width='20%'>" + break_row[3] + "</td>";
                    rsp += "<td width='20%'>" + break_row[4] + "</td></tr>";
                }
            }
            recordlist.innerHTML = allrecords+rsp+"</table>";
        }*/

        if(resp.match("getPassword")){
            break_resp = resp.split("getPassword");
            document.getElementById("username2").value = break_resp[1];
            document.getElementById("firstname2").value = break_resp[2];
            document.getElementById("lastname2").value = break_resp[3];
            $('#showPrompt').dialog('close');
            document.getElementById("password").focus();
            //return true;
        }

        if(resp.match("getUser")){
            break_resp = resp.split("getUser");
            document.getElementById("username").value = break_resp[1];
            document.getElementById("firstname").value = break_resp[2];
            document.getElementById("lastname").value = break_resp[3];
            $('#showPrompt').dialog('close');
            document.getElementById("password").focus();
            //return true;
        }

        if(resp.match("getAllUsers")){
            if(resp == "getAllUsers"){
                //return true;
            }
            break_resp = resp.split("getAllUsers");
            var allrecords = "<table border='1' style='border-color:#fff;border-style:solid;border-width:1px; height:10px;wi0dth:550px;background-color:#336699;margin-top:5px;'>";
            allrecords += "<tr style='font-weight:bold; color:white'>";
            allrecords += "<td width='5%'>S/No</td><td width='20%'>Username</td><td width='20%'>Firstname</td><td width='20%'>Lastname</td><td width='10%'>Login Status</td><td width='10%'>Active</td></tr>";

            var recordlist = document.getElementById('userlist');
            var counter = 0;
            var rsp = "";
            var flg = 0;
            var break_row = "";
            for(var i=0; i < (break_resp.length-1); i++){
                break_row = break_resp[i].split("~_~");
                if (flg == 1) {
                    flg = 0;
                    rsp += "<tr style='font-weight:bold; color:#999999;background-color:#FFFFFF;'>";
                    rsp += "<td width='5%' align='right'>" + (++counter) + ".</td>";
                    rsp += "<td width='20%'><a href=javascript:populateRecords('" + break_row[0] + "','" + break_row[1] + "','" + break_row[2] + "','" + break_row[3] + "','" + break_row[4] + "')>" + break_row[0] + "</a></td>";
                    rsp += "<td width='20%'>" + break_row[1] + "</td>";
                    rsp += "<td width='20%'>" + break_row[2] + "</td>";
                    rsp += "<td width='20%'>" + break_row[3] + "</td>";
                    rsp += "<td width='20%'>" + break_row[4] + "</td></tr>";
                } else {
                    flg = 1;
                    rsp += "<tr style='font-weight:bold; color:#FFFFFF;background-color:#999999;'>";
                    rsp += "<td width='5%' align='right'>" + (++counter) + ".</td>";
                    rsp += "<td width='20%'><a href=javascript:populateRecords('" + break_row[0] + "','" + break_row[1] + "','" + break_row[2] + "','" + break_row[3] + "','" + break_row[4] + "')>" + break_row[0] + "</a></td>";
                    rsp += "<td width='20%'>" + break_row[1] + "</td>";
                    rsp += "<td width='20%'>" + break_row[2] + "</td>";
                    rsp += "<td width='20%'>" + break_row[3] + "</td>";
                    rsp += "<td width='20%'>" + break_row[4] + "</td></tr>";
                }
            }
            recordlist.innerHTML = allrecords+rsp+"</table>";
        }

        /*if(resp.match("checkAccess")){
            if(resp.match("checkAccessSuccess")){
                eval(readCookie("access"));
            }else{
                break_resp = resp.split("checkAccessFailed");
                resp = "<b>Access Denied!!!</b><br><br>Menu ["+break_resp[1]+"] not accessible by "+readCookie("currentuser");
                document.getElementById("showPrompt").innerHTML = resp;
                $('#showPrompt').dialog('open');
            }
            //return true;
        }*/

        if(resp.match("inserted") || resp.match("recordexists")){
            $('#showPrompt').dialog('close');
            if(resp=="inserted"){
                getRegister();
                resp = "<br><b>Congratulations!!!</b><br><br>The user has been created";
                resp += "<br><br><br><br>Thank you.";
                document.getElementById("showPrompt").innerHTML = resp;
                $('#showPrompt').dialog('open');
                document.getElementById("firstname").value="";
                document.getElementById("lastname").value="";
                document.getElementById("username").value="";
                document.getElementById("selectactive").selectedIndex=0;
                document.getElementById("username").disabled = false;
                //document.getElementById("password").value="";
                //document.getElementById("repeatpassword").value="";
            }else{
                resp = "<br><b>Too bad!!!</b><br><br>The username you typed already exists";
                resp += "<br><br>Please type another username and try again.";
                resp += "<br><br><br><br>Thank you.";
                document.getElementById("showPrompt").innerHTML = resp;
                $('#showPrompt').dialog('open');
            }
        }

        if(resp.match("updated") || resp.match("recordnotexist")){
            $('#showPrompt').dialog('close');
            if(resp=="updated"){
                resp = "<br><b>Congratulations!!!</b><br><br>The user has been  updated";
                resp += "<br><br><br><br>Thank you.";
                document.getElementById("showPrompt").innerHTML = resp;
                $('#showPrompt').dialog('open');
                document.getElementById("firstname").value="";
                document.getElementById("lastname").value="";
                document.getElementById("username").value="";
                document.getElementById("selectlogin").value="";
                document.getElementById("selectactive").value="";
                //document.getElementById("selectactive").selectedIndex=0;
                document.getElementById("username").disabled = false;
                //document.getElementById("password").value="";
                //document.getElementById("repeatpassword").value="";
                getRegister();
            }else{
                resp = "<br><b>Too bad!!!</b><br><br>The username you typed does exist";
                resp += "<br><br>Please select another username and try again.";
                resp += "<br><br><br><br>Thank you.";
                document.getElementById("showPrompt").innerHTML = resp;
                $('#showPrompt').dialog('open');
            }
        }

        if(resp.match("getAllMenus")){
            if(resp == "getAllMenus"){
                //return true;
            }
            break_resp = resp.split("getAllMenus");
            break_row = break_resp[1].split("row_separator");

            var menulist = document.getElementById('menulist2');
            var allmenus = "<table border='1' style='border-color:#fff;border-style:solid;border-width:1px; height:10px;width:100%;background-color:#336699;margin-top:5px;'>";
            var flag = 0;
            for(k=0; k < break_row.length-1; k++){
                var break_col = break_row[k].split("_~_");
                var serialno = break_col[0];
                var menuoption = break_col[1];
                var access = break_col[2];
                if (flag == 1) {
                    flag = 0;
                    allmenus += "<tr style='font-weight:bold; color:#999999;background-color:#FFFFFF;'>";
                } else {
                    flag = 1;
                    allmenus += "<tr style='font-weight:bold; color:#FFFFFF;background-color:#999999;'>";
                }
                allmenus += "<td align='right' width='10%'>" + (k+1) + ".</td>";
                allmenus += "<td width='45%'>" + menuoption + "</td>";
                allmenus += "<td class='input' width='10%'><select id='access" + serialno + "' name='access" + serialno + "' onchange=chgAccess('" + serialno + "')>";
                if (access == "No") {
                    allmenus += "<option selected>No</option><option>Yes</option></select></td>";
                } else {
                    //allmenus += "<td class='input' width='10px'><select id='access" + serialno + "' name='access" + serialno + "' onchange=chgAccess('" + serialno + "','" + menuoption + "')>";
                    allmenus += "<option>No</option><option selected>Yes</option></select></td>";
                }
            }
            menulist.innerHTML = allmenus+"</table>";
            if(break_resp[1] == ""){
                menulist.innerHTML = "";
            }
            document.getElementById("email").value = readCookie("userEmail");
            //return true;
        }

		if(resp.match("invalidlogin")){
			$('#showPrompt').dialog('close');
            resp = "<br><b>Invalid Username or Password!!!</b><br><br>The Username or Password you typed is invalid";
            resp += "<br><br>Please type another username and/or password and try again.";
            resp += "<br><br><br><br>Thank you.";

            document.getElementById("showPrompt").innerHTML = resp;
            $('#showPrompt').dialog('open');
        }

		if(resp == "userloggedin"){
            $('#showPrompt').dialog('close');
            resp = "<br><b>User Logged In!!!</b><br><br>The Username you used is logged in on anothe computer or you did not logout the last time";
            resp += "<br><br>Logout from the other system or tell the Administrator to clear your login for this username.";

            document.getElementById("showPrompt").innerHTML = resp;
            $('#showPrompt').dialog('open');
        }

		if(resp.match("validlogin") && !resp.match("invalidlogin")){
            $('#showPrompt').dialog('close');
            break_resp = resp.split("validlogin");
			if(readCookie('currentuser')==null) {
				window.location = "login.php";
				return true;
			}
			if(readCookie('currentuser')=="Admin") {
				checkBalanceBF(break_resp[1]);
			}else{
				window.location = "home.php";
			}
            
        }

		if(resp.match("BalanceBF")){
			clearInterval(myVar);
			$('#showAlert').dialog('close');
			window.location = "home.php";
        }

		if (resp.match("showCurrentCardno")) {
			if (resp=="showCurrentCardno") {
				clearInterval(myVar);
                $('#showAlert').dialog('close');
				alert("Processing Finished!!!");
				window.location = "home.php";
				return true;
			}			
			var break_resp = resp.split("showCurrentCardno");
			resp = "<br><b>Balance Brought Forward!!!</b><br><br>The system is processing balance brought forward for: <br><br>"+break_resp[1];
            document.getElementById("showAlert").innerHTML = resp;
            $('#showAlert').dialog('open');
            if (break_resp[1] === readCookie("currentCardno")) {
                duplicatecounter++;
				if (duplicatecounter >= 100) {
                    //document.getElementById("showAlert").innerHTML = "Commission calculation completed!!!";
                    //$('#showAlert').dialog('open');
                    alert("Processing Finished!!!");
                    clearInterval(myVar);
                    $('#showAlert').dialog('close');
					window.location = "home.php";
                }
            } else {
                duplicatecounter = 0;
                createCookie("currentCardno", break_resp[1], false);
            }
        }
		
		if(resp.match("passwordchanged")){
            document.getElementById("showPrompt").innerHTML = "<br><b>Password Reset!!!</b><br><br>"+document.getElementById("username").value+" successfully reset Password";
            $('#showPrompt').dialog('open');
        }

		if(resp.match("changepassword")){
			createCookie('thisuser', document.getElementById("username").value, false);
            alert("Change Password!!!\n\n\nDefault password detected. You must change your password to login");
            window.location = "changepassword2.php";
        }

        if(resp == "invalidusername"){
            $('#showPrompt').dialog('close');
            resp = "<br><b>Invalid username!!!</b><br><br>The Username you typed is invalid";
            resp += "<br><br>Please type another username and try again.";
            resp += "<br><br><br><br>Thank you.";
            document.getElementById("showPrompt").innerHTML = resp;
            $('#showPrompt').dialog('open');
            document.getElementById("firstname").value="";
            document.getElementById("lastname").value="";
            document.getElementById("username").value="";
            document.getElementById("password").value="";
            //return true;
        }

        if(resp == "invalidpassword"){
            $('#showPrompt').dialog('close');
            resp = "<br><b>Invalid Password!!!</b><br><br>The old Password you typed is invalid";
            resp += "<br><br>Please type another password and try again.";
            resp += "<br><br><br><br>Thank you.";
            document.getElementById("showError").innerHTML = resp;
            $('#showError').dialog('open');
            return true;
        }

        if(resp.match("changePass")){
            $('#showPrompt').dialog('close');
            resp = "<br><b>Successful Password Change!!!</b><br><br>You have successfully changed your password";
            resp += "<br><br>Please click ok to continue.";
            resp += "<br><br><br><br>Thank you.";
            document.getElementById("showPrompt").innerHTML = resp;
            $('#showPrompt').dialog('open');
        }

        if(resp.match("logoutUser")){
			window.location = "login.php";
		}
    }
    return true;
}

function GetXmlHttpObject(){
    if (window.XMLHttpRequest){
        // code for IE7+, Firefox, Chrome, Opera, Safari
        return new XMLHttpRequest();
    }

    if (window.ActiveXObject){
        // code for IE6, IE5
        return new ActiveXObject("Microsoft.XMLHTTP");
    }
    return null;
}
