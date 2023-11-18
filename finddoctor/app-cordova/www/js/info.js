var shipping = {
	filled: false,
	first_name: "",
	last_name: "",
	company: "",
	address_1: "",
	address_2: "",
	city: "",
	state: "",
	postcode: "",
	country: ""
}
var info = function () {
    return {
	  	user: {},
	  	client: [],
		bandsform: {
			shipping: [
				{ field: "first_name" },
				{ field: "last_name" },
				{ field: "company" },
				{ field: "address_1" },
				{ field: "address_2" },
				{ field: "city" },
				{ field: "state" },
				{ field: "postcode" },
				{ field: "country" }
			]
		},
	  	menu: [
	      	{
	      		title: 'Inicio',
	      		url: '/',
	      		mdicon: 'home',
	      		iosicon: 'info_fill',
	      		popup: '',
	      		wpopup: '',
	      		external: ''
	      	},
	  	],
	  	products: [],
	  	carrito: {
  		 	payment_method: null,
			payment_method_title: null,
		  	set_paid: true,
		  	shipping: [],
		  	line_items: [],
		  	looks: [],
  			shipping_lines: [
			    {
			      method_id: "flat_rate",
			      method_title: "Flat Rate",
			      total: 10
			    }
		  	]
	  	}
  	}
}