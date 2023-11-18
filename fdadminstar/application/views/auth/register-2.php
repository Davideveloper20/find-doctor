<?php
defined('BASEPATH') OR exit('No direct script access allowed');
$this->load->view('_partials/header');
?>
<body>
  <div id="app">
    <section class="section">
      <div class="d-flex flex-wrap align-items-stretch mt-0">
        <div class="col-lg-8 col-12 order-lg-2 order-1 min-vh-100 position-relative overlay-gradient-bottom hiden-sm hidden-xs backGN backSignUp rflex" data-background="<?php echo base_url(); ?>assets/images/bg/bg6.jpg" style="">
          <div class="absolute-bottom-left index-2">
            <div class="text-light p-5 pb-2">
              <div class="mb-5 pb-3" style="text-shadow: -3px 3px 2px rgba(245,245,255, 0.7);">
                <h1 class="mb-2 display-4 font-weight-bold blue1" id="salutationNow"><?php $h = date('H');echo ($h >=0 && 5>$h)? 'Excelente Madrugada': (($h >=5 && 12>$h) ? 'Buenos Días' : (($h >=12 && 19>$h) ? 'Buenas Tardes': 'Buenas Noches')); ?></h1>
                <h5 class="font-weight-normal text-muted-transparent blue2 text-center" id="timeNow"><?php echo date('d/m/Y h:i a');?></h5>
              </div>
            </div>
          </div>
        </div>

        <div class="col-lg-4 col-md-6 col-12 order-lg-1 min-vh-100 order-2 bg-white"  style="position: absolute !important; float: right; top: 0; right: 0;">
          <div class="p-4 m-3">
            <img src="<?php echo base_url(); ?>assets/images/logo.png" alt="logo" width="100%" class="mb-2 mt-2">
            <h4 class="text-dark font-weight-normal">Bienvenido a <span class="font-weight-bold"><span class="blue1">Find</span><span class="blue2">Doctor</span></span></h4>
            <p class="text-muted">Antes de comenzar, debe iniciar sesión o registrarse si aún no tiene una cuenta.</p>
            <?php 
            if(( null != $this->session->flashdata('message_errro')) || validation_errors() != '') { ?>
              <div class="row">
                <div class="alert alert-warning col-12">
                  <?php 
                    echo ( null != $this->session->flashdata('message_errro')) ? $this->session->flashdata('message_errro') : '';
                    echo validation_errors();
                    $this->session->set_flashdata('message_errro', '');
                  ?>
                </div>
              </div>
            <?php 
            }
            if(( null != $this->session->flashdata('message'))) { ?>
              <div class="row">
                <div class="alert alert-info col-12">
                  <?php 
                    echo ( null != $this->session->flashdata('message')) ? $this->session->flashdata('message') : '';
                    $this->session->set_flashdata('message', '');
                  ?>
                </div>
              </div>
            <?php 
            }
            ?>
            <form method="POST" id="register-form" class="needs-validation" novalidate="">
              <div class="form-group">
                <input id="full_name" type="text" tabindex="1"  class="form-control" name="fullname" autofocus placeholder="Escriba su nombre completo" value="<?php echo set_value('fullname', @"$fullname");?>">
                <div class="invalid-feedback">
                  Por favor escriba su nombre completo
                </div>
              </div>
              <div class="form-group">
                <input id="email" data-valid="false" type="email" class="form-control" name="email" tabindex="2" value="<?php echo set_value('email', @"$email");?>" required autofocus placeholder="Escriba su correo electrónico">
                <div class="invalid-feedback">
                  Por favor escriba su correo electrónico
                </div>
              </div>
              <div class="row" id="pwd-container">
                <div class="form-group col-12">
                  <label for="password" class="d-block">Contraseña</label>
                  <input id="password" type="password" tabindex="3"  class="form-control pwstrength" data-indicator="pwindicator" name="password" minlength="8" maxlength="23" placeholder="Escriba una contraseña">
                  <div id="pwindicator" class="pwindicator">
                    <div class="bar"></div>
                    <div class="label"></div>
                  </div>
                </div>
                <div class="col-sm-12 col-sm-offset-2" style="padding-top: 30px;">
                  <div class="pwstrength_viewport_progress">
                  </div>
                  <div class="pwstrength_viewport_verdict">
                  </div>
                </div>
                <div class="col-sm-12" id="message"></div>
              </div>
              <div class="row">
                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                  <label for="idcity" class="d-block">Ciudad</label>
                  <input class="form-control" tabindex="4"  type="text" id="idcity" value="<?php echo set_value('city', @"$city".(isset($state) && !empty($state) ? ", $state" : '') ); ?>" placeholder="Seleccione su ciudad">
                  <input class="form-control hidden sr-only" type="text" name="idcity" id="idcityval" value="<?php echo set_value('idcity', @$idcity); ?>">
                </div>
                <div class="form-group col-lg-12 col-md-12 col-sm-12">
                  <label for="address" class="d-block">Dirección</label>
                  <input type="text" class="form-control" tabindex="5"  id="address" name="address" placeholder="Escriba su dirección" value="<?php echo set_value('address', @"$address");?>">
                </div>
              </div>

              <div class="row">
                <div class="form-group col-lg-8 col-sm-12 text-right">
                  <div class="custom-control custom-checkbox">
                    <input type="checkbox" name="agree" class="custom-control-input" tabindex="6" id="agree">
                    <label class="custom-control-label" for="agree">Estoy de acuerdo con los <a href="#modalTerms" data-toggle="modal">términos y condiciones</a></label>
                  </div>
                </div>
                <div class="form-group col-lg-4 col-sm-12 text-right">
                  <button type="submit" tabindex="7" class="btn btn-primary btn-lg btn-icon icon-right" tabindex="4">
                    Registrar
                  </button>
                </div>
              </div>

              <div class="mt-3 text-center">
                Ya tiene una cuenta? <a href="<?php echo base_url(); ?>index.php/Auth/Login">Inicie Sesión</a>
              </div>
            </form>

            <div class="text-center mt-3 text-small">
              Copyright &copy; <a href="https://solucionesstar.com" target="_blank">Soluciones Star SAS</a> 2019
              <div class="mt-2">
                <a href="#privacity-modal">Politicas de Privacidad</a>
                <div class="bullet"></div>
                <a href="#terms-service">Terminos de Servicio</a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </div>
<script>
  var cities = <?php echo json_encode(((array)$cities) );?>;
</script>
<?php $this->load->view('_partials/js'); ?>