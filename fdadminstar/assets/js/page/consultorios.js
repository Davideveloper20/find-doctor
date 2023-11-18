"use strict";
moment().locale('es');
var curLat = 0, curLon = 0, marker = false, map, infoWindow, geocoder,
geocodePosition = function(pos) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      console.log('geocodePosition', responses)
      var locatebd = {};
      marker.formatted_address = responses[0].formatted_address;
      var addre= responses[0].formatted_address;
      $('#address').val(addre);
      addre='';
      //responses[0].address_components = responses[0].address_components.reverse();
      responses[0].address_components.forEach( function(element, index) {
        if(element.types[0] == 'country') {
          locatebd.country = element.long_name;
        }
        if(element.types[0] == 'administrative_area_level_1') {
          locatebd.region = element.long_name;
        }
        if(element.types[0] == 'administrative_area_level_2') {
          locatebd.city = element.long_name;
        }
        if(element.types[0] == 'locality') {
          addre+= (addre!=''?', ':'')+element.long_name;
        }
        if(element.types[0] == 'neighborhood') {
          addre+= (addre!=''?', ':'')+element.long_name;
        }
        if(element.types[0] == 'route') {
          addre+= (addre!=''?', ':'')+element.long_name;
        }
        if(element.types[0] == 'street_number') {
          addre+= (addre!=''?', ':'')+element.long_name;
        }
      });
      //$('#address').val(addre);
      console.log('addre', addre);
      solstar.ajaxRequest(`l/${locatebd.country}/${locatebd.region}/${locatebd.city}`, 'GET', function(data) {
      if(data.success) {
        if(data.data.length > 0) {
          $('#idcity').val(data.data[0].item);
          $('#idcityval').val(data.data[0].id);
        }
      } else {
      }
    }, {}, true, null, function(err, errtxt) {
      //console.log('Ha Ocurrido un error');
    }
  );
    } else {
      marker.formatted_address = 'Cannot determine address at this location.';
      $('#address').val('Cannot determine address at this location.');
    }
  });
},
toggleBounce = function () {
  if (marker.getAnimation() !== null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
},
currGeo = function(locate) {
  console.log('currGeo', locate);
  curLat = parseFloat(locate.latitude);
  curLon = parseFloat(locate.longitude);
  //if($('#latitude').val() == '') {
    $('#latitude').val(curLat);
  //}
  //if($('#longitude').val() == '') {
    $('#longitude').val(curLon);
  //}
  initMap();
},
redrawDt = function(evt) {
  $('#dtDatatable .editConsultinR').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.editFormation', $data);
    solstar.ajaxRequest('ConsultoriosSave', 'PATCH', function(resp) {
        if(resp.success) {
          console.log(resp)
          $('#modalDocEduc #saveFormation').data('type', 1);
          $('#modalDocEduc #saveFormation').data('reqid', resp.data.id);
          $('#modalDocEduc #name').val(resp.data.name);
          $('#modalDocEduc #idcity').val(resp.data.city+', '+resp.data.state+', '+resp.data.country);
          $('#modalDocEduc #idcityval').val(resp.data.idcityval);
          $('#modalDocEduc #address').val(resp.data.address);
          $('#modalDocEduc #phone1').val(resp.data.phone1);
          $('#modalDocEduc #phone2').val(resp.data.phone2);
          $('#modalDocEduc #email').val(resp.data.email);
          $('#modalDocEduc #url').val(resp.data.url);
          $('#modalDocEduc #latitude').val(resp.data.latitud);
          $('#modalDocEduc #longitude').val(resp.data.longitud);
          $('#modalDocEduc .modal-title').html('<i class="fa fa-edit fa-fw"></i> Editar Consultorio');
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
},
initMap = function () {
  geocoder = new google.maps.Geocoder();
  var lat = parseFloat($('#latitude').val() || curLat);
  var lon = parseFloat($('#longitude').val() || curLon);
  console.log('initMap', {lat: lat, lng:lon});
  map = new google.maps.Map(document.getElementById('map'), {
    center: new google.maps.LatLng(lat, lon),
    zoom: 12
  });
  /*if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(function(position) {
      var pos = {
        lat: position.coords.latitude,
        lng: position.coords.longitude
      };

      infoWindow.setPosition(pos);
      infoWindow.setContent('Location found.');
      infoWindow.open(map);
      map.setCenter(pos);
    }, function() {
      handleLocationError(true, infoWindow, map.getCenter());
    });
  }*/
  console.log('init marker');
  var imgmarker = new google.maps.MarkerImage(
    solstar.Almacen_fi+'assets/images/icon.png',
    new google.maps.Size(71, 71),
    new google.maps.Point(0, 0),
    new google.maps.Point(17, 34),
    new google.maps.Size(35, 35)
  );

  marker = new google.maps.Marker({
    position: {lat: lat, lng: lon}, 
    map: map,
    title: 'Esta Aqui!',
    draggable: true,
    animation: google.maps.Animation.DROP,
    icon: imgmarker,
  });
  
  marker.addListener('click', toggleBounce);
  google.maps.event.addListener(marker, 'dragend', function() {
      console.log('Drag ended');
      let p = marker.getPosition();
      $('#latitude').val(p.lat());
      $('#longitude').val(p.lng());
      geocodePosition(p);
  });
};

$.getScript('https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDg2Z6QWeOzf4FkS5ZYQx2Isw6efT90dHI&libraries=geometry&callback=initMap');

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

  $('#modalDocEduc').on('hide.bs.modal', function(e) {
    $('input, textarea','#modalDocEduc .modal-body').val('');
    $('#modalDocEduc .modal-title').html('<i class="fa fa-plus fa-fw"></i>Agregar Consultorio');
    $('#modalDocEduc #saveFormation').html('<i class="fa fa-save"></i> Guardar');
    $('#modalDocEduc #saveFormation').data('type', 0);
    $('#modalDocEduc #saveFormation').data('reqid', '');
  });

  $('#modalDocEduc').on('show.bs.modal', function(e) {
    if($('#saveFormation').data('reqid') == '') {
      $.getScript('https://get.geojs.io/v1/ip/geo.js?callback=currGeo');
    } else {
      initMap();
    }
  });

  $('#saveFormation').off('click').on('click', function(evt) {
    $('#addConsultingRoom-frm').validate();
    if($('#addConsultingRoom-frm').valid()) {
      var $data = {
        name: $('#modalDocEduc #name').val(),
        idconsult: $('#modalDocEduc #idconsult').val(),
        idcity: $('#modalDocEduc #idcityval').val(),
        city: $('#modalDocEduc #idcity').val(),
        address: $('#modalDocEduc #address').val(),
        phone1: $('#modalDocEduc #phone1').val(),
        phone2: $('#modalDocEduc #phone2').val(),
        email: $('#modalDocEduc #email').val(),
        url: $('#modalDocEduc #url').val(),
        latitude: $('#modalDocEduc #latitude').val(),
        longitude: $('#modalDocEduc #longitude').val(),
      };
      if($('#modalDocEduc #saveFormation').data('type') == 1) {
        $data.id = $('#modalDocEduc #saveFormation').data('reqid');
      }
      
      solstar.ajaxRequest('ConsultoriosSave', ($('#modalDocEduc #saveFormation').data('type') == 1 ? 'PUT': 'POST'), function(resp) {
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

  var dT = solstar.dataTableReq({
    el: "#dtDatatable",
    dataSource: function ( json ) {
      json = json.data;
      for ( var i=0, ien=json.length ; i<ien ; i++ ) {
        json[i].num = i+1;
        json[i].phone1 = '<i class="fa fa-phone fa-fw" aria-hidden="true"></i><a href="tel:'+json[i].phone1+'" target="_blank">'+json[i].phone1+'</a>'+((json[i].phone2 && json[i].phone2 != '') ? '<hr><i class="fa fa-phone fa-fw" aria-hidden="true"></i><a href="'+json[i].phone2+'" target="_blank">'+json[i].phone2+'</a>' :'');
        json[i].email = '<i class="fa fa-envelope fa-fw" aria-hidden="true"></i><a href="mailto:'+json[i].email+'" target="_blank">'+json[i].email+'</a>';
        let addr = json[i].address;
        json[i].address = '<i class="fa fa-map-marker-alt fa-fw" aria-hidden="true"></i>'+json[i].address+'<hr><div class="col-lg-12 rflex">';
        json[i].address+= '<a class="btn btn-info btn-sm mx-2" href="https://waze.com/ul?q='+encodeURIComponent(addr+' '+json[i].city+' '+json[i].state+' '+json[i].country)+'&z=8&navigate=yes&zom=8" class="button link external rflex"><i class="fab fa-waze"></i></a>';
        json[i].address+= '<a class="btn btn-info btn-sm mx-2" href="https://www.google.com/maps/dir/?api=1&destination='+encodeURIComponent(addr+' '+json[i].city+' '+json[i].state+' '+json[i].country)+'&travelmode=transit&dir_action=navigate" class="button link external rflex"><i class="fas fa-map-marked-alt"></i></a></div>';
        json[i].actions = '<a href="javascript:void(\''+json[i].id+'\')" class="btn btn-primary btn-sm editConsultinR" data-toggle="popover" title="Editar" data-content="'+json[i].name+'" data-id="'+json[i].id+'"><i class="fa fa-edit"></i></a>';
      }
      return json;
    },
    redrawFunction: redrawDt,
    columnDefs: [ {
      "targets": 0,
      "searchable": false
    }],
    columns: [
      {data: 'num'},
      {data: 'name'},
      {data: 'address'},
      {data: 'email'},
      {data: 'phone1'},
      {data: 'actions'},
    ],
    url: 'ConsultoriosSave',
    typeMethod: 'GET',
  });
});