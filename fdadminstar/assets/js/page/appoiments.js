"use strict";
$("#myCalendardhxtml, #scheduler_here").css({
  minHeight: $(window).outerHeight() - 125
});
moment().locale('es');
$(function(){
  var changeserv = function(obj) {
    console.log('changeserv', obj);
    $('#idserviceval').val(obj.item.id);
    $('#idserviceval').data('id', obj.item.id);
    $('#idserviceval').data('amount', obj.item.amount);
    $('#idserviceval').data('selamount', obj.item.amount);
    $('#idserviceval').data('amount-prepaid', obj.item.amount_prepaid);
    $('#idserviceval').data('duration', obj.item.duration);
    $('#prepaidstatus')[0].checked = false;
    $('#service_amount').val(obj.item.amount);
    $('#duration').html('<option value="0">Seleccione...</option>');
    for (var i = 1; i < 5; i++) {
      let txt = obj.item.duration*i;
      let m = moment(new Date(1986,5,17,0,0,0)).add(txt,'minutes').format((txt >= 60 ? 'hh:mm': 'mm'))+(txt >= 60 ? ' hora': ' minutos');
      $('#duration').append(`<option value="${obj.item.duration*i}">${m}</option>`);  
    }
  };
  solstar.autocomplete($('#idservice', '#event_form')[0], docservices, $('#idserviceval', '#event_form')[0], changeserv);

  console.log('optionCalBD', optionCalBD);

  var html = function(id) { return document.getElementById(id); }; //just a helper
  var format = scheduler.date.date_to_str("%H:%i");
  var delete_event = function (evt) {
    solstar.jqc_alert('Eliminar Agendamiento!','¿Confirma que desea eliminar <b>DEFINITIVAMENTE</b> este agendamineto?','modern','fa fa-times', 'red', {
        Cancelar: function(){},
        "Si, Eliminar!": function() {
          var event_id = scheduler.getState().lightbox_id;
          scheduler.endLightbox(false, html("event_form"));
          scheduler.deleteEvent(event_id);
          $('#event_form').modal('hide');
        }
      });
    
  }
  scheduler.showLightbox = function(id) {
    var ev = scheduler.getEvent(id);
    scheduler.startLightbox(id, html("event_form"));
    console.log('showLightbox', ev);
    $('#duration').val(0);
    $('#patienname, #type_doc, #document, #idservice, #idserviceval, #details').val('');
    if((typeof ev.apment_date) == 'undefined') {
      $('#prepaidstatus')[0].checked = false;
      $('#deleteAppoint').fadeOut();
      $('#deleteAppoint').off('click');
      $('#event_form #event_formtitle').html('Agendar Nueva Cita');
      let min = moment(ev.start_date).minute();
      if(min >=0 && min<15) min = 0;
      else if(min >=15 && min<30) min = 15;
      else if(min >=30 && min<45) min = 30;
      else if(min >=45) min = 45;
      ev.start_date.setMinutes(min);
      $('#idserviceval').data('newevent', 1);
      $('#stautacont').hide();
      $('#status').val(1);
    } else {
      $('#idserviceval').data('newevent', 0);
      $('#deleteAppoint').fadeIn();
      $('#stautacont').show();
      $('#deleteAppoint').off('click').on('click', delete_event);
      $('#event_form #event_formtitle').html('Editar: '+ev.title);
      $('#prepaidstatus')[0].checked = ev.prepaid == 1;
      $('#patienname').val(ev.name);
      $('#type_doc').val(ev.type_doc);
      $('#status').val(ev.idstatus);
      $('#document').val(ev.document);
      $('#document').data('id', ev.idpatient);
      $('#idservice').val(`${ev.service}, ${ev.serv_duration} minutos`);
      $('#idserviceval').val(ev.idservice);
      $('#details').val(ev.details);
      changeserv({
        item: {
          id: ev.idservice,
          amount: ev.serv_amount,
          amount_prepaid: ev.serv_amount_prepaid,
          duration: ev.serv_duration,
        }
      });
      $('#duration').val(((ev.end_date-ev.start_date)/1000)/60);
      $('#service_amount').val(ev.amount);
    }
    
    $('#idserviceval').data('start_date', ev.start_date);
    $('#apment_date').val(moment(ev.start_date).format('DD/MM/YYYY'));
    $('#start_at').val(moment(ev.start_date).format('hh:mm A'));
    $('#event_form').modal({
      backdrop: 'static', 
      show: true,
      keyboard: false,
      focus: false,
    });
    $('#event_form').css('top', '0px !important');
  };

  $('#event_form #saveAppoint').on('click', function(evt) {
    
    var ev = scheduler.getEvent(scheduler.getState().lightbox_id);
    ev.document = $('#document').val() || '';
    ev.type_doc = $('#type_doc').val() || '';
    ev.idpatient = $('#document').data('id') || '';
    ev.patienname = $('#patienname').val() || '';
    ev.name = $('#patienname').val() || '';
    ev.idservice = $('#idserviceval').val() || '';
    ev.prepaid = ($('#prepaidstatus')[0].checked ? 1 : 0) || 0;
    ev.amount = $('#service_amount').val() || '0.00';
    ev.duration = $('#duration').val() || '15';
    ev.details = $('#details').val() || 'Ninguna';
    var start_date = $('#idserviceval').data('start_date');
    ev.apment_date = moment(start_date).format('YYYY-MM-DD');
    ev.start_at = moment(start_date).format('HH:mm');
    ev.end_at = moment(start_date).add(ev.duration, 'minutes').format('HH:mm');
    ev.newevent = $('#idserviceval').data('newevent');
    ev.status = ev.newevent== 1 ? '1':$('#status').val();
    console.log(ev)
    ev.start_date = moment(start_date).format('YYYY-MM-DD HH:mm');
    ev.end_date = moment(start_date).add(ev.duration, 'minutes').format('YYYY-MM-DD HH:mm');
    ev.apment_date= moment(start_date).format('YYYY-MM-DD');
    var eventId = ev.id;
    console.log('eventId', eventId);
    let url = `Doctor/AppoinmentsSave${ev.newevent== 1 ? '': '/'+scheduler.getState().lightbox_id}` ;
    solstar.ajaxRequest(url, (ev.newevent== 1 ? 'POST': 'PUT'), function(resp) {
        if(resp.success) {
          alertify.warning('Agendamiento '+(ev.newevent== 1 ? 'realizado': 'actualizado')+'!', 10);
          resp.data.start_date = new Date(resp.data.start_date);
          resp.data.end_date = new Date(resp.data.end_date);
          ev.start_date = resp.data.start_date;
          ev.end_date = resp.data.end_date;
          $.extend(true, scheduler.getEvent(eventId), resp.data);
          //scheduler.addEvent(resp.data);
          scheduler.updateEvent(eventId);
          console.log('resp.data', resp.data);
          $('#event_form').modal('hide');
          scheduler.endLightbox(true, $('#event_form')[0]);
        } else {
          alertify.warning(resp.message, 10);
        }
      }, ev, true, null, function(err) {
        alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
      }
    );
  });

  $('#event_form .closeModal').on('click', function(evt) {
    $('#event_form').modal('hide');
    scheduler.endLightbox(false, html("event_form"));
  });

  $('#type_doc, #document').on('change', function(evt) {
    var $doc = $('#document').val() || '';
    var $tdoc = $('#type_doc').val() || '';
    console.log('patient', $doc, $tdoc);
    if($doc != '' && $tdoc != '') {
      $('#patienname').val('');
      $('#patienname').data('id', false);
      solstar.ajaxRequest(`Patient/Search/${$tdoc}/${$doc}`, 'POST', function(resp) {
          if(resp.success && resp.data.length > 0) {
            $('#patienname').val(resp.data[0].name);
            $('#patienname').data('id',resp.data[0].id);
            $('#document').data('id',resp.data[0].id);
          } else if(!resp.success) {
            alertify.warning(resp.message, 10);
          }
        }, {
        }, true, null, function(err) {
          alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
        }
      );
    }
  });

  $('#duration').on('change', function(evt) {
    var selam = 0;
    if(this.checked) {
      $('#idserviceval').data('selamount', $('#idserviceval').data('amount-prepaid'));
      selam = $('#idserviceval').data('amount-prepaid');
    } else {
      $('#idserviceval').data('selamount', $('#idserviceval').data('amount'));
      selam = $('#idserviceval').data('amount');
    }
    var $duration = $('#duration').val();
    var inter = $('#idserviceval').data('duration');
    var clcam = selam *($duration/inter);
    console.log('duration', {
      duration:$duration,
      inter:inter,
      selam:selam,
      clcam:clcam,
    });
    $('#service_amount').val(clcam);
  });

  $('#prepaidstatus').on('change', function(evt) {
    var selam = 0;
    if(this.checked) {
      $('#idserviceval').data('selamount', $('#idserviceval').data('amount-prepaid'));
      selam = $('#idserviceval').data('amount-prepaid');
    } else {
      $('#idserviceval').data('selamount', $('#idserviceval').data('amount'));
      selam = $('#idserviceval').data('amount');
    }
    var $prepaidstatus = $('#prepaidstatus').val();
    console.log('prepaidstatus', $prepaidstatus, this.checked);
    var $duration = $('#duration').val();
    var inter = $('#idserviceval').data('duration');
    var clcam = selam *($duration/inter);
    console.log('prepaidstatus', {
      prepaidstatus: this.checked,
      duration:$duration,
      inter:inter,
      selam:selam,
      clcam:clcam,
    });
    $('#service_amount').val(clcam);
  });
  var hourSize = 170;
  $.extend(true, scheduler, {
    config: {
      //readonly: true,
      icons_select: ["icon_edit","icon_details","icon_delete"],
      update_render: true,
      start_on_monday : false,
      hour_date:"%h:%i %A",
      hour_size_px: hourSize,
      prevent_cache: true,
      first_hour: parseInt((optionCalBD.profile.time_start.split(':')[0] || '8')),
      last_hour: parseInt((optionCalBD.profile.time_end.split(':')[0] || '17')),
      drag_in: false,
      drag_out: false,
      drag_resize: false,
      drag_move: false,
      limit_time_select: true,
      details_on_create: true,
      details_on_dblclick: true,
      multi_day: true,
    },
    xy: {
      scale_width: 70,
      scale_height: 40,
      //nav_height:0,
    },
    templates: {
      hour_scale: function(date) {
        var step = 15;
        var html = "";
        var r = hourSize/(60/step);
        for (var i=0; i<60/step; i++){
          html += "<div style='height:"+r+"px;line-height:"+r+"px;'>"+format(date)+"</div>";
          date = scheduler.date.add(date, step, "minute");
        }
        return html;
      },
      week_date_class: function(date,today) {
        return "custom_color";  
      }
    }
  });

  scheduler.init('scheduler_here',new Date(),"week");
  scheduler.load(`${almacen}Doctor/AppoinmentsList`, function(){
        //scheduler.showLightbox(2);
      });
  var dp = new dataProcessor(`${almacen}Doctor/AppoinmentsSave`);
  dp.init(scheduler);
  dp.setTransactionMode("REST", false);
});