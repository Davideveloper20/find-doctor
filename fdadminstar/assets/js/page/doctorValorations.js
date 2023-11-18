"use strict";
moment().locale('es');
$(function(){
  $("#dtDatatable").dataTable({
    ajax: {
      url: solstar.params.almacen+'Doctor/Comments',
      method: "POST",
      "dataSrc": function ( data ) {
        var json = data.data;
        //console.log('dataSrc', json)
        for ( var i=0, ien=json.length ; i<ien ; i++ ) {
          json[i].number = i+1;
          json[i].media = parseFloat(json[i].media).toFixed(1);
          json[i].valoraciones = '<ul><li><b>Puntualidad</b>: '+json[i].puntualidad+'</li><li><b>Atención</b>: '+json[i].atencion+'</li><li><b>Instalaciones</b>: '+json[i].instalaciones+'</li><li><b>Experiencia</b>:  '+json[i].item4+'</li><li><b>Apariencia</b>:  '+json[i].item5+'</li></ul>';
          json[i].fecha = moment(json[i].fecha).locale('es').format('DD/MM/YYYY');

          json[i].actions = '';
          //console.log(json[i])
        }
        return json;
      }
    },
    "bSort": false,
    columns: [
        { data: "number", class: "dt-center text-right" },
        { data: "fullname", class: "dt-center text-left"  },
        { data: "media", class: "dt-center text-left" },
        { data: "valoraciones", class: "dt-center text-left" },
        { data: "comments", class: "dt-center text-left" },
        { data: "fecha", class: "dt-center text-left" },
        { data: "actions", class: "dt-center text-center" },
    ],
    "columnDefs": [
      //{ "sortable": false, "targets": [2,3] }
    ]
  });

});