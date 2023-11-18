var url = "192.168.0.115:8000";
solstar.init({
	name:"CYCLOS",
	almacen:"http://"+url+"/",
	loginurl:"auth/signIn",
	loginpage:"login.html",
	indexpage:"index.html",
	recoverurl:"auth/recoverPassword",
	jqconfirm:true,
	alertify:false,
	loader: "#loadergif",
	sessionToken: "bmsession",
	session:false,
	multilanguage: false,
	telMask: true,
	intTelInput: "#cellphone"
})
var almacen = solstar.params.almacen
var url_gallery = "http://"+url+"/beamaster/api/uploads/images/"
var swiper = null