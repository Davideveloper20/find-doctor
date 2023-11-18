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

  $('#modalDocGoal').on('show.bs.modal', function(e) {
    
  });

  $('#modalDocGoal').on('hide.bs.modal', function(e) {
    $('input, textarea','#modalDocGoal .modal-body').val('');
    $('#modalDocGoal .modal-title').html('<i class="fa fa-plus fa-fw"></i>Agregar Logro');
    $('#modalDocGoal #saveGoal').html('<i class="fa fa-save"></i> Guardar');
    $('#modalDocGoal #saveGoal').data('type', 0);
  });

  $('#saveGoal').off('click').on('click', function(evt) {
    $('#addGoal-frm').validate();
    if($('#addGoal-frm').valid()) {
      var $data = {
        titulo: $('#modalDocGoal #titulo').val(),
        fecha: $('#modalDocGoal #fecha').val(),
        description: $('#modalDocGoal #description').val(),
      };
      if($('#modalDocGoal #saveGoal').data('type') == 1) {
        $data.id = $('#modalDocGoal #saveGoal').data('reqid');
      }
      
      solstar.ajaxRequest('Doctor/GoalSave', ($('#modalDocGoal #saveGoal').data('type') == 1 ? 'PUT': 'POST'), function(resp) {
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

  $('.editGoal').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.editGoal', $data);
    solstar.ajaxRequest('Doctor/GoalSave', 'PATCH', function(resp) {
        if(resp.success) {
          console.log(resp)
          console.log(resp.data.titulo)
          console.log(resp.data.fecha)
          console.log(resp.data.descripcion)
          $('#modalDocGoal #saveGoal').data('type', 1);
          $('#modalDocGoal #titulo').val(resp.data.titulo);
          $('#modalDocGoal #fecha').val(resp.data.fecha);
          $('#modalDocGoal #description').val(resp.data.descripcion);
          $('#modalDocGoal #saveGoal').data('reqid', resp.data.id);
          $('#modalDocGoal').modal({
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
    $('#modalDocGoal .modal-title').html('<i class="fa fa-edit fa-fw"></i> Editar Logro');
    $('#modalDocGoal #saveGoal').html('<i class="fa fa-save"></i> Guardar');
  });

  $('.deleteGoal').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.deleteGoal', $data)
    solstar.jqc_alert('Eliminar Logro!','¿Confirma que desea eliminar este registro?','modern','fas fa-trash fa-1x', 'red', {
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
          solstar.ajaxRequest('Doctor/GoalSave', 'DELETE', function(resp) {
              if(resp.success) {
                $('.goalDocCnt-'+($data.id || -1 )).remove();
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