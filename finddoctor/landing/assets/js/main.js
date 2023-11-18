$(function() {
	
});

$("#btn_close_list").click(function(e){
	e.preventDefault();

	$("#list_specialist").addClass("d-none");
});

$("#btn_search").click(function(e){
	var specialist = $("#search_specialist").val();
	var city = $("#search_city").val();

	if(specialist != "" || city != "")
	{	
		$("#btn_search").html('<i class="fa fa-circle-notch fa-spin"></i> Buscando...');
		$("#list_specialist").removeClass("d-none");
		$("#content_specialist").html('<p class="text-center"><i class="fa fa-circle-notch fa-spin"></i> Encontrando especialistas, espera por favor...</p>');
		$.ajax({
            url: 'https://fileblocks.co/find-doctor/searchDoctor/?city=1&speciality=1',
            dataType: 'json',
            method: 'post'
        }).done(function (response) {
	        if(response)
	        {
	        	if(response.data.length > 0)
	        	{
	        		//self.setContent('Encontramos los siguientes especialistas');
	        		var specialists = '';
	        		response.data.forEach( function(element, index) {
	        			specialists += '<div class="card mt-2 border-0 card-block p-3" style="font-size:15px;">'+
										'<div class="row">'+
											'<div class="col-md-2">'+
												'<img src="'+element.profileimage+'" class="img-fluid d-block mx-auto">'+
											'</div>'+
											'<div class="col-md-10">'+
												'<h6 class="card-title">'+element.titulo+'. '+element.fullname+'<span class="float-right"> '+element.rating+' <i class="fa fa-star text-warning"></i></span></h6>'+
												'<p class="text-muted">'+
													'<i class="fa fa-map-marker-alt"></i> '+element.address+'<br>'+
													'<i class="fa fa-phone"></i> '+element.phonenumber+
													'<br>'+element.description+
												'</p>'+
												'<!--<small class="text-primary">$50.000-$70.000</small>-->'+
												'<div class="d-block">'+
													'<button class="btn btn-sm btn-primary float-right px-3 mx-1" onclick="getCalendar('+element.idusuario+')"><i class="fa fa-calendar"></i> Agendar</button>'+
													'<button class="btn btn-sm btn-primary float-right px-3 mx-1" onclick="chat('+element.idusuario+')" data-toggle="modal" data-target="#chat"><i class="fa fa-comment"></i> Chatear</button>'+
												'</div>'+
											'</div>'+
										'</div>'+
									'</div>';
					});

					$("#content_specialist").html(specialists);
	        		//$("#list_specialist").removeClass("d-none");
	        	}
	        }
	        $("#btn_search").html('Buscar');
	    }).fail(function(){
	    	$("#btn_search").html('Buscar');
	        /*$.alert({
			    title: 'Sin resultados',
			    content: 'No se encontraron especialistas',
			    type: 'orange',
			    buttons: {
			        Aceptar: function () {
			        }
			    },
			    closeIcon: true
			});*/

			//$("#list_specialist").removeClass("d-none");
			$("#content_specialist").html('<p class="text-center"><i class="fa fa-close"></i> No se encontraron especialistas en la busqueda.</p>');
	    });

	}else{
		$("#btn_search").html('Buscar');
		$.alert({
		    title: 'No hay busqueda',
		    content: 'Es necesario que ingreses un especialista o una ciudad.',
		    type: 'orange',
		    buttons: {
		        Aceptar: function () {
		        }
		    },
		    closeIcon: true
		});
	}
});

function chat(specialist)
{
	$.confirm({
    title: 'Iniciar chat',
    content: '<textarea class="form-control" rows="2">',
    buttons: {
        formSubmit: {
            text: 'Enviar',
            btnClass: 'btn-blue',
            action: function () {
                $.dialog("Se envió un mensaje al especialista");
            }
        },
        Cancelar: function () {
            //close
        },
    },
    onContentReady: function () {
        // bind to events
        var jc = this;
        this.$content.find('form').on('submit', function (e) {
            // if the user submits the form by pressing enter in the field.
            e.preventDefault();
            jc.$$formSubmit.trigger('click'); // reference the button and click it
        });
    }
});
}

function getCalendar(specialist)
{
	$.confirm({
    title: 'Agendar cita',
    content: '<div class="container">'+
	'<div class="row">'+
        '<div class="col-md-12">'+
    		'<center><table class="table-condensed table-bordered table-striped">'+
                '<thead>'+
                    '<tr>'+
                      '<th colspan="7">'+
                        '<span class="btn-group">'+
                            '<a class="btn"><i class="fa fa-arrow-left"></i></a>'+
                        	'<a class="btn active">Noviembre 29 2019</a>'+
                        	'<a class="btn"><i class="fa fa-arrow-right"></i></a>'+
                        '</span>'+
                      '</th>'+
                    '</tr>'+
                    '<tr>'+
                        '<th class="text-center">Lu</th>'+
                        '<th class="text-center">Ma</th>'+
                        '<th class="text-center">Mi</th>'+
                        '<th class="text-center">Ju</th>'+
                        '<th class="text-center">Vi</th>'+
                        '<th class="text-center">Sa</th>'+
                        '<th class="text-center">Do</th>'+
                    '</tr>'+
                '</thead>'+
                '<tbody align="center">'+
                    '<tr>'+
                        '<td class="text-muted">29</td>'+
                        '<td class="text-muted">30</td>'+
                        '<td class="text-muted">31</td>'+
                        '<td class="text-muted">1</td>'+
                        '<td class="text-muted">2</td>'+
                        '<td class="text-muted">3</td>'+
                        '<td class="text-muted">4</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td class="text-muted">5</td>'+
                        '<td class="text-muted">6</td>'+
                        '<td class="text-muted">7</td>'+
                        '<td class="text-muted">8</td>'+
                        '<td class="text-muted">9</td>'+
                        '<td class="text-muted">10</td>'+
                        '<td class="text-muted">11</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td class="text-muted">12</td>'+
                        '<td class="text-muted">13</td>'+
                        '<td class="text-muted">14</td>'+
                        '<td class="text-muted">15</td>'+
                        '<td class="text-muted">16</td>'+
                        '<td class="text-muted">17</td>'+
                        '<td class="text-muted">18</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td class="text-muted">19</td>'+
                        '<td class="text-muted">20</td>'+
                        '<td class="text-muted">21</td>'+
                        '<td class="text-muted">22</td>'+
                        '<td class="text-muted">23</td>'+
                        '<td class="text-muted">24</td>'+
                        '<td class="text-muted">25</td>'+
                    '</tr>'+
                    '<tr>'+
                        '<td class="text-muted">26</td>'+
                        '<td class="text-muted">27</td>'+
                        '<td class="text-muted">28</td>'+
                        '<td class="btn-primary">29<strong></td>'+
                        '<td>1</td>'+
                        '<td>2</td>'+
                        '<td>3</td>'+
                    '</tr>'+
                '</tbody>'+
            '</table></center>'+
        '</div>'+
	'</div>'+
'</div>',
    buttons: {
        formSubmit: {
            text: 'Aceptar',
            btnClass: 'btn-blue',
            action: function () {
               
            }
        },
        Cancelar: function () {
            //close
        },
    },
    onContentReady: function () {
        // bind to events
        var jc = this;
        this.$content.find('form').on('submit', function (e) {
            // if the user submits the form by pressing enter in the field.
            e.preventDefault();
            jc.$$formSubmit.trigger('click'); // reference the button and click it
        });
    }
});
}