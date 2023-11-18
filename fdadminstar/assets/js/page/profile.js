"use strict";
moment().locale('es');

var curLat = 0, curLon = 0, marker = false, map, infoWindow, geocoder;

function geocodePosition(pos) {
  geocoder.geocode({
    latLng: pos
  }, function(responses) {
    if (responses && responses.length > 0) {
      marker.formatted_address = responses[0].formatted_address;
      $('#address').val(responses[0].formatted_address);
    } else {
      marker.formatted_address = 'Cannot determine address at this location.';
      $('#address').val('Cannot determine address at this location.');
    }
  });
}

function toggleBounce() {
  if (marker.getAnimation() !== null) {
    marker.setAnimation(null);
  } else {
    marker.setAnimation(google.maps.Animation.BOUNCE);
  }
}

function currGeo(locate) {
  console.log('currGeo', locate);
  curLat = parseFloat(locate.latitude);
  curLon = parseFloat(locate.longitude);
  if($('#latitude').val() == '') {
    $('#latitude').val(curLat);
  }
  if($('#longitude').val() == '') {
    $('#longitude').val(curLon);
  }
  $.getScript('https://maps.googleapis.com/maps/api/js?v=3&key=AIzaSyDg2Z6QWeOzf4FkS5ZYQx2Isw6efT90dHI&libraries=geometry&callback=initMap');
};

var initMap = function () {
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
    title: 'Estoy Aqui!',
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

$(function(){
  solstar.autocomplete($('#idcity', '#profile-form')[0], cities, $('#idcityval', '#profile-form')[0]);
  solstar.autocomplete($('#idcountry', '#profile-form')[0], countries, $('#idcountryval', '#profile-form')[0], function(obj) {
    console.log('autocomplete idcountry', obj)
    var url = `getCities/${obj.id}`;
    solstar.ajaxRequest(url, 'POST', function(resp) {
        console.log(url, resp)
        if(resp.success) {
          solstar.autocomplete($('#idcity', '#profile-form')[0], resp.data, $('#idcityval', '#profile-form')[0]);
        } else {
          alertify.warning('Ha ocurrido un error! '+resp.message, 10);
        }
      }, {
      }, true, null, function(err) {
        alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
      }
    );
  });
  $('#uploadThumb').on('change', function(evt) {
      $.readURL(this, '#thumbnailPreview');
  });
  
  $('.date').datetimepicker({
      locale: 'es',
      format: 'LT',
      format: 'DD/MM/YYYY',
      showTodayButton: true,
      showClear: true,
      ignoreReadonly: true,
      allowInputToggle: false,
  });

  //$('.swicht').bootstrapToggle();
  $('.intlTelInput').intlTelInput({
      customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
          return "e.g. " + selectedCountryPlaceholder;
      },
      initialCountry: 'CO',
      localizedCountries: { 'CO': 'Colombia'},
      onlyCountries: ['co'],
      separateDialCode: true, 
  });

  $('.intlTelInput').on('change', function(e) {
      $($(this).data('telvalue')).val($(this).intlTelInput('getNumber'));
  });//*/

  $('#birthdate').on('click', function(evt) {
      $('#birthdate-addon').click();
  });
  

  $.getScript('https://get.geojs.io/v1/ip/geo.js?callback=currGeo');
});