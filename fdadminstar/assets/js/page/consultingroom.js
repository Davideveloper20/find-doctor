"use strict";
moment().locale('es');
$(function(){
  solstar.autocomplete($('#idcity', '#modalDocEduc')[0], cities, $('#idcityval', '#modalDocEduc')[0]);
  solstar.autocomplete($('#name', '#modalDocEduc')[0], cnfcondultorios, $('#idconsult', '#modalDocEduc')[0],null, false);
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

  $('#saveFormation').off('click').on('click', function(evt) {
    $('#addConsultRoom-frm').validate();
    if($('#addConsultRoom-frm').valid()) {
      var $data = {
        name: $('#modalDocEduc #name').val(),
        idcity: $('#modalDocEduc #idcityval').val(),
        address: $('#modalDocEduc #address').val(),
        phone1: $('#modalDocEduc #phone1').val(),
        phone2: $('#modalDocEduc #phone2').val(),
        email: $('#modalDocEduc #email').val(),
        url: $('#modalDocEduc #url').val(),
      };
      if($('#modalDocEduc #saveFormation').data('type') == 1) {
        $data.id = $('#modalDocEduc #saveFormation').data('reqid');
      }
      console.log('Doctor/Consulting-Room-Save', $data);
      solstar.ajaxRequest('Doctor/Consulting-Room-Save', ($('#modalDocEduc #saveFormation').data('type') == 1 ? 'PUT': 'POST'), function(resp) {
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

  //deleteConsulRoom
  $('.deleteConsulRoom').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.deleteConsulRoom', $data)
    solstar.jqc_alert('Eliminar Consultorio!','¿Confirma que desea eliminar este registro?','modern','fas fa-trash fa-1x', 'red', {
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
          solstar.ajaxRequest('Doctor/Consulting-Room-Save', 'DELETE', function(resp) {
              if(resp.success) {
                $('.consultingRoomCnt-'+($data.id || -1 )).remove();
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