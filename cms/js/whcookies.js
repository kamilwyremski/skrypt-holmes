
function WHCreateCookie(name, value, days) {
    var date = new Date();
    date.setTime(date.getTime() + (days*24*60*60*1000));
    var expires = "; expires=" + date.toGMTString();
	document.cookie = name+"="+value+expires+"; path=/";
}
function WHReadCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0; i < ca.length; i++) {
		var c = ca[i];
		while (c.charAt(0) == ' ') c = c.substring(1, c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length, c.length);
	}
	return null;
}

window.onload = WHCheckCookies;

function WHCheckCookies() {
    if(WHReadCookie('cookies_accepted') != 'T') {
		function moveCookie(){
			var cookies_message = document.getElementById('cookies-message');
			cookies_message.style.top = parseInt(cookies_message.style.top) + 1 + 'px';
			if(parseInt(cookies_message.style.top)<0){
				animate = setTimeout(moveCookie,30);
			}
		}
		animate = setTimeout(moveCookie,1000);
    }else{
		var cookies_message = document.getElementById('cookies-message');
		cookies_message.style.display = "none";
	}
}

function WHCloseCookiesWindow() {
    WHCreateCookie('cookies_accepted', 'T', 365);
    document.getElementById('cookies-message-container').removeChild(document.getElementById('cookies-message'));
}
