"use strict";
moment().locale('es');
$(function(){
  //solstar.autocomplete($('#idcity', '#profile-form')[0], cities, $('#idcityval', '#profile-form')[0]);
  //$('#uploadThumb').on('change', function(evt) {
      //$.readURL(this, '#thumbnailPreview');
  //});
  $('.date').datetimepicker({
      locale: 'es',
      format: 'LT',
      format: 'DD/MM/YYYY',
      showTodayButton: true,
      showClear: true,
      ignoreReadonly: true,
      allowInputToggle: false,
  });

  $('#fecha').on('click', function(evt) {
      $('#fecha-addon').click();
  });

  $('#modalDocEduc').on('show.bs.modal', function(e) {
    
  });

  $('#modalDocEduc').on('hide.bs.modal', function(e) {
    $('input, textarea','#modalDocEduc .modal-body').val('');
    $('#modalDocEduc .modal-title').html('<i class="fa fa-plus fa-fw"></i>Agregar Formación');
    $('#modalDocEduc #saveFormation').html('<i class="fa fa-save"></i> Guardar');
    $('#modalDocEduc #saveFormation').data('type', 0);
  });

  $('#saveFormation').off('click').on('click', function(evt) {
    $('#addFormation-frm').validate();
    if($('#addFormation-frm').valid()) {
      var $data = {
        institucion: $('#modalDocEduc #institucion').val(),
        titulacion: $('#modalDocEduc #titulacion').val(),
        descripcion: $('#modalDocEduc #descripcion').val(),
      };
      if($('#modalDocEduc #saveFormation').data('type') == 1) {
        $data.id = $('#modalDocEduc #saveFormation').data('reqid');
      }
      
      solstar.ajaxRequest('Doctor/FormationSave', ($('#modalDocEduc #saveFormation').data('type') == 1 ? 'PUT': 'POST'), function(resp) {
        if(resp.success) {
          location.reload();
        } else {
          alertify.warning('Ha ocurrido un error! '+resp.message, 10);
        }
      }, $data, true, null, function(err) {
        alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
      });
    }
  });

  $('.editFormation').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.editFormation', $data);
    solstar.ajaxRequest('Doctor/FormationSave', 'PATCH', function(resp) {
        if(resp.success) {
          console.log(resp)
          console.log(resp.data.titulo)
          console.log(resp.data.fecha)
          console.log(resp.data.descripcion)
          $('#modalDocEduc #saveFormation').data('type', 1);
          $('#modalDocEduc #institucion').val(resp.data.institucion);
          $('#modalDocEduc #titulacion').val(resp.data.titulacion);
          $('#modalDocEduc #descripcion').val(resp.data.descripcion);
          $('#modalDocEduc #saveFormation').data('reqid', resp.data.id);
          $('#modalDocEduc .modal-title').html('<i class="fa fa-edit fa-fw"></i> Editar Formación');
          $('#modalDocEduc #saveFormation').html('<i class="fa fa-save"></i> Guardar');
          $('#modalDocEduc').modal({
            show: true,
            backdrop: 'static',
            keyboard: false,
          });
        } else {
          alertify.warning(resp.message, 10);
        }
      }, {id: ($data.id || -1 )}, true, null, function(err) {
        alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
      }
    );
    
  });

  $('.deleteFormation').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.deleteFormation', $data)
    solstar.jqc_alert('Eliminar Formación!','¿Confirma que desea eliminar este registro?','modern','fas fa-trash fa-1x', 'red', {
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
          solstar.ajaxRequest('Doctor/FormationSave', 'DELETE', function(resp) {
              if(resp.success) {
                $('.formationCtn-'+($data.id || -1 )).remove();
              } else {
                alertify.warning(resp.message, 10);
              }
            }, {id: ($data.id || -1 )}, true, null, function(err) {
              alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
            }
          );
        }
      }
    });
  });

});