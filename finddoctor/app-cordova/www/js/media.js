var gallery = {
  file: null,
  id: 0,
  serverPath: 'usuarios',
  galleryList: '.profile-gallery-area',
  preview: false,
  replace: false,
  send: true,
  onSuccess: null,
  setOptions: function(srcType) 
  {
    var options = {
        // Some common settings are 20, 50, and 100
        quality: 50,
        destinationType: navigator.camera.DestinationType.FILE_URI,
        // In this app, dynamically set the picture source, Camera or photo gallery
        sourceType: srcType,
        encodingType: navigator.camera.EncodingType.JPEG,
        mediaType: navigator.camera.MediaType.PICTURE,
        allowEdit: true,
        correctOrientation: true  //Corrects Android orientation quirks
    }
    return options;
  },
  access: function(media)
  {
    app.methods.permisos()
    app.dialog.create({
      title: "Seleccionar foto",
      text: '<div class="block no-margin">'+
        '<div class="row">'+
          '<div class="col-50 text-align-center text-color-light bg-alternative round-100" onclick="gallery.picker()">'+
            '<i class="f7-icons text-color-light margin-top">images_fill</i>'+
            '<p class="text-align-center text-color-light no-margin-top">Galería</p>'+
          '</div>'+
          '<div class="col-50 text-align-center text-color-light bg-alternative round-100" onclick="gallery.capture()">'+
            '<i class="f7-icons text-color-light margin-top">camera_fill</i>'+
            '<p class="text-align-center text-color-light no-margin-top">Capturar</p>'+
          '</div>'+
        '</div>',
      buttons: [
        {
          text: "Salir",
          cssClass:"button button-app secondary"
        }
      ],
      cssClass: "dialog-profile"
    }).open()
  },
  picker: function()
  {
    app.dialog.close()
    var self = this
    var srcType = navigator.camera.PictureSourceType.SAVEDPHOTOALBUM;
    var options = self.setOptions(srcType);

    navigator.camera.getPicture(function cameraSuccess(imageUri) {
      plugins.crop(function success (pathcrop) {
        self.upload(pathcrop)
      }, function fail () {
        console.log("Error")
      }, imageUri, { quality: 120})
    }, function cameraError(error) {
      //app.dialog.alert("Unable to obtain picture: " + error);
    }, options);
  },
  capture: function()
  {
    var self = this
    app.dialog.close()
    var options = {
      limit: 1
    };
    navigator.device.capture.captureImage(onSuccess, onError, options);

    function onSuccess(mediaFiles) {
      var i, path, len;
      len = mediaFiles.length
      for (i = 0; i < len; i++) {
        path = mediaFiles[i]
      }
      plugins.crop(function captured (pathcrop) {

        self.upload(pathcrop)

      }, function fail (error) {
        console.log(error)
      }, path.fullPath, { quality: 120})
    }

    function onError(error) {
      console.log('Error code: ' + error.code, null, 'Capture Error');
    }
  },
  upload: function(path)
  {
    var self = this
    var images = [{path:path}]
    var defs = [];
        
    var pb = 'progressbar_'+Math.floor((Math.random() * 100) + 1);

    if(self.send)
    {
      var fd = new FormData();
      
      images.forEach(function(i, val) {
        var def = $.Deferred();
        window.resolveLocalFileSystemURL(i.path, function(fileEntry) {
          fileEntry.file(function(file) {
            var reader = new FileReader();
            reader.onloadend = function(e) {
              var imgBlob = new Blob([this.result], { type:file.type});
              fd.append('X-File-Name', imgBlob);

              def.resolve();
            };
            reader.readAsArrayBuffer(file);
          }, function(e) {
            console.log('error getting file', e);
          });     
        }, function(e) {
          console.log('Error resolving fs url', e);
        });
        defs.push(def.promise());
      });
      $.when.apply($, defs).then(function() {
        app.preloader.show()
        
        fd.append('location', self.serverPath);
        fd.append('fromApp', 1)

        solstar.rq_file('library/add_image/', function(data){
          app.preloader.hide()
          if(self.onSuccess)
          {
            if(self.preview) 
            {
              self.onSuccess(data, pb)
            }else{
              self.onSuccess(data)
            }
          }
          console.log(data)
        }, fd, true,  function() {
          var xhr = $.ajaxSettings.xhr();

          if(self.preview) self.progress(pb, xhr)

          return xhr;
        });
      });
    }else{
      self.printEl(pb)
      self.replacePreview(pb, {img: path, id: null})
    }
    
  },
  progress: function(pb, ft)
  {
    this.printEl(pb)
    ft.upload.onprogress = function(e) {
      var perc = Math.floor(e.loaded / e.total *100)
      console.log(perc)
      app.progressbar.set('.'+pb, perc)
    }
  },
  printEl: function(pb)
  {
    if(this.replace)
    {
      this.replace = false
      $(this.galleryList).html('<div class="img-prof-gallery position-relative" id="'+pb+'"><div class="floated-loader"><p><span data-progress="10" class="progressbar '+pb+'" id="demo-inline-progressbar"></span></p></div><img src="images/wonwit/loader.png"></div>')
    }else{
      $(this.galleryList).append('<div class="img-prof-gallery position-relative" id="'+pb+'"><div class="floated-loader"><p><span data-progress="10" class="progressbar '+pb+'" id="demo-inline-progressbar"></span></p></div><img src="images/wonwit/loader.png"></div>')
    }
  },
  replacePreview: function(pb,data)
  {
    $(this.galleryList).find('#'+pb).find('img').attr('src', data.img)
    $(this.galleryList).find('#'+pb).find('.floated-loader').remove()
    $(this.galleryList).find('#'+pb).find('button').remove()
    $(this.galleryList).find('#'+pb).append('<button class="button" onclick="deleteProfilePicture(this, '+data.id+')"><i class="material-icons">delete</i></button>')
    this.preview = false
  }
}
  function saveprofile_picture(idlibraries)
  {
    solstar.ajaxRequest("app/account/update", "POST", function(data){
    }, {profileimage: idlibraries})
  }
  function save_image(idlibraries)
  {
    let idcata = homeView.router.currentRoute.params.idcata
    solstar.ajaxRequest("app/grupo_cata/media/"+idcata, "POST", function(data){
      
    }, {el: idlibraries})
  }