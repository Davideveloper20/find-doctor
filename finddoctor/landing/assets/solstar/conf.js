//var url = "fileblocks.co/api";
var url = "localhost/finddoctor/backend";
solstar.init({
	name:"FindDoctorand",
	almacen: location.protocol+"//"+url+"/",
	loginurl:"auth/signIn",
	loginpage:"login.html",
	indexpage:"index.html",
	recoverurl:"auth/recoverPassword",
	jqconfirm:true,
	alertify:false,
	loader: "#loadergif",
	sessionToken: "fbsession",
	session:false,
	multilanguage: false,
	//telMask: false,
	//intTelInput: "#cellphone",
})
var almacen = solstar.params.almacen
var url_gallery = location.protocol+"//"+url+"/beamaster/api/uploads/images/"
var swiper = null
if((typeof $) == 'function') {
	$.extend(true, $, solstar);
}