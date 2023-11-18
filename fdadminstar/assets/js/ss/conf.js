var solstar = new SolStar({
	name:"",
	almacen:"",
  	almacen_fi: "http://localhost/uploads/",
	loginurl:"init/login",
	loginpage:"init",
	indexpage:"init",
	recoverurl:"users/recoverPassword",
	jqconfirm:true,
	alertify:false,
	bootstraptoast:false,
	loader: "#loaderGIF",
	sessionToken: "",
	session:false,
	multilanguage: false,
	//telMask: true,
	//intTelInput: "#cellphone"
})
var almacen = solstar.params.almacen;