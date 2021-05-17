/* 
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var initialTop = undefined;
var initialLeft = undefined;
var initialMouseX = undefined;
var initialMouseY = undefined;
var draggedObject = null;
var myfunc = null;
var myobj = null;
var panel = null;
function startDragMouse(obj, thepanel, func, e) {
    myfunc = func;
    myobj = obj;
    panel = thepanel;
    initialTop = findPosY(obj);
    initialLeft = findPosX(obj);
    //document.getElementById('companyName').value = myfunc+"(("+initialTop+"), ("+initialLeft+"))";
    startDrag(obj);
    var evt = e|| window.event;
    initialMouseX = evt.clientX;
    initialMouseY = evt.clientY;
    addEventSimple(document,'mousemove',dragMouse);
    addEventSimple(document,'mouseup',releaseElement);
    return false;
}

function startDrag(obj) {
    if (draggedObject) releaseElement();
    startX = obj.offsetLeft;
    startY = obj.offsetTop;
    startX = findPosX(obj);
    startY = findPosY(obj);
    draggedObject = obj;
}

function dragMouse(e) {
    var evt = e || window.event;
    var dX = evt.clientX - initialMouseX;
    var dY = evt.clientY - initialMouseY;
    //document.getElementById('userName').value = myfunc+"(("+(initialTop+dY)+"), ("+(initialLeft+dX)+"))";
    eval(myfunc+"(("+(initialTop+dY)+"), ("+(initialLeft+dX)+"))");
    return false;
}

function drawObj(T,L){
    var mypanel = document.getElementById(panel);
    mypanel.style.position = "absolute";
    mypanel.style.top = (T - 60) + "px";
    mypanel.style.left = L + "px";
}

function releaseElement() {
    removeEventSimple(document,'mousemove',dragMouse);
    removeEventSimple(document,'mouseup',releaseElement);
    draggedObject = null;
}

function addEventSimple(obj,evt,fn) {
    if (obj.addEventListener)
        obj.addEventListener(evt,fn,false);
    else if (obj.attachEvent)
        obj.attachEvent('on'+evt,fn);
}

function removeEventSimple(obj,evt,fn) {
    if (obj.removeEventListener)
        obj.removeEventListener(evt,fn,false);
    else if (obj.detachEvent)
        obj.detachEvent('on'+evt,fn);
}

function toolTip(id,msg,e){
	if(msg==""){
		document.getElementById(id).innerHTML="";
	}else{
	    var evt = e|| window.event;
		var initialMouseX = evt.clientX;
		var initialMouseY = evt.clientY;
		document.getElementById(id).style.position = "absolute";
        document.getElementById(id).style.top = (initialMouseY - 150) + 'px';
        document.getElementById(id).style.left = (initialMouseX - 250) + 'px';
		document.getElementById(id).innerHTML="<table border='1' style='border-color:#fff;border-style:solid;border-width:1px; height:10px;width:100%;background-color:#804000;margin-top:5px;'><tr style='font-weight:bold; color:white'><td>"+msg.replace(/_/g, ' ');+"</td></tr></table>";
	}
}

String.prototype.trim = function() {
    return this.replace(/^\s+|\s+$/g,"");
}
String.prototype.ltrim = function() {
    return this.replace(/^\s+/,"");
}
String.prototype.rtrim = function() {
    return this.replace(/\s+$/,"");
}

function trim(stringToTrim) {
    return stringToTrim.replace(/^\s+|\s+$/g,"");
}
function ltrim(stringToTrim) {
    return stringToTrim.replace(/^\s+/,"");
}
function rtrim(stringToTrim) {
    return stringToTrim.replace(/\s+$/,"");
}
function capitalize(arg){
    return arg.toUpperCase();
}
function capFirst(arg){
    arg = arg.toLowerCase()
    return arg.substring(0,1).toUpperCase()+arg.substring(1);
}
function capAdd(arg){
    arg = arg.toLowerCase()
    var k = 0;
    var token = "";
    var adrs = arg.substring(k,1).toUpperCase();
    for(k=1; k<arg.length; k++){
        token = arg.substring(k,k+1);
        if(token == " "){
            adrs += token+arg.substring(++k,k+1).toUpperCase();
        } else {
            adrs += token;
        }
    }
    return adrs;
}
function numberFormat(arg){
    var k = 0;
    var dot = 0;
    for(k=0; k<arg.trim().length; k++){
        token = arg.substring(k,k+1);
        if(token == "."){
            dot++;
        }
    }
    if(dot == 0 && arg.trim().length > 0){
        //arg = arg.trim()+".00";
    }
    if(dot > 0){
        var arg_split = arg.split(".");
        arg = arg_split[0].trim()+"."+arg_split[1].trim() + ((arg_split[1].trim().length == 1) ? "0" : "");
    }
    return arg.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}

String.prototype.existsinString = function(arg) {
    var k = 0;
    var token = "";
    var found= false;
    for(k=1; k<this.length; k++){
        token = this.substring(k,k+1);
        if(token == arg){
            found = true;
        }
    }
    return found;
}

String.prototype.contains = function(arg) {
    var token = "";
    var found= false;
    for(var k=0; k<this.length-(arg.length-1); k++){
        token = this.substring(k,arg.length);
		alert(token);
        if(token == arg){
            found = true;
			break;
        }
    }
    return found;
}

function contains(str,arg) {
	str = str+"";
	var token = "";
	var found= false;
	for(var k=0; k<str.length-(arg.length-1); k++){
		token = str.substring(k,k+arg.length);
		if(token == arg){
			found = true;
			break;
		}
	}
	return found;
}

function createCookie(name,value,days) {
    var expires = "";
    if (days) {
        var date = new Date();
        date.setTime(date.getTime()+(days*24*60*60*1000));
        expires = "; expires="+date.toGMTString();
    }
    document.cookie = name+"="+value+expires+"; path=/";
}

function readCookie(name) {
    var nameEQ = name + "=";
    var ca = document.cookie.split(';');
    for(var i=0;i < ca.length;i++) {
        var c = ca[i];
        while (c.charAt(0)==' ') c = c.substring(1,c.length);
        if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
    }
    return "";
}

function eraseCookie(name) {
    createCookie(name,"",-1);
}

function checkEnter(e){ //e is event object passed from function invocation
	var characterCode; //literal character code will be stored in this variable

    if(e && e.which){ //if which property of event object is supported (NN4)
        e = e;
        characterCode = e.which //character code is contained in NN4's which property
    } else {
        e = event;
        characterCode = e.keyCode; //character code is contained in IE's keyCode property
    }
	if(characterCode == 13){ //if generated character code is equal to ascii 13 (if enter key)
		if(readCookie('progid')=="changePass") changePass();
		if(readCookie('progid')=="loginForm") loginForm();
        return false;
    } else {
        return true;
    }

}

function maxHeight(){
    var h = 0;
    if(window.document.innerHeight > h) h = window.document.innerHeight;
    if(window.document.documentElement.clientHeight > h) h = window.document.documentElement.clientHeight;
    if(window.document.body.clientHeight > h) h = window.document.body.clientHeight;
    return h;
}

function maxWidth() {
    var w = 0;
    if(window.document.innerWidth > w) w = window.document.innerWidth;
    if(window.document.documentElement.clientWidth > w) w = window.document.documentElement.clientWidth;
    if (window.document.body.clientWidth > w) w = window.document.body.clientWidth;
    return w;
}

function findPosX(obj){
    var curleft = 0;
    if(obj.offsetParent)
        while(1){
            curleft += obj.offsetLeft;
            if(!obj.offsetParent)
                break;
            obj = obj.offsetParent;
        }
    else if(obj.x)
        curleft += obj.x;
    return curleft;
}

function findPosY(obj){
    var curtop = 0;
    if(obj.offsetParent)
        while(1){
            curtop += obj.offsetTop;
            if(!obj.offsetParent)
                break;
            obj = obj.offsetParent;
        }
    else if(obj.y)
        curtop += obj.y;
    return curtop;
}

function createElement(E, T, L){
    var element = document.getElementById(E);
    element.style.top = T + 'px';
    element.style.left = L + 'px';
    element.style.position = 'absolute';
}

function writeFooter(E){
    var T = maxHeight() - 100;
    var L = 0; //maxWidth();
    createElement(E, T, L);
}

function toggleMenu(objID) {
    if (!document.getElementById) return;
    var ob = document.getElementById(objID).style;
    ob.display = (ob.display == 'block') ? 'none' : 'block';
}

function toggleObjInnerHTML(obj){
    var iLen = String(obj.innerHTML).length;
    obj.innerHTML = ((String(obj.innerHTML).substring(0,1) == "+") ? "-" : "+") + String(obj.innerHTML).substring(1, iLen);
}

function checkLogin(){
    if(readCookie("currentuser")==null || readCookie("currentuser")==""){
        alert("You must login to access this page!!!\n Click Ok to return to Login page.");
        window.location = "login.php";
    }
}

function closeForm(){
    window.location = "home.php";
}

//function logoutUser(){
//    eraseCookie("currentuser");
//    window.location = "login.php";
//}

function logout(){
    //alert('logout');
    eraseCookie("currentuser");
    window.location = "login.php";
}


function drawScroller(T, L){
    var scrollHeader = document.getElementById('scrollHeader');
    scrollHeader.style.top = (T + 0) + 'px';
    scrollHeader.style.left = (L + 0) + 'px';

    var scrollContentCover = document.getElementById('scrollContentCover');
    scrollContentCover.style.top = (T + 20) + 'px';
    scrollContentCover.style.left = (L + 0) + 'px';

    var scrollFooter = document.getElementById('scrollFooter');
    scrollFooter.style.top = (T + 170) + 'px';
    scrollFooter.style.left = (L + 0) + 'px';
}

// Scrollers width here (in pixels)
var scrollerwidth="300px";

// Scrollers height here
var scrollerheight="380px";
//scrollerheight = document.getElementById('footer').offsetTop - (document.getElementById('subhead').offsetTop + 8);
//var divh = document.getElementById('content').offsetHeight;
//alert(document.getElementById('footer').offsetTop +" - "+document.getElementById('subhead').offsetTop);
//alert(scrollerheight);

// Scrollers speed here (larger is faster 1-10)
var scrollerspeed = 1;

// Scrollers content goes here! Keep all of the message on the same line!
//var iescroller = document.getElementById("iescroller");
//iescroller.style.position = "absolute";
var scrollercontent='<font face="Arial" color="blue" size="3"><b>- This box, the Login dialogue box and the other boxes are dragable.<BR><BR>- The aim of drag and drop is to allow the user to work on two or more form windows cocurrently.<BR><BR>- The scrolling text can be paused by placing the mouse over it, remove the mouse and the scroll continues.</b></font>';
//Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.

var pauseit = 1;

// Change nothing below!
scrollerspeed = (document.all)? scrollerspeed : Math.max(1, scrollerspeed-1);

//slow speed down by 1 for NS
var copyspeed = scrollerspeed;
var iedom = document.getElementById || document.all;
var actualheight = '';
var cross_scroller, ns_scroller;
var pausespeed=(pauseit==0)? copyspeed : 0;

//document.getElementById("content").position='absolute';
function populateScroller(){
    if(iedom){
        cross_scroller = document.getElementById ? document.getElementById("iescroller") : document.all.iescroller;
        cross_scroller.style.top = parseInt(scrollerheight,10) + 8 + "px";
        document.getElementById("createInner2").innerHTML=document.getElementById("content").style.top;
		
        cross_scroller.innerHTML = scrollercontent;
        actualheight = cross_scroller.offsetHeight;
    } else if(document.layers){
        ns_scroller = document.ns_scroller.document.ns_scroller2;
        ns_scroller.top = parseInt(scrollerheight,10) + 8;
        ns_scroller.document.write(scrollercontent);
        ns_scroller.document.close();
        actualheight = ns_scroller.document.height
    }

    lefttime = setInterval("scrollscroller()", 20);
}

populateScroller();

function scrollscroller(){
    if(iedom){
        if(parseInt(cross_scroller.style.top,10) > (actualheight * (-1) + 8))
            cross_scroller.style.top=parseInt(cross_scroller.style.top,10) - copyspeed + "px";
        else
            cross_scroller.style.top=parseInt(scrollerheight,10) + 8 + "px";
    } else if(document.layers){
        if(ns_scroller.top > (actualheight * (-1) + 8))
            ns_scroller.top -= copyspeed;
        else
            ns_scroller.top=parseInt(scrollerheight,10)+8;
    }
}

