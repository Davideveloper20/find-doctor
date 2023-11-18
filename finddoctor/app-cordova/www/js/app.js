// Dom7
var $$ = Dom7;

// Theme
var theme = 'md';
/*if (document.location.search.indexOf('theme=') >= 0) {
  theme = document.location.search.split('theme=')[1].split('&')[0];
}*/

// Init App
var app = new Framework7({
  id: 'io.framework7.finddoctor',
  name: 'Find Doctor',
  root: '#app',
  theme: theme,
  routes: [
    {
      path: '/menu/',
      componentUrl: './pages/menu.html'
    }
  ],
  data: info,
  // methods: {},
  methods: {
    loadAll: function(callback) {
      if(solstar.getToken()){
        solstar.ajaxRequest('App/getSpecialities', 'POST', function(r) {
          app.data.specialists = r.data;
  
          solstar.ajaxRequest('App/getCities', 'POST', function(r2) {
            app.data.defaultCity = r2.data.defaultCity;
            app.data.cities = r2.data.cities;
            callback()
          }, {});
        }, {});
      } else {
        app.data.specialists = [];
        app.data.cities = [];
        callback()
      }
    }
  },
  on:{
    pageInit: function()
    {
      if(homeView.router.currentRoute.url == "/")
      {
        homeView.router.clearHistory()
        homeView.router.history = ['/']; // url of the home pagec
      }
      $('.price').each(function(){
        $(this).html(solstar.puntos("$ "+$(this).text()))
      })
    },
    pageBeforeIn: function()
    {
      console.log('custom-tabbar');
      $('.custom-tabbar').hide();

      if(homeView.router.currentRoute.url == "/log-in/")
      {
        $('.custom-tabbar').hide()
      }else{
        $('.custom-tabbar').show()
      }
      if(homeView.router.currentRoute.url == "/log-in/")
      {
        $('.tab-link[href="#tab-2"]').addClass('tab-link-active')
      }
      let pages = ["undefined","/home/", "/"],
        href = ["#", "#view-home", "#view-home"]
        index = pages.indexOf(homeView.router.currentRoute.url)

      if(typeof(homeView.router.currentRoute.url) != "undefinded" &&  index >= 0)
      {
        if(index == 0)
        {
          index = pages.indexOf(solstar.getSession('home'))
          app.tab.show(href[index])
        }else{
          app.tab.show(href[index])
        }
        $('.tab-link[href="'+href[index]+'"]').addClass('tab-link-active')
        app.toolbar.show('.custom-tabbar')
      }else{
        $('.tab-link-active').removeClass('tab-link-active')
        app.toolbar.hide('.custom-tabbar')
      }
    }
  }
});
var homeView = app.views.create('#view-home', {
  url: '/',
  routes: routes
});

var menuView = app.views.create('.panel', {
  url: '/menu/'
});


function validarinternet(success, error) 
{
  if (navigator.onLine) {
    success()
  } else {
    error()
  }
}
function bringToast(showMsj,icon=true)
{
  if(icon){ icon = "done"; }else{ icon = "cancel"; }
  var toastCenter = app.toast.create({
    text: showMsj,
    position: 'center',
    closeTimeout: 2000,
    icon: '<i class="material-icons">'+icon+'</i>',
    closeButton: false,
  })

  toastCenter.open();
}
function abrirLink(link)
{
  if(window.cordova)
  {
    var op = device.platform
      // Single file selector
    if(op == "Android" || op == "android")
    {
      window.open(link, '_blank', 'location=yes');
    }else{
      cordova.InAppBrowser.open(encodeURI(link), '_system', 'location=yes');
    }
  }else{
    window.open(link, '_blank');
  }
}
function compartirApp(mensaje, titulo, url)
{
  if(window.cordova)
  {
    // this is the complete list of currently supported params you can pass to the plugin (all optional)
    var options = {
      message: mensaje, // not supported on some apps (Facebook, Instagram)
      subject: titulo, // fi. for email
      files: [], // an array of filenames either locally or remotely
      url: url,
      chooserTitle: 'FindDoctor' // Android only, you can override the default share sheet title
    }
     
    var onSuccess = function(result) {
      console.log("Share completed? " + result.completed); // On Android apps mostly return false even while it's true
      console.log("Shared to app: " + result.app); // On Android result.app is currently empty. On iOS it's empty when sharing is cancelled (result.completed=false)
    }
     
    var onError = function(msg) {
      console.log("Sharing failed with message: " + msg);
    }
     
    window.plugins.socialsharing.shareWithOptions(options, onSuccess, onError);
  }else{
    linkWhatsapp = "https://api.whatsapp.com/send?text="+mensaje ;
    linkTwitter = "https://twitter.com/intent/tweet?text=" + encodeURIComponent(mensaje);   
    linkEmail = "mailto:?subject="+titulo+"&body="+mensaje;
    linkFb = 'https://www.facebook.com/sharer/sharer.php?u=' + mensaje, 'facebook-popup', 'height=350,width=600';
    
    app.dialog.create({
      title: 'Compartir', 
      content: '<div class="block">'+
        '<div class="row">'+
          '<div class="col-50">'+
            '<a class="external button button-wonwit share" target="_blank" href="'+encodeURI(linkWhatsapp)+'">'+
              '<i class="fab fa-whatsapp" aria-hidden="true"></i>'+
            '</a>'+
          '</div>'+
          '<div class="col-50">'+
            '<a class="external button button-wonwit share" target="_blank" href="'+linkFb+'">'+
              '<i class="fab fa-facebook-square" aria-hidden="true"></i>'+
            '</a>'+
          '</div>'+
          '<div class="col-50">'+
            '<a class="external button button-wonwit share" href="'+linkTwitter+'" target="_blank">'+
              '<i class="fab fa-twitter" aria-hidden="true"></i>'+
            '</a>'+
          '</div>'+
          '<div class="col-50">'+
            '<a class="external button button-wonwit share" target="_blank" href="'+linkEmail+'">'+
              '<i class="fa fa-envelope" aria-hidden="true"></i>'+
            '</a>'+
          '</div>'+
        '</div>'+
      '</div>',
      buttons: [
        {
          text: 'Volver',
          cssClass: 'button button-wonwit secondary',
        }
      ]
    }).open()
  }
}