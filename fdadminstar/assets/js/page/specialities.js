"use strict";
moment().locale('es');
$(function(){
  solstar.autocomplete($('#idspeciality', '#modalDocEduc')[0], specialities, $('#idspecialityval', '#modalDocEduc')[0], function(obj) {
    console.log('ato', obj);
    if(obj.id == '' || obj.id < 0) {
      $('#modalDocEduc #specialityDes-cnt').fadeIn('swing');
      $('#modalDocEduc #specialityDes-cnt .invalid-feedback').fadeIn('swing');
      $('#modalDocEduc #specialityDes-cnt .textarea').attr('disabled', false);
      $('#modalDocEduc #idspeciality').attr('required', true);
      $('#modalDocEduc #idspecialityval').attr('required', false).val('');
      $('#modalDocEduc #specialityDes-cnt #idspecialitydes').attr('required', true);
    } else {
      $('#modalDocEduc #specialityDes-cnt').fadeOut('swing');
      $('#modalDocEduc #specialityDes-cnt .invalid-feedback').fadeOut('swing');
      $('#modalDocEduc #specialityDes-cnt .textarea').attr('disabled', true);
      $('#modalDocEduc #specialityDes-cnt .textarea').val('');
      $('#modalDocEduc #idspeciality').attr('required', true);
      $('#modalDocEduc #idspecialityval').attr('required', true).val(obj.id);
      $('#modalDocEduc #specialityDes-cnt #idspecialitydes').attr('required', false);
    }
  }, false);
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
    $('#modalDocEduc .modal-title').html('<i class="fa fa-plus fa-fw"></i>Agregar Especialidad');
    $('#modalDocEduc #saveSpeciality').html('<i class="fa fa-save"></i> Guardar');
    $('#modalDocEduc #saveSpeciality').data('type', 0);
  });

  $('#saveSpeciality').off('click').on('click', function(evt) {
    console.log("click")
    $('#addSpecial-frm').validate();
    if($('#addSpecial-frm').valid()) {
      var $data = {
        idspeciality: $('#modalDocEduc #idspecialityval').val(),
        speciality: $('#modalDocEduc #idspeciality').val(),
        specialitydes: $('#modalDocEduc #idspecialitydes').val(),
      };
      if($('#modalDocEduc #saveSpeciality').data('type') == 1) {
        $data.id = $('#modalDocEduc #saveSpeciality').data('reqid');
      }
      
      solstar.ajaxRequest('Doctor/SpecialitiesSave', ($('#modalDocEduc #saveSpeciality').data('type') == 1 ? 'PUT': 'POST'), function(resp) {
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

  $('.editSpeciality').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.editSpeciality', $data);
    solstar.ajaxRequest('Doctor/SpecialitiesSave', 'PATCH', function(resp) {
        if(resp.success) {
          console.log(resp)
          $('#modalDocEduc #saveSpeciality').data('type', 1);
          $('#modalDocEduc #idspeciality').val(resp.data.speciality);
          $('#modalDocEduc #idspecialityval').val(resp.data.idspeciality);
          $('#modalDocEduc #idspecialitydes').val(resp.data.descrip);
          $('#modalDocEduc #saveSpeciality').data('reqid', resp.data.idspectdoc);
          $('#modalDocEduc .modal-title').html('<i class="fa fa-edit fa-fw"></i> Editar Especialidad');
          $('#modalDocEduc #saveSpeciality').html('<i class="fa fa-save"></i> Guardar');
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

  $('.deleteSpeciality').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.deleteSpeciality', $data)
    solstar.jqc_alert('Eliminar Especialidad!','¿Confirma que desea eliminar este registro?','modern','fas fa-trash fa-1x', 'red', {
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
          solstar.ajaxRequest('Doctor/SpecialitiesSave', 'DELETE', function(resp) {
              if(resp.success) {
                $('.specialityCtn-'+($data.id || -1 )).remove();
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