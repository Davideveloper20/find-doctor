<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
  <!-- General JS Scripts -->
  <!--script src="<?php echo base_url(); ?>assets/modules/jquery.min.js"></script-->
  <script src="<?php echo base_url(); ?>assets/libs/jquery/jquery.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/libs/bootstrap/js/bootstrap.bundle.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/modules/nicescroll/jquery.nicescroll.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/stisla.js"></script>

  <script src="<?php echo base_url(); ?>assets/libs/moment/moment.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/libs/moment/es.js"></script>
  <script src="<?php echo base_url(); ?>assets/libs/moment/moment-with-locales.min.js"></script>
  
  <script src="<?php echo base_url(); ?>assets/libs/alertify/alertify.js"></script>
  <script src="<?php echo base_url(); ?>assets/libs/jquery-ui-1.9.2.custom.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/libs/jqc/jquery-confirm.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/ss/solstar.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/ss/conf.js"></script>
  <script> var ext = '<?php echo $this->uri->segment(1); ?>';</script>
<?php
if ($this->uri->segment(1) == "Auth") { ?>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/libs/jquery.backstretch.min.js"></script>
  <script>
    $.backstretch("<?php echo base_url(); ?>assets/images/bg/bg<?php echo (int)rand(1,5); ?>.jpg", {
      speed: 500
    });
  </script>
  <?php
    if (in_array($this->uri->segment(2), ["Login",'Iniciar-Sesion', 'SignIn'])) { ?> 
      
  <?php
    } 
    if (in_array($this->uri->segment(2), ["SignUp",'Registro','Register'])) { ?> 
      <script src="<?php echo base_url(); ?>assets/modules/jquery-pwstrength/i18next.js"></script>
  <?php
    }
    ?>
  <script src="<?php echo base_url(); ?>assets/modules/jquery-pwstrength/pwstrength-bootstrap.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/modules/jquery-selectric/jquery.selectric.min.js"></script>

  <script src="<?php echo base_url(); ?>assets/js/page/auth.js"></script>
<?php
} 
if ($this->uri->segment(1) != "Auth") { ?>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/libs/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/libs/bootstrap-toggle/js/bootstrap-toggle.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/libs/IntlTelInput/js/intlTelInput-jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/libs/IntlTelInput/js/utils.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/libs/bootstrap-inputmask/jquery.inputmask.min.js"></script>
<?php
}
if (in_array($this->uri->segment(1), ["Consultorios",'Specialities','EPS-Seguros'])) { ?>
  <script type="text/javascript" src="<?php echo base_url(); ?>assets/libs/dt4/datatables.js"></script>
  <script src="<?php echo base_url(); ?>assets/libs/form-validation/jquery.validate.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/libs/form-validation/additional-methods.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/libs/form-validation/localization/messages_es.min.js"></script>
  <script type="text/javascript" charset="utf-8">
    $.extend( true, $.fn.dataTable.defaults, {
      //"searching": false,
      //"ordering": false
      "language": {
       "url": "<?php echo base_url(); ?>assets/libs/dt4/langs/Spanish.json"
      },
    });
  </script>
<?php
}
if (in_array($this->uri->segment(1), ["Consultorios"])) { ?>
  <script src="<?php echo base_url(); ?>assets/libs/form-validation/jquery.validate.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/libs/form-validation/additional-methods.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/libs/form-validation/localization/messages_es.min.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/page/consultorios.js"></script>
<?php
}
if (in_array($this->uri->segment(1), ["Specialities"])) { ?>
  <script src="<?php echo base_url(); ?>assets/js/page/confspecialities.js"></script>
<?php
}
if (in_array($this->uri->segment(1), ['EPS-Seguros'])) { ?> 
    <script src="<?php echo base_url(); ?>assets/js/page/confepsseguros.js"></script>
  ?>
<?php
}
if ($this->uri->segment(1) == "Doctor") { ?>
  <?php
  if (in_array($this->uri->segment(2), ["","Profile",'Perfil'])) { ?> 
    <script src="<?php echo base_url(); ?>assets/libs/IntlTelInput/js/intlTelInput-jquery.min.js"></script>
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&amp;key=AIzaSyDg2Z6QWeOzf4FkS5ZYQx2Isw6efT90dHI&amp;libraries=geometry"></script>
    <script src="<?php echo base_url(); ?>assets/libs/IntlTelInput/js/utils.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/page/profile.js"></script>
  <?php
  }
  if (in_array($this->uri->segment(2), ['Goals','Logros'])) { ?> 
    <script src="<?php echo base_url(); ?>assets/libs/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/jquery.validate.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/additional-methods.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/localization/messages_es.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/page/goals.js"></script>
  ?>
  <?php
  }
  if (in_array($this->uri->segment(2), ['Formation','Formacion'])) { ?> 
    <script src="<?php echo base_url(); ?>assets/libs/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/jquery.validate.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/additional-methods.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/localization/messages_es.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/page/formation.js"></script>
  ?>
  <?php
  }
  if (in_array($this->uri->segment(2), ['Settings','Configuraciones'])) { ?> 
    <script src="<?php echo base_url(); ?>assets/libs/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/page/settings.js"></script>
  ?>
  <?php
  }
  if (in_array($this->uri->segment(2), ['Specialities','Especialidades'])) { ?> 
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/jquery.validate.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/additional-methods.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/localization/messages_es.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/page/specialities.js"></script>
  ?>
  <?php
  }
  if (in_array($this->uri->segment(2), ['Services','Servicios'])) { ?> 
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/jquery.validate.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/additional-methods.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/localization/messages_es.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/page/services.js"></script>
  ?>
  <?php
  }
  if (in_array($this->uri->segment(2), ['Consulting-Room','Consultorios'])) { ?> 
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/jquery.validate.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/additional-methods.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/localization/messages_es.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/page/consultingroom.js"></script>
  ?>
  <?php
  }
  if (in_array($this->uri->segment(2), ["Appoinments",'Citas'])) { ?>
    <!--script src="<?php echo base_url(); ?>assets/modules/fullcalendar/fullcalendar.js"></script>
    <script src="<?php echo base_url(); ?>assets/modules/fullcalendar/gcal.js"></script>
    <script src="<?php echo base_url(); ?>assets/modules/fullcalendar/locale-all.js"></script-->
    <script >
      $("#myCalendardhxtml, #scheduler_here").css({
        minHeight: $(window).outerHeight() - 125
      });
    </script>
    <script src="<?php echo base_url(); ?>assets/libs/schuelder/codebase/dhtmlxscheduler.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/schuelder/codebase/ext/dhtmlxscheduler_collision.js?v=5.3.4"></script>
    <script src="<?php echo base_url(); ?>assets/libs/schuelder/codebase/ext/dhtmlxscheduler_serialize.js?v=5.3.4"></script>
    <script src="<?php echo base_url(); ?>assets/libs/schuelder/codebase/ext/dhtmlxscheduler_expand.js?v=5.3.4"></script>
    <script src="<?php echo base_url(); ?>assets/libs/schuelder/codebase/ext/dhtmlxscheduler_all_timed.js?v=5.3.4"></script>
    <script src="<?php echo base_url(); ?>assets/libs/schuelder/codebase/ext/dhtmlxscheduler_readonly.js?v=5.3.4"></script>
    <script src="<?php echo base_url(); ?>assets/libs/schuelder/codebase/locale/locale_es.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/page/appoiments.js"></script>
  <?php
  }
  if (in_array($this->uri->segment(2), ['EPS-Seguros'])) { ?> 
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/jquery.validate.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/additional-methods.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/libs/form-validation/localization/messages_es.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/js/page/epsseguros.js"></script>
  <?php
  }
  if (in_array($this->uri->segment(2), ['Chat-Room'])) { ?> 

    <script src="https://unpkg.com/rxjs@6.4.0/bundles/rxjs.umd.min.js"></script>
    <!-- Firebase -->
    <script src="https://www.gstatic.com/firebasejs/7.24.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.24.0/firebase-analytics.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.24.0/firebase-app.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.24.0/firebase-auth.js"></script>
    <script src="https://www.gstatic.com/firebasejs/7.24.0/firebase-firestore.js"></script>
    <!-- RXJS -->
    <script src="https://unpkg.com/rxjs@6.4.0/bundles/rxjs.umd.min.js"></script>
    <script>
      var firebaseConfig = {
        apiKey: "AIzaSyBwhHfQFim8GLxFomqZaHE-9oK6iXRXrLU",
        authDomain: "finddoctor-7c44f.firebaseapp.com",
        databaseURL: "https://finddoctor-7c44f.firebaseio.com",
        projectId: "finddoctor-7c44f",
        storageBucket: "finddoctor-7c44f.appspot.com",
        messagingSenderId: "215645564149",
        appId: "1:215645564149:web:69571acb1fa726cce5e9bc",
        measurementId: "G-DTK6YL82ZG"
      };
      // Initialize Firebase
      firebase.initializeApp(firebaseConfig);
      var db = firebase.firestore();
      firebase.firestore().enablePersistence().catch(function(err) {
          if (err.code == 'failed-precondition') {
              console.log(err.code);
          } else if (err.code == 'unimplemented') {
              console.log(err.code);
          }
      });
    </script>
    <script src="<?php echo base_url(); ?>assets/js/page/chatroom.js?v=<?php echo rand(0, 99); ?>"></script>
  <?php
  }
  if (in_array($this->uri->segment(2), ['Patients-App','Patients-Own','Patients', 'Pacientes', 'Comments', 'transactions'])) { ?> 
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/libs/dt4/datatables.js"></script>
    <script type="text/javascript" charset="utf-8">
      $.extend( true, $.fn.dataTable.defaults, {
        //"searching": false,
        //"ordering": false
        "language": {
         "url": "<?php echo base_url(); ?>assets/libs/dt4/langs/Spanish.json"
        },
      });
    </script>
  <?php
  }
  if (in_array($this->uri->segment(2), ['transactions'])) { ?> 
    <script src="<?php echo base_url(); ?>assets/js/page/transactions.js"></script>
  <?php
  }
  if (in_array($this->uri->segment(2), ['Patients-Own', 'Pacientes'])) { ?> 
    <script src="<?php echo base_url(); ?>assets/js/page/patientes.js"></script>
  <?php
  }
  if (in_array($this->uri->segment(2), ['Patients-App'])) { ?> 
    <script src="<?php echo base_url(); ?>assets/js/page/patientes2.js"></script>
  <?php
  }
  if (in_array($this->uri->segment(2), ["Comments"])) { ?> 
    <script src="<?php echo base_url(); ?>assets/js/page/doctorValorations.js"></script>
  <?php
  }
  ?>
<?php
} 
if ($this->uri->segment(1) == "Admin") { ?>
  <?php
    if (in_array($this->uri->segment(2), ["Doctors","Patients",'Req-Payments', 'Patients-Users', 'Patients-NoUsers'])) { ?> 
      <script type="text/javascript" src="<?php echo base_url(); ?>assets/libs/dt4/datatables.js"></script>
      <script type="text/javascript" charset="utf-8">
        $.extend( true, $.fn.dataTable.defaults, {
          //"searching": false,
          //"ordering": false
          "language": {
           "url": "<?php echo base_url(); ?>assets/libs/dt4/langs/Spanish.json"
          },
        });
      </script>
  <?php
    }
    if (in_array($this->uri->segment(2), ["Doctors"])) { ?> 
      <script src="<?php echo base_url(); ?>assets/libs/form-validation/jquery.validate.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/libs/form-validation/additional-methods.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/libs/form-validation/localization/messages_es.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/libs/IntlTelInput/js/intlTelInput-jquery.min.js"></script>
      <!--script type="text/javascript" src="https://maps.googleapis.com/maps/api/js?v=3&amp;key=AIzaSyDg2Z6QWeOzf4FkS5ZYQx2Isw6efT90dHI&amp;libraries=geometry"></script-->
      <script src="<?php echo base_url(); ?>assets/libs/IntlTelInput/js/utils.js"></script>
      <script src="<?php echo base_url(); ?>assets/libs/bootstrap-datetimepicker/js/bootstrap-datetimepicker.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/js/page/adminDoctors.js"></script>
    <?php
    }
    if (in_array($this->uri->segment(2), ["Req-Payments"])) { ?> 
      <script src="<?php echo base_url(); ?>assets/libs/numeral/numeral.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/libs/numeral/locales.min.js"></script>
      <script src="<?php echo base_url(); ?>assets/js/page/adminReqDoctors.js"></script>
    <?php
    }
    if (in_array($this->uri->segment(2), ["Patients-Users"])) { ?> 
      <script src="<?php echo base_url(); ?>assets/js/page/admregappatients.js"></script>
    <?php
    }
    if (in_array($this->uri->segment(2), ["Patients-NoUsers"])) { ?> 
      <script src="<?php echo base_url(); ?>assets/js/page/admnoregappatients.js"></script>
    <?php
    } 
    ?>
<?php
} 
?>
  <!-- Template JS File -->
  <script src="<?php echo base_url(); ?>assets/js/scripts.js"></script>
  <script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
</body>
</html>