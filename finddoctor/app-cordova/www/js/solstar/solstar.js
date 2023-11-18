/*!
 *  SolStar Solution v2.0 - 24/07/2019 - ECMAScript 6 
 *  Clase SolStar
 *  Funciones Genéricas para proyectos en Soluciones Star S.A.S
 *  Desarrollado por Ing. Yossen Cano
 */
"use strict";
(function () {
  if (typeof $ === 'undefined') {
    throw new TypeError('Soluciones Star\'s ECMAScript requires jQuery. jQuery must be included before Soluciones Star\'s ECMAScript.');
  }

  var version = $.fn.jquery.split(' ')[0].split('.');
  var minMajor = 1;
  var ltMajor = 2;
  var minMinor = 9;
  var minPatch = 1;
  var maxMajor = 4;

  if (version[0] < ltMajor && version[1] < minMinor || version[0] === minMajor && version[1] === minMinor && version[2] < minPatch || version[0] >= maxMajor) {
    throw new Error('Soluciones Star\'s ECMAScript requires at least jQuery v1.9.1 but less than v4.0.0');
  }
})();

class SolStar {
  /**
   * [constructor description]
   * @param  {[type]} params [description]
   * @return {[type]}        [description]
   */
  constructor(params=null) {
    this.started=0
    this.registerspages=""
    this.noresult=""
    this.searchu=""
    this.previous=""
    this.next=""
    //this.Token = 'KRqwT6mdGU2vOUy9yV'
    var param={
      name:"Soluciones Star S.A.S",
      session: false,
      jqconfirm: false,
      alertify: false,
      telMask: false,
      intTelInput: null,
      telMaskUtils: null,
      loader: null,
      storage: "local",
      loginpage: "login.html",
      indexpage: "index.html",
      navDefault: "chrome",
      almacen: "",
      almacen_fi: "",
      multilanguage: true,
      bootstraptoast:false,
      loginurl: "login",
      recoverpassword: "recover",
      langurl: "language/index/",
      sessionToken:"userToken",
    }
    if(params) {
      $.extend(true, param, params);
    }
    this.params=param
    this.init()
  }

  /**
   * [init description]
   * @return {[type]} [description]
   */
  init() {
    this.start1
    this.initialize()
  }

  /**
   * [initialize description]
   * @return {[type]} [description]
   */
  initialize() {
    if(this.params.session)     {
      if(this.getToken()==false&&this.window()!=this.params.loginpage) {
        this.relocate(this.params.loginpage)
      }
    }
    if(this.params.bootstraptoast) {
      $('body').append('<div aria-live="polite" aria-atomic="true" style="position: fixed;min-height: auto;bottom: 40px;right: 0px;width: 30%;z-index: 2147483647;display: none;"><div style="min-height: 100px;display: flex;flex-direction: column;justify-content: center;"><div class="toast m-auto" data-delay="5000" role="alert" aria-live="assertive" aria-atomic="true" style="width: 80%;max-width: 80%;"><div class="toast-header"><img src="'+this.params.almacen_fi+'icon.png" class="rounded mr-2" style="width: 25px;" alt="..."><strong class="mr-auto toast-title" ></strong><small class="text-muted">2 seconds ago</small><button type="button" class="ml-2 mb-1 close" data-dismiss="toast" aria-label="Close"><span aria-hidden="true">&times;</span></button></div><div class="toast-body"></div></div></div></div>')
    }
    this.navigator()
    if(this.params.telMask) {
      this.initPhoneMask()
    }
    if(this.params.multilanguage) {
      this.language("es")
    }
    this.log = console.log.bind(console);
    this.debug = console.debug.bind(console);
    this.info = console.info.bind(console);
    this.warn = console.warn.bind(console);
    this.error = console.error.bind(console);
    this.console("Soluciones Star S.A.S, Proyecto Listo!")
  }

  /**
   * [window description]
   * @return {[type]} [description]
   */
  window() {
    return location.href.split('/')[location.href.split('/').length-1]
  }

  /**
   * [session description]
   * @param  {[type]} params [description]
   * @return {[type]}        [description]
   */
  session(params) {
    params.forEach(function(e){
      this.setSession(e.key,e.val)
    })
  }

  /**
   * [setSession description]
   * @param {[type]} key [description]
   * @param {[type]} val [description]
   */
  setSession(key,val) {
    if(this.params.storage=="local") {
      localStorage.setItem(key,val)
    } else {
      sessionStorage.setItem(key,val)
    }
  }

  /**
   * [getSession description]
   * @param  {[type]} val [description]
   * @return {[type]}     [description]
   */
  getSession(val) {
    if(this.params.storage=="local") {
      return localStorage.getItem(val)
    } else {
      return sessionStorage.getItem(val)
    }
  }

  /**
   * [Storage description]
   * @param {[type]} value [description]
   */
  set Storage(value) {
    if(['local','session'].indexOf(value) >= 0) {
      this.params.storage = value;
    } else {
      throw new Error('El storage de session');
    }
  }

  /**
   * [Storage description]
   */
  get Storage() {
    return this.params.storage;
  }

  set LoginPage(value) {
    this.params.loginpage = value;
  }

  get LoginPage() {
    return this.params.loginpage;
  }

  set IndexPage(value) {
    this.params.indexpage = value;
  }

  get IndexPage() {
    return this.params.indexpage;
  }

  set Almacen(value) {
    this.params.almacen = value;
  }

  get Almacen() {
    return this.params.almacen;
  }

  set Almacen_fi(value) {
    this.params.almacen_fi = value;
  }

  get Almacen_fi() {
    return this.params.almacen_fi;
  }

  set LoginUrl(value) {
    this.params.loginurl = value;
  }

  get LoginUrl() {
    return this.params.loginurl;
  }

  set RecoverUrl(value) {
    this.params.recoverpassword = value;
  }

  get RecoverUrl() {
    return this.params.recoverpassword;
  }

  set sessionToken(value) {
    this.params.sessionToken = value;
  }

  get sessionToken() {
    return this.params.sessionToken;
  }

  /**
   * [getToken description]
   * @return {[type]} [description]
   */
  getToken() {
    if(this.getSession(this.params.sessionToken)!=null) {
      return this.getSession(this.params.sessionToken)
    }
    return false
  }

  /**
   * Get Token Session
   */
  get Token() {
    if(this.getSession(this.params.sessionToken)!=null && this.getSession(this.params.sessionToken) != 'undefined' && this.getSession(this.params.sessionToken) != undefined) {
      return this.getSession(this.params.sessionToken)
    }
    return null;
  }

  /**
   * [Token description]
   * @param {[type]} value [description]
   */
  set Token(value) {
    this.params.sessionToken = value;
  }

  /**
   * [unsetSession description]
   * @return {[type]} [description]
   */
  unsetSession() {
    if(this.params.storage=="local") {
      localStorage.clear()
    } else {
      sessionStorage.clear()
    }
    this.relocate(this.params.loginpage)
  }

  /**
   * [rmSession description]
   * @param  {[type]} key [description]
   * @return {[type]}     [description]
   */
  rmSession(key) {
    if(this.params.storage=="local") {
      return localStorage.removeItem(key)
    } else {
      return sessionStorage.removeItem(key)
    }
  }

  /**
   * [navigator description]
   * @return {[type]} [description]
   */
  navigator() {
    var me = this
    if(navigator.userAgent.match('CriOS')){
      this.jqc_alert('Lo sentimos!','Descarga la app de validocus en la App Store para continuar con el proceso de firma','my-theme','fa fa-times', 'red', {
        Salir: function(){me.relocate(this.params.indexpage)},
        "Ir al Inicio": function(){
        }
      });
    } else if(navigator.userAgent.indexOf("Firefox") != -1 ) {
      $('#princicalDiv').addClass('mb-5');
        $('body').append('<div class="navigatorWrong"><span data-translate="wrong-navigator">Disfruta de la funcionalidad al 100% usando el navegador Google Chrome </span> <img src="http://validocus.com/v2/docs/libs/validocus/images/chrome.png">, <a href="https://www.google.es/chrome/index.html" data-translate="download-link">descarga aquí</></div>');
        dontOpen = true;
    } else if(navigator.userAgent.indexOf('Mobile') != -1) {
      $('.mobile-on-show').addClass('active');
      $('.mobile-on-hide').addClass('active');
    } else if((navigator.userAgent.indexOf("MSIE") != -1 ) || (!!document.documentMode == true )) {//IF IE > 10
      $('#princicalDiv').addClass('mb-5');
        $('body').append('<div class="navigatorWrong"><span data-translate="wrong-navigator">Disfruta de la funcionalidad al 100% usando el navegador Google Chrome </span> <img src="http://validocus.com/v2/docs/libs/validocus/images/chrome.png">, <a href="https://www.google.es/chrome/index.html" data-translate="download-link">descarga aquí</></div>');
        dontOpen = true;
    } else if(navigator.userAgent.indexOf("Edge") > -1) {
      $('#princicalDiv').addClass('mb-5');
        $('body').append('<div class="navigatorWrong"><span data-translate="wrong-navigator">Disfruta de la funcionalidad al 100% usando el navegador Google Chrome </span> <img src="http://validocus.com/v2/docs/libs/validocus/images/chrome.png">, <a href="https://www.google.es/chrome/index.html" data-translate="download-link">descarga aquí</></div>');
        dontOpen = true;
    } else if((navigator.userAgent.indexOf("Opera") > -1) || (navigator.userAgent.indexOf('OPR') != -1 )) {
      $('#princicalDiv').addClass('mb-5');
        $('body').append('<div class="navigatorWrong"><span data-translate="wrong-navigator">Disfruta de la funcionalidad al 100% usando el navegador Google Chrome </span> <img src="http://validocus.com/v2/docs/libs/validocus/images/chrome.png">, <a href="https://www.google.es/chrome/index.html" data-translate="download-link">descarga aquí</></div>');
        dontOpen = false;
    }
  }

  /**
   * [relocate description]
   * @param  {[type]} url [description]
   * @return {[type]}     [description]
   */
  relocate(url) {
    location.href = url
  }

  /**
   * [console description]
   * @param  {[type]} msj [description]
   * @return {[type]}     [description]
   */
  console(msj) {
    console.log(msj)
  }

  /**
   * [fadein description]
   * @return {[type]} [description]
   */
  fadein() {
    $(this.params.loader).fadeIn('swing');
  }

  /**
   * [fadeout description]
   * @return {[type]} [description]
   */
  fadeout() {
    $(this.params.loader).fadeOut('swing');
  }

  /**
   * [emailvalidation description]
   * @param  {[type]} email [description]
   * @return {[type]}       [description]
   */
  static emailvalidation(email) {
    if(/^(([^<>()[\]\.,;:\s@\"]+(\.[^<>()[\]\.,;:\s@\"]+)*)|(\".+\"))@(([^<>()[\]\.,;:\s@\"]+\.)+[^<>()[\]\.,;:\s@\"]{2,})$/i.test(email)) {
      return true;
    } else {
      return false;  
    } 
  }

  /**
   * [passwordValidation description]
   * @param  {[type]} password  [description]
   * @param  {Number} condition [description]
   * @return {[type]}           [description]
   */
  static passwordValidation(password, condition=0) {
    var ret = {
      success: false,
      message: 'Password inválido',
      conditons: '',
    }
    if(!password || password == '') {
      return ret;
    }
    switch (condition) {
      case 1:
        // Mínimo 8 caracteres al menos 1 alfabeto y 1 número
        if(/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/i.test(password)) {
          ret.success = true;
          ret.message = 'Password válido';
        } else {
          ret.conditons = 'Mínimo 8 caracteres al menos 1 alfabeto y 1 número';
        }
        break;
      case 2:
        // Mínimo de 8 caracteres al menos 1 alfabeto, 1 número y 1 carácter especial
        if(/^(?=.*[A-Za-z])(?=.*\d)(?=.*[$@$!%*#?&])[A-Za-z\d$@$!%*#?&]{8,}$/i.test(password)) {
          ret.success = true;
          ret.message = 'Password válido';
        } else {
          ret.conditons = 'Mínimo de 8 caracteres al menos 1 alfabeto, 1 número y 1 carácter especial';
        }
        break;
      case 3:
        // Mínimo de 8 caracteres al menos 1 alfabeto en mayúsculas, 1 alfabeto en minúsculas y 1 número
        if(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)[a-zA-Z\d]{8,}$/i.test(password)) {
          ret.success = true;
          ret.message = 'Password válido';
        } else {
          ret.conditons = 'Mínimo de 8 caracteres al menos 1 alfabeto en mayúsculas, 1 alfabeto en minúsculas y 1 número';
        }
        break;
      case 4:
        // Mínimo de 8 caracteres al menos 1 alfabeto en mayúsculas, 1 alfabeto en minúsculas, 1 número y 1 carácter especial
        if(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,}$/i.test(password)) {
          ret.success = true;
          ret.message = 'Password válido';
        } else {
          ret.conditons = 'Mínimo de 8 caracteres al menos 1 alfabeto en mayúsculas, 1 alfabeto en minúsculas, 1 número y 1 carácter especial';
        }
        break;
      case 5:
        // Mínimo 8 y máximo 10 caracteres al menos 1 alfabeto en mayúsculas, 1 alfabeto en minúsculas, 1 número y 1 carácter especial
        if(/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[$@$!%*?&])[A-Za-z\d$@$!%*?&]{8,10}$/i.test(password)) {
          ret.success = true;
          ret.message = 'Password válido';
        } else {
          ret.conditons = 'Mínimo 8 y máximo 10 caracteres al menos 1 alfabeto en mayúsculas, 1 alfabeto en minúsculas, 1 número y 1 carácter especial';
        }
        break;
      default:
        ret.message = 'Condicion No válida';
        break;
    }
    return ret;
  }

  /**
   * [today description]
   * @return {[type]} [description]
   */
  get today() {
    var fecha= new Date()
    var horas= fecha.getHours()
    var minutos = fecha.getMinutes()
    var segundos = fecha.getSeconds()
    var mes = fecha.getMonth()+1;
    var dia = fecha.getDate()
    var ano = fecha.getFullYear()
    return ano+'/'+mes+'/'+dia+' '+horas + ":" + minutos + ":" + segundos;
  }

  /**
   * 
   * @return {[type]} [description]
   */
  get todaytime() {
    var date = new Date();
    var hora = date.getHours();
    var minuto = date.getMinutes();
    return hora+':'+minuto;
  }

  /**
   * Input con lista desplegable de autocomplete
   * @param  {[type]} inp [description]
   * @param  {[Array]} arr Array de objetos { id: id del item, item: descripcion del item}
   * @return {[type]}     [description]
   */
  autocomplete(inp, arr, valctrl=false) {
    /*the autocomplete function takes two arguments,
    the text field element and an array of possible autocompleted values:*/
    var currentFocus;
    /*execute a function when someone writes in the text field:*/
    inp.addEventListener("input", function(e) {
        var a, b, i, val = this.value;
        /*close any already open lists of autocompleted values*/
        closeAllLists();
        if (!val) { return false;}
        currentFocus = -1;
        /*create a DIV element that will contain the items (values):*/
        a = document.createElement("DIV");
        a.setAttribute("id", this.id + "autocomplete-list");
        a.setAttribute("class", "autocomplete-items");
        /*append the DIV element as a child of the autocomplete container:*/
        this.parentNode.appendChild(a);
        /*for each item in the array...*/
        for (i = 0; i < arr.length; i++) {
          /*check if the item starts with the same letters as the text field value:*/
          if (arr[i].item.substr(0, val.length).toUpperCase() == val.toUpperCase()) {
            /*create a DIV element for each matching element:*/
            b = document.createElement("DIV");
            /*make the matching letters bold:*/
            b.innerHTML = "<strong>" + arr[i].item.substr(0, val.length) + "</strong>";
            b.innerHTML += arr[i].item.substr(val.length);
            /*insert a input field that will hold the current array item's value:*/
            b.innerHTML += "<input type='hidden' data-id="+arr[i].id+" value='" + arr[i].item + "'>";
            /*execute a function when someone clicks on the item value (DIV element):*/
            b.addEventListener("click", function(e) {
                /*insert the value for the autocomplete text field:*/
                inp.value = this.getElementsByTagName("input")[0].value;
                inp.dataset.id = this.getElementsByTagName("input")[0].dataset.id;
                if(valctrl) {
                  valctrl.value = this.getElementsByTagName("input")[0].dataset.id;
                }
                /*close the list of autocompleted values,
                (or any other open lists of autocompleted values:*/
                closeAllLists();
            });
            a.appendChild(b);
          }
        }
    });
    /*execute a function presses a key on the keyboard:*/
    inp.addEventListener("keydown", function(e) {
        var x = document.getElementById(this.id + "autocomplete-list");
        if (x) x = x.getElementsByTagName("div");
        if (e.keyCode == 40) {
          /*If the arrow DOWN key is pressed,
          increase the currentFocus variable:*/
          currentFocus++;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 38) { //up
          /*If the arrow UP key is pressed,
          decrease the currentFocus variable:*/
          currentFocus--;
          /*and and make the current item more visible:*/
          addActive(x);
        } else if (e.keyCode == 13) {
          /*If the ENTER key is pressed, prevent the form from being submitted,*/
          e.preventDefault();
          if (currentFocus > -1) {
            /*and simulate a click on the "active" item:*/
            if (x) x[currentFocus].click();
          }
        }
    });
    function addActive(x) {
      /*a function to classify an item as "active":*/
      if (!x) return false;
      /*start by removing the "active" class on all items:*/
      removeActive(x);
      if (currentFocus >= x.length) currentFocus = 0;
      if (currentFocus < 0) currentFocus = (x.length - 1);
      /*add class "autocomplete-active":*/
      x[currentFocus].classList.add("autocomplete-active");
    }
    function removeActive(x) {
      /*a function to remove the "active" class from all autocomplete items:*/
      for (var i = 0; i < x.length; i++) {
        x[i].classList.remove("autocomplete-active");
      }
    }
    function closeAllLists(elmnt) {
      /*close all autocomplete lists in the document,
      except the one passed as an argument:*/
      var x = document.getElementsByClassName("autocomplete-items");
      for (var i = 0; i < x.length; i++) {
        if (elmnt != x[i] && elmnt != inp) {
        x[i].parentNode.removeChild(x[i]);
        }
      }
    }
    /*execute a function when someone clicks in the document:*/
    document.addEventListener("click", function (e) {
        closeAllLists(e.target);
    });
    console.log('Autocomplete agregado a: ', (inp.name || inp.id || inp) );
  }

  /**
   * [ajaxRequest description]
   * @param  {[type]}  url        [description]
   * @param  {[type]}  typeMethod [description]
   * @param  {[type]}  success    [description]
   * @param  {[type]}  content    [description]
   * @param  {Boolean} loader     [description]
   * @param  {[type]}  progress   [description]
   * @param  {[type]}  errorCb    [description]
   * @return {[type]}             [description]
   */
  ajaxRequest(url, typeMethod, success, content=null, loader = true, progress=null, errorCb=null) {
    var me = this;
    if(loader) { this.fadein(); }
    if((['POST', 'PUT', 'DELETE'].indexOf(typeMethod)>=0)) {
      content.auth=this.getSession(this.params.sessionToken)
      var data = content
    } else {
      var data={auth:this.getSession(this.params.sessionToken)}
    }
    if(progress){
      this.ajaxRequestFollow(url, typeMethod, success, content, loader, progress, errorCb)
      return;
    }
    $.ajax({
      type: typeMethod,
      contentType: "application/json; charset=utf-8",
      url: me.params.almacen+url,
      data : JSON.stringify(data),
      cache: false,
      beforeSend: function (xhr) {
          /* Authorization header */
          if(me.Token && (['POST', 'PUT', 'DELETE', 'PATCH'].indexOf(typeMethod)>=0)) {
            xhr.setRequestHeader("Authorization", "Bearer " + btoa(me.Token));
          }
          xhr.setRequestHeader("Access-Control-Allow-Origin", "*");
      },
      success: function(data) {
        success(JSON.parse(data));
      },
      complete: function(data) {me.fadeout();},
      error: function(data, err) {
        if((typeof errorCb) == 'function') {
          errorCb({data: data, error: err});
        }
      }
    })
  }

  /**
   * [ajaxRequestFollow description]
   * @param  {[type]} url        [description]
   * @param  {[type]} typeMethod [description]
   * @param  {[type]} success    [description]
   * @param  {[type]} data       [description]
   * @param  {[type]} loader     [description]
   * @param  {[type]} progress   [description]
   * @param  {[type]} errorCb    [description]
   * @return {[type]}            [description]
   */
  ajaxRequestFollow(url, typeMethod, success, data, loader, progress, errorCb=null) {
    var me = this
    data.append('auth', this.Token)
    $.ajax({
      type: typeMethod,
      dataType: "json",
      url: me.params.almacen+url,
      data: data,
      xhr: progress,
      cache: false,
      processData: false,
      contentType: false,
      success: function(data) {success(data);},
      complete: function(data) {me.fadeout();},
      error: function(data, err) {
        if((typeof errorCb) == 'function') {
          errorCb({data: data, error: err});
        }
      }
    })
  }  

  /**
   * [fetchApi description]
   * @param  {[type]}  url        [description]
   * @param  {[type]}  typeMethod [description]
   * @param  {[type]}  dataform   [description]
   * @param  {Boolean} preload    [description]
   * @param  {Boolean} alertar    [description]
   * @return {[type]}             [description]
   */
  fetchApi(url, typeMethod, dataform = null, preload = true, alertar = false) {
    var me = this
    var data = ''
    if(dataform != null) {
      var object = {};
      dataform.forEach(function(value, key){
          object[key] = value;
      });
      var data = JSON.stringify(object);
    }
    return new Promise(function(resolve, reject) {
      if (preload) {
        me.fadein();
      }

      if (self.fetch) {
        // ejecutar peticiÃ³n fetch
          var headers
          if (typeMethod === 'POST') {
            headers = {
              'Authorization': 'Bearer ' + btoa(me.Token),
              'Content-Type': 'application/json'
            }
          } else if (typeMethod === 'PUT') {
            headers = {
              'Authorization': 'Bearer ' + btoa(me.Token),
              'Content-Type': 'application/json'
            }
          } else {
            headers = {
              'Authorization': 'Bearer ' + btoa(me.Token),
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
  }

  /**
   * [ajaxDataTable description]
   * @param  {[type]} url        [description]
   * @param  {[type]} table      [description]
   * @param  {[type]} columns    [description]
   * @param  {[type]} columnDefs [description]
   * @param  {[type]} type       [description]
   * @return {[type]}            [description]
   */
  ajaxDataTable(url, table, columns, columnDefs, type) {
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
  }

  /**
   * [rq_method description]
   * @param  {String}  params   [description]
   * @param  {[type]}  url      [description]
   * @param  {String}  method   [description]
   * @param  {String}  auth     [description]
   * @param  {[type]}  success  [description]
   * @param  {[type]}  formdata [description]
   * @param  {[type]}  failure  [description]
   * @param  {Boolean} loader   [description]
   * @param  {[type]}  progress [description]
   * @return {[type]}           [description]
   */
  rq_method(params="json",url,method="GET",auth="",success,formdata=null,failure=null,loader=true,progress=null) {
    var me = this;
    if(failure==null) {
      failure=rq_error
    }
    var json;
    if((['POST', 'PUT', 'DELETE'].indexOf(typeMethod)>=0)) {
      formdata.append('id_user', getItem('candidato'));
      var object = {};
      formdata.forEach(function(value, key){
          object[key] = value;
      });
      json = JSON.stringify(object);
    }
    if(auth=="") {
      auth={}
    }
    if(auth=="JWT") {
      auth={'Authorization': 'JWT ' + me.Token}
    }
    if(auth=="BAERER")
    {
      auth={'Authorization': 'Bearer ' + btoa(me.Token)}
    }
    if(loader) {
      fadeIn();
    }
    if(params=="file") {
      if(success=="GET") {
        this.bringAlert(mensajes.msjErrorPeticionArchivo)
      } else {
        this.rq_file(url, success, formdata, loader, progress)
      }
    } else {
      this.rq_json(url, method, auth, success, formdata, failure, loader)
    }
  }

  /**
   * [rq_file description]
   * @param  {[type]} url      [description]
   * @param  {[type]} success  [description]
   * @param  {[type]} formdata [description]
   * @param  {[type]} loader   [description]
   * @param  {[type]} progress [description]
   * @param  {[type]} errorCb  [description]
   * @return {[type]}          [description]
   */
  rq_file(url, success, formdata, loader, progress=null, errorCb=null) {
    var me = this
    if(!progress) {
      progress = function() {
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
      error: function(data, err) {
        if((typeof errorCb) == 'function') {
          errorCb({data: data, error: err});
        }
      }
    })
  }

  /**
   * [rq_json description]
   * @param  {[type]} url     [description]
   * @param  {String} method  [description]
   * @param  {String} auth    [description]
   * @param  {[type]} success [description]
   * @param  {[type]} json    [description]
   * @param  {[type]} failure [description]
   * @return {[type]}         [description]
   */
  rq_json(url,method="GET",auth="",success,json=null,failure=null) {
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
  }

  /**
   * [rq_error description]
   * @param  {[type]} data [description]
   * @return {[type]}      [description]
   */
  rq_error(data) {
    console.log(data);
  }

  /**
   * [login description]
   * @param  {[type]} username [description]
   * @param  {[type]} password [description]
   * @param  {[type]} success  [description]
   * @return {[type]}          [description]
   */
  login(username,password,success) {
    this.ajaxRequest(this.params.loginurl, "POST", success, {email:email,password:password});
  }

  /**
   * [language description]
   * @param  {[type]} lang [description]
   * @return {[type]}      [description]
   */
  language(lang) {
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
  }

  /**
   * [translateN description]
   * @return {[type]} [description]
   */
  translateN() {
    var ms = JSON.parse(this.getSession('textos'));
    $.each(ms, function( key, value ) {
      if($('[data-translate="'+key+'"]').is('input')){
        $('[data-translate="'+key+'"]').attr('placeholder', value);
      } else {
        $('[data-translate="'+key+'"]').html(value);
      }
    }); 
    if(ms != null) {
      $('.myProfileC').html(ms['menu-profile']);
      $('.editProfileC').html(ms['menu-editprofile']);
      $('.singOffC').html(ms['menu-log']);
      this.registerspages = ms['registerspages'];
      this.noresult = ms['no-result'];
      this.searchu = ms['search'];
      this.previous = ms['previous'];
      this.next = ms['next'];
    }
  }

  /**
   * [jqc_alert description]
   * @param  {[type]} titulo  [description]
   * @param  {[type]} mensaje [description]
   * @param  {[type]} tema    [description]
   * @param  {[type]} icon    [description]
   * @param  {[type]} type    [description]
   * @param  {[type]} btn     [description]
   * @return {[type]}         [description]
   */
  jqc_alert(titulo,mensaje,tema,icon,type,btn=null) {
    if(this.params.jqconfirm) {
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
    } else {
      console.log("Habilite las alertas por JQuery Confrim {jqconfirm:true} ")
    }
  }

  /**
   * [btptoast description]
   * @param  {[type]} titulo  [description]
   * @param  {[type]} mensaje [description]
   * @return {[type]}         [description]
   */
  btptoast(titulo,mensaje) {
    if(this.params.bootstraptoast) {
      if(titulo=="") {
        titulo=this.params.name
      }
      $('.toast').find('.toast-title').html(titulo)
      $('.toast').find('.toast-body').html(mensaje)
      $('.toast').parent().parent().show()
      $('.toast').toast('show')
      $('.toast').on('hidden.bs.toast', function () {
        $('.toast').parent().parent().hide()
      })
    } else {
      console.log("No hay funcionalidad de Bootstrap")
    }
  }

  /**
   * [puntos description]
   * @param  {[type]} varNum [description]
   * @return {[type]}        [description]
   */
  puntos(varNum) {
    varNum += '';
    var x = varNum.split('.');
    var x1 = x[0];
    var x2 = x.length > 1 ? '.' + x[1] : '';
    var rgx = /(\d+)(\d{3})/;
    while (rgx.test(x1)) {
      x1 = x1.replace(rgx, '$1' + '.' + '$2');
    }
    return x1 + x2;
  }

  /**
   * [bigalert description]
   * @param  {[type]} msj   [description]
   * @param  {[type]} title [description]
   * @return {[type]}       [description]
   */
  bigalert(msj,title=null) {
    if(this.params.alertify==true) {
      alertify.alert(this.params.name,msj)
    }else{
      this.console("Habilite la librería Alertify.js {alertify:true}")
    }
  }

  /**
   * [toast description]
   * @param  {[type]} msj [description]
   * @return {[type]}     [description]
   */
  toast(msj) {
    if(this.params.alertify==true) {
      alertify.success(msj)
    } else {
      this.console("Habilite la librería Alertify.js {alertify:true}")
    }
  }

  /**
   * [getId description]
   * @param  {[type]} splitt [description]
   * @return {[type]}        [description]
   */
  getId(splitt) {
    var name = location.href.split(splitt)[1];
    if(name == ''){
      return false;
    } else {
      return name;
    }
  }

  /**
   * [initPhoneMask description]
   * @return {[type]} [description]
   */
  initPhoneMask() {
    if(this.params.telMask==false) {
      this.console("Habiliete la librería IntlTelInput {telMask: true}")
      return
    }
    if(this.params.intTelInput==null) {
      this.console("No se ha asignado el elemento HTML para inicializar el Plugin intlTelInput {intTelInpu: '#element'}")
      return
    }
    $(this.params.intTelInput).intlTelInput({
      customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
          return "e.g. " + selectedCountryPlaceholder;
      },
      initialCountry: 'CO',
      //separateDialCode: true, 
      utilsScript: this.params.telMaskUtils || '',
    });
  }

  /**
   * [initPhoneMaskEl description]
   * @param  {[type]} el [description]
   * @return {[type]}    [description]
   */
  initPhoneMaskEl(el) {
    if(this.params.telMask==false) {
      this.console("Habiliete la librería IntlTelInput {telMask: true}")
      return;
    }
    if(el==null ) {
      this.console("No se ha asignado el elemento HTML para inicializar el Plugin intlTelInput {intTelInpu: '#element'}")
      return;
    }
    return $(el).intlTelInput({
      customPlaceholder: function(selectedCountryPlaceholder, selectedCountryData) {
          return "e.g. " + selectedCountryPlaceholder;
      },
      initialCountry: 'CO',
      //separateDialCode: true, 
      utilsScript: this.params.telMaskUtils || '',
    });
  }

  /**
   * [managefileinput description]
   * @param  {[type]}  input    [description]
   * @param  {[type]}  div      [description]
   * @param  {Boolean} multiple [description]
   * @return {[type]}           [description]
   */
  managefileinput(input,div,multiple=false) {
    if (input.files && input.files[0]) {
      var total_file = input.files.length;
      var icon = '';
      for(var i=0;i<total_file;i++) {
        var tipo = event.target.files[i].type;
        var quetipo = tipo.split('/');
        if(quetipo[1] == 'pdf') { 
          icon += '<div class="element-open"><i class="fas fa-file-pdf"></i><br><i class="fa fa-search"></i></div>'; 
        } else if(quetipo[1] == 'mp4') { 
          icon += '<div class="element-open"><i class="fas fa-file-video"></i><br><i class="fa fa-search"></i></div>';
        } else if(quetipo[1] == 'jpg' || quetipo[1] == 'jpeg' || quetipo[1] == 'png') { 
          icon += '<div class="element-open"><img src="'+URL.createObjectURL(event.target.files[i])+'"><i class="fa fa-search"></i></div>'; 
        } else {
          this.toast("Carga un archivo permitido!")
          input.value = '';
          return;
        }
      }
      if(!multiple) {
        $(div).addClass('w-value');
        $(div).find('img').remove();
        $(div).find('.element-open').remove();
        $(div).find('.fa').remove();
      }
      $(div).append(icon);
    }
  }

  /**
   * [shortdatatable description]
   * @param  {[type]}  table    [description]
   * @param  {[type]}  url      [description]
   * @param  {[type]}  target   [description]
   * @param  {Boolean} download [description]
   * @return {[type]}           [description]
   */
  shortdatatable(table, url, target, download=true) { 
    //columnas, columnDefs, token, datos = 0)
    var buttons = []
    if(download) {
      buttons= [
          {extend:'excelHtml5', className:'btn btn-sm btn-primary', text: '<i class="fas fa-cloud-download-alt"></i> Descargar en Excel'},
      ]
    }
    $(table).DataTable({
      'pageLength' : 10,
      "ajax": {
        "url":  url,
        "type": "GET"
      },
      "createdRow": function( row, data, dataIndex ) {
        $(row).attr('id', 'client_'+data[0] );
      },
      "autoWidth": false,
      "order": [],
      "columnDefs": [
        { "orderable": false, "targets": [target] }
      ],
      "dom": 'Bfrtip',
      "buttons": buttons,
      'language': {
        //"url": "//cdn.datatables.net/plug-ins/9dcbecd42ad/i18n/Spanish.json"
      }
    });
  }

  /**
   * [addTableRow description]
   * @param {[type]} id    [description]
   * @param {[type]} datos [description]
   * @param {[type]} table [description]
   */
  addTableRow(id, datos, table) {
    var rowNode = $(table)
      .row.add(datos)
      .draw()
      .node();
    $( rowNode )
      .css( 'color', 'black' )
      .css( 'fontWeight', 'bold')
      .attr('id', 'item_'+id)
  }

  /**
   * [reloadTable description]
   * @param  {[type]} table [description]
   * @return {[type]}       [description]
   */
  reloadTable(table) {
    $(table).DataTable().ajax.reload()
  }

  /**
   * [alterTableRow description]
   * @param  {[type]} row   [description]
   * @param  {[type]} datos [description]
   * @param  {[type]} table [description]
   * @return {[type]}       [description]
   */
  alterTableRow(row, datos, table) {
    $(table).DataTable().row(document.getElementById(row)).data(datos).draw(false)
  }

  /**
   * [deleteTableRow description]
   * @param  {[type]} row   [description]
   * @param  {[type]} table [description]
   * @return {[type]}       [description]
   */
  deleteTableRow(row,table) {
    $(table).DataTable().row(document.getElementById(row)).remove().draw(false)
  }
}