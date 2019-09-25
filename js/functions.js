String.prototype.trim = function() {
 	return this.replace(/^\s+|\s+$/g,"");
}
String.prototype.ltrim = function() {
 	return this.replace(/^\s+/,"");
}
String.prototype.rtrim = function() {
	return this.replace(/\s+$/,"");
}
Array.prototype.has = function(v) {
	for (i=0; i<this.length; i++) {
		if (this[i] == v) return i;
	}
	return false;
}
Array.prototype.exists = function(v) {
	for (i=0; i<this.length; i++) {
		if (this[i] == v) return true;
	}
	return false;
}

function jump(url) {
	self.location.hash = url
}

function empty(str) {
 	var res = str.trim();
 	if (res.length == 0) return true;
 	return false;
}

function go(url){
	window.document.location.href=url;	
}

function getCurrTime() {
	var d = new Date();
	var t = d.getTime();
	return t;
}

function gopopup(url) {
	var newwindow=window.open(url,'name'+getCurrTime());
	if (window.focus) {newwindow.focus()}
}

function OnDelete(url){
	if( confirm("Вы уверены?") ){
		window.document.location.href=url;
	}
}

$(document).ready(function(){
	$(".selected").mouseover(function() {
		var current = $(this).css('background-color');
		$(this).attr('rel', current);
		$(this).css('background-color','#eee');
	}).mouseout(function() {
		var old = $(this).attr('rel');
		if (old === undefined || old === null || old.toString().length == 0) old = '#fff';
		$(this).css('background-color', old);		
	});
});