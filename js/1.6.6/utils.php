<?php
chdir('../..');
require './config/main.php';
$db = require './config/database.php';
ini_set('display_errors', 'Off');

$playerStats = new PlayerStats($db);
$bdata = json_encode($playerStats->getAllBorders());

$etag = md5($jsVersion.$bdata);
header('Content-type: application/javascript');
header("Cache-Control: max-age=0, private, must-revalidate");
header_remove("Pragma");
$tag = isset($_SERVER['HTTP_IF_NONE_MATCH']) ? $_SERVER['HTTP_IF_NONE_MATCH'] : '';
$tag_parts = explode('-',$tag);
$tag = $tag_parts[0];
$tag = str_replace('"','',$tag);
$iftag = $tag == $etag; 
if($iftag){
	header('HTTP/1.0 304 Not Modified');
	exit();
}else
	header('ETag: "'.$etag.'"');

//echo '//'.$etag."\n";
//echo '//'.$tag."\n";
?>
bdata = <?=$bdata?>;

function getNationName (nation) {
	if(nation == 1)return 'ussr';
	if(nation == 2)return 'germany';
	if(nation == 3)return 'usa';
	if(nation == 4)return 'china';
	if(nation == 5)return 'france';
	if(nation == 6)return 'uk';
	if(nation == 7)return 'japan';
}

function toRoman (n,s){
	// Convert to Roman Numerals
	// copyright 25th July 2005, by Stephen Chapman http://javascript.about.com
	// permission to use this Javascript on your web page is granted
	// provided that all of the code (including this copyright notice) is
	// used exactly as shown
	var r = '';
	var rn = new Array('IIII','V','XXXX','L','CCCC','D','MMMM');
	for (var i=0; i< rn.length; i++) {
		var x = rn[i].length+1;
		var d = n%x;
		r= rn[i].substr(0,d)+r;
		n = (n-d)/x;
	} 
	if (s) {
		r=r.replace(/DCCCC/g,'CM');
		r=r.replace(/CCCC/g,'CD');
		r=r.replace(/LXXXX/g,'XC');
		r=r.replace(/XXXX/g,'XL');
		r=r.replace(/VIIII/g,'IX');
		r=r.replace(/IIII/g,'IV');
	}
	return r;		                  
}

function labelClass(val,key){
	if(bdata[key]){
		if(val >= bdata[key][4])return 6;
		if(val >= bdata[key][3])return 5;
		if(val >= bdata[key][2])return 4;
		if(val >= bdata[key][1])return 3;
		if(val >= bdata[key][0])return 2;
		return 1;
	}else return false;
}

function scrClassType (scr,t){
	var f = 1;
	switch(t){
	case 1:
		f = 0.23;
		break;
	case 2:
		f = 0.35;
		break;
	case 3:
		f = 0.27;
		break;
	case 4:
		f = 0.15;
		break;
	}
	if(scr > bdata["SC3"][4]*f)return 6;
	if(scr > bdata["SC3"][3]*f)return 5;
	if(scr > bdata["SC3"][2]*f)return 4;
	if(scr > bdata["SC3"][1]*f)return 3;
	if(scr > bdata["SC3"][0]*f)return 2;
	return 1;
}

function scrClassTank (scr){
	if(scr > 2000)return 6;
	if(scr > 1500)return 5;
	if(scr > 1200)return 4;
	if(scr > 900)return 3;
	if(scr > 0)return 2;
	return 1;
}

function roundNumber (num, dec) {
	var result = Math.round(num*Math.pow(10,dec))/Math.pow(10,dec);
	return result;
}

function formatNumber(num){
	if(num > 10000000)return Math.floor(num/1000000)+"M";
	if(num > 10000)return Math.floor(num/1000)+"k";
	else return Math.round(num);
}

function formatTime(time){
	now = (new Date()).getTime();
	if(now-time < 3600*1000){
		diff = Math.round((now-time)/60000);
		if(diff == 1)text = ' minute ago'; else text = ' minutes ago';
		return diff+text;
	}
	else if(now-time < 3600*24*1000){
		diff = Math.round((now-time)/3600000);
		if(diff == 1)text = ' hour ago'; else text = ' hours ago';
		return diff+text;
	}
	else {
		diff = Math.round((now-time)/3600/24/1000);
		if(diff == 1)text = ' day ago'; else text = ' days ago';
		return diff+text;
	}
}

function calcCS3(battles, wins, wn7, base){
	var percentage = wins/battles*100,
		factor = (percentage-35)/15*Math.min(battles,75)/75;
	return base*factor*wn7/1500;
}

$.ajaxTransport("+*", function( options, originalOptions, jqXHR ) {
	
    if(jQuery.browser.msie && window.XDomainRequest && options.crossDomain) {

        var xdr;

        return {

            send: function( headers, completeCallback ) {

                // Use Microsoft XDR
                xdr = new XDomainRequest();

                xdr.open("get", options.url);

                xdr.onload = function() {

                    if(this.contentType.match(/\/xml/)){

                        var dom = new ActiveXObject("Microsoft.XMLDOM");
                        dom.async = false;
                        dom.loadXML(this.responseText);
                        completeCallback(200, "success", [dom]);

                    }else{

                        completeCallback(200, "success", [this.responseText]);

                    }

                };

                xdr.ontimeout = function(){
                    completeCallback(408, "error", ["The request timed out."]);
                };

                xdr.onerror = function(){
                    completeCallback(404, "error", ["The requested resource could not be found."]);
                };

                xdr.send();
          },
          abort: function() {
              if(xdr)xdr.abort();
          }
        };
      }
    });
