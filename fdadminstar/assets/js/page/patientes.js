"use strict";
moment().locale('es');
$(function(){
  solstar.autocomplete($('#idcity', '#modalDocEduc')[0], cities, $('#idcityval', '#modalDocEduc')[0]);
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

  /*$('#saveGoal').off('click').on('click', function(evt) {
      solstar.ajaxRequest('Doctor/Goals', 'POST', function(resp) {
        if(resp.success) {
          location.href = 'Doctor/Goals';
        } else {
          alertify.warning('Ha ocurrido un error! '+resp.message, 10);
        }
      }, {
        titulo: $('#modalDocGoal #titulo').val(),
        fecha: $('#modalDocGoal #fecha').val(),
        description: $('#modalDocGoal #description').val(),
      }, true, null, function(err) {
        alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
      });
  });//*/
  $("#dtDatatable").dataTable({
    "columnDefs": [
      //{ "sortable": false, "targets": [2,3] }
    ]
  });

});