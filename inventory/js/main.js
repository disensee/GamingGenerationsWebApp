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
	
});