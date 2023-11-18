"use strict";
moment().locale('es');

var redrawDt = function(evt) {
  $('#dtDatatable .editSpeciality').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.editSpeciality', $data);
    solstar.ajaxRequest('SpecialitiesSave', 'PATCH', function(resp) {
        if(resp.success) {
          console.log(resp)
          $('#modalDocEduc .modal-title').html('<i class="fa fa-edit fa-fw"></i>Actualzar Consultorio');
          $('#modalDocEduc #saveFormation').data('type', 1);
          $('#modalDocEduc #saveFormation, #modalDocEduc').data('reqid', resp.data.id);
          $('#modalDocEduc #speciality').val(resp.data.speciality);
          $('#modalDocEduc #tipo').val(resp.data.tipo);
          $('#modalDocEduc #descrip').val(resp.data.descrip);
          $('#modalDocEduc #saveFormation').html('<i class="fa fa-save"></i> Actaulizar');
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

  $('#dtDatatable .deleteSpeciality').off('click').on('click', function(evt) {
    var $this = $(this);
    var $data = $this.data();
    console.log('.deleteSpeciality', $data)
    solstar.jqc_alert('Eliminar Especialidad!','¿Confirma que desea eliminar este registro?','modern','fas fa-trash fa-1x', 'red', {
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
          solstar.ajaxRequest('SpecialitiesSave', 'DELETE', function(resp) {
              if(resp.success) {
                $('#dtDatatable').DataTable().ajax.reload();
              } else {
                alertify.warning(resp.message, 10);
              }
            }, {id: ($data.id || -1 )}, true, null, function(err) {
              alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
            }
          );
        }
      }
    });
  });
};

$(function(){
  //solstar.autocomplete($('#idcity', '#modalDocEduc')[0], cities, $('#idcityval', '#modalDocEduc')[0]);

  $('#modalDocEduc').on('hide.bs.modal', function(e) {
    $('input, textarea','#modalDocEduc .modal-body').val('');
    $('#modalDocEduc .modal-title').html('<i class="fa fa-plus fa-fw"></i>Agregar Consultorio');
    $('#modalDocEduc #saveFormation').html('<i class="fa fa-save"></i> Guardar');
    $('#modalDocEduc #saveFormation').data('type', 0);
    $('#modalDocEduc #saveFormation, #modalDocEduc').data('reqid', '');
  });

  $('#modalDocEduc').on('show.bs.modal', function(e) {
  });

  $('#saveFormation').off('click').on('click', function(evt) {
    $('#addSpeciality-frm').validate();
    if($('#addSpeciality-frm').valid()) {
      var $data = {
        speciality: $('#modalDocEduc #speciality').val(),
        tipo: $('#modalDocEduc #tipo').val(),
        descrip: $('#modalDocEduc #descrip').val(),
      };
      if($('#modalDocEduc #saveFormation').data('type') == 1) {
        $data.id = $('#modalDocEduc #saveFormation').data('reqid');
      }
      
      solstar.ajaxRequest('SpecialitiesSave', ($('#modalDocEduc #saveFormation').data('type') == 1 ? 'PUT': 'POST'), function(resp) {
        if(resp.success) {
          //location.reload();
          $('#dtDatatable').DataTable().ajax.reload();
          $('#modalDocEduc').modal('hide');
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
        json[i].actions = '<a href="javascript:void(\''+json[i].id+'\')" class="btn btn-primary btn-sm mx-1 editSpeciality" data-toggle="popover" title="Editar" data-content="'+json[i].speciality+'" data-id="'+json[i].id+'"><i class="fa fa-edit"></i></a>';
        json[i].actions+= '<a href="javascript:void(\''+json[i].id+'\')" class="btn btn-danger btn-sm mx-1 deleteSpeciality" data-toggle="popover" title="Editar" data-content="'+json[i].speciality+'" data-id="'+json[i].id+'"><i class="fa fa-trash"></i></a>';
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
      {data: 'speciality'},
      {data: 'tipo'},
      {data: 'descrip'},
      {data: 'actions'},
    ],
    url: 'SpecialitiesSave',
    typeMethod: 'GET',
  });
});