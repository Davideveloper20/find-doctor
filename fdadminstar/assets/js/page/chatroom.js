"use strict";
var chatsObj = {};
var chatsLst = {};
var loadingChats = false;
var loadingChatsLst = false;
var chatingWith = -1;
var { Observable } = rxjs;
var { map } = rxjs.operators;
var messages = []
var active_chat = 0
var active_messages = []
Observable.create((observer) => db.collection("chat")
  .where('doctor.id', '==', id_doctor)
  .onSnapshot(observer))
  .pipe(
    map((data) => {
      data.docs.map((d) => {
        console.log(d.data());
        const chat = {
          id: d.id,
          ...d.data()
        };
        console.log(chat.id)
        var chat_list_user_left = $('#chatUsersBox');
        if(chat_list_user_left.find(`#chat-${chat.id}`).length == 0)
        {
          $('#chatUsersBox').append(`<li class="media" id="chat-${chat.id}"><img alt="image" class="mr-3 rounded-circle loadChatMessages" width="50" data-lastid="0" data-firstid="0" src="assets/images/icon.png"><div class="media-body"><div class="mt-0 mb-1 font-weight-bold loadChatMessages" data-lastid="0" data-firstid="0" onclick="fillChat('${chat.doctor.id}','${chat.patient.id}', '${chat.code}')">${chat.patient.name}</div></div><span class="badge badge-primary">Nuevo</span></li>`);
        }
        //alertify.message('Nuevo Chat de <b>'+val.fullname+'</b>', 15);
        //$('#dingAudio')[0].play();
        $('#notifmessage').addClass('beep');
        setTimeout(() => {$('#dingAudio')[0].pause();}, 500);
        /*chat.messages.map((message) => {
          const type = message.sender === 'doctor' ? 'received' : 'sent';
          messages.push({ text: message.payload, type });
          
        })*/
      });

      //print_messages()
    }),
  ).subscribe();
function fillChat(doc_id, patient_id, code) {
  var messages = []
  console.log(typeof(doc_id), typeof(patient_id))
  Observable.create((observer) => db.collection("chat")
  .where('doctor.id', '==', doc_id)
  .where('patient.id', '==', patient_id)
  .where('code', '==', code)
  .onSnapshot(observer))
  .pipe(
    map((data) => {
      $('#chat-content').html('')
      console.log(data.docs);
      data.docs.map((d) => {
        console.log(d.data());
        const chat = {
          id: d.id,
          ...d.data()
        };
        active_chat = chat.id
        active_messages = chat.messages
        chat.messages.map((message) => {
          const type = message.sender === 'doctor' ? 'received' : 'sent';
          $.chatCtrl('#mychatbox', {
            text: message.payload,
            picture: 'assets/images/icon.png',
            position: 'chat-'+(message.addressee == "doctor" ? 'left' : 'right'),
            time: '',//'<span id="'+chats[i].unixtime+'">'+moment(chats[i].create_at).format('DD/MM/YYYY hh:mm a')+'</span>',
            type: 'text',
          });
        })
        $('#chat-content').animate({ scrollTop: $('#chat-content').prop("scrollHeight")}, 0);
      });
    }),
  ).subscribe();
  /*if(chats.length <=0) {
    $('#chat-content').animate({ scrollTop: $('#chat-content').prop("scrollHeight")}, 300);
    return false;
  }
  for(var i = 0; i < chats.length; i++) {
    //console.log('existemsg', chats[i].unixtime, $('#mychatbox span[id='+chats[i].unixtime+']').length)
    if($('#mychatbox span[id='+chats[i].unixtime+']').length <= 0) {
      var type = chats[i].type;
      //if(chats[i].typing != undefined) type = 'typing';
      var messaBox = '';
      if(type != 'text') {
        chats[i].message = JSON.parse(chats[i].message);
      }
      if(type == 'text') {
        messaBox = (chats[i].message != undefined ? chats[i].message : '')
      } else if(type == 'audio') {
        messaBox = '<i class="fas fa-volume-up"></i> <audio preload="auto" controls><source src="'+chats[i].message.Location+'" type="'+chats[i].message.mime+'"> </audio>';
      } else if(type == 'video') {
        messaBox = '<i class="fas fa-video"></i> <video width="300" height="220" preload="auto" controls><source src="'+chats[i].message.Location+'" type="'+chats[i].message.mime+'"> </video>';
      } else if(type == 'image') {
        messaBox = '<a class="link external" href="'+chats[i].message.Location+'" target="_blank"><img class="img img-responsive img-fluid" style="max-width:300px; height:auto;" src="'+chats[i].message.Location+'" alt="'+baseName(chats[i].message.File)+'"/></a>';
      } else if(type == 'file') {
        messaBox = '<a class="link external" href="'+chats[i].message.Location+'" target="_blank"><i class="fas fa-download fa-fw"></i> '+baseName(chats[i].message.File)+'</a>';
      } else {
        messaBox = '<a class="link external" href="'+chats[i].message.Location+'" target="_blank"><i class="fas fa-download fa-fw"></i> '+baseName(chats[i].message.File)+'</a>';
      }
      $.chatCtrl('#mychatbox', {
        text: messaBox,
        picture: chats[i].profimg_from,
        position: 'chat-'+(chats[i].owner == "0" ? 'left' : 'right'),
        time: '<span id="'+chats[i].unixtime+'">'+moment(chats[i].create_at).format('DD/MM/YYYY hh:mm a')+'</span>',
        type: 'text',
      });
    }
    if(scrr) {
      $('#chat-content').animate({ scrollTop: $('#chat-content').prop("scrollHeight")}, 0);
    }
  }
  if((typeof cb) == 'function') {
    return cb();
  }
  return true;*/
}

function loadNewMessage(idpatient, play=false) {

  var lastid = $(`.loadChatMessages[data-iduserchat=${idpatient}]`).data('lastid');
  var url = `Doctor/Get-Chat-Messages/${idpatient}/1/${lastid}`;
  solstar.ajaxRequest(url, 'POST', function(resp) {
      if(resp.success) {
        if(resp.data.length > 0) {
          if((typeof chatsObj[idpatient]) == 'undefined') {
            chatsObj[idpatient] = [];
          }
          if(play) {
	        	//$('#startAudio')[0].play();
	        	//setTimeout(() => {$('#startAudio')[0].pause();}, 500);
	        }
          $(`.loadChatMessages[data-iduserchat=${idpatient}]`).data({
            'lastid' : parseInt(resp.data[resp.data.length-1].id) || 0,
          });
          //chatsObj[idpatient] = 
          $.extend(true, chatsObj[idpatient], resp.data);
          if(chatingWith > 0) {
            fillChat(resp.data, true, function() {
              $('#chat-content').animate({ scrollTop: $('#chat-content').prop("scrollHeight")}, 10);
            });
          }
        } else {
          $('#chat-content').animate({ scrollTop: $('#chat-content').prop("scrollHeight")}, 10);
        }
      } else {
        alertify.warning('Ha ocurrido un error! '+resp.message, 10);
      }
    }, {
    }, true, null, function(err) {
      alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
    }
  );
}

function loadNewChats() {
  $('div.loadChatMessages').each(function(idx, val) {
		var $iduserchat = $(val).data('iduserchat');
		if((typeof chatsLst[$iduserchat]) == 'undefined') {
			chatsLst[$iduserchat] = 0;
		}
  })
  if(!loadingChats) {
    loadingChats = true;
    var url = `Doctor/DocPatientChat`;
    solstar.ajaxRequest(url, 'POST', function(resp) {
        if(resp.success) {
          loadingChats = false;
          //console.log('Doctor/DocPatientChat', resp.data);
          $.each(resp.data, function(index, val) {
            if($('#chatUsersBox li[data-iduserchat='+val.idusers+']').length == 0) {
              $('#chatUsersBox').append('<li class="media" data-iduserchat="'+val.idusers+'"><img alt="image" class="mr-3 rounded-circle loadChatMessages" data-name="'+val.fullname+'" width="50" data-lastid="0" data-firstid="0" data-iduserchat="'+val.idusers+'" src="'+val.profileimage+'"><div class="media-body"><div class="mt-0 mb-1 font-weight-bold loadChatMessages" data-name="'+val.fullname+'" data-iduserchat="'+val.idusers+'" data-lastid="0" data-firstid="0">'+val.fullname+'</div></div><span class="badge badge-primary">Nuevo</span></li>');
              alertify.message('Nuevo Chat de <b>'+val.fullname+'</b>', 15);
              //$('#dingAudio')[0].play();
              $('#notifmessage').addClass('beep');
              setTimeout(() => {$('#dingAudio')[0].pause();}, 500);
              $('.loadChatMessages').off('click').on('click', fnLoadChatMessages);
              $('#chatUsersBox, #chatUsersBoxCnt').css('overflow', 'auto !important');
            }
          });
        } else {
          loadingChats = false;
          alertify.warning('Ha ocurrido un error! '+resp.message, 10);
        }
      }, {
      }, true, null, function(err) {
        loadingChats = false;
        alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
      }
    );
  }

  if(!loadingChatsLst) {
    loadingChatsLst = true;
    var url = `Doctor/DocPatientChatLst`;
    solstar.ajaxRequest(url, 'POST', function(resp) {
        if(resp.success) {
          loadingChatsLst = false;
          $.each(resp.data, function(index, val) {
  					if(chatsLst[index] < val) {
  						loadNewMessage(index, false);
  					}
  					//      	 
          });
        } else {
          loadingChatsLst = false;
          alertify.warning('Ha ocurrido un error! '+resp.message, 10);
        }
      }, {
      	users: chatsLst
      }, true, null, function(err) {
        loadingChatsLst = false;
        alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
      }
    );
  }
}

$('#messageBox-txt').on('keypress', function(evt) {
  if(evt.keyCode == 13 || evt.charCode == 13) {
    $("#btnSend").trigger('click');
  }
});
$("#btnSend").off('click').on('click', function(evt) {
  var me = $("#chat-form");
  var $this = $(this);
  if(me.find('input').val().trim().length > 0) {    

    active_messages.push({
      addressee: 'patient',
      sender: 'doctor',
      payload: me.find('input').val(),
      created_at: new Date(),
    })

    db.collection('chat')
    .doc(active_chat)
    .update({
      messages: active_messages,
    });
    me.find('input').val('')
    /*var url = `Doctor/Send-Chat-Messages/${$this.data('iduser')}`;
    solstar.ajaxRequest(url, 'POST', function(resp) {
        if(resp.success) {
          var msgObj = resp.data[0];
          //$(`.loadChatMessages[data-iduserchat=${$this.data('iduser')}]`).data({
          //  'lastid' : parseInt(msgObj.id) || 0,
          //});
          $.chatCtrl('#mychatbox', {
            text: msgObj.message,
            picture: msgObj.profimg_from,
            type: msgObj.type,
            time: moment(msgObj.create_at).format('DD/MM/YYYY hh:mm a'),
            onShow: function(th, el) {
              $('#chat-content').animate({ scrollTop: $('#chat-content').prop("scrollHeight")}, 300);
            }
          });
          $('#chat-content').animate({ scrollTop: $('#chat-content').prop("scrollHeight")}, 20);
          me.find('input').val('');
        } else {
          alertify.warning('Ha ocurrido un error! '+resp.message, 10);
        }
      }, {
        message: me.find('input').val(),
        unixtime: moment().locale('es').unix(),
      }, true, null, function(err) {
        alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
      }
    );*/
  } 
  return false;
});
