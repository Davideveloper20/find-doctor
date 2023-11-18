
  $$(".circle-progress").each(function() {

    var value = $$(this).attr('data-value');
    var left = $$(this).find('.circle-progress-left .circle-progress-bar');
    var right = $$(this).find('.circle-progress-right .circle-progress-bar');

    if (value > 0) {
      if (value <= 50) {
        right.css('transform', 'rotate(' + percentageToDegrees(value) + 'deg)')
      } else {
        right.css('transform', 'rotate(180deg)')
        left.css('transform', 'rotate(' + percentageToDegrees(value - 50) + 'deg)')
      }
    }

  })

  function percentageToDegrees(percentage) {

    return percentage / 100 * 360

  }

function TODataURL(file, callback) 
{
  var reader = new FileReader();
  reader.readAsDataURL(file);
  reader.onload = function () {
    callback(reader.result)
  };
  reader.onerror = function (error) {
    console.log('Error: ', error);
  };
}
function changePhoto(input,el)
{
    var file = input.files[0]
    console.log(file);
    file_ex = file;
    var form = new FormData()
    form.append("file", file)
    solstar.rq_file("apivision.php", function(data){
      console.log(data.data[0][0].split(/(\r\n|\n|\r)/gm))
      var me = $('div[id="printcontent"]')
      me.html('<div class="list no-hairlines no-margin"><ul><li class="item-content item-input"><div class="item-inner"><div class="item-title item-label">PESO REGISTRADO (KG)</div><div class="item-input-wrap"><input readonly type="text" placeholder="Valor OCR" id="pesomuestra" value="'+data.data[0][0].split(/(\r\n|\n|\r)/gm)[0]+'" class="readedOCR"></div></div></li><li class="item-content item-input"><div class="item-inner"><div class="item-title item-label">USUARIO</div><div class="item-input-wrap"><input readonly type="text" placeholder="Valor OCR" value="macuma"></div></div></li><li class="item-content item-input"><div class="item-inner"><div class="item-title item-label">FECHA|HORA</div><div class="item-input-wrap"><input readonly type="text" placeholder="Valor OCR" value="20/SEP/2019 10:05:32"></div></div></li><li class="item-content item-input"><div class="item-inner"><div class="item-title item-label">POSICIÓN</div><div class="item-input-wrap"><input readonly type="text" placeholder="Valor OCR" value="-77.1231, -12.2342"></div></div></li><li class="item-content item-input"><div class="item-inner"><div class="item-title item-label">OBSERVACIONES</div><div class="item-input-wrap"><input type="text" placeholder="Escribe algo aquí"></div></div></li></ul></div>')
      $('#btn-save').prop('disabled', false)
      TODataURL(file, function(dataURL){
      var element = $$('#div_preview'+el);
        element.append('<div class="position-relative text-align-center"><img src="'+dataURL+'"  width="100%" name="input_files'+element.find('img').length+'"></div>');
      })
    }, form)
}
function deleteThis(el)
{
  el.parentElement.remove()
}
function enviarFotos()
{
  var pesomuestra=document.getElementById('pesomuestra')
  if(pesomuestra.value=="")
  {
    pesomuestra.focus()
    return
  }
  app.dialog.alert("La muestra ha sido almacenada, ya no puede hacer modificaciones a la muestra")
  app.router.back()
}



/*
    CHAT */
    var refreshIntervalId
    function activarChat(subject)
    {
        refreshIntervalId = setInterval(function() {
            if($('[data-name="chat"]').hasClass('page-current')){
                loadMessages(subject);
            } else{
                clearInterval(refreshIntervalId);    
            }
        }, 5000);
    }
    function loadMessages(subject)
    {
        lastId = 0;
        count = 0;
        solstar.ajaxRequest("app/loadchat/"+subject, "GET", function(data) {
            if(data.chat.length > 0)
            {
                valueChat = "";
                nick = "";
                date = "";
                fecha = "";
                var messages = app.messages.get('.messages'), msgqty = $('.messages').find('.message').length
                if(data.chat.length > msgqty)
                {
                    $.each(data.chat, function(i, item) {
                        if(item.owner)
                        {
                            if($('#msg'+item.idchats).length==0&&$('[data-msg="'+item.chatmessage+'"]').length==0)
                            {
                                messages.addMessage({
                                    text: item.chatmessage,
                                    textFooter: item.chatdate+ ' <i class="material-icons" id="'+item.idchats+'">done</i>'
                                });
                                $('#'+item.idchats).parents('.message').attr('id', 'msg'+item.idchats)
                            }else{
                                let check = item.readed?"done_all":"done";
                                $('#msg'+item.idchats).find('.message-text-footer').find('i').html(' <i class="material-icons" id="'+item.idchats+'">'+check+'</i>')
                            }
                        }else{
                            if($('#msg'+item.idchats).length==0&&$('[data-msg="'+item.chatmessage+'"]').length==0)
                            {
                                messages.addMessage({
                                    text: item.chatmessage,
                                    type: 'received',
                                    textFooter: item.chatdate+ ' <i class="material-icons" id="'+item.idchats+'">done</i>',
                                    name: item.fullname,
                                    avatar: item.profileimage
                                });
                                $('#'+item.idchats).parents('.message').attr('id', 'msg'+item.idchats)
                            }else{
                                $('#msg'+item.idchats).find('.message-text-footer').find('i').html(' <i class="material-icons" id="'+item.idchats+'">done_all</i>')
                            }
                        }
                    })
                }
                counter += data.chat.length
                $('.messages').scrollTop($('.messages')[0].scrollHeight)
            }
        }, {}, false)
    }