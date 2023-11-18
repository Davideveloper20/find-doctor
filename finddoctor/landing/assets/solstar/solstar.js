/*!
 *  SolStar Solution v1.0 - 24/07/2019 - ECMAScript 6 
 *  Clase SolStar
 *  Funciones Genéricas para proyectos en Soluciones Star S.A.S
 *  Desarrollado por Ing. Yossen Cano
 */

var solstar = {
  started: 0,
  registerspages: "",
  noresult: "",
  searchu: "",
  previous: "",
  next: "",
  params: [],
  init: function(params=null) {
    this.started=0
    this.registerspages=""
    this.noresult=""
    this.searchu=""
    this.previous=""
    this.next=""
    var param={
      name:"Soluciones Star S.A.S",
      session: false,
      jqconfirm: false,
      alertify: false,
      telMask: false,
      intTelInput: null,
      loader: null,
      storage: "local",
      loginpage: "login.html",
      navDefault: "chrome",
      almacen: "",
      multilanguage: true,
      loginurl: "login",
      recoverpassword: "recover",
      langurl: "language/index/",
      sessionToken:"userToken",
      indexpage: "index.html"
    }
    if(params){
      /*$.each(params,function(key,value){
        param[key]=value
      })*/
      $.extend(true, param, params);
      
    }
    //this.params=param
    $.extend(true, this.params, param);
    this.started=1
    this.initialize()
  },
  initialize: function() {
    if(this.params.session) {
      if(this.getToken()==false&&this.window()!=this.params.loginpage) {
        this.relocate(this.params.loginpage)
      }
    }
    if(this.params.telMask) {
      this.initPhoneMask()
    }
    if(this.params.multilanguage) {
      this.language("es")
    }
    this.console("Soluciones Star S.A.S, Proyecto Listo!");
  },
  window: function() {
    return location.href.split('/')[location.href.split('/').length-1]
  },
  session: function(params) {
    params.forEach(function(e){
      this.setSession(e.key,e.val)
    })
  },
  setSession: function(key,val) {
    console.log('setSession', key,val, this.params.sessionToken)
    if(this.params.storage=="local") {
      localStorage.setItem(key,val)
    } else {
      sessionStorage.setItem(key,val)
    }
  },
  getSession: function(val) {
    if(this.params.storage=="local") {
      return localStorage.getItem(val)
    } else {
      return sessionStorage.getItem(val)
    }
  },
  getToken: function() {
    if(this.getSession(this.params.sessionToken)!=null) {
      return this.getSession(this.params.sessionToken)
    }
    return false
  },
  unsetSession: function(relocate=true) {
    if(this.params.storage=="local") {
      localStorage.clear()
    } else {
      sessionStorage.clear()
    }
    if(relocate) {
      this.relocate(this.params.loginpage)
    }
  },
  rmSession: function(key) {
    if(this.params.storage=="local") {
      return localStorage.removeItem(key)
    } else {
      return sessionStorage.removeItem(key)
    }
  },
  navigator()
  {
    var me = this
    if(navigator.userAgent.match('CriOS')){
        this.jqc_alert('Lo sentimos!','Descarga la app de validocus en la App Store para continuar con el proceso de firma','my-theme','fa fa-times', 'red', {
          Salir: function(){me.relocate('https://validocus.com/')},
          "Ir a la Tienda": function(){

          }
        })
      }else if(navigator.userAgent.indexOf("Firefox") != -1 ) {
        $('#princicalDiv').addClass('mb-5');
          $('body').append('<div class="navigatorWrong"><span data-translate="wrong-navigator">Disfruta de la funcionalidad al 100% usando el navegador Google Chrome </span> <img src="http://validocus.com/v2/docs/libs/validocus/images/chrome.png">, <a href="https://www.google.es/chrome/index.html" data-translate="download-link">descarga aquí</></div>');
          dontOpen = true;
      }else if(navigator.userAgent.indexOf('Mobile') != -1){
        $('.mobile-on-show').addClass('active');
        $('.mobile-on-hide').addClass('active');
      }
      else if((navigator.userAgent.indexOf("MSIE") != -1 ) || (!!document.documentMode == true )) {//IF IE > 10
        $('#princicalDiv').addClass('mb-5');
          $('body').append('<div class="navigatorWrong"><span data-translate="wrong-navigator">Disfruta de la funcionalidad al 100% usando el navegador Google Chrome </span> <img src="http://validocus.com/v2/docs/libs/validocus/images/chrome.png">, <a href="https://www.google.es/chrome/index.html" data-translate="download-link">descarga aquí</></div>');
          dontOpen = true;
      }else if(navigator.userAgent.indexOf("Edge") > -1){
        $('#princicalDiv').addClass('mb-5');
          $('body').append('<div class="navigatorWrong"><span data-translate="wrong-navigator">Disfruta de la funcionalidad al 100% usando el navegador Google Chrome </span> <img src="http://validocus.com/v2/docs/libs/validocus/images/chrome.png">, <a href="https://www.google.es/chrome/index.html" data-translate="download-link">descarga aquí</></div>');
          dontOpen = true;
      }else if((navigator.userAgent.indexOf("Opera") > -1) || (navigator.userAgent.indexOf('OPR') != -1 ))  {
        $('#princicalDiv').addClass('mb-5');
          $('body').append('<div class="navigatorWrong"><span data-translate="wrong-navigator">Disfruta de la funcionalidad al 100% usando el navegador Google Chrome </span> <img src="http://validocus.com/v2/docs/libs/validocus/images/chrome.png">, <a href="https://www.google.es/chrome/index.html" data-translate="download-link">descarga aquí</></div>');
          dontOpen = false;
      }
  },
  relocate(url)
  {
    location.href = url
  },
  console(msj)
  {
    console.log(msj)
  },
  fadein()
  {
    $(this.params.loader).fadeIn()
  },
  fadeout()
  {
    $(this.params.loader).fadeOut()
  },
  emailvalidation(email)
  {
    if(!/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(email)) {
      return 1;
    } else {
      return 0;  
    } 
  },  
  passwordValidation(password, condition=1, message=false) {
    if(!password || password == '') {
      return false;
    }
    switch (condition) {
      case 1:
        // Mínimo 8 caracteres al menos 1 alfabeto y 1 número
        if(message) {
          return "Mínimo 8 caracteres al menos 1 alfabeto y 1 número";
        }
        return /^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/i.test(password)
        break;
      case 2:
        // Mínimo de 8 caracteres al menos 1 alfabeto, 1 número y 1 carácter especial
        if(message) {
          return "Mínimo de 8 caracteres al menos 1 alfabeto, 1 número y 1 carácter especial";
        }
        return /^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/i.test(password)
        break;
      case 3:
        // Mínimo de 8 caracteres al menos 1 alfabeto en mayúsculas, 1 alfabeto en minúsculas y 1 número
        if(message) {
          return "Mínimo de 8 caracteres al menos 1 alfabeto en mayúsculas, 1 alfabeto en minúsculas y 1 número";
        }
        return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/i.test(password)
        break;
      case 4:
        // Mínimo de 8 caracteres al menos 1 alfabeto en mayúsculas, 1 alfabeto en minúsculas, 1 número y 1 carácter especial
        if(message) {
          return "Mínimo de 8 caracteres al menos 1 alfabeto en mayúsculas, 1 alfabeto en minúsculas, 1 número y 1 carácter especial";
        }
        return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}$/i.test(password)
        break;
      case 5:
        // Mínimo 8 y máximo 10 caracteres al menos 1 alfabeto en mayúsculas, 1 alfabeto en minúsculas, 1 número y 1 carácter especial
        if(message) {
          return "Mínimo 8 y máximo 10 caracteres al menos 1 alfabeto en mayúsculas, 1 alfabeto en minúsculas, 1 número y 1 carácter especial";
        }
        return /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,10}$/i.test(password)
        break;
      default:
        return false;
        break;
    }
    return false;
  },
  today()
  {
    var fecha= new Date()
    var horas= fecha.getHours()
    var minutos = fecha.getMinutes()
    var segundos = fecha.getSeconds()
    var mes = fecha.getMonth()+1;
    var dia = fecha.getDate()
    var ano = fecha.getFullYear()
     
    return ano+'/'+mes+'/'+dia+' '+horas + ":" + minutos + ":" + segundos;
  },
  todaytime()
  {
    var date = new Date();
    var hora = date.getHours();
    var minuto = date.getMinutes();
    return hora+':'+minuto;
  },
  ajaxRequest: function(url,typeMethod,success,content=null,loader = true, progress=null, errorCb=null) {
    console.log('try ajaxRequest', url, typeMethod)
    var me = this
    if(loader){this.fadein();}
    if(typeMethod == 'POST' || typeMethod == 'PUT') {
      content.auth=this.getSession(this.params.sessionToken)
      content.app=1
      var data = content
    } else {
      var data={auth:this.getSession(this.params.sessionToken),app:1}
    }
    if(progress) {
      this.ajaxRequestFollow(url,typeMethod,success,content,loader,progress)
      return
    }
    $.ajax({
      type: typeMethod,
      dataType: "json",
      url: me.params.almacen+url,
      beforeSend: function (xhr) {
          /* Authorization header */
          console.log('TKN', me.getToken())
          if(me.getToken() && (['POST', 'PUT', 'DELETE'].indexOf(typeMethod)>=0)) {
            xhr.setRequestHeader("Authorization", "Basic " + me.getToken());
          }
          //xhr.setRequestHeader("X-Mobile", "false");
      },
      data: data,
      success: function(data) {success(data);},
      complete: function(data) {me.fadeout();},
      error: function(data, err) {
        console.log(data);
        if((typeof errorCb) == 'fucntion') {
          errorCb({data, err});
        }
      }
    })
  },
  ajaxRequestSync: function(url,typeMethod,success,content=null,loader = true, progress=null, errorCb=null) {
    console.log('try ajaxRequest', url, typeMethod)
    var me = this
    if(loader){this.fadein();}
    if(typeMethod == 'POST' || typeMethod == 'PUT') {
      content.auth=this.getSession(this.params.sessionToken)
      content.app=1
      var data = content
    } else {
      var data={auth:this.getSession(this.params.sessionToken),app:1}
    }
    if(progress) {
      this.ajaxRequestFollow(url,typeMethod,success,content,loader,progress)
      return
    }
    $.ajax({
      type: typeMethod,
      dataType: "json",
      url: me.params.almacen+url,
      async: false,
      beforeSend: function (xhr) {
          /* Authorization header */
          console.log('TKN', me.getToken())
          if(me.getToken() && (['POST', 'PUT', 'DELETE'].indexOf(typeMethod)>=0)) {
            xhr.setRequestHeader("Authorization", "Basic " + me.getToken());
          }
          //xhr.setRequestHeader("X-Mobile", "false");
      },
      data: data,
      success: function(data) {success(data);},
      complete: function(data) {me.fadeout();},
      error: function(data, err) {
        console.log(data);
        if((typeof errorCb) == 'fucntion') {
          errorCb({data, err});
        }
      }
    })
  },
  ajaxRequestFollow(url,typeMethod,success,data,loader,progress)
  {
    var me = this
    data.append('auth', this.getToken())
    $.ajax({
      type: typeMethod,
      dataType: "json",
      url: me.params.almacen+url,
      data: data,
      xhr: progress,
      processData: false,
      contentType: false,
      success: function(data) {success(data);},
      complete: function(data) {me.fadeout();},
      error: function(data) {console.log(data);}
    })
  } , 
  fetchApi(url, typeMethod, dataform = null, preload = true, alertar = false)
  {
    var me = this
    var data = ''
    if(dataform != null)
    {
      var object = {};
      dataform.forEach(function(value, key){
          object[key] = value;
      });
      var data = JSON.stringify(object);
    }
    return new Promise(function(resolve, reject) {

      if (preload) {
        
        me.fadein()
      }

      if (self.fetch) {
        // ejecutar peticiÃ³n fetch
          var headers
          if (typeMethod === 'POST') {
            headers = {
              'Authorization': 'Bearer ' + getToken(),
              'Content-Type': 'application/json'
            }
          } else if (typeMethod === 'PUT') {
            headers = {
              'Authorization': 'Bearer ' + getToken(),
              'Content-Type': 'application/json'
            }
          } else {
            headers = {
              'Authorization': 'Bearer ' + getToken()
            }
          }

          var params = {
            method: typeMethod,
            cache: 'default',
            mode: 'cors'
          }
          params.headers = headers

          if (data !== '') {
            params.body = data
          }

          var request = new Request(me.params.almacen + url, params)
          fetch(request)
            .then(status) //=> consume(status)
            .then(res => res.json())
            .then(data => {
                resolve(data)
            }).catch(error => {
                reject(Error('Request failed', error))
            })
      } else {
          alert('Por favor actualiza el navegador')
      }
    })
  },
  ajaxDataTable(url, table, columns, columnDefs, type)
  {
    var self = this
    this.table = $(table).DataTable({
      "bProcessing": true,
          "serverSide": true,
          "bStateSave": true,
          "fnStateSave": function (oSettings, oData) {
              sessionStorage.setItem('offersDataTables2', JSON.stringify(oData));
          },
          "fnStateLoad": function (oSettings) {
              return JSON.parse(sessionStorage.getItem('offersDataTables2'));
        
          },
       
       "ajax": {
              "url":  self.params.almacen+url,
              "type": type,
        "data": {
              auth: self.getSession(self.sessionItem),
        },  
          },
      "columnDefs" : columnDefs,
        "pageLength": 5,
        "lengthMenu": [5, 10, 25, 50, 75, 100],
        "columns": columns,
      "order": [[ 0, "desc" ]],
        "language": {
          "lengthMenu": "_MENU_ ",
          "zeroRecords": '',
          "info": "_PAGE_ of _PAGES_",
          "infoEmpty": '',
          "search": '',
          "infoFiltered": "(filtered from _TOTAL_ total records)",
          "paginate": {
            "previous": 'previous',
            "next": 'next'
          }
        }   
      });
  },
  rq_file(url,success,formdata,loader,progress=null)
  {
    var me = this
    if(!progress)
    {
      progress=function() {
        var xhr = $.ajaxSettings.xhr();
        xhr.upload.onprogress = function(e) {
          
        };
        return xhr;
      }
    }
    formdata.append('auth', this.getToken());
    $.ajax({
      type: "POST",
      dataType: "json",
      url: me.params.almacen+url,
      data: formdata,
      processData: false,
      contentType: false,
      xhr: progress,
      beforeSend: function(data) {},
      success: function(data) {success(data)},
      complete: function(data) {me.fadeout()},
      error: function(data) {console.log(data)}
    })
  },
  rq_json(url,method="GET",auth="",success,json=null,failure=null)
  {
    var me = this
    $.ajax({
      url: me.params.almacen+url,
      data: json,
      type: method,
      dataType: 'json',
      contentType: 'application/json',
      headers: auth,
      success: function(data){fadeOut();success(data);},
      error: function(data){fadeOut();failure(data);}
    })
  },
  rq_error(data)
  {
    console.log(data);
  },
  login(username,password,success)
  {
    this.ajaxRequest(this.params.loginurl, "POST", success, {email:email,password:password});
  },
  language(lang)
  {
    var me = this
    this.ajaxRequest(this.params.langurl+lang,"GET", function(data){
      me.setSession('textos', JSON.stringify(data))
      $.each( data, function( key, value ) {
        if($('[data-translate="'+key+'"]').is('input') || $('[data-translate="'+key+'"]').is('textarea')){
          $('[data-translate="'+key+'"]').attr('placeholder', value);
        }else{
          $('[data-translate="'+key+'"]').html(value);
        }
      })
      me.translateN()
    })
  },
  translateN()
  {
    var ms = JSON.parse(this.getSession('textos'));
    $.each(ms, function( key, value ) {
        if($('[data-translate="'+key+'"]').is('input')){
            $('[data-translate="'+key+'"]').attr('placeholder', value);
       }else{
            $('[data-translate="'+key+'"]').html(value);
        }
    }); 
    if(ms != null)
    {
      $('.myProfileC').html(ms['menu-profile']);
      $('.editProfileC').html(ms['menu-editprofile']);
      $('.singOffC').html(ms['menu-log']);
      this.registerspages = ms['registerspages'];
      this.noresult = ms['no-result'];
      this.searchu = ms['search'];
      this.previous = ms['previous'];
      this.next = ms['next'];
    }
  },
  jqc_alert(titulo,mensaje,tema,icon,type,btn=null)
  {
    if(this.params.jqconfirm)
    {
      $.confirm({
        title: titulo,
        content: mensaje,
        theme: tema,
        icon: icon,
        type: type,
        animation: 'rotateX',
        closeAnimation: 'rotateY',
        animationBounce: 2,
        buttons: btn
      });
    }else{
      console.log("Habilite las alertas por JQuery Confrim {jqconfirm:true} ")
    }
  },
  puntos(varNum)
  {
    varNum += '';

    var x = varNum.split('.');

    var x1 = x[0];

    var x2 = x.length > 1 ? '.' + x[1] : '';

    var rgx = /(\d+)(\d{3})/;

    while (rgx.test(x1)) 

    {

      x1 = x1.replace(rgx, '$1' + '.' + '$2');

    }

    return x1 + x2;
  },
  bigalert(msj,title=null)
  {
    if(this.params.alertify==true)
    {
      alertify.alert(this.params.name,msj)
    }else{
      this.console("Habilite la librería Alertify.js {alertify:true}")
    }
  },
  toast(msj)
  {
    if(this.params.alertify==true)
    {
      alertify.success(msj)
    }else{
      this.console("Habilite la librería Alertify.js {alertify:true}")
    }
  },
  getId(splitt){
    var name = location.href.split(splitt)[1];
    if(name == ''){
      return false;
    }else{
      return name;
    }
  },
  initPhoneMask: function() {
    if(this.params.telMask==false) {
      this.console("Habiliete la librería IntlTelInput {telMask: true}")
      return;
    }
    if(this.params.intTelInput==null ) {
      this.console("No se ha asignado el elemento HTML para inicializar el Plugin intlTelInput {intTelInpu: '#element'}")
      return;
    }
    $(this.params.intTelInput).intlTelInput({
      customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
          return "e.g. " + selectedCountryPlaceholder;
      },
      initialCountry: 'CO'
    });
  },
  initPhoneMaskEl: function(el) {
    if(this.params.telMask==false) {
      this.console("Habiliete la librería IntlTelInput {telMask: true}")
      return;
    }
    if(el==null ) {
      this.console("No se ha asignado el elemento HTML para inicializar el Plugin intlTelInput {intTelInpu: '#element'}")
      return;
    }
    $(el).intlTelInput({
      customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
          return "e.g. " + selectedCountryPlaceholder;
      },
      initialCountry: 'CO'
    });
  },
  managefileinput(input,div,multiple=false)
  {
    if (input.files && input.files[0]) {
      var total_file = input.files.length;
      var icon = '';
      for(var i=0;i<total_file;i++)
      {
        var tipo = event.target.files[i].type;
        var quetipo = tipo.split('/');
        if(quetipo[1] == 'pdf'){ 
          icon += '<div class="element-open"><i class="fas fa-file-pdf"></i><br><i class="fa fa-search"></i></div>'; 
        }else if(quetipo[1] == 'mp4'){
          icon += '<div class="element-open"><i class="fas fa-file-video"></i><br><i class="fa fa-search"></i></div>';
        }else if(quetipo[1] == 'jpg' || quetipo[1] == 'jpeg' || quetipo[1] == 'png'){ 
          icon += '<div class="element-open"><img src="'+URL.createObjectURL(event.target.files[i])+'"><i class="fa fa-search"></i></div>'; 
        }else{
          this.toast("Carga un archivo permitido!")
          input.value = '';
          return;
        }
      }
      //$(div).addClass('w-value');
      //$(div).find('img').remove();
      //$(div).find('.element-open').remove();
      //$(div).find('.fa').remove();
      $(div).append(icon);
    }
  }
}