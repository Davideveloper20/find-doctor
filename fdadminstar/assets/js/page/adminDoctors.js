"use strict";
moment().locale('es');
var admapp = {lat:6.22636423654194, lng:-75.7953717514038}, 
marker = false, dT=null, geocoder = null, map=null, 
redrawDt = function(evt) {
  $('#dtDatatable .editDoctor').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.editDoctor', $data);
    solstar.ajaxRequest('Admin/Doctor-Save', 'PATCH', function(resp) {
        if(resp.success && resp.data) {
          console.log(resp)
          $('#modalDocEduc #saveDoctor').data('type', 1);
          $('#modalDocEduc #saveDoctor, #modalDocEduc').data('reqid', resp.data.iddoctor);
          $('#modalDocEduc #saveDoctor, #modalDocEduc').data('reqidusers', resp.data.idusers);
          $('#modalDocEduc #titulo').val(resp.data.titulo);
          $('#modalDocEduc #fullname').val(resp.data.fullname);
          $('#modalDocEduc #type_doc').val(resp.data.type_doc);
          $('#modalDocEduc #document').val(resp.data.document);
          $('#modalDocEduc #rethus').val(resp.data.rethus);
          $('#modalDocEduc #gender').val(resp.data.gender);
          $('#modalDocEduc #numhabi').val(resp.data.numhabi);
          $('#modalDocEduc #birthdate').val(resp.data.birthdate);
          $('#modalDocEduc #latitude').val(resp.data.latitude);
          $('#modalDocEduc #longitude').val(resp.data.longitude);
          //$('#modalDocEduc #phonenumber').val(resp.data.phonenumber);
          $('#modalDocEduc #phonenumber').intlTelInput('setNumber', resp.data.phonenumber);
          $('#modalDocEduc #idcountry').data('id',resp.data.idcountry);
          $('#modalDocEduc #idcountry').val(resp.data.country);
          $('#modalDocEduc #idcity').data('id', resp.data.idcity);
          $('#modalDocEduc #idcity').data('idstate', resp.data.idstate);
          $('#modalDocEduc #idcity').val(resp.data.city+', '+resp.data.state);
          $('#modalDocEduc #address').val(resp.data.address);
          $('#modalDocEduc #especiality').val(resp.data.especiality);
          $('#modalDocEduc #enferme_trat').val(resp.data.enferme_trat);
          $('#modalDocEduc #description').val(resp.data.aboutme);
          $('#modalDocEduc #saveDoctor').html('<i class="fa fa-save"></i> Actualizar');
          $('#modalDocEduc').modal({
            show: true,
            backdrop: 'static',
            keyboard: false,
          });
        } else {
          solstar.jqc_alert('No se encontró proveedor!','Intente nuevamente','modern','fas fa-exclamation fa-1x', 'primary', {
            cancel: {
              text: 'Aceptar', // text for button
              btnClass: 'btn-secondary', // class for the button
              //keys: ['enter', 'a'], // keyboard event for button
              isHidden: false, // initially not hidden
              isDisabled: false, // initially not disabled
              action: function(heyThereButton){
                  // longhand method to define a button
                  // provides more features
              }
            }
          });
          alertify.warning(resp.message, 10);
        }
      }, {id: ($data.id || -1 )}, true, null, function(err) {
        alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
      }
    );
  });
  $('#dtDatatable .deleteDoctor').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.deleteDoctor', $data)
    solstar.jqc_alert('Eliminar Doctor!','¿Confirma que desea eliminar este registro?','modern','fas fa-trash fa-1x', 'red', {
      cancel: {
        text: 'Cancelar', // text for button
        btnClass: 'btn-secondary', // class for the button
        //keys: ['enter', 'a'], // keyboard event for button
        isHidden: false, // initially not hidden
        isDisabled: false, // initially not disabled
        action: function(heyThereButton){
            // longhand method to define a button
            // provides more features
        }
      },
      delete: {
        text: 'Si, Eliminar!', // text for button
        btnClass: 'btn-danger', // class for the button
        //keys: ['enter', 'a'], // keyboard event for button
        isHidden: false, // initially not hidden
        isDisabled: false, // initially not disabled
        action: function() {
          solstar.ajaxRequest('Admin/DoctorsSave', 'DELETE', function(resp) {
              if(resp.success) {
                $('#dtDatatable').DataTable().ajax.reload();
              } else {
                alertify.warning(resp.message, 10);
              }
            }, {
              id: ($data.id || -1 )
            }, true, null, function(err) {
              alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
            }
          );
        }
      }
    });
  });
  $('#dtDatatable .doctorChangeStatus').off('change').on('change', function(evt) {
    var el = evt.currentTarget
    console.log(evt)
    var $check = el.checked;
    var $this = el;
    solstar.jqc_alert('Cambio de Estatus!','¿Confirma que desea cambiar a status <b>'+($this.checked ? 'Activo': 'Inactivo')+'</b> este doctor?','modern',($this.checked ? 'fas fa-toggle-on': 'fas fa-toggle-off')+' fa-1x', ($this.checked ? 'blue': 'red'), {
      Cancelar: function(){$this.checked = !$check;},
      "Si, Cambiar!": function() {
        solstar.ajaxRequest('Admin/DoctorsChgStatus/'+($check?1:0), 'POST', function(resp) {
            if(resp.success) {
              solstar.jqc_alert('Estado cambiado!','El doctor ahora está: <b>'+($this.checked ? 'Activo': 'Inactivo')+'</b>','modern',($this.checked ? 'far fa-check-circle': 'fas fa-check-circle')+' fa-1x', ($this.checked ? 'blue': 'red'), {})
            } else {
              $this.checked = !$check;
              alertify.warning(resp.message, 10);
            }
          }, $($this).data(), true, null, function(err) {
            $this.checked = !$check;
            alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
          }
        );
      }
    });
  });
}, currGeo = function(locate) {
  console.log('currGeo', locate);
  admapp.lat = parseFloat(locate.latitude);
  admapp.lng = parseFloat(locate.longitude);
  if($('#latitude').val() == '') {
    $('#latitude').val(admapp.lat);
  }
  if($('#longitude').val() == '') {
    $('#longitude').val(admapp.lng);
  }
  //$.getScript('https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDg2Z6QWeOzf4FkS5ZYQx2Isw6efT90dHI&libraries=geometry&callback=initMap');
}, initMap = function () {
  geocoder = new google.maps.Geocoder();
  var lat = parseFloat($('#latitude').val() || admapp.lat);
  var lon = parseFloat($('#longitude').val() || admapp.lng);
  console.log('initMap', {lat: lat, lng:lon});
  map = new google.maps.Map(document.getElementById('map'), {
    center: new google.maps.LatLng(lat, lon),
    zoom: 12
  });
  var imgmarker = new google.maps.MarkerImage(
    solstar.Almacen_fi+'assets/images/icon.png',
    new google.maps.Size(71, 71),
    new google.maps.Point(0, 0),
    new google.maps.Point(17, 34),
    new google.maps.Size(35, 35)
  );

  marker = new google.maps.Marker({
    position: {lat: lat, lng: lon}, 
    map: map,
    title: 'Estoy Aqui!',
    draggable: true,
    animation: google.maps.Animation.DROP,
    icon: imgmarker,
  });
  
  marker.addListener('click', toggleBounce);
  google.maps.event.addListener(marker, 'dragend', function() {
      console.log('Drag ended');
      let p = marker.getPosition();
      $('#latitude').val(p.lat());
      $('#longitude').val(p.lng());
      geocodePosition(p);
  });
}, geocodePosition = function(pos) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      console.log('responses[0]', responses[0])
      $('#idcountry, #idcity', '#modalDocEduc').val('');
      $('#idcountry, #idcity', '#modalDocEduc').data('id', '');
      var citydet = '';
      responses[0].address_components.forEach( function(el, index) {
        console.log('el', el.types[0])
        if(el.types[0] == 'country') {
          $.map(countries, function(item, index) {
            if(item.item == el.long_name) {
              $('#idcountry', '#modalDocEduc').val(item.item);
              $('#idcountry', '#modalDocEduc').data('id', item.id);
              var url = `getCities/${item.id}`;
              solstar.ajaxRequest(url, 'POST', function(resp) {
                  console.log(url, resp)
                  if(resp.success) {
                    solstar.autocomplete($('#idcity', '#modalDocEduc')[0], cities, null, function(obj) {
                      $('#idcity', '#modalDocEduc').data('id', obj.id);
                      $('#idcity', '#modalDocEduc').data('idstate', obj.item.idstate);
                    }, false);
                  } else {
                    alertify.warning('Ha ocurrido un error! '+resp.message, 10);
                  }
                }, {
                }, true, null, function(err) {
                  alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
                }
              );
            }
          });
        }

        if(el.types[0] == 'administrative_area_level_1') {
          citydet = (citydet.length == 0? '' : citydet+', ') + el.long_name;
          console.log('citydet 1', citydet);
          $('#idcity', '#modalDocEduc').val(citydet);
        }

        if(el.types[0] == 'administrative_area_level_2') {
          citydet = el.long_name + (citydet.length == 0? '' : citydet);
          console.log('citydet 2', citydet)
          $('#idcity', '#modalDocEduc').val(citydet);
          $.map(cities, function(item, index) {
            if(item.city == el.long_name) {
              $('#idcity', '#modalDocEduc').val(item.item);
              $('#idcity', '#modalDocEduc').data('id', item.id);
            }
          });
        }

        console.log('citydet', citydet)
      });

      marker.formatted_address = responses[0].formatted_address;

      $('#address').val(responses[0].formatted_address);
    } else {
      marker.formatted_address = 'Cannot determine address at this location.';
      $('#address').val('Cannot determine address at this location.');
    }
  });
}, toggleBounce = function() {
  if (marker.getAnimation() !== null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
};

$(function(){
  solstar.autocomplete($('#idcountry', '#modalDocEduc')[0], countries, null, function(obj) {
    $('#idcountry', '#modalDocEduc').data('id', obj.id);
    console.log('autocomplete idcountry', obj)
    var url = `getCities/${obj.id}`;
    solstar.ajaxRequest(url, 'POST', function(resp) {
        console.log(url, resp)
        if(resp.success) {
          solstar.autocomplete($('#idcity', '#modalDocEduc')[0], cities, null, function(obj) {
            $('#idcity', '#modalDocEduc').data('id', obj.id);
            $('#idcity', '#modalDocEduc').data('idstate', obj.item.idstate);
          }, false);
        } else {
          alertify.warning('Ha ocurrido un error! '+resp.message, 10);
        }
      }, {
      }, true, null, function(err) {
        alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
      }
    );
  }, false);

  solstar.autocomplete($('#idcity', '#modalDocEduc')[0], cities, null, function(obj) {
    $('#idcity', '#modalDocEduc').data('id', obj.id);
    $('#idcity', '#modalDocEduc').data('idstate', obj.item.idstate);
  }, false);

  dT = solstar.dataTableReq({
    el: "#dtDatatable",
    dataSource: function ( json ) {
      json = json.data;
      for ( var i=0, ien=json.length ; i<ien ; i++ ) {
        json[i].number = i+1;
        json[i].fullname = json[i].titulo + ' '+json[i].fullname;
        json[i].contacto = '<i class="fas fa-mobile-alt"></i>: '+(json[i].phonenumber || '') + '<hr><i class="fas fa-at"></i>: '+(json[i].email || '');
        json[i].document = '<i class="far fa-id-card"></i>: <b>'+(json[i].type_docabrev || '') + '</b>'+(json[i].document || '') + '<hr><i class="fas fa-id-card-alt"></i> <b>ReTHUS:</b>'+(json[i].rethus || '');
        json[i].profileimage = '<img src="'+(json[i].profileimage || 'assets/images/icon.png') + '" class="img img-responsive img-fluid profimg" style="width:80px;"/>';

        json[i].satatuschoice = '<label class="custom-switch mt-1"><span class="custom-switch-description"></span><input type="checkbox" id="doctorstaus'+json[i].iddoctor+'" data-iddoctor="'+json[i].iddoctor+'" data-idusers="'+json[i].idusers+'" name="doctorstaus'+json[i].iddoctor+'" '+(parseInt(json[i].idstatus) == 1 ? 'checked' : '')+' class="custom-switch-input doctorChangeStatus"><span class="custom-switch-indicator"></span></label>';
        json[i].actions = '<a href="javascript:void(\''+json[i].id+'\')" class="btn btn-primary btn-sm mx-1 editDoctor" data-toggle="popover" title="Editar" data-content="'+json[i].fullname+'" data-id="'+json[i].iddoctor+'"><i class="fa fa-edit"></i></a>';
        //json[i].actions+= '<a href="javascript:void(\''+json[i].id+'\')" class="btn btn-danger btn-sm mx-1 deleteDoctor" data-toggle="popover" title="Eliminar" data-content="'+json[i].fullname+'" data-id="'+json[i].iddoctor+'"><i class="fa fa-trash"></i></a>';
        //console.log(json[i])
      }
      return json;
    },
    redrawFunction: redrawDt,
    columnDefs: [ {
      "targets": 0,
      "searchable": false
    }],
    columns: [
      { data: "number", class: "dt-center text-right" },
      { data: "profileimage", class: "dt-center text-center"  },
      { data: "fullname", class: "dt-center text-left"  },
      { data: "document", class: "dt-center text-left" },
      { data: "contacto", class: "dt-center text-left" },
      { data: "especiality", class: "dt-center text-left" },
      { data: "satatuschoice", class: "dt-center text-left" },
      { data: "actions", class: "dt-center text-center" },
    ],
    url: 'Admin/Doctors',
    typeMethod: 'POST',
  });

  $('.date').datetimepicker({
      locale: 'es',
      format: 'LT',
      format: 'YYYY-MM-DD',
      showTodayButton: true,
      showClear: true,
      maxDate: moment().format('YYYY-MM-DD'),
      ignoreReadonly: true,
      allowInputToggle: true,
  });

  $('.intlTelInput').intlTelInput({
      customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
          return "e.g. " + selectedCountryPlaceholder;
      },
      initialCountry: 'CO',
      localizedCountries: { 'CO': 'Colombia'},
      onlyCountries: ['co'],
      separateDialCode: true, 
  });

  $('#modalDocEduc').on('show.bs.modal', function(e) {
    //initMap();
  });

  $('#modalDocEduc').on('hide.bs.modal', function(e) {
    $('#modalDocEduc #saveDoctor, #modalDocEduc').data('reqidusers', '');
    $('#modalDocEduc #saveDoctor, #modalDocEduc').data('reqid', '');
    $('#modalDocEduc #idcountry').data('id','');
    $('#modalDocEduc #idcity').data('id', '');
    $('#modalDocEduc #idcity').data('idstate', '');
    $('input, textarea, select','#modalDocEduc .modal-body').val('');
  });

  $('#saveDoctor').off('click').on('click', function(evt) {
    $('#editDoctor-frm').validate();
    if($('#editDoctor-frm').valid()) {
      var $data = {
        titulo : $('#modalDocEduc #titulo').val(),
        fullname : $('#modalDocEduc #fullname').val(),
        type_doc : $('#modalDocEduc #type_doc').val(),
        document : $('#modalDocEduc #document').val(),
        rethus : $('#modalDocEduc #rethus').val(),
        gender : $('#modalDocEduc #gender').val(),
        birthdate : $('#modalDocEduc #birthdate').val(),
        phonenumber : $('#modalDocEduc #phonenumber').intlTelInput('getNumber'),
        idcity : $('#modalDocEduc #idcity').data('id'),
        cityname : $('#modalDocEduc #idcity').val(),
        idcountry : $('#modalDocEduc #idcountry').data('id'),
        countryname : $('#modalDocEduc #idcountry').val(),
        address : $('#modalDocEduc #address').val(),
        especiality : $('#modalDocEduc #especiality').val(),
        enferme_trat : $('#modalDocEduc #enferme_trat').val(),
        aboutme : $('#modalDocEduc #description').val(),
      };
      if($('#modalDocEduc #saveDoctor').data('type') == 1) {
        $data.id = $('#modalDocEduc #saveDoctor').data('reqid');
        $data.idusers = $('#modalDocEduc #saveDoctor').data('reqidusers');
      }
      
      solstar.ajaxRequest('Admin/Doctor-Save', ($('#modalDocEduc #saveDoctor').data('type') == 1 ? 'PUT': 'POST'), function(resp) {
        if(resp.success) {
          $('#dtDatatable').DataTable().ajax.reload();
          $('#modalDocEduc').modal('hide');
        } else {
          alertify.warning('Ha ocurrido un error! '+resp.message, 10);
        }
      }, $data, true, null, function(err) {
        alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
      });
    } else {
      alertify.warning('Verifique los campos del formulario', 10);
    }
  });
  $.getScript('https://get.geojs.io/v1/ip/geo.js?callback=currGeo');
  //$.getScript('https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDg2Z6QWeOzf4FkS5ZYQx2Isw6efT90dHI&libraries=geometry');
});