var reload = true
routes = [
  {
    path: '/',
    async: function(_routeTo, _routeFrom, _resolve, reject)
    {
      app.methods.loadAll(()=>{
        reject()
        app.router.navigate('/home/')
      })
    }
  },
  {
    path: '/home/',
    async: function(_routeTo, _routeFrom, resolve, reject) {
      if(!solstar.getToken()){
        resolve({
          componentUrl: './pages/login/login.html',
        }, {
          context: {}
        })
      }else{
        solstar.ajaxRequest('Auth/getUser', 'POST', function(r) {
          if(r.success) {
            app.data.user = r.data;

            resolve({
              componentUrl: './pages/home.html',
            }, {
              context: {}
            })
          } else {
            app.dialog.alert(r.message);
            localStorage.clear()
            solstar.unsetSession();
            location.reload()
          }
        }, {});

        resolve({
          componentUrl: './pages/home.html',
        }, {
          context: {}
        })
      }
    },
    reloadAll: false,
    name: "index"
  },
  {
    path: '/search',
    async: function(_routeTo, _routeFrom, resolve, reject)
    {
      app.data.searching = true;
      app.data.sdlim = 5; 
      app.data.sdoff = 0;
      app.data.sessionRun = 0;

      solstar.ajaxRequest('App/getDoctors', 'POST', function(r) {
        if(r.success) {
          if(r.data.length > 0) {

            resolve({
              componentUrl: './pages/search-result.html'
            }, {
              context: {
                doctors: r.data
              }
            })
          } else {
            reject()
            app.dialog.alert("No se han encontrado resultados en el area y/o especialidad que seleccionaste")
          }
        } else {
          app.dialog.alert(r.message)
        }
      },
       { search: app.data.search });

    }
  },
  {
    path: '/doctor-detail/:iddoctor',
    async: function(routeTo, routeFrom, resolve, reject)
    {
      var json = {iddoctor: routeTo.params.iddoctor}
      solstar.ajaxRequest('getDoctorDetail', 'POST', function(r) {
        if(r.success) {
          resolve({
            componentUrl: './pages/doctor-detail.html'
          }, {
            context: {
              data: r.data
            }
          })
        }
      }, json);
    }
  },
  {
    path: '/doctor-chat/:iddoctor',
    async: function (routeTo, routeFrom, resolve, reject) 
    {
      // solstar.ajaxRequest('chatDoctorPatient/'+routeTo.params.iddoctor, 'GET', function(data) {
      //   if(data.success) {
      //     resolve({
      //       componentUrl: './assets/pages/doctor-chat.html',
      //     },
      //     {
      //       context: {
      //         messages: data.data
      //       },
      //     });
      //   } else {

      //   }
      // });

      resolve(
        {
          componentUrl: './pages/doctor-chat.html',
        },
        {
          context: {
            iddoctor: routeTo.params.iddoctor,
          },
        }
      );
    }
  },
  
  {
    path: '/appointment/',
    async: function(_routeTo, _routeFrom, resolve, reject)
    {
      app.data.searching = true;
      app.data.sdlim = 5; 
      app.data.sdoff = 0;
      app.data.sessionRun = 0;

      solstar.ajaxRequest('Doctor/saveAppointmentsApp', 'POST', function(r) {
        if(r.success) {
          app.dialog.alert("Se ha realizado el agendamiento correctamente")

          if(r.data) {

            resolve({
              componentUrl: './pages/appointment-panel.html'
            }, {
              context: {
                doctors: r.data
              }
            })
          } else {
            reject()
            app.dialog.alert("No hay cita disponible en ese horario")
          }
        } else {
          app.dialog.alert(r.message)
        }
      },
       { appointment: app.data.appoint });

    }
  },

  {
    path: '/doctor-detail1/',
    componentUrl: './pages/doctor-detail1.html',
    reloadAll: false,
    name: "Doctor detail"
  },

  {
    path: '/profile1/',
    componentUrl: './pages/profile1.html',
    reloadAll: false,
    name: "Profile detail"
  },


  {
    path: '/log-in/',
    componentUrl: './pages/login/login.html',
    reloadAll: false,
    name: "Menú"
  },



/*
  {
    path: '/doctor-book/:iddoctor',
    componentUrl: './pages/doctor-book.html',
    reloadAll: false,
    name: "Book"
  },

  */


  {
    path: '/doctor-book/:iddoctor',
    async: function (routeTo, routeFrom, resolve, reject) 
    {    

      resolve(
        {
          componentUrl: './pages/doctor-book.html',
        },
        {
          context: {
            iddoctor: routeTo.params.iddoctor,
          },
        }
      );
    }
  },













  {
    path: '/forgot/',
    componentUrl: './pages/login/forgot-password.html',
    reloadAll: false,
    name: "Menú"
  },
  {
    path: '/search/',
    componentUrl: './pages/home.html',
    reloadAll: false,
    name: "Menú"
  },
  {
    path: '/sign-up/',
    componentUrl: './pages/login/sign-up.html',
    reloadAll: false,
    name: ""
  },
  // Default route (404 page). MUST BE THE LAST
  {
    path: '(.*)',
    url: './pages/404.html',
    reloadAll: false,
    name: "404"
  },
];
