/**
 * Cookie plugin
 *
 * Copyright (c) 2006 Klaus Hartl (stilbuero.de)
 * Dual licensed under the MIT and GPL licenses:
 * http://www.opensource.org/licenses/mit-license.php
 * http://www.gnu.org/licenses/gpl.html
 *
 */

/**
 * Create a cookie with the given name and value and other optional parameters.
 *
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Set the value of a cookie.
 * @example $.cookie('the_cookie', 'the_value', { expires: 7, path: '/', domain: 'jquery.com', secure: true });
 * @desc Create a cookie with all available options.
 * @example $.cookie('the_cookie', 'the_value');
 * @desc Create a session cookie.
 * @example $.cookie('the_cookie', null);
 * @desc Delete a cookie by passing null as value. Keep in mind that you have to use the same path and domain
 *       used when the cookie was set.
 *
 * @param String name The name of the cookie.
 * @param String value The value of the cookie.
 * @param Object options An object literal containing key/value pairs to provide optional cookie attributes.
 * @option Number|Date expires Either an integer specifying the expiration date from now on in days or a Date object.
 *                             If a negative value is specified (e.g. a date in the past), the cookie will be deleted.
 *                             If set to null or omitted, the cookie will be a session cookie and will not be retained
 *                             when the the browser exits.
 * @option String path The value of the path atribute of the cookie (default: path of page that created the cookie).
 * @option String domain The value of the domain attribute of the cookie (default: domain of page that created the cookie).
 * @option Boolean secure If true, the secure attribute of the cookie will be set and the cookie transmission will
 *                        require a secure protocol (like HTTPS).
 * @type undefined
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */

/**
 * Get the value of a cookie with the given name.
 *
 * @example $.cookie('the_cookie');
 * @desc Get the value of a cookie.
 *
 * @param String name The name of the cookie.
 * @return The value of the cookie.
 * @type String
 *
 * @name $.cookie
 * @cat Plugins/Cookie
 * @author Klaus Hartl/klaus.hartl@stilbuero.de
 */
jQuery.cookie = function(name, value, options) {
    if (typeof value != 'undefined') { // name and value given, set cookie
        options = options || {};
        if (value === null) {
            value = '';
            options = $.extend({}, options); // clone object since it's unexpected behavior if the expired property were changed
            options.expires = -1;
        }
        var expires = '';
        if (options.expires && (typeof options.expires == 'number' || options.expires.toUTCString)) {
            var date;
            if (typeof options.expires == 'number') {
                date = new Date();
                date.setTime(date.getTime() + (options.expires * 24 * 60 * 60 * 1000));
            } else {
                date = options.expires;
            }
            expires = '; expires=' + date.toUTCString(); // use expires attribute, max-age is not supported by IE
        }
        // NOTE Needed to parenthesize options.path and options.domain
        // in the following expressions, otherwise they evaluate to undefined
        // in the packed version for some reason...
        var path = options.path ? '; path=' + (options.path) : '';
        var domain = options.domain ? '; domain=' + (options.domain) : '';
        var secure = options.secure ? '; secure' : '';
        document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
    } else { // only name given, get cookie
        var cookieValue = null;
        if (document.cookie && document.cookie != '') {
            var cookies = document.cookie.split(';');
            for (var i = 0; i < cookies.length; i++) {
                var cookie = jQuery.trim(cookies[i]);
                // Does this cookie string begin with the name we want?
                if (cookie.substring(0, name.length + 1) == (name + '=')) {
                    cookieValue = decodeURIComponent(cookie.substring(name.length + 1));
                    break;
                }
            }
        }
        return cookieValue;
    }
};

/*
 * jQuery JSON Plugin
 * version: 2.1 (2009-08-14)
 *
 * This document is licensed as free software under the terms of the
 * MIT License: http://www.opensource.org/licenses/mit-license.php
 *
 * Brantley Harris wrote this plugin. It is based somewhat on the JSON.org 
 * website's http://www.json.org/json2.js, which proclaims:
 * "NO WARRANTY EXPRESSED OR IMPLIED. USE AT YOUR OWN RISK.", a sentiment that
 * I uphold.
 *
 * It is also influenced heavily by MochiKit's serializeJSON, which is 
 * copyrighted 2005 by Bob Ippolito.
 */
 
(function($) {
    /** jQuery.toJSON( json-serializble )
        Converts the given argument into a JSON respresentation.

        If an object has a "toJSON" function, that will be used to get the representation.
        Non-integer/string keys are skipped in the object, as are keys that point to a function.

        json-serializble:
            The *thing* to be converted.
     **/
    $.toJSON = function(o)
    {
        if (typeof(JSON) == 'object' && JSON.stringify)
            return JSON.stringify(o);
        
        var type = typeof(o);
    
        if (o === null)
            return "null";
    
        if (type == "undefined")
            return undefined;
        
        if (type == "number" || type == "boolean")
            return o + "";
    
        if (type == "string")
            return $.quoteString(o);
    
        if (type == 'object')
        {
            if (typeof o.toJSON == "function") 
                return $.toJSON( o.toJSON() );
            
            if (o.constructor === Date)
            {
                var month = o.getUTCMonth() + 1;
                if (month < 10) month = '0' + month;

                var day = o.getUTCDate();
                if (day < 10) day = '0' + day;

                var year = o.getUTCFullYear();
                
                var hours = o.getUTCHours();
                if (hours < 10) hours = '0' + hours;
                
                var minutes = o.getUTCMinutes();
                if (minutes < 10) minutes = '0' + minutes;
                
                var seconds = o.getUTCSeconds();
                if (seconds < 10) seconds = '0' + seconds;
                
                var milli = o.getUTCMilliseconds();
                if (milli < 100) milli = '0' + milli;
                if (milli < 10) milli = '0' + milli;

                return '"' + year + '-' + month + '-' + day + 'T' +
                             hours + ':' + minutes + ':' + seconds + 
                             '.' + milli + 'Z"'; 
            }

            if (o.constructor === Array) 
            {
                var ret = [];
                for (var i = 0; i < o.length; i++)
                    ret.push( $.toJSON(o[i]) || "null" );

                return "[" + ret.join(",") + "]";
            }
        
            var pairs = [];
            for (var k in o) {
                var name;
                var type = typeof k;

                if (type == "number")
                    name = '"' + k + '"';
                else if (type == "string")
                    name = $.quoteString(k);
                else
                    continue;  //skip non-string or number keys
            
                if (typeof o[k] == "function") 
                    continue;  //skip pairs where the value is a function.
            
                var val = $.toJSON(o[k]);
            
                pairs.push(name + ":" + val);
            }

            return "{" + pairs.join(", ") + "}";
        }
    };

    /** jQuery.evalJSON(src)
        Evaluates a given piece of json source.
     **/
    $.evalJSON = function(src)
    {
        if (typeof(JSON) == 'object' && JSON.parse)
            return JSON.parse(src);
        return eval("(" + src + ")");
    };
    
    /** jQuery.secureEvalJSON(src)
        Evals JSON in a way that is *more* secure.
    **/
    $.secureEvalJSON = function(src)
    {
        if (typeof(JSON) == 'object' && JSON.parse)
            return JSON.parse(src);
        
        var filtered = src;
        filtered = filtered.replace(/\\["\\\/bfnrtu]/g, '@');
        filtered = filtered.replace(/"[^"\\\n\r]*"|true|false|null|-?\d+(?:\.\d*)?(?:[eE][+\-]?\d+)?/g, ']');
        filtered = filtered.replace(/(?:^|:|,)(?:\s*\[)+/g, '');
        
        if (/^[\],:{}\s]*$/.test(filtered)){
            return eval("(" + src + ")");
		} else {
            //throw new SyntaxError("Error parsing JSON, source is not valid.");
		}
    };

    /** jQuery.quoteString(string)
        Returns a string-repr of a string, escaping quotes intelligently.  
        Mostly a support function for toJSON.
    
        Examples:
            >>> jQuery.quoteString("apple")
            "apple"
        
            >>> jQuery.quoteString('"Where are we going?", she asked.')
            "\"Where are we going?\", she asked."
     **/
    $.quoteString = function(string)
    {
        if (string.match(_escapeable))
        {
            return '"' + string.replace(_escapeable, function (a) 
            {
                var c = _meta[a];
                if (typeof c === 'string') return c;
                c = a.charCodeAt();
                return '\\u00' + Math.floor(c / 16).toString(16) + (c % 16).toString(16);
            }) + '"';
        }
        return '"' + string + '"';
    };
    
    var _escapeable = /["\\\x00-\x1f\x7f-\x9f]/g;
    
    var _meta = {
        '\b': '\\b',
        '\t': '\\t',
        '\n': '\\n',
        '\f': '\\f',
        '\r': '\\r',
        '"' : '\\"',
        '\\': '\\\\'
    };
})(jQuery);


/** 
 * JSON Cookie - jquery.jsoncookie.js
 *
 * Sets and retreives native JavaScript objects as cookies.
 * Depends on the object serialization framework provided by JSON2.
 *
 * Dependencies: jQuery, jQuery Cookie, JSON2
 * 
 * @project JSON Cookie
 * @author Randall Morey
 * @version 0.9
 */
(function ($) {
	var isObject = function (x) {
		return (typeof x === 'object') && !(x instanceof Array) && (x !== null);
	};
	
	$.extend({
		getJSONCookie: function (cookieName) {
			/*
			var cookieData = $.cookie(cookieName);
			return cookieData ? JSON.parse(cookieData) : {};
			*/
			var cookieData = $.cookie(cookieName);
			obj = cookieData ? $.evalJSON(cookieData) : {};
			return obj;
			
		},
		setJSONCookie: function (cookieName, data, options) {
			var cookieData = '';
			
			options = $.extend({
				expires: 90,
				path: '/'
			}, options);
							
			if (!isObject(data)) {	// data must be a true object to be serialized
				throw new Error('JSONCookie data must be an object');
			}
			return $.cookie(cookieName, $.toJSON (data), options);
		},
		removeJSONCookie: function (cookieName) {
			return $.cookie(cookieName, null);
		},
		JSONCookie: function (cookieName, data, options) {
			
			if (isObject(data)) {
				$.setJSONCookie(cookieName, data, options);
			}else{
				return $.getJSONCookie(cookieName);
			}
		}
	});
})(jQuery);





function fontSize(act, obj){
	var isObject = function (x) {
		return (typeof x === 'object') && !(x instanceof Array) && (x !== null);
	};
	
	if (!act){
		var obj = $.JSONCookie('fontSize');
	}
	
	var pSize = parseInt($('p').css('fontSize'));
	var mSize = parseInt($('.subNavi').css('fontSize'));
	var h2Size = parseInt($('h2').css('fontSize'));
	var ulSize = parseInt($('ul').css('fontSize'));
	var h3Size = parseInt($('h3').css('fontSize'));

	// Save Initial
	$.initFontSize = $.JSONCookie('fontSizeInit');
	
	if (!$.initFontSize.pSize){
		var initFontSize = {
			pSize: pSize,
			mSize: mSize,

			h2Size: h2Size,
			ulSize: ulSize,
			h3Size: h3Size
		}; 

		$.JSONCookie('fontSizeInit', initFontSize, {path: '/'});
	}
	
	var dPlus = 3;
	var dMinus = 4;
	
	if (act == 'more'){
		pSize = (pSize + 1 <= $.initFontSize.pSize + dPlus) ? (pSize + 1) : pSize;
		mSize = (mSize + 1 <= $.initFontSize.mSize + dPlus) ? (mSize + 1) : mSize;
		h2Size = (h2Size + 1 <= $.initFontSize.h2Size + dPlus) ? (h2Size + 1) : h2Size;
		ulSize = (ulSize + 1 <= $.initFontSize.ulSize + dPlus) ? (ulSize + 1) : ulSize;
		h3Size = (h3Size + 1 <= $.initFontSize.h3Size + dPlus) ? (h3Size + 1) : h3Size;
	} else if (act == 'less'){
		pSize = (pSize - 1 >= $.initFontSize.pSize - dMinus) ? (pSize - 1) : pSize;
		mSize = (mSize - 1 >= $.initFontSize.mSize - dMinus) ? (mSize - 1) : mSize;
		h2Size = (h2Size - 1 >= $.initFontSize.h2Size - dMinus) ? (h2Size - 1) : h2Size;
		ulSize = (ulSize - 1 >= $.initFontSize.ulSize - dMinus) ? (ulSize - 1) : ulSize;		
		h3Size = (h3Size - 1 >= $.initFontSize.h3Size - dMinus) ? (h3Size - 1) : h3Size;
	} else if (!act && isObject(obj)){
		if (!obj.pSize){return}
	}
	
	if (act){
		var obj = {
			pSize: pSize,
			mSize: mSize,
			h2Size: h2Size,
			ulSize: ulSize,
			h3Size: h3Size
		};
	}
	
	if (isObject(obj)){
		$('p').css('fontSize', obj.pSize);
		$('.subNavi').css('fontSize', obj.mSize);
		$('h2').css('fontSize', obj.h2Size);
		$('ul').css('fontSize', obj.ulSize);
		$('h3').css('fontSize', obj.h3Size);
		$.JSONCookie('fontSize', obj, {path: '/'});
	}
}

function subNavi(){
	// Subnavi
	var subNavi = $('<div />').attr('id','subNaviInner')
	.css({position: 'absolute'});
	
	$('h2').each( function(i, v){ 
		var el = $(v);
		var n = i+1;
		el.attr ('id', 'heading_'+n);
		el.attr ('name', 'heading_'+n);
		var lnk = $('<div />').html(el.html()).addClass('subNavi').click(function(){
			$(window).scrollTo('#heading_'+n,{
				duration: 300,
				onAfter: function(){
					
					$('#heading_'+n).addClass('point');
					setTimeout(function(){$('#heading_'+n).removeClass('point');}, 1500);
				}
			});
		}).hover(
			function(){
				$(this).addClass("subNaviHover");
			},
			function(){
				$(this).removeClass("subNaviHover");
			}
		);
		$(subNavi).append(lnk);
		if (el.html().match(/Options/gi)){
			var oopt = optionsDrop();
			$(subNavi).append('<DIV style="margin-bottom: 5px">'+oopt+'</DIV>');			
		}
	});
	

	
	$('#rightNavi').append(subNavi);
	
	fontSize();
	var subNaviInnerPos = $('#subNaviInner').position();
	var inSubNaviTop = Math.round(subNaviInnerPos.top);
	
	$(window).bind('scroll', function(){
		var top = $(window).scrollTop();
		var pos = top+20;

		if (pos < inSubNaviTop){
			pos = inSubNaviTop;
		}
		$('#subNaviInner').animate({
				top: pos
			},{
				queue: false,
				duration: 300,
				easing: 'swing'
			}
		);
	});	
}

function showTransition(trans, obj){
	var w = parseInt($('#'+trans).width());
	var cp = $(obj).position();
	var l = 0;
	if (cp.left == 0){
		l = w - 30;
	}
	
	$(obj).stop().animate({
		left: l
	},{
		queue: false, 
		duration: 1500, 
		easing: trans,
		complete: function(){
			
		}
	});
}

function optionsDrop(){
	var opt = new Array();
	$('#optionsTable tbody tr').each(function(){
		var id = $(this).attr('id');
		if (id){
			//opt += '<option value="'+id+'">'+id+'</option>';
			//opt[id] = id;
			opt.push(id);
		}
	});
	
	if (opt.length > 2){
		var top = opt[0];
		opt.sort();
		optString = '<option value="'+top+'">Options A - Z</value>';
		$.each(opt,function(a,b){
			optString += '<option value="'+b+'">'+b+'</option>'; 
		});
		var ret = '<select style="width: 178px" onChange="$(window).scrollTo(\'#\'+this.value, {duration: 300});">';
		ret += optString;
		ret += '</select>';
		return ret;
	}
}


function reloadCaptcha(){
	$('#captchaImage').fadeTo('fast',0.05,function(){
		$('#captchaImage').load("captcha.php?ajax=1",'',function(){
			$('#captchaImage').fadeTo('fast',1.0);
			$('#private_key').attr('value','');
		}); 
	});
}


function captchaMp3() {
	var mp3cf = "/captcha/captchaMP3.php";
	var delaytime = 2500;
	var delayer = false;

	var IEswitch = true;
	
	var msie = navigator.userAgent.toLowerCase();
	msie = (msie.indexOf("msie") > -1) ? true : false;
	var d = new Date();
	if (delayer) {return false;}
	delayer = true;
	setTimeout(function(){delayer = false;}, delaytime);
	if (IEswitch && document.all && msie) { 	
		if (Number(parseFloat(navigator.appVersion.split('MSIE')[1])) < 7) {
			embed = document.createElement("bgsound");
			embed.setAttribute("src", mp3cf + "?cfsnd=" + d.getTime());
			document.getElementsByTagName("body")[0].appendChild(embed);
			return true;
		}
	} 
	if (document.getElementById) { 
		var mp3player = '<embed src="' + mp3cf + "?cfsnd=" + d.getTime() + '"';
		mp3player += ' hidden="true" type="audio/x-mpeg" autostart="true" />';
		document.getElementById('codecf').innerHTML = mp3player; 
	}
	return true;
}

function disable_submit(btn){btn.SUBMIT_BTN.value=btn_messege;btn.SUBMIT_BTN.disabled=true;}

