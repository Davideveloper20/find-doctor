"use strict";
	
$(function(){
    if($('#register-form').length > 0) {
      solstar.autocomplete($('#idcity', '#register-form')[0], cities, $('#idcityval', '#register-form')[0]);
      i18next.init({
          lng: 'es',
          resources: {
            es: {
              translation: {
                "wordLength": "Tu contrase&ntilde;a es demasiado corta",
                "wordNotEmail": "No uses tu email como tu contrase&ntilde;a",
                "wordSimilarToUsername": "Tu contrase&ntilde;a no puede contener tu nombre de usuario",
                "wordTwoCharacterClasses": "Mezcla diferentes clases de caracteres",
                "wordRepetitions": "Demasiadas repeticiones",
                "wordSequences": "Tu contrase&ntilde;a contiene secuencias",
                "errorList": "Errores:",
                "veryWeak": "Muy D&eacute;bil",
                "weak": "D&eacute;bil",
                "normal": "Normal",
                "medium": "Media",
                "strong": "Fuerte",
                "veryStrong": "Muy Fuerte",

                "start": "Comience a escribir la contrase&ntilde;a",
                "label": "Contrase&ntilde;a",
                "pageTitle": "Bootstrap 4 Password Strength Meter - Ejemplo de Traducciones",
                "goBack": "Atr&aacute;s"
              }
            }
          }
        }, function () {
          // Initialized and ready to go

          var options = {};
          options.ui = {
              container: "#pwd-container",
              showVerdictsInsideProgressBar: true,
              viewports: {
                  verdict: ".pwstrength_viewport_verdict",
                  progress: ".pwstrength_viewport_progress",
                  errors: ".pwstrength_viewport_verdict",
              },
              showErrors: true
          };
          options.common = {
              debug: true,
              usernameField: "#email",
              onLoad: function () {
                  $('#messages').html(i18next.t('start'));
              }
          };
          options.rules = {
		        activated: {
		            wordMaxLength: true,
		            wordInvalidChar: true
		        }
  		    };
          $('.pwstrength').pwstrength(options);
          $(".pwstrength").pwstrength("addRule", "testRule", function (options, word, score) {
            console.log('pwstrength testRule', options, word, score);
  			    return word.match(/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{8,}$/) && score;
  		    }, 3, true);
      });
      $('#register-form #email').off('change').on('change', function(evt) {
        var $email = $(this).val();
        solstar.ajaxRequest(`Validations/EmailSignUp`, 'POST', function(resp) {
            console.log(`Validations/EmailSignUp/${$email}`, resp)
            if(resp.success) {
              if(resp.data[0]) {
                $('#register-form #email').data('valid', 'false');
                solstar.jqc_alert('Rgistro de Usuario!','El correo electrónico <b>'+($email)+'</b> no esta disponible, verifique e intente nuevamente','modern', 'fa fa-exclamation', 'red',{'Aceptar': ()=>{$('#register-form #email').val(''); $('#register-form #full_name').focus();evt.preventDefault()}});
              } else {
                $('#register-form #email').data('valid', 'true');
              }
            } else {
              $('#register-form #email').data('valid', 'false');
              alertify.warning(resp.message, 10);
            }
          }, {q: $email}, true, null, function(err) {
            $('#register-form #email').data('valid', 'false');
            alertify.warning('Ha ocurrido un error! '+(err.message || ''), 10);
          }
        );
      });
    }
    if($('#salutationNow').length > 0) {
      var $h = parseInt(moment().locale('es').format('HH'));
      $('#salutationNow').html((($h >=0 && 5>$h) ? 'Excelente Madrugada': (($h >=5 && 12>$h) ? 'Buenos Días' : (($h >=12 && 19>$h) ? 'Buenas Tardes': 'Buenas Noches'))));
      setInterval(() => {
        var $h = parseInt(moment().locale('es').format('HH'));
        $('#salutationNow').html((($h >=0 && 5>$h) ? 'Excelente Madrugada': (($h >=5 && 12>$h) ? 'Buenos Días' : (($h >=12 && 19>$h) ? 'Buenas Tardes': 'Buenas Noches'))));
      }, 1000*60*30);
    }
    if($('#timeNow').length > 0) {
      setInterval(() => {
        $('#timeNow').html(moment().locale('es').format('DD/MM/YYYY')+'<br>'+moment().locale('es').format('hh:mm:ss a'));
      }, 1000);
    }
});