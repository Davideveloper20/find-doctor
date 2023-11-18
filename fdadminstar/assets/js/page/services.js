"use strict";
moment().locale('es');
$(function(){
  //solstar.autocomplete($('#idspeciality', '#modalDocEduc')[0], specialities, $('#idspecialityval', '#modalDocEduc')[0]);
  //$('#uploadThumb').on('change', function(evt) {
      //$.readURL(this, '#thumbnailPreview');
  //});
  /*$('.date').datetimepicker({
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
  });//*/

  $('#modalDocEduc').on('show.bs.modal', function(e) {
    
  });

  $('#modalDocEduc').on('hide.bs.modal', function(e) {
    $('input, textarea','#modalDocEduc .modal-body').val('');
    $('#modalDocEduc .modal-title').html('<i class="fa fa-plus fa-fw"></i>Agregar Servicio');
    $('#modalDocEduc #saveService').html('<i class="fa fa-save"></i> Guardar');
    $('#modalDocEduc #saveService').data('type', 0);
  });

  $('#saveService').off('click').on('click', function(evt) {
    $('#addServices-frm').validate();
    if($('#addServices-frm').valid()) {
      var $data = {
        type: $('#modalDocEduc #type').val(),
        service: $('#modalDocEduc #service').val(),
        duration: $('#modalDocEduc #duration').val(),
        amount: $('#modalDocEduc #amount').val(),
        amount_prepaid: $('#modalDocEduc #amount_prepaid').val(),
        description: $('#modalDocEduc #description').val(),
      };
      if($('#modalDocEduc #saveService').data('type') == 1) {
        $data.id = $('#modalDocEduc #saveService').data('reqid');
      }
      
      solstar.ajaxRequest('Doctor/ServicesSave', ($('#modalDocEduc #saveService').data('type') == 1 ? 'PUT': 'POST'), function(resp) {
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

  $('.editService').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.editService', $data);
    solstar.ajaxRequest('Doctor/ServicesSave', 'PATCH', function(resp) {
        if(resp.success) {
          console.log(resp)
          $('#modalDocEduc #saveService').data('type', 1);
          $('#modalDocEduc #saveService').data('reqid', resp.data.id);
          $('#modalDocEduc #type').val(resp.data.type);
          $('#modalDocEduc #service').val(resp.data.service);
          $('#modalDocEduc #duration').val(resp.data.duration);
          $('#modalDocEduc #amount').val(resp.data.amount);
          $('#modalDocEduc #amount_prepaid').val(resp.data.amount_prepaid);
          $('#modalDocEduc #description').val(resp.data.description);
          $('#modalDocEduc .modal-title').html('<i class="fa fa-edit fa-fw"></i> Editar Servicio');
          $('#modalDocEduc #saveService').html('<i class="fa fa-save"></i> Guardar');
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

  $('.deleteService').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.deleteService', $data)
    solstar.jqc_alert('Eliminar Servicio!','¿Confirma que desea eliminar este registro?','modern','fas fa-trash fa-1x', 'red', {
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
          solstar.ajaxRequest('Doctor/ServicesSave', 'DELETE', function(resp) {
              if(resp.success) {
                $('.serviceCtn-'+($data.id || -1 )).remove();
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
});