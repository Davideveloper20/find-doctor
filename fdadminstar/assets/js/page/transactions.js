"use strict";
moment().locale('es');
var redrawDt = function(evt) {
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
}

$(function(){
  dT = solstar.dataTableReq({
    el: "#dtDatatable",
    dataSource: function ( json ) {
      json = json.data;
      for ( var i=0, ien=json.length ; i<ien ; i++ ) {
        let ammo = json[i].ammount
        json[i].number = i+1;
        json[i].sesion = json[i].id_user_transactions;
        json[i].ammount = `COP ${solstar.puntos(ammo)}`;
        json[i].perc = `COP ${solstar.puntos((ammo*0.1))} (10%)`;
        json[i].total = `COP ${solstar.puntos((ammo - (ammo*0.1)))}`;
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
      { data: "sesion", class: "dt-center text-center"  },
      { data: "ammount", class: "dt-center text-left"  },
      { data: "perc", class: "dt-center text-left" },
      { data: "total", class: "dt-center text-left" },
    ],
    url: 'Doctor/load_transactions',
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