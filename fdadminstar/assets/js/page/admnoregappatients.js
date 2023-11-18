"use strict";
moment().locale('es');
$(function(){
  //$.extend(true, $.fn, {
  //});
  
  $("#dtDatatable").dataTable({
    ajax: {
      url: solstar.params.almacen+'Admin/Patients-NoUsers',
      method: "POST",
      "dataSrc": function ( data ) {
        var json = data.data;
        //console.log('dataSrc', json)
        for ( var i=0, ien=json.length ; i<ien ; i++ ) {
          json[i].number = i+1;
          json[i].name = json[i].name;
          json[i].contacto = '<i class="fas fa-mobile-alt"></i>: '+(json[i].phonenumber || '') + '<hr><i class="fas fa-at"></i>: '+(json[i].email || '');
          json[i].document = '<i class="far fa-id-card"></i>: <b>'+(json[i].type_docabrev || '') + '</b>'+(json[i].document || '');
          //json[i].profileimage = '<img src="'+(json[i].profileimage || 'assets/images/icon.png') + '" class="img img-responsive img-fluid profimg" style="width:80px;"/>';

          //json[i].satatuschoice = '<label class="custom-switch mt-1"><span class="custom-switch-description"></span><input type="checkbox" id="doctorstaus'+json[i].iddoctor+'" data-iddoctor="'+json[i].idusers+'" data-idusers="'+json[i].idusers+'" name="doctorstaus'+json[i].iddoctor+'" '+(parseInt(json[i].idstatus) == 1 ? 'checked' : '')+' class="custom-switch-input doctorChangeStatus"><span class="custom-switch-indicator"></span></label>';
          json[i].actions = '';
          //console.log(json[i])
        }
        return json;
      }
    },
    "bSort": false,
    columns: [
        { data: "number", class: "dt-center text-right" },
        //{ data: "profileimage", class: "dt-center text-center"  },
        { data: "name", class: "dt-center text-left"  },
        { data: "document", class: "dt-center text-left" },
        //{ data: "contacto", class: "dt-center text-left" },
        //{ data: "satatuschoice", class: "dt-center text-left" },
        { data: "actions", class: "dt-center text-center" },
    ],
    "columnDefs": [
      //{ "sortable": false, "targets": [2,3] }
    ]
  })
  .on( 'preDraw', function () {
  })
  .on( 'draw.dt', function () {
    $('.doctorChangeStatus').off('change').on('change', function(evt) {
        $(this).patientChangeStatus(evt);
    });
  })
  .on( 'page.dt', function () {
    $('.doctorChangeStatus').off('change').on('change', function(evt) {
        $(this).patientChangeStatus(evt);
    });
  });

});