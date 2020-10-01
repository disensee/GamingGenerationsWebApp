window.addEventListener("load", function(){
	var customer;
	var tim;

	customer = namespace.CustomerModule({
		leftColumnContainer: document.getElementById("left-column"),
		midColumnContainer : document.getElementById("mid-column"),
		rightColumnContainer: document.getElementById("right-column"),
		webServiceAddress: "https://localhost/GG/web-services/customers/"
		//webServiceAddress: "https://www.dylanisensee.com/gg/web-services/customers/"
	});

	document.querySelector(".logout").addEventListener("click", function(event){
		removeCookie("PHPSESSID");
	});

	function removeCookie(cookieName){
		cookieValue = "";
    	cookieLifetime = -1;
    	var date = new Date();
    	date.setTime(date.getTime()+(cookieLifetime*24*60*60*1000));
    	var expires = "; expires="+date.toGMTString();
    	document.cookie = cookieName+"="+JSON.stringify(cookieValue)+expires+"; path=/";
	}
	
});