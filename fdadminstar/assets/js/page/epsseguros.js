"use strict";
moment().locale('es');
$(function(){
  solstar.autocomplete($('#idseguroeps', '#modalDocEduc')[0], allservices, $('#idseguroepsval', '#modalDocEduc')[0], null, false);
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
  $('#med_prepagado').off('change').on('change', function(evt) {
    var $this = this;
    console.log('med_prepagado', this.checked);
    if(!$this.checked) {
      solstar.jqc_alert('Medicina Prepagada', '¿Confirma que desea no aceptar medicina prepagada?','modern','fas fa-question fa-3x','warning', {
        'Si, ya no aceptaré': function() {
          console.log('Enviar request');
          solstar.ajaxRequest(`Doctor/setMedPrepaid/${$this.checked}`, 'POST', function(resp) {
              console.log(`Doctor/setMedPrepaid/${$this.checked}`, resp)
              if(resp.success) {
                $('#doctorEpsRow').html('');
                $('#doctorEpsAddBtn, #doctorEpsAddBtn2').fadeOut('fast');
                $('#doctorEpsAddBtn, #doctorEpsAddBtn2').addClass('sr-only hidden');
              } else {
                alertify.warning('Ha ocurrido un error! '+resp.message, 10);
              }
            }, {
            }, true, null, function(err) {
              alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
            }
          );
        },
        'Cancelar': function() {
          $this.checked = !$this.checked;
        }
      });
    } else {
      solstar.ajaxRequest(`Doctor/setMedPrepaid/${$this.checked}`, 'POST', function(resp) {
          console.log(`Doctor/setMedPrepaid/${$this.checked}`, resp)
          if(resp.success) {
            $('#doctorEpsAddBtn, #doctorEpsAddBtn2').fadeIn('fast');
            $('#doctorEpsAddBtn, #doctorEpsAddBtn2').removeClass('sr-only hidden');
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

  $('#saveFormation').off('click').on('click', function(evt) {
    $('#addEpsSeguro-frm').validate();
    if($('#addEpsSeguro-frm').valid()) {
      var $data = {
        seguroeps: $('#modalDocEduc #idseguroeps').val(),
        idseguroeps: $('#modalDocEduc #idseguroepsval').val(),
      };
      if($('#modalDocEduc #saveFormation').data('type') == 1) {
        $data.id = $('#modalDocEduc #saveFormation').data('reqid');
      }
      console.log('Doctor/EPS-SegurosSave', $data);
      solstar.ajaxRequest('Doctor/EPS-SegurosSave', ($('#modalDocEduc #saveFormation').data('type') == 1 ? 'PUT': 'POST'), function(resp) {
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

  $('.deleteEpsDoc').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.deleteEpsDoc', $data)
    solstar.jqc_alert('Eliminar EPS / Seguros!','¿Confirma que desea eliminar este registro?','modern','fas fa-trash fa-1x', 'red', {
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
          solstar.ajaxRequest('Doctor/EPS-SegurosSave', 'DELETE', function(resp) {
              if(resp.success) {
                $('.epsSegurDocCnt-'+($data.id || -1 )).remove();
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