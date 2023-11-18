"use strict";
moment().locale('es');
$(function(){
  //solstar.autocomplete($('#idcity', '#profile-form')[0], cities, $('#idcityval', '#profile-form')[0]);
  //$('#uploadThumb').on('change', function(evt) {
      //$.readURL(this, '#thumbnailPreview');
  //});
  $('.datei, .datef').datetimepicker({
      format: 'LT',
      ignoreReadonly: true,
      allowInputToggle: true,
      format: 'HH:mm',
      useCurrent: false,
      stepping: 15,
  });

  $('.datei').datetimepicker().on("dp.change", function (e) {
    var time = $('#datei').val();
    var times = time.trim().split(':');
      $('.datef').datetimepicker('disabledTimeIntervals', [
        [
          moment(e.date).hour(0).minute(0), 
          moment(e.date).hour(times[0]).minutes(times[1]).add(15, 'minutes')
        ]
      ]);//*/
  });

});