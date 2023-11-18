"use strict";
moment().locale('es');
$(function(){
  //$.extend(true, $.fn, {
  //});
  
  $("#dtDatatable").dataTable({
    ajax: {
      url: solstar.params.almacen+'Admin/Doctors',
      method: "POST",
      "dataSrc": function ( data ) {
        var json = data.data;
        //console.log('dataSrc', json)
        for ( var i=0, ien=json.length ; i<ien ; i++ ) {
          json[i].number = i+1;
          json[i].fullname = json[i].titulo + ' '+json[i].fullname;
          json[i].contacto = '<i class="fas fa-mobile-alt"></i>: '+(json[i].phonenumber || '') + '<hr><i class="fas fa-at"></i>: '+(json[i].email || '');
          json[i].document = '<i class="far fa-id-card"></i>: <b>'+(json[i].type_docabrev || '') + '</b>'+(json[i].document || '') + '<hr><i class="fas fa-id-card-alt"></i> <b>ReTHUS:</b>'+(json[i].rethus || '');
          json[i].profileimage = '<img src="'+(json[i].profileimage || 'assets/images/icon.png') + '" class="img img-responsive img-fluid profimg" style="width:80px;"/>';

          json[i].satatuschoice = '<b>$ '+numeral((Math.random()*300000).toFixed(2)).format('0,0.00')+'</b>';
          json[i].actions = '';
          //console.log(json[i])
        }
        return json;
      }
    },
    "bSort": false,
    columns: [
        { data: "number", class: "dt-center text-right" },
        { data: "profileimage", class: "dt-center text-center"  },
        { data: "fullname", class: "dt-center text-left"  },
        { data: "document", class: "dt-center text-left" },
        { data: "contacto", class: "dt-center text-left" },
        { data: "especiality", class: "dt-center text-left" },
        { data: "satatuschoice", class: "dt-center text-left" },
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
        //$(this).doctorChangeStatus(evt);
    });
  })
  .on( 'page.dt', function () {
    $('.doctorChangeStatus').off('change').on('change', function(evt) {
        //$(this).doctorChangeStatus(evt);
    });
  });

});