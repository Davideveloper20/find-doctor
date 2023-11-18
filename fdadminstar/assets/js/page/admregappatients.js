"use strict";
moment().locale('es');
$(function(){
  $.extend(true, $.fn, {
    patientChangeStatus: function(evt) {
      var $check = this[0].checked;
      var $this = this[0];

      console.log('doctorChangeStatus', this, $(this).data());
      console.log('doctorChangeStatus 2', $this, $check, evt);
      solstar.jqc_alert('Cambio de Estatus!','¿Confirma que desea cambiar a status <b>'+($this.checked ? 'Activo': 'Inactivo')+'</b> este paciente de la app?','modern',($this.checked ? 'fas fa-toggle-on': 'fas fa-toggle-off')+' fa-1x', ($this.checked ? 'blue': 'red'), {
        Cancelar: function(){$this.checked = !$check;},
        "Si, Cambiar!": function() {
          solstar.ajaxRequest(`Admin/PatientChgStatus/${$check?1:0}`, 'POST', function(resp) {
              if(resp.success) {
                
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
    }
  });
  
  $("#dtDatatable").dataTable({
    ajax: {
      url: solstar.params.almacen+'Admin/Patients-User',
      method: "POST",
      "dataSrc": function ( data ) {
        var json = data.data;
        //console.log('dataSrc', json)
        for ( var i=0, ien=json.length ; i<ien ; i++ ) {
          json[i].number = i+1;
          json[i].fullname = json[i].fullname;
          json[i].contacto = '<i class="fas fa-mobile-alt"></i>: '+(json[i].phonenumber || '') + '<hr><i class="fas fa-at"></i>: '+(json[i].email || '');
          json[i].document = '<i class="far fa-id-card"></i>: <b>'+(json[i].type_docabrev || '') + '</b>'+(json[i].document || '');
          json[i].profileimage = '<img src="'+(json[i].profileimage || 'assets/images/icon.png') + '" class="img img-responsive img-fluid profimg" style="width:80px;"/>';

          json[i].satatuschoice = '<label class="custom-switch mt-1"><span class="custom-switch-description"></span><input type="checkbox" id="doctorstaus'+json[i].iddoctor+'" data-iddoctor="'+json[i].idusers+'" data-idusers="'+json[i].idusers+'" name="doctorstaus'+json[i].iddoctor+'" '+(parseInt(json[i].idstatus) == 1 ? 'checked' : '')+' class="custom-switch-input doctorChangeStatus"><span class="custom-switch-indicator"></span></label>';
          json[i].actions = '';
          //console.log(json[i])
        }
        return json;
      }
    },
    "bSort": false,
    columns: [
        { data: "number", class: "dt-center text-right" },
        { data: "profileimage", class: "dt-center text-center"  },
        { data: "fullname", class: "dt-center text-left"  },
        { data: "document", class: "dt-center text-left" },
        { data: "contacto", class: "dt-center text-left" },
        { data: "satatuschoice", class: "dt-center text-left" },
        { data: "actions", class: "dt-center text-center" },
    ],
    "columnDefs": [
      //{ "sortable": false, "targets": [2,3] }
    ]
  })
  .on( 'preDraw', function () {
  })
  .on( 'draw.dt', function () {
    $('.doctorChangeStatus').off('change').on('change', function(evt) {
        $(this).patientChangeStatus(evt);
    });
  })
  .on( 'page.dt', function () {
    $('.doctorChangeStatus').off('change').on('change', function(evt) {
        $(this).patientChangeStatus(evt);
    });
  });

});