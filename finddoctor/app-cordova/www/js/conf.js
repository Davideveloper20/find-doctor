var solstar = new SolStar({
	name:"FindDoctor",
	//almacen:"http://localhost/fdadminstar/",
	//almacen:"http://localhost/findoctor2/fdadminstar/",
	almacen:"http://localhost/FindDoctor/fdadminstar/",	
	loginurl:"init/login",
	loginpage:"index.html",
	indexpage:"index.html",
	recoverurl:"users/recoverPassword",
	jqconfirm:true,
	alertify:false,
	loader: "#loadergif",
	sessionToken: "fdsession",
	session:false,
	multilanguage: false,
	// Token: 'KRqwT6mdGU2vOUy9yV'
	//telMask: true,
	//intTelInput: "#cellphone"
})
var almacen = solstar.params.almacen
var url_gallery = ""
var swiper = null